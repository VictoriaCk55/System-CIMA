<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'nombre',
        'codigo_documento',
        'version',
        'fecha_documento',
        'config',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'config' => 'array',
            'activo' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function config(string $key, mixed $default = null): mixed
    {
        return data_get($this->config, $key, $default);
    }
}
