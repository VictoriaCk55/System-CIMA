<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'razon_social',
        'persona_contacto',
        'telefono',
        'nit',
        'direccion',
    ];

    protected $dates = ['deleted_at'];

    public function proformas()
    {
        return $this->hasMany(Proforma::class);
    }
}
