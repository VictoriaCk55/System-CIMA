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
        'info_aire',
        'info_gases',
        'info_ruido',
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
            'info_aire' => 'array',
            'info_gases' => 'array',
            'info_ruido' => 'array',
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

    public function info(string $categoria): array
    {
        $columna = 'info_'.strtolower($categoria);
        $guardado = is_array($this->{$columna} ?? null) ? $this->{$columna} : [];

        return array_merge([
            'codigo_reporte' => $this->codigo_reporte,
            'fecha_emision' => $this->fecha_emision?->format('Y-m-d'),
            'fecha_medicion' => $this->fecha_medicion?->format('Y-m-d'),
            'fecha_inicio_muestreo' => $this->fecha_inicio_muestreo?->format('Y-m-d'),
            'fecha_fin_muestreo' => $this->fecha_fin_muestreo?->format('Y-m-d'),
            'periodo_medicion' => $this->periodo_medicion,
            'tipo_muestreo' => $this->tipo_muestreo,
            'tipo_medicion' => $this->tipo_medicion,
            'medicion_efectuada_por' => $this->medicion_efectuada_por,
            'equipo_usado' => $this->equipo_usado,
            'condiciones_muestreo' => $this->condiciones_muestreo,
            'condiciones_reporte' => $this->condiciones_reporte,
            'subtipo_ruido' => $this->subtipo_ruido,
        ], $guardado);
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
        $sub = str_contains($this->info('RUIDO')['subtipo_ruido'] ?? '', 'INDUSTRIAL') ? 'RUIND' : 'RUAM';

        return "UIA-REP-{$sub}-{$this->proforma_id}/{$year}";
    }

    public function codigoGases(): string
    {
        $year = now()->format('y');

        return "UIA-REP-GS-{$this->proforma_id}/{$year}";
    }
}
