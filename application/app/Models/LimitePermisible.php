<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LimitePermisible extends Model
{
    protected $table = 'limites_permisibles';

    protected $fillable = [
        'tipo',
        'parametro_nombre',
        'limite_diario',
        'limite_mes',
        'limite_permisible',
    ];

    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}
