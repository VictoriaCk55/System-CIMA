<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProformaParametro extends Pivot
{
    protected $table = 'proforma_parametro';

    protected $fillable = [
        'proforma_id',
        'parametro_id',
        'numero_muestras',
        'precio_unitario',
        'total',
    ];

    protected $casts = [
        'numero_muestras' => 'integer',
        'precio_unitario' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Método para calcular total automáticamente
    protected static function booted()
    {
        static::saving(function ($model) {
            $model->total = $model->numero_muestras * $model->precio_unitario;
        });
    }
}
