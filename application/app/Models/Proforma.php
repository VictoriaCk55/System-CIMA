<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proforma extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'codigo',
        'cliente_id',
        'tipo',
        'tipo_muestra',
        'unidad',
        'fecha_emision',
        'fecha_recepcion',
        'persona_contacto',
        'telefono_contacto',
        'procedencia',
        'coordenadas',
        'muestreado_por',
        'adelanto',
        'observaciones',
        'subtotal',
        'descuento',
        'total',
        'saldo',
        'aplica_descuento_institucional',
        'estado',
        'parametros_modificados',
        'justificacion_modificacion',
        'modificado_por',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_recepcion' => 'date',
        'adelanto' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'descuento' => 'decimal:2',
        'total' => 'decimal:2',
        'saldo' => 'decimal:2',
        'aplica_descuento_institucional' => 'boolean',
        'parametros_modificados' => 'boolean',
    ];

    protected $dates = ['deleted_at'];

    // ========== ESTADOS PARA PROFORMAS ==========
    public const ESTADOS = [
        'BORRADOR' => 'Borrador',
        'ENVIADA' => 'Enviada',
        'APROBADA' => 'Aprobada',
        'RECHAZADA' => 'Rechazada',
        'FINALIZADA' => 'Finalizada',
    ];

    // ========== TIPOS DE PROFORMA ==========
    public const TIPOS = [
        'AMBIENTAL' => 'AMB',
        'AGUA' => 'AGUA',
        'INVESTIGACION' => 'INV',
    ];

    // ========== ACCESSORS ==========
    
    public function getEstadoTextoAttribute()
    {
        return self::ESTADOS[$this->estado] ?? $this->estado;
    }

    public function getEstadoColorAttribute()
    {
        return match($this->estado) {
            'BORRADOR' => 'secondary',
            'ENVIADA' => 'info',
            'APROBADA' => 'success',
            'RECHAZADA' => 'danger',
            'FINALIZADA' => 'dark',
            default => 'light',
        };
    }

    public function getEstadoIconoAttribute()
    {
        return match($this->estado) {
            'BORRADOR' => 'fa-edit',
            'ENVIADA' => 'fa-paper-plane',
            'APROBADA' => 'fa-check-circle',
            'RECHAZADA' => 'fa-times-circle',
            'FINALIZADA' => 'fa-flag-checkered',
            default => 'fa-file',
        };
    }

    /**
     * Generar código de proforma en formato: {unidad}-{tipo}-{numero}
     * Ejemplos: UIA-INV-001, UAQ-AMB-002, UAQ-AGUA-003
     */
    public static function generarCodigo($unidad, $tipo)
    {
        // Obtener el tipo abreviado
        $tipoAbr = self::TIPOS[$tipo] ?? 'GEN';
        
        // Si no hay unidad, usar 'GEN'
        $unidadAbr = $unidad ?? 'GEN';
        
        // Buscar el último número para esta combinación unidad-tipo
        // Buscar en formato nuevo: {unidad}-{tipo}-{numero}
        $ultimo = self::where('codigo', 'LIKE', $unidadAbr . '-' . $tipoAbr . '-%')
            ->orderBy('id', 'desc')
            ->first();
        
        if ($ultimo) {
            $partes = explode('-', $ultimo->codigo);
            $ultimoNumero = intval(end($partes));
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            // Si no hay códigos con el nuevo formato, buscar en formato antiguo
            // Formato antiguo: {numero}-{tipo} (ejemplo: 001-INV)
            $ultimoAntiguo = self::where('codigo', 'LIKE', '%-' . $tipoAbr)
                ->where('codigo', 'NOT LIKE', '%-%-%')
                ->orderBy('id', 'desc')
                ->first();
            
            if ($ultimoAntiguo) {
                // Extraer el número del formato antiguo
                $partes = explode('-', $ultimoAntiguo->codigo);
                $ultimoNumero = intval($partes[0]);
                $nuevoNumero = $ultimoNumero + 1;
            } else {
                $nuevoNumero = 1;
            }
        }
        
        return $unidadAbr . '-' . $tipoAbr . '-' . str_pad($nuevoNumero, 3, '0', STR_PAD_LEFT);
    }

    // ========== SCOPES ==========
    
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    public function scopeEditables($query)
    {
        return $query->where('estado', 'BORRADOR');
    }

    public function scopeNoEditables($query)
    {
        return $query->whereIn('estado', ['ENVIADA', 'APROBADA', 'RECHAZADA', 'FINALIZADA']);
    }

    // ========== RELACIONES ==========
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function parametros()
    {
        return $this->belongsToMany(Parametro::class, 'proforma_parametro')
                    ->withPivot('cantidad_muestras', 'precio_unitario')
                    ->withTimestamps();
    }

    public function informe()
    {
        return $this->hasOne(Informe::class);
    }

    public function movimientosFinancieros()
    {
        return $this->morphMany(MovimientoFinanciero::class, 'origen');
    }

    public function usuarioModificacion()
    {
        return $this->belongsTo(User::class, 'modificado_por');
    }

    // ========== MÉTODOS ==========
    public function calcularTotales()
    {
        $subtotal = 0;
        
        foreach ($this->parametros as $parametro) {
            $subtotal += $parametro->pivot->precio_unitario * $parametro->pivot->cantidad_muestras;
        }
        
        $this->aplica_descuento_institucional = ($this->tipo == 'INVESTIGACION');
        $descuento = $this->aplica_descuento_institucional ? $subtotal * 0.20 : 0;
        
        $total = $subtotal - $descuento;
        $saldo = $total - $this->adelanto;
        
        $this->subtotal = $subtotal;
        $this->descuento = $descuento;
        $this->total = $total;
        $this->saldo = $saldo;
        
        return $this;
    }
}