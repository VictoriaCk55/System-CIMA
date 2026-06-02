<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    protected $table = 'configuraciones';

    protected $fillable = [
        'institucion_nombre',
        'laboratorio_nombre',
        'direccion',
        'telefono',
        'email',
        'logo_path',
        'footer_texto',
        'footer_direccion',
        'footer_telefono',
        'footer_email',
        'responsable_nombre',
        'responsable_cargo',
        'director_nombre',
        'director_cargo',
        'firma_path',
    ];

    public static function obtener()
    {
        return static::first() ?? new static;
    }
}
