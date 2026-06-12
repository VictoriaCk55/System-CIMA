<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteAmbiental extends Model
{
    use HasFactory;

    protected $table = 'reportes_ambientales';

    protected $fillable = [
        'proforma_id',
        'codigo_reporte',
        'fecha_emision',
        'fecha_medicion',
        'fecha_inicio_muestreo',
        'fecha_fin_muestreo',
        'periodo_medicion',
        'tipo_muestreo',
        'tipo_medicion',
        'medicion_efectuada_por',
        'equipo_usado',
        'condiciones_muestreo',
        'condiciones_reporte',
        'comentarios',
        'responsable_uia',
        'cargo_responsable',
        'directora_cima',
        'cargo_directora',
        'resultados_aire',
        'resultados_ruido',
        'resultados_gases',
        'puntos_medicion',
        'subtipo_ruido',
        'unidad_ruido',
        'observaciones_aire',
        'observaciones_ruido',
        'observaciones_gases',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'resultados_aire' => 'array',
            'resultados_ruido' => 'array',
            'resultados_gases' => 'array',
            'puntos_medicion' => 'array',
            'unidad_ruido' => 'array',
            'fecha_emision' => 'date',
            'fecha_medicion' => 'date',
            'fecha_inicio_muestreo' => 'date',
            'fecha_fin_muestreo' => 'date',
        ];
    }

    public function proforma()
    {
        return $this->belongsTo(Proforma::class);
    }

    public function categoriasPresentes(): array
    {
        return $this->proforma->parametros()
            ->where('tipo', 'AMBIENTAL')
            ->get()
            ->pluck('categoria')
            ->unique()
            ->values()
            ->toArray();
    }

    public function codigoAire(): string
    {
        $year = now()->format('y');

        return "UIA-REP-PRT-{$this->proforma_id}/{$year}";
    }

    public function codigoRuido(): string
    {
        $year = now()->format('y');
        $sub = $this->subtipo_ruido === 'INDUSTRIAL' ? 'RUIND' : 'RUAM';

        return "UIA-REP-{$sub}-{$this->proforma_id}/{$year}";
    }

    public function codigoGases(): string
    {
        $year = now()->format('y');

        return "UIA-REP-GS-{$this->proforma_id}/{$year}";
    }
}
