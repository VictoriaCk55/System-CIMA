<?php

namespace Database\Seeders;

use App\Models\LogisticaMuestreo;
use Illuminate\Database\Seeder;

class LogisticaMuestreoSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['categoria' => 'PUNTOS', 'descripcion' => '1 a 6 puntos', 'costo' => 300],
            ['categoria' => 'PUNTOS', 'descripcion' => '7 a 12 puntos', 'costo' => 550],
            ['categoria' => 'PUNTOS', 'descripcion' => '13 a 18 puntos', 'costo' => 800],
            ['categoria' => 'PUNTOS', 'descripcion' => '19 a 20 puntos', 'costo' => 1050],
            ['categoria' => 'LOCAL', 'descripcion' => 'Media jornada transporte del cliente', 'costo' => 250],
            ['categoria' => 'LOCAL', 'descripcion' => 'Media jornada sin transporte', 'costo' => 400],
            ['categoria' => 'DEPARTAMENTAL', 'descripcion' => '1 día', 'costo' => 1400],
            ['categoria' => 'DEPARTAMENTAL', 'descripcion' => '2 días', 'costo' => 1900],
            ['categoria' => 'DEPARTAMENTAL', 'descripcion' => '3 días', 'costo' => 2450],
            ['categoria' => 'DEPARTAMENTAL', 'descripcion' => '4 días', 'costo' => 3000],
            ['categoria' => 'NACIONAL', 'descripcion' => '1 día', 'costo' => 1500],
            ['categoria' => 'NACIONAL', 'descripcion' => '2 días', 'costo' => 2350],
            ['categoria' => 'NACIONAL', 'descripcion' => '3 días', 'costo' => 3200],
            ['categoria' => 'NACIONAL', 'descripcion' => '4 días', 'costo' => 4100],
        ];

        foreach ($items as $item) {
            LogisticaMuestreo::create($item);
        }
    }
}
