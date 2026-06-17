<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProformaResultadoAuditoria extends Model
{
    protected $table = 'proforma_resultado_auditoria';

    protected $fillable = [
        'proforma_id',
        'parametro_id',
        'campo_modificado',
        'valor_anterior',
        'valor_nuevo',
        'motivo',
        'user_id',
        'tipo',
    ];

    public function proforma(): BelongsTo
    {
        return $this->belongsTo(Proforma::class);
    }

    public function parametro(): BelongsTo
    {
        return $this->belongsTo(Parametro::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
