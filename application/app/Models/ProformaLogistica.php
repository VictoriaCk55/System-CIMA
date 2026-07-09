<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProformaLogistica extends Pivot
{
    protected $table = 'proforma_logisticas';

    protected $fillable = [
        'proforma_id',
        'logistica_muestreo_id',
        'cantidad',
        'subtotal',
        'descripcion',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'subtotal' => 'decimal:2',
    ];

    public function proforma()
    {
        return $this->belongsTo(Proforma::class);
    }

    public function logisticaMuestreo()
    {
        return $this->belongsTo(LogisticaMuestreo::class);
    }
}
