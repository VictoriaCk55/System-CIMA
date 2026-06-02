<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CadenaResultado extends Model
{
    protected $table = 'cadena_resultados';

    protected $fillable = [
        'proforma_id', 'cadena_custodia_id', 'parametro_id', 'parametro_nombre',
        'metodo_ensayo', 'limite_cuantificacion', 'unidad',
        'resultado', 'fecha_analisis', 'analizado_por', 'observaciones', 'orden', 'vb',
    ];

    protected $casts = [
        'fecha_analisis' => 'date',
    ];

    public function cadenaCustodia(): BelongsTo
    {
        return $this->belongsTo(CadenaCustodia::class);
    }

    public function parametro(): BelongsTo
    {
        return $this->belongsTo(Parametro::class);
    }
}
