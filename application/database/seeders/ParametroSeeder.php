<?php

namespace Database\Seeders;

use App\Models\Parametro;
use Illuminate\Database\Seeder;

class ParametroSeeder extends Seeder
{
    public function run(): void
    {
        $parametros = [
            // Parámetros AMBIENTALES
            [
                'nombre' => 'PST',
                'metodo' => 'TAS (Tactical Air Sampler) USA',
                'precio_unitario' => 200.00,
                'tipo' => 'AMBIENTAL',
            ],
            [
                'nombre' => 'PM-10',
                'metodo' => 'TAS (Tactical Air Sampler) USA',
                'precio_unitario' => 200.00,
                'tipo' => 'AMBIENTAL',
            ],
            [
                'nombre' => 'Ruido',
                'metodo' => 'SONÓMETRO',
                'precio_unitario' => 60.00,
                'tipo' => 'AMBIENTAL',
            ],
            [
                'nombre' => 'Logística de muestreo',
                'metodo' => 'Número de puntos totales',
                'precio_unitario' => 1220.00,
                'tipo' => 'AMBIENTAL',
            ],
            
            // Parámetros de AGUA
            [
                'nombre' => 'Conductividad',
                'metodo' => 'POE-1-02 Potenciometría',
                'precio_unitario' => 150.00,
                'tipo' => 'AGUA',
            ],
            [
                'nombre' => 'pH',
                'metodo' => 'POE-1-01 Potenciometría',
                'precio_unitario' => 120.00,
                'tipo' => 'AGUA',
            ],
            [
                'nombre' => 'Color',
                'metodo' => 'POE-1-71 Otros',
                'precio_unitario' => 180.00,
                'tipo' => 'AGUA',
            ],
            [
                'nombre' => 'Turbidez',
                'metodo' => 'POE-1-97 Nefelometria',
                'precio_unitario' => 160.00,
                'tipo' => 'AGUA',
            ],
            [
                'nombre' => 'Solidos Suspendidos T.',
                'metodo' => 'POE-1-91 Gravimetría',
                'precio_unitario' => 240.00,
                'tipo' => 'AGUA',
            ],
            [
                'nombre' => 'Aceites y Grasas',
                'metodo' => 'POE-1-96 Gravimetria',
                'precio_unitario' => 280.00,
                'tipo' => 'AGUA',
            ],
            [
                'nombre' => 'DBO5',
                'metodo' => 'POE-1-80 Volumetria',
                'precio_unitario' => 300.00,
                'tipo' => 'AGUA',
            ],
            [
                'nombre' => 'Coliformes Fecales',
                'metodo' => 'POE-1-103 Filtro de membrana',
                'precio_unitario' => 260.00,
                'tipo' => 'AGUA',
            ],
            [
                'nombre' => 'E. Coli',
                'metodo' => 'POE-1-105 Filtro de membrana',
                'precio_unitario' => 270.00,
                'tipo' => 'AGUA',
            ],
            [
                'nombre' => 'Cianuro Libre',
                'metodo' => 'POE-1-78 Ionometria',
                'precio_unitario' => 350.00,
                'tipo' => 'AGUA',
            ],
            [
                'nombre' => 'Cloro Residual',
                'metodo' => 'POE-1-58 Fotometria',
                'precio_unitario' => 190.00,
                'tipo' => 'AGUA',
            ],
            [
                'nombre' => 'Sulfatos',
                'metodo' => 'POE-1-60 UV Visible',
                'precio_unitario' => 230.00,
                'tipo' => 'AGUA',
            ],
        ];

        foreach ($parametros as $parametro) {
            Parametro::firstOrCreate(
                ['nombre' => $parametro['nombre']], // Buscar por nombre
                $parametro // Si no existe, crear con estos datos
            );
        }

        $this->command->info('✅ Parámetros verificados/creados correctamente');
    }
}