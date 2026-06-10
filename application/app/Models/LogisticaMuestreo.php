<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogisticaMuestreo extends Model
{
    protected $table = 'logisticas_muestreo';

    protected $fillable = [
        'categoria',
        'descripcion',
        'costo',
        'estado',
    ];

    protected $casts = [
        'costo' => 'decimal:2',
        'estado' => 'boolean',
    ];

    public function proformas()
    {
        return $this->belongsToMany(Proforma::class, 'proforma_logisticas')
            ->withPivot('cantidad', 'subtotal')
            ->withTimestamps();
    }
}
