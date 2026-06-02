<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoFinanciero extends Model
{
    use HasFactory;

    protected $table = 'movimientos_financieros';

    protected $fillable = [
        'origen_id',
        'origen_type',
        'cliente_id',
        'tipo',
        'monto',
        'saldo_cliente',
        'concepto',
        'fecha',
        'referencia',
        'usuario_id',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto' => 'decimal:2',
        'saldo_cliente' => 'decimal:2',
    ];

    // Relación polimórfica con proformas, informes, etc.
    public function origen()
    {
        return $this->morphTo();
    }

    // Relación con cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Scope para filtrar por fecha
    public function scopeEntreFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
    }

    // Scope para filtrar por tipo
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    // Scope para filtrar por cliente
    public function scopePorCliente($query, $clienteId)
    {
        return $query->where('cliente_id', $clienteId);
    }
}
