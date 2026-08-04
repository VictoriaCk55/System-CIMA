<?php

namespace App\Http\Controllers;

use App\Models\Parametro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ParametroController extends Controller
{
    /**
     * Verificar si el usuario es administrador
     */
    private function esAdmin()
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'tecnico', 'analista']);
    }

    /**
     * Display a listing of the resource - SOLO ACTIVOS
     */
    public function index(Request $request)
    {
        $query = Parametro::query();

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'LIKE', "%{$search}%")
                    ->orWhere('metodo', 'LIKE', "%{$search}%")
                    ->orWhere('codigo_poe', 'LIKE', "%{$search}%")
                    ->orWhere('limite_cuantificacion', 'LIKE', "%{$search}%")
                    ->orWhere('unidad', 'LIKE', "%{$search}%")
                    ->orWhere('matriz', 'LIKE', "%{$search}%")
                    ->orWhere('tecnica', 'LIKE', "%{$search}%")
                    ->orWhere('tipo', 'LIKE', "%{$search}%");
            });
        }

        $parametros = $query->latest()->paginate(10);

        return view('parametros.index', compact('parametros'));
    }

    /**
     * Mostrar parámetros eliminados (papelera) - SOLO ADMIN
     */
    public function trash()
    {
        if (! $this->esAdmin()) {
            return redirect()->route('parametros.index')
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede ver parámetros eliminados.');
        }

        $parametros = Parametro::onlyTrashed()
            ->latest('deleted_at')
            ->paginate(10);

        return view('parametros.trash', compact('parametros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (! $this->esAdmin()) {
            return redirect()->route('parametros.index')
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede crear parámetros.');
        }

        return view('parametros.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! $this->esAdmin()) {
            return redirect()->route('parametros.index')
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede crear parámetros.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'metodo' => 'required|string|max:255',
            'codigo_poe' => 'nullable|string|max:255',
            'limite_cuantificacion' => 'nullable|string|max:255',
            'unidad' => 'nullable|string|max:255',
            'matriz' => 'nullable|string|max:255',
            'tecnica' => 'nullable|string|max:255',
            'precio_unitario' => 'required|numeric|min:0',
            'tipo' => 'required|in:AMBIENTAL,AGUA,INVESTIGACION',
        ]);

        Parametro::create($validated);

        return redirect()->route('parametros.index')
            ->with('success', '✅ Parámetro creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Parametro $parametro)
    {
        return view('parametros.show', compact('parametro'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Parametro $parametro)
    {
        if (! $this->esAdmin()) {
            return redirect()->route('parametros.show', $parametro)
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede editar parámetros.');
        }

        return view('parametros.edit', compact('parametro'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Parametro $parametro)
    {
        if (! $this->esAdmin()) {
            return redirect()->route('parametros.show', $parametro)
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede actualizar parámetros.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'metodo' => 'required|string|max:255',
            'codigo_poe' => 'nullable|string|max:255',
            'limite_cuantificacion' => 'nullable|string|max:255',
            'unidad' => 'nullable|string|max:255',
            'matriz' => 'nullable|string|max:255',
            'tecnica' => 'nullable|string|max:255',
            'precio_unitario' => 'required|numeric|min:0',
            'tipo' => 'required|in:AMBIENTAL,AGUA,INVESTIGACION',
        ]);

        $parametro->update($validated);

        return redirect()->route('parametros.show', $parametro)
            ->with('success', '✅ Parámetro actualizado exitosamente.');
    }

    /**
     * Soft Delete (mover a papelera)
     */
    public function destroy(Parametro $parametro)
    {
        if (! $this->esAdmin()) {
            return redirect()->route('parametros.index')
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede eliminar parámetros.');
        }

        try {
            $usoEnProformas = $parametro->proformas()->count();

            if ($usoEnProformas > 0) {
                return redirect()->route('parametros.index')
                    ->with('warning', "⚠️ El parámetro está siendo usado en {$usoEnProformas} proforma(s). Se moverá a la papelera pero las proformas seguirán visibles.");
            }

            $parametro->delete();

            return redirect()->route('parametros.index')
                ->with('success', '✅ Parámetro movido a la papelera exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al eliminar parámetro: '.$e->getMessage());

            return redirect()->route('parametros.index')
                ->with('error', '❌ Error al eliminar el parámetro: '.$e->getMessage());
        }
    }

    /**
     * Restaurar parámetro desde papelera - SOLO ADMIN
     */
    public function restore($id)
    {
        if (! $this->esAdmin()) {
            return redirect()->route('parametros.index')
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede restaurar parámetros.');
        }

        try {
            $parametro = Parametro::onlyTrashed()->findOrFail($id);
            $parametro->restore();

            return redirect()->route('parametros.trash')
                ->with('success', '✅ Parámetro restaurado exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al restaurar parámetro: '.$e->getMessage());

            return redirect()->route('parametros.trash')
                ->with('error', '❌ Error al restaurar el parámetro: '.$e->getMessage());
        }
    }

    /**
     * Eliminar permanentemente de la base de datos - SOLO ADMIN
     */
    public function forceDelete($id)
    {
        if (! $this->esAdmin()) {
            return redirect()->route('parametros.index')
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede eliminar permanentemente.');
        }

        try {
            $parametro = Parametro::onlyTrashed()->findOrFail($id);

            // Verificar si tiene proformas
            if ($parametro->proformas()->count() > 0) {
                return redirect()->route('parametros.trash')
                    ->with('error', '❌ No se puede eliminar permanentemente un parámetro con proformas asociadas.');
            }

            $parametro->forceDelete();

            return redirect()->route('parametros.trash')
                ->with('success', '✅ Parámetro eliminado permanentemente.');

        } catch (\Exception $e) {
            Log::error('Error al eliminar permanentemente: '.$e->getMessage());

            return redirect()->route('parametros.trash')
                ->with('error', '❌ Error al eliminar permanentemente: '.$e->getMessage());
        }
    }

    /**
     * 🔍 Buscar parámetros para Select2
     */
    public function buscar(Request $request)
    {
        try {
            $term = $request->get('q', '');
            $categoria = $request->get('categoria', '');
            $incluirEliminados = $request->get('incluir_eliminados', false);

            Log::info('Buscando parámetros con término: '.$term);

            $query = Parametro::query();

            if ($incluirEliminados) {
                $query->withTrashed();
            }

            if ($categoria) {
                $query->where('categoria', $categoria);
            }

            if (empty($term)) {
                $parametros = $query->latest()->limit(10)->get();
            } else {
                $query->where(function ($q) use ($term) {
                    $q->where('nombre', 'ILIKE', '%'.$term.'%')
                        ->orWhere('metodo', 'ILIKE', '%'.$term.'%')
                        ->orWhere('codigo_poe', 'ILIKE', '%'.$term.'%')
                        ->orWhere('unidad', 'ILIKE', '%'.$term.'%')
                        ->orWhere('matriz', 'ILIKE', '%'.$term.'%')
                        ->orWhere('tecnica', 'ILIKE', '%'.$term.'%')
                        ->orWhere('tipo', 'ILIKE', '%'.$term.'%');
                });
                $parametros = $query->limit(20)->get();
            }

            $results = [];
            foreach ($parametros as $parametro) {
                $texto = $parametro->nombre.' ('.$parametro->tipo.') - Bs. '.number_format($parametro->precio_unitario, 2);
                if ($parametro->trashed()) {
                    $texto .= ' (ELIMINADO)';
                }

                $results[] = [
                    'id' => $parametro->id,
                    'text' => $texto,
                    'precio_unitario' => $parametro->precio_unitario,
                    'metodo' => $parametro->metodo,
                    'codigo_poe' => $parametro->codigo_poe,
                    'limite_cuantificacion' => $parametro->limite_cuantificacion,
                    'unidad' => $parametro->unidad,
                    'matriz' => $parametro->matriz,
                    'tecnica' => $parametro->tecnica,
                    'tipo' => $parametro->tipo,
                    'trashed' => $parametro->trashed(),
                ];
            }

            return response()->json($results);

        } catch (\Exception $e) {
            Log::error('ERROR en búsqueda de parámetros: '.$e->getMessage());

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
