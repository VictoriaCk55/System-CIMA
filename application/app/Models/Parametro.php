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
        'metodo',
        'precio_unitario',
        'tipo',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
    ];

    protected $dates = ['deleted_at'];

    public function proformas()
    {
        return $this->belongsToMany(Proforma::class, 'proforma_parametro')
                    ->withPivot('cantidad_muestras', 'precio_unitario')
                    ->withTimestamps();
    }
}