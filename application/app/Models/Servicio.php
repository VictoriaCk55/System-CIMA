<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'proforma_id',
        'descripcion',
        'detalle',
        'precio',
    ];

    public function proforma()
    {
        return $this->belongsTo(Proforma::class);
    }
}
