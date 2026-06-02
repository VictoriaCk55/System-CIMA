<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Informe extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'codigo',
        'proforma_id',
        'fecha_emision',
        'fecha_entrega',
        'fecha_analisis',
        'fecha_revision',
        'resultado',
        'conclusiones',
        'recomendaciones',
        'observaciones',
        'archivo_adjunto',
        'archivo_resultados',
        'estado',
        'prioridad',
        'creado_por',
        'revisado_por',
        'aprobado_por',
        'entregado_por',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_entrega' => 'date',
        'fecha_analisis' => 'date',
        'fecha_revision' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Opciones de estado para el informe
    public const ESTADOS = [
        'BORRADOR' => 'Borrador',
        'EN_PROCESO' => 'En Proceso',
        'REVISADO' => 'Revisado',
        'APROBADO' => 'Aprobado',
        'ENTREGADO' => 'Entregado',
    ];

    // Opciones de prioridad
    public const PRIORIDADES = [
        'BAJA' => 'Baja',
        'MEDIA' => 'Media',
        'ALTA' => 'Alta',
        'URGENTE' => 'Urgente',
    ];

    // Relación con la proforma
    public function proforma(): BelongsTo
    {
        return $this->belongsTo(Proforma::class);
    }

    // Relación con el usuario creador
    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    // Relación con el usuario revisor
    public function revisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    // Relación con el usuario aprobador
    public function aprobador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    // Relación con el usuario que entrega
    public function entregador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entregado_por');
    }

    // Accessor para estado legible
    protected function estadoTexto(): Attribute
    {
        return Attribute::make(
            get: fn () => self::ESTADOS[$this->estado] ?? $this->estado,
        );
    }

    // Accessor para prioridad legible
    protected function prioridadTexto(): Attribute
    {
        return Attribute::make(
            get: fn () => self::PRIORIDADES[$this->prioridad] ?? $this->prioridad,
        );
    }

    // Accessor para color del estado (para badges)
    protected function estadoColor(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->estado) {
                    'BORRADOR' => 'secondary',
                    'EN_PROCESO' => 'warning',
                    'REVISADO' => 'info',
                    'APROBADO' => 'success',
                    'ENTREGADO' => 'primary',
                    default => 'light',
                };
            }
        );
    }

    // Accessor para color de la prioridad
    protected function prioridadColor(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->prioridad) {
                    'BAJA' => 'success',
                    'MEDIA' => 'info',
                    'ALTA' => 'warning',
                    'URGENTE' => 'danger',
                    default => 'light',
                };
            }
        );
    }

    // Scope para filtrar por estado
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    // Scope para filtrar por prioridad
    public function scopePorPrioridad($query, $prioridad)
    {
        return $query->where('prioridad', $prioridad);
    }

    // Scope para informes pendientes (no entregados)
    public function scopePendientes($query)
    {
        return $query->where('estado', '!=', 'ENTREGADO');
    }

    /**
     * Generar código automático para informes
     * ✅ CORREGIDO: Manejo seguro de códigos existentes
     */
    public static function generarCodigo(): string
    {
        // Obtener el último informe (incluyendo eliminados)
        $ultimo = self::withTrashed()->orderBy('id', 'desc')->first();

        if (! $ultimo) {
            return 'INF-001';
        }

        // Extraer el número del código (asumiendo formato INF-XXX)
        $partes = explode('-', $ultimo->codigo);
        if (count($partes) != 2 || ! is_numeric($partes[1])) {
            return 'INF-001';
        }

        $numero = intval($partes[1]) + 1;

        return 'INF-'.str_pad($numero, 3, '0', STR_PAD_LEFT);
    }
}
