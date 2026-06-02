<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\MovimientoFinanciero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancieroController extends Controller
{
    /**
     * Mostrar resumen financiero con filtros
     */
    public function index(Request $request)
    {
        $query = MovimientoFinanciero::with(['cliente', 'usuario'])
            ->orderBy('fecha', 'desc')
            ->orderBy('created_at', 'desc');

        // Filtro por fecha inicial
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha', '>=', $request->fecha_inicio);
        }

        // Filtro por fecha final
        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha', '<=', $request->fecha_fin);
        }

        // Filtro por cliente
        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $movimientos = $query->paginate(20)->withQueryString();

        // Calcular resúmenes
        $resumen = $this->calcularResumen($request);

        // Lista de clientes para el filtro
        $clientes = Cliente::orderBy('razon_social')->get();

        return view('financiero.index', compact('movimientos', 'resumen', 'clientes'));
    }

    /**
     * Calcular resúmenes financieros
     */
    private function calcularResumen($request)
    {
        $query = MovimientoFinanciero::query();

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha', '<=', $request->fecha_fin);
        }

        $totalDeudas = (clone $query)->where('tipo', 'DEUDA')->sum('monto');
        $totalPagos = (clone $query)->where('tipo', 'PAGO')->sum('monto');
        $totalAjustes = (clone $query)->where('tipo', 'AJUSTE')->sum('monto');

        // Obtener último saldo de cada cliente
        $ultimosSaldos = DB::table('movimientos_financieros as m1')
            ->select('m1.cliente_id', 'm1.saldo_cliente')
            ->whereIn('m1.id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('movimientos_financieros')
                    ->groupBy('cliente_id');
            })
            ->get();

        $saldoTotal = $ultimosSaldos->sum('saldo_cliente');
        $clientesConDeuda = $ultimosSaldos->filter(function ($item) {
            return $item->saldo_cliente > 0;
        })->count();

        return [
            'total_deudas' => $totalDeudas,
            'total_pagos' => $totalPagos,
            'total_ajustes' => $totalAjustes,
            'saldo_total' => $saldoTotal,
            'clientes_con_deuda' => $clientesConDeuda,
            'movimientos_count' => (clone $query)->count(),
        ];
    }

    /**
     * Mostrar detalle de un cliente específico
     */
    public function cliente(Cliente $cliente, Request $request)
    {
        $movimientos = MovimientoFinanciero::with(['usuario'])
            ->where('cliente_id', $cliente->id)
            ->orderBy('fecha', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $saldoActual = $movimientos->isNotEmpty() ? $movimientos->first()->saldo_cliente : 0;

        return view('financiero.cliente', compact('cliente', 'movimientos', 'saldoActual'));
    }

    /**
     * Exportar a Excel (opcional)
     */
    public function exportar(Request $request)
    {
        return redirect()->route('financiero.index')
            ->with('info', 'Función de exportación próximamente');
    }
}
