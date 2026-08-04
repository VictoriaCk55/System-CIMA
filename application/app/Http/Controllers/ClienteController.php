<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\MovimientoFinanciero;
use App\Traits\RegistraMovimientoFinanciero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClienteController extends Controller
{
    use RegistraMovimientoFinanciero;

    /**
     * Verificar si el usuario es administrador
     */
    private function esAdmin()
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'tecnico', 'analista']);
    }

    /**
     * Display a listing of the resource - CON BÚSQUEDA Y ORDENAMIENTO POR SALDO
     */
    public function index(Request $request)
    {
        $query = Cliente::query();

        // ===== BÚSQUEDA POR TÉRMINO =====
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('razon_social', 'LIKE', "%{$search}%")
                    ->orWhere('persona_contacto', 'LIKE', "%{$search}%")
                    ->orWhere('telefono', 'LIKE', "%{$search}%")
                    ->orWhere('nit', 'LIKE', "%{$search}%");
            });
        }

        // Obtener clientes con sus proformas para calcular saldos
        $clientes = $query->with(['proformas' => function ($q) {
            $q->select('id', 'cliente_id', 'total', 'adelanto', 'saldo');
        }])->get();

        // Calcular saldo total para cada cliente
        foreach ($clientes as $cliente) {
            $cliente->saldo_calculado = $cliente->proformas->sum('saldo');
        }

        // ===== ORDENAMIENTO POR SALDO =====
        if ($request->filled('orden')) {
            switch ($request->orden) {
                case 'saldo_desc':
                    $clientes = $clientes->sortByDesc('saldo_calculado');
                    break;
                case 'saldo_asc':
                    $clientes = $clientes->sortBy('saldo_calculado');
                    break;
                case 'nombre_asc':
                    $clientes = $clientes->sortBy('razon_social');
                    break;
                case 'nombre_desc':
                    $clientes = $clientes->sortByDesc('razon_social');
                    break;
            }
        }

        // Paginación manual después de ordenar
        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        $clientesPaginados = new \Illuminate\Pagination\LengthAwarePaginator(
            $clientes->slice($offset, $perPage)->values(),
            $clientes->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('clientes.index', ['clientes' => $clientesPaginados]);
    }

    /**
     * Mostrar clientes eliminados (papelera) - SOLO ADMIN
     */
    public function trash()
    {
        if (! $this->esAdmin()) {
            return redirect()->route('clientes.index')
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede ver clientes eliminados.');
        }

        $clientes = Cliente::onlyTrashed()
            ->with(['proformas' => function ($q) {
                $q->select('id', 'cliente_id', 'total', 'adelanto', 'saldo');
            }])
            ->latest('deleted_at')
            ->paginate(10);

        return view('clientes.trash', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (! $this->esAdmin()) {
            return redirect()->route('clientes.index')
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede crear clientes.');
        }

        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! $this->esAdmin()) {
            return redirect()->route('clientes.index')
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede crear clientes.');
        }

        $validated = $request->validate([
            'razon_social' => 'required|string|max:255',
            'persona_contacto' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'nit' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:500',
        ]);

        Cliente::create($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        // Cargar proformas con sus totales para mostrar estadísticas
        $cliente->load(['proformas' => function ($q) {
            $q->select('id', 'cliente_id', 'codigo', 'fecha_emision', 'estado', 'total', 'adelanto', 'saldo');
        }]);

        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        if (! $this->esAdmin()) {
            return redirect()->route('clientes.show', $cliente)
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede editar clientes.');
        }

        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        if (! $this->esAdmin()) {
            return redirect()->route('clientes.show', $cliente)
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede actualizar clientes.');
        }

        $validated = $request->validate([
            'razon_social' => 'required|string|max:255',
            'persona_contacto' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'nit' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:500',
        ]);

        $cliente->update($validated);

        return redirect()->route('clientes.show', $cliente)
            ->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Soft Delete (mover a papelera)
     */
    public function destroy(Cliente $cliente)
    {
        if (! $this->esAdmin()) {
            return redirect()->route('clientes.index')
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede eliminar clientes.');
        }

        try {
            // Verificar si tiene proformas (soft delete igual permite verlas)
            if ($cliente->proformas()->count() > 0) {
                return redirect()->route('clientes.index')
                    ->with('warning', '⚠️ El cliente tiene proformas asociadas. Se moverá a la papelera pero las proformas seguirán visibles.');
            }

            $cliente->delete(); // Soft delete

            return redirect()->route('clientes.index')
                ->with('success', '✅ Cliente movido a la papelera exitosamente.');

        } catch (\Exception $e) {
            return redirect()->route('clientes.index')
                ->with('error', 'Error al eliminar cliente: '.$e->getMessage());
        }
    }

    /**
     * Restaurar cliente desde papelera - SOLO ADMIN
     */
    public function restore($id)
    {
        if (! $this->esAdmin()) {
            return redirect()->route('clientes.index')
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede restaurar clientes.');
        }

        try {
            $cliente = Cliente::onlyTrashed()->findOrFail($id);
            $cliente->restore();

            return redirect()->route('clientes.trash')
                ->with('success', '✅ Cliente restaurado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->route('clientes.trash')
                ->with('error', 'Error al restaurar cliente: '.$e->getMessage());
        }
    }

    /**
     * Eliminar permanentemente de la base de datos - SOLO ADMIN
     */
    public function forceDelete($id)
    {
        if (! $this->esAdmin()) {
            return redirect()->route('clientes.index')
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede eliminar permanentemente.');
        }

        try {
            $cliente = Cliente::onlyTrashed()->findOrFail($id);

            // Verificar si tiene proformas (no debería eliminarse si tiene)
            if ($cliente->proformas()->count() > 0) {
                return redirect()->route('clientes.trash')
                    ->with('error', '❌ No se puede eliminar permanentemente un cliente con proformas asociadas.');
            }

            $cliente->forceDelete(); // Eliminación real

            return redirect()->route('clientes.trash')
                ->with('success', '✅ Cliente eliminado permanentemente.');

        } catch (\Exception $e) {
            return redirect()->route('clientes.trash')
                ->with('error', 'Error al eliminar permanentemente: '.$e->getMessage());
        }
    }

    /**
     * API para crear cliente desde modal
     */
    public function storeApi(Request $request)
    {
        if (! $this->esAdmin()) {
            return response()->json([
                'success' => false,
                'message' => '⛔ Acceso denegado. Solo el administrador puede crear clientes.',
            ], 403);
        }

        $validated = $request->validate([
            'razon_social' => 'required|string|max:255',
            'persona_contacto' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'nit' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:500',
        ]);

        $cliente = Cliente::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cliente creado exitosamente',
            'cliente' => $cliente,
        ]);
    }

    /**
     * 🔍 Buscar clientes para Select2
     */
    public function buscar(Request $request)
    {
        try {
            $term = $request->get('q', '');
            $incluirEliminados = $request->get('incluir_eliminados', false);

            Log::info('=== BÚSQUEDA DE CLIENTES ===');
            Log::info('Término de búsqueda: "'.$term.'"');
            Log::info('Incluir eliminados: '.($incluirEliminados ? 'Sí' : 'No'));

            $query = Cliente::query();

            if ($incluirEliminados) {
                $query->withTrashed();
            }

            if (empty($term) || strlen($term) < 2) {
                $clientes = $query->latest()->limit(10)->get();
                Log::info('Término muy corto, devolviendo últimos 10');
            } else {
                $clientes = $query->where('razon_social', 'ILIKE', "%{$term}%")
                    ->orWhere('persona_contacto', 'ILIKE', "%{$term}%")
                    ->orWhere('nit', 'ILIKE', "%{$term}%")
                    ->limit(20)
                    ->get();
            }

            $results = [];
            foreach ($clientes as $cliente) {
                $texto = $cliente->razon_social.' - '.$cliente->persona_contacto;
                if ($cliente->trashed()) {
                    $texto .= ' (ELIMINADO)';
                }

                $results[] = [
                    'id' => $cliente->id,
                    'text' => $texto,
                    'razon_social' => $cliente->razon_social,
                    'persona_contacto' => $cliente->persona_contacto,
                    'trashed' => $cliente->trashed(),
                ];
                Log::info(' - '.$cliente->razon_social.($cliente->trashed() ? ' (ELIMINADO)' : ''));
            }

            return response()->json($results);

        } catch (\Exception $e) {
            Log::error('ERROR en búsqueda: '.$e->getMessage());

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Registrar un pago manual para el cliente
     */
    public function registrarPago(Request $request, $id)
    {
        if (! $this->esAdmin()) {
            return redirect()->route('clientes.index')
                ->with('error', '⛔ Acceso denegado.');
        }

        $request->validate([
            'monto' => 'required|numeric|min:0.01',
            'concepto' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $cliente = Cliente::findOrFail($id);

            // Obtener el saldo actual
            $ultimoMovimiento = MovimientoFinanciero::where('cliente_id', $cliente->id)
                ->latest()
                ->first();

            $saldoActual = $ultimoMovimiento ? $ultimoMovimiento->saldo_cliente : 0;
            $montoPago = $request->monto;

            // Verificar que el pago no exceda la deuda
            if ($montoPago > $saldoActual && $saldoActual > 0) {
                return redirect()->back()
                    ->with('error', "❌ El pago (Bs. {$montoPago}) no puede ser mayor a la deuda actual (Bs. {$saldoActual})");
            }

            // Buscar proformas con deuda para distribuir el pago
            $proformasConDeuda = $cliente->proformas()
                ->where('saldo', '>', 0)
                ->orderBy('fecha_emision', 'asc')
                ->get();

            $montoRestante = $montoPago;
            $pagosRegistrados = [];

            foreach ($proformasConDeuda as $proforma) {
                if ($montoRestante <= 0) {
                    break;
                }

                $pagoParaEstaProforma = min($proforma->saldo, $montoRestante);

                // Actualizar adelanto de la proforma
                $nuevoAdelanto = $proforma->adelanto + $pagoParaEstaProforma;
                $proforma->adelanto = $nuevoAdelanto;
                $proforma->saldo = $proforma->total - $nuevoAdelanto;
                $proforma->save();

                $pagosRegistrados[] = [
                    'proforma' => $proforma->codigo,
                    'monto' => $pagoParaEstaProforma,
                ];

                $montoRestante -= $pagoParaEstaProforma;
            }

            // Registrar el movimiento financiero general
            $concepto = $request->concepto ?: 'Pago manual';
            $observaciones = "Pago registrado manualmente: Bs. {$montoPago}\n";
            foreach ($pagosRegistrados as $pago) {
                $observaciones .= "- {$pago['proforma']}: Bs. {$pago['monto']}\n";
            }
            if ($request->observaciones) {
                $observaciones .= "\n".$request->observaciones;
            }

            $this->registrarMovimiento(
                $cliente,
                $cliente->id,
                'PAGO',
                $montoPago,
                $concepto,
                'PAGO-MANUAL',
                $observaciones
            );

            DB::commit();

            return redirect()->route('clientes.show', $cliente)
                ->with('success', '✅ Pago registrado exitosamente. Monto: Bs. '.number_format($montoPago, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar pago manual: '.$e->getMessage());

            return redirect()->back()
                ->with('error', '❌ Error al registrar pago: '.$e->getMessage());
        }
    }

    /**
     * Actualizar saldo del cliente (recalcula desde movimientos financieros)
     */
    public function actualizarSaldo($id)
    {
        if (! $this->esAdmin()) {
            return redirect()->route('clientes.index')
                ->with('error', '⛔ Acceso denegado.');
        }

        try {
            $cliente = Cliente::findOrFail($id);

            // Obtener el último movimiento financiero
            $ultimoMovimiento = MovimientoFinanciero::where('cliente_id', $cliente->id)
                ->latest()
                ->first();

            if (! $ultimoMovimiento) {
                return redirect()->route('clientes.show', $cliente)
                    ->with('info', 'ℹ️ No hay movimientos financieros para este cliente.');
            }

            $saldoActual = $ultimoMovimiento->saldo_cliente;

            // Verificar si el saldo coincide con la suma de proformas
            $saldoProformas = $cliente->proformas()->sum('saldo');

            if ($saldoActual != $saldoProformas) {
                // Hay inconsistencia, podemos crear un movimiento de ajuste
                $diferencia = $saldoProformas - $saldoActual;

                if ($diferencia != 0) {
                    $this->registrarMovimiento(
                        $cliente,
                        $cliente->id,
                        'AJUSTE',
                        $diferencia,
                        'Ajuste manual de saldo',
                        null,
                        "Saldo anterior: Bs. {$saldoActual}, Saldo calculado: Bs. {$saldoProformas}"
                    );

                    return redirect()->route('clientes.show', $cliente)
                        ->with('success', '✅ Saldo actualizado correctamente. Se realizó un ajuste de Bs. '.number_format($diferencia, 2));
                }
            }

            return redirect()->route('clientes.show', $cliente)
                ->with('success', '✅ Saldo verificado correctamente. Todo está en orden.');

        } catch (\Exception $e) {
            Log::error('Error al actualizar saldo: '.$e->getMessage());

            return redirect()->route('clientes.show', $cliente)
                ->with('error', '❌ Error al actualizar saldo: '.$e->getMessage());
        }
    }
}
