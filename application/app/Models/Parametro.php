<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parametro extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'nombre_completo',
        'metodo',
        'descripcion',
        'codigo_poe',
        'limite_cuantificacion',
        'unidad',
        'unidad_default',
        'matriz',
        'tecnica',
        'precio_unitario',
        'tipo',
        'categoria',
        'tipo_medicion',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
    ];

    protected $dates = ['deleted_at'];

    public function proformas()
    {
        return $this->belongsToMany(Proforma::class, 'proforma_parametro')
            ->withPivot('cantidad_muestras', 'precio_unitario', 'metodo')
            ->withTimestamps();
    }
}
