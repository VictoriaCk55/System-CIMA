<?php

namespace Database\Seeders;

use App\Models\LimitePermisible;
use Illuminate\Database\Seeder;

class LimitePermisibleSeeder extends Seeder
{
    public function run(): void
    {
        $anexoA2 = [
            ['Cobre', '1.0', '0.5'],
            ['Zinc', '3.0', '1.5'],
            ['Plomo', '0.6', '0.3'],
            ['Cadmio', '0.3', '0.15'],
            ['Arsénico', '1.0', '0.5'],
            ['Cromo +3', '1.0', '0.5'],
            ['Cromo +6', '0.1', '0.05'],
            ['Mercurio', '0.002', '0.001'],
            ['Fierro', '1.0', '0.5'],
            ['Antimonio (s)', '1.0', null],
            ['Estaño', '2.0', '1.0'],
            ['Cianuro (libre)', '0.2', '0.10'],
            ['Cianuro (total)', '0.5', '0.3'],
            ['pH', '6-9', '6-9'],
            ['Temperatura (*)', '+5°C', '+5°C'],
            ['Compuestos fenólicos', '1.0', '0.5'],
            ['Sólidos susp. Totales', '60.0', null],
            ['Coloidales (NMP/100 ml)', '1000', null],
            ['Aceite y Grasa (e)', '10.0', null],
            ['Aceite y Grasa (d)', '20.0', null],
            ['DBO5', '80.0', null],
            ['DQO (e)', '250.0', null],
            ['DQO (d)', '300.0', null],
            ['Amonio como N', '4.0', '2.0'],
            ['Sulfuros', '2.0', '1.0'],
        ];

        $nb512 = [
            ['Conductividad', '1500,0'],
            ['pH', '6,5 - 9,0'],
            ['Color', '15'],
            ['Turbidez', '5'],
            ['Fluoruros', '1,5'],
            ['Sólidos Disueltos Totales', '1000'],
            ['Cromo Total', '0,05'],
            ['Cloro Residual', '0,2 - 1,5'],
            ['Nitratos', '45,0'],
            ['Nitritos', '0,1'],
            ['Cadmio', '0,003'],
            ['Plomo', '0,01'],
            ['Sodio', '200,0'],
            ['Calcio', '200,0'],
            ['Magnesio', '150,0'],
            ['Boro', '0,3'],
            ['Alcalinidad', '370,0'],
            ['Cloruros', '250,0'],
            ['Dureza', '500,0'],
            ['Sulfatos', '400,0'],
            ['Zinc', '5,0'],
            ['Cobre', '1,0'],
            ['Hierro', '0,3'],
            ['Manganeso', '0,1'],
            ['Escherichia Coli', '<1'],
            ['Coliformes Fecales', '<1'],
        ];

        foreach ($anexoA2 as [$parametro, $diario, $mes]) {
            LimitePermisible::create([
                'tipo' => 'ANEXO_A-2',
                'parametro_nombre' => $parametro,
                'limite_diario' => $diario,
                'limite_mes' => $mes,
            ]);
        }

        foreach ($nb512 as [$parametro, $limite]) {
            LimitePermisible::create([
                'tipo' => 'NB-512',
                'parametro_nombre' => $parametro,
                'limite_permisible' => $limite,
            ]);
        }
    }
}
