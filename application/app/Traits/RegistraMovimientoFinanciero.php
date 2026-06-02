<?php

namespace App\Traits;

use App\Models\MovimientoFinanciero;
use Illuminate\Support\Facades\Auth;

trait RegistraMovimientoFinanciero
{
    /**
     * Registrar un movimiento financiero
     */
    protected function registrarMovimiento($origen, $clienteId, $tipo, $monto, $concepto, $referencia = null, $observaciones = null)
    {
        // Calcular nuevo saldo del cliente
        $ultimoMovimiento = MovimientoFinanciero::where('cliente_id', $clienteId)
            ->latest()
            ->first();

        $saldoAnterior = $ultimoMovimiento ? $ultimoMovimiento->saldo_cliente : 0;

        // Calcular nuevo saldo según tipo
        switch ($tipo) {
            case 'DEUDA':
                $nuevoSaldo = $saldoAnterior + $monto;
                break;
            case 'PAGO':
                $nuevoSaldo = $saldoAnterior - $monto;
                break;
            case 'AJUSTE':
                // Para ajustes, puede ser positivo o negativo
                $nuevoSaldo = $saldoAnterior + $monto; // El monto puede ser positivo o negativo
                break;
            default:
                $nuevoSaldo = $saldoAnterior;
        }

        return MovimientoFinanciero::create([
            'origen_id' => $origen->id,
            'origen_type' => get_class($origen),
            'cliente_id' => $clienteId,
            'tipo' => $tipo,
            'monto' => $monto,
            'saldo_cliente' => $nuevoSaldo,
            'concepto' => $concepto,
            'fecha' => now(),
            'referencia' => $referencia,
            'usuario_id' => Auth::id(),
            'observaciones' => $observaciones,
        ]);
    }
}
