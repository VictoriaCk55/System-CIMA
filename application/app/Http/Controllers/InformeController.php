<?php

namespace App\Http\Controllers;

use App\Models\Informe;
use App\Models\Proforma;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InformeController extends Controller
{
    /**
     * Verificar si el usuario es administrador
     */
    private function esAdmin()
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'tecnico', 'analista']);
    }

    /**
     * Obtener ID del usuario actual de forma segura
     */
    private function getCurrentUserId()
    {
        return Auth::check() ? Auth::id() : null;
    }

    /**
     * Display a listing of the resource - CON FILTROS POR MES, AÑO Y ESTADO
     */
    public function index(Request $request)
    {
        $query = Informe::with(['proforma.cliente', 'creador']);

        if ($request->filled('mes') && $request->filled('anio')) {
            $query->whereMonth('fecha_emision', $request->mes)
                ->whereYear('fecha_emision', $request->anio);
        } elseif ($request->filled('mes') && ! $request->filled('anio')) {
            $query->whereMonth('fecha_emision', $request->mes)
                ->whereYear('fecha_emision', date('Y'));
        } elseif (! $request->filled('mes') && $request->filled('anio')) {
            $query->whereYear('fecha_emision', $request->anio);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $añosDisponibles = Informe::selectRaw('DISTINCT EXTRACT(YEAR FROM fecha_emision) as año')
            ->orderBy('año', 'desc')
            ->pluck('año')
            ->toArray();

        $informes = $query->latest()
            ->paginate(15)
            ->withQueryString();

        $estadisticas = $this->obtenerEstadisticas($request);

        return view('informes.index', compact('informes', 'estadisticas', 'añosDisponibles'));
    }

    /**
     * Mostrar informes eliminados (papelera) - SOLO ADMIN
     */
    public function trash()
    {
        if (! $this->esAdmin()) {
            return redirect()->route('informes.index')
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede ver informes eliminados.');
        }

        $informes = Informe::onlyTrashed()
            ->with(['proforma.cliente', 'creador'])
            ->latest('deleted_at')
            ->paginate(15);

        return view('informes.trash', compact('informes'));
    }

    /**
     * Restaurar informe desde papelera - SOLO ADMIN
     */
    public function restore($id)
    {
        if (! $this->esAdmin()) {
            return redirect()->route('informes.index')
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede restaurar informes.');
        }

        try {
            $informe = Informe::onlyTrashed()->findOrFail($id);
            $informe->restore();

            return redirect()->route('informes.trash')
                ->with('success', '✅ Informe restaurado exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al restaurar informe: '.$e->getMessage());

            return redirect()->route('informes.trash')
                ->with('error', '❌ Error al restaurar el informe: '.$e->getMessage());
        }
    }

    /**
     * Eliminar permanentemente de la base de datos - SOLO ADMIN
     */
    public function forceDelete($id)
    {
        if (! $this->esAdmin()) {
            return redirect()->route('informes.index')
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede eliminar permanentemente.');
        }

        try {
            $informe = Informe::onlyTrashed()->findOrFail($id);

            // Eliminar archivos si existen
            if ($informe->archivo_adjunto) {
                Storage::disk('public')->delete($informe->archivo_adjunto);
            }
            if ($informe->archivo_resultados) {
                Storage::disk('public')->delete($informe->archivo_resultados);
            }

            $informe->forceDelete();

            return redirect()->route('informes.trash')
                ->with('success', '✅ Informe eliminado permanentemente.');

        } catch (\Exception $e) {
            Log::error('Error al eliminar permanentemente: '.$e->getMessage());

            return redirect()->route('informes.trash')
                ->with('error', '❌ Error al eliminar permanentemente: '.$e->getMessage());
        }
    }

    /**
     * Obtener estadísticas aplicando los mismos filtros
     */
    private function obtenerEstadisticas($request)
    {
        $query = Informe::query();

        if ($request->filled('mes') && $request->filled('anio')) {
            $query->whereMonth('fecha_emision', $request->mes)
                ->whereYear('fecha_emision', $request->anio);
        } elseif ($request->filled('mes')) {
            $query->whereMonth('fecha_emision', $request->mes)
                ->whereYear('fecha_emision', date('Y'));
        } elseif ($request->filled('anio')) {
            $query->whereYear('fecha_emision', $request->anio);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        return [
            'total' => $query->count(),
            'borrador' => (clone $query)->where('estado', 'BORRADOR')->count(),
            'en_proceso' => (clone $query)->where('estado', 'EN_PROCESO')->count(),
            'revisado' => (clone $query)->where('estado', 'REVISADO')->count(),
            'aprobado' => (clone $query)->where('estado', 'APROBADO')->count(),
            'entregado' => (clone $query)->where('estado', 'ENTREGADO')->count(),
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (! $this->esAdmin()) {
            return redirect()->route('informes.index')
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede crear informes.');
        }

        try {
            $proformasSinInforme = Proforma::with('cliente')
                ->whereDoesntHave('informe')
                ->orderBy('created_at', 'desc')
                ->get();

            $proforma = null;
            if ($request->has('proforma_id')) {
                $proforma = Proforma::with('cliente')
                    ->whereDoesntHave('informe')
                    ->find($request->proforma_id);
            }

            return view('informes.create', compact('proformasSinInforme', 'proforma'));

        } catch (\Exception $e) {
            Log::error('Error en create de informes: '.$e->getMessage());

            return redirect()->route('informes.index')
                ->with('error', 'Error al cargar el formulario: '.$e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! $this->esAdmin()) {
            return redirect()->route('informes.index')
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede crear informes.');
        }

        try {
            $request->validate([
                'proforma_id' => 'required|exists:proformas,id',
                'fecha_emision' => 'required|date',
                'fecha_entrega' => 'nullable|date',
                'fecha_analisis' => 'nullable|date',
                'fecha_revision' => 'nullable|date',
                'prioridad' => 'required|in:BAJA,MEDIA,ALTA,URGENTE',
                'estado' => 'nullable|in:BORRADOR,EN_PROCESO,REVISADO,APROBADO,ENTREGADO',
                'resultado' => 'nullable|string',
                'conclusiones' => 'nullable|string',
                'recomendaciones' => 'nullable|string',
                'observaciones' => 'nullable|string',
                'archivo_adjunto' => 'nullable|file|mimes:pdf|max:10240',
                'archivo_resultados' => 'nullable|file|mimes:csv,xlsx,xls,txt|max:5120',
            ]);

            DB::beginTransaction();

            $proforma = Proforma::find($request->proforma_id);
            if ($proforma->informe()->exists()) {
                return redirect()->back()
                    ->with('error', '❌ Esta proforma ya tiene un informe asociado.')
                    ->withInput();
            }

            // ✅ Generar código único
            $codigo = Informe::generarCodigo();

            $contador = 1;
            $codigoOriginal = $codigo;
            while (Informe::where('codigo', $codigo)->exists()) {
                $numero = intval(substr($codigoOriginal, 4)) + $contador;
                $codigo = 'INF-'.str_pad($numero, 3, '0', STR_PAD_LEFT);
                $contador++;
            }

            $informe = new Informe;
            $informe->codigo = $codigo;
            $informe->proforma_id = $request->proforma_id;
            $informe->fecha_emision = $request->fecha_emision;
            $informe->fecha_entrega = $request->fecha_entrega;
            $informe->fecha_analisis = $request->fecha_analisis;
            $informe->fecha_revision = $request->fecha_revision;
            $informe->resultado = $request->resultado;
            $informe->conclusiones = $request->conclusiones;
            $informe->recomendaciones = $request->recomendaciones;
            $informe->observaciones = $request->observaciones;
            $informe->prioridad = $request->prioridad;
            $informe->estado = $request->estado ?? 'BORRADOR';
            $informe->creado_por = $this->getCurrentUserId();

            if ($request->hasFile('archivo_adjunto')) {
                $path = $request->file('archivo_adjunto')->store('informes/adjuntos', 'public');
                $informe->archivo_adjunto = $path;
            }

            if ($request->hasFile('archivo_resultados')) {
                $path = $request->file('archivo_resultados')->store('informes/resultados', 'public');
                $informe->archivo_resultados = $path;
            }

            $informe->save();

            DB::commit();

            return redirect()->route('informes.show', $informe)
                ->with('success', '✅ Informe creado exitosamente con código: '.$informe->codigo);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear informe: '.$e->getMessage());

            return redirect()->back()
                ->with('error', '❌ Error al crear el informe: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Informe $informe)
    {
        $informe->load([
            'proforma.cliente',
            'proforma.parametros',
            'creador',
            'revisor',
            'aprobador',
            'entregador',
        ]);

        return view('informes.show', compact('informe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Informe $informe)
    {
        if (! $this->esAdmin()) {
            return redirect()->route('informes.show', $informe)
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede editar informes.');
        }

        if (! in_array($informe->estado, ['BORRADOR', 'EN_PROCESO'])) {
            return redirect()->route('informes.show', $informe)
                ->with('error', 'No se puede editar un informe en estado: '.$informe->estado_texto);
        }

        $informe->load('proforma.cliente');
        $usuarios = User::orderBy('name')->get();

        return view('informes.edit', compact('informe', 'usuarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Informe $informe)
    {
        if (! $this->esAdmin()) {
            return redirect()->route('informes.show', $informe)
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede actualizar informes.');
        }

        try {
            $request->validate([
                'fecha_entrega' => 'nullable|date',
                'fecha_analisis' => 'nullable|date',
                'fecha_revision' => 'nullable|date',
                'prioridad' => 'required|in:BAJA,MEDIA,ALTA,URGENTE',
                'resultado' => 'nullable|string',
                'conclusiones' => 'nullable|string',
                'recomendaciones' => 'nullable|string',
                'observaciones' => 'nullable|string',
            ]);

            DB::beginTransaction();

            $informe->fecha_entrega = $request->fecha_entrega;
            $informe->fecha_analisis = $request->fecha_analisis;
            $informe->fecha_revision = $request->fecha_revision;
            $informe->prioridad = $request->prioridad;
            $informe->resultado = $request->resultado;
            $informe->conclusiones = $request->conclusiones;
            $informe->recomendaciones = $request->recomendaciones;
            $informe->observaciones = $request->observaciones;

            $informe->save();

            DB::commit();

            return redirect()->route('informes.show', $informe)
                ->with('success', '✅ Informe actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar informe: '.$e->getMessage());

            return redirect()->back()
                ->with('error', '❌ Error al actualizar el informe: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Informe $informe)
    {
        if (! $this->esAdmin()) {
            return redirect()->route('informes.index')
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede eliminar informes.');
        }

        try {
            $informe->delete(); // Soft delete

            return redirect()->route('informes.index')
                ->with('success', '✅ Informe movido a la papelera exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al eliminar informe: '.$e->getMessage());

            return redirect()->route('informes.index')
                ->with('error', '❌ Error al eliminar el informe: '.$e->getMessage());
        }
    }

    /**
     * Cambiar estado del informe
     */
    public function cambiarEstado(Request $request, Informe $informe)
    {
        if (! $this->esAdmin()) {
            return back()->with('error', '⛔ Acceso denegado. Solo el administrador puede cambiar el estado de informes.');
        }

        try {
            $request->validate([
                'estado' => 'required|in:BORRADOR,EN_PROCESO,REVISADO,APROBADO,ENTREGADO',
            ]);

            DB::beginTransaction();

            $informe->estado = $request->estado;

            $userId = $this->getCurrentUserId();
            switch ($request->estado) {
                case 'REVISADO':
                    $informe->revisado_por = $userId;
                    break;
                case 'APROBADO':
                    $informe->aprobado_por = $userId;
                    break;
                case 'ENTREGADO':
                    $informe->entregado_por = $userId;
                    break;
            }

            $informe->save();

            DB::commit();

            return redirect()->route('informes.show', $informe)
                ->with('success', '✅ Estado actualizado a: '.$informe->estado_texto);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al cambiar estado: '.$e->getMessage());

            return back()->with('error', '❌ Error al cambiar el estado: '.$e->getMessage());
        }
    }

    /**
     * Generar PDF del informe
     */
    public function pdf(Informe $informe)
    {
        try {
            $informe->load([
                'proforma.cliente',
                'proforma.parametros',
                'creador',
                'revisor',
                'aprobador',
                'entregador',
            ]);

            $pdf = Pdf::loadView('informes.pdf.informe', compact('informe'));

            return $pdf->download('informe-'.$informe->codigo.'.pdf');

        } catch (\Exception $e) {
            Log::error('Error al generar PDF de informe: '.$e->getMessage());

            return back()->with('error', '❌ Error al generar PDF: '.$e->getMessage());
        }
    }

    /**
     * Descargar archivo adjunto
     */
    public function descargarArchivo(Informe $informe, $tipo = 'adjunto')
    {
        try {
            $path = $tipo === 'resultados' ? $informe->archivo_resultados : $informe->archivo_adjunto;

            if (! $path || ! Storage::disk('public')->exists($path)) {
                return back()->with('error', '❌ El archivo no existe.');
            }

            return Storage::disk('public')->download($path);

        } catch (\Exception $e) {
            Log::error('Error al descargar archivo: '.$e->getMessage());

            return back()->with('error', '❌ Error al descargar archivo: '.$e->getMessage());
        }
    }

    /**
     * 🔍 Buscar proformas para Select2
     */
    public function buscarProformas(Request $request)
    {
        try {
            $term = $request->get('q', '');

            Log::info('Buscando proformas con término: '.$term);

            $query = Proforma::with('cliente')
                ->whereDoesntHave('informe');

            if (! empty($term)) {
                $query->where(function ($q) use ($term) {
                    $q->where('codigo', 'ILIKE', '%'.$term.'%')
                        ->orWhere('tipo', 'ILIKE', '%'.$term.'%')
                        ->orWhere('tipo_muestra', 'ILIKE', '%'.$term.'%')
                        ->orWhereHas('cliente', function ($clientQuery) use ($term) {
                            $clientQuery->where('razon_social', 'ILIKE', '%'.$term.'%')
                                ->orWhere('persona_contacto', 'ILIKE', '%'.$term.'%');
                        });
                });
            }

            $proformas = $query->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();

            $results = [];
            foreach ($proformas as $proforma) {
                $clienteNombre = $proforma->cliente->razon_social ?? $proforma->cliente->nombre ?? 'Sin cliente';
                $tipoMuestra = $proforma->tipo_muestra ?? $proforma->tipo ?? 'N/A';

                $results[] = [
                    'id' => $proforma->id,
                    'text' => $proforma->codigo.' - '.$clienteNombre.' ('.$tipoMuestra.')',
                    'codigo' => $proforma->codigo,
                    'cliente' => $clienteNombre,
                    'tipo' => $tipoMuestra,
                ];
            }

            return response()->json($results);

        } catch (\Exception $e) {
            Log::error('ERROR en búsqueda de proformas: '.$e->getMessage());

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
