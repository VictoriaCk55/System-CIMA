<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $updates = [
            [
                'nombre' => 'Solidos Suspendidos T.',
                'limite_cuantificacion' => '2,0',
                'unidad' => 'mg/l',
            ],
            [
                'nombre' => 'Aceites y Grasas',
                'limite_cuantificacion' => '2,0',
                'unidad' => 'mg/l',
            ],
            [
                'nombre' => 'DBO5',
                'limite_cuantificacion' => '2',
                'unidad' => 'mg/l',
            ],
            [
                'nombre' => 'Coliformes Fecales',
                'limite_cuantificacion' => '---',
                'unidad' => 'UFC/100ml',
            ],
            [
                'nombre' => 'E. Coli',
                'limite_cuantificacion' => '---',
                'unidad' => 'UFC/100ml',
            ],
            [
                'nombre' => 'Cianuro Libre',
                'limite_cuantificacion' => '0,03',
                'unidad' => 'mg/l',
            ],
            [
                'nombre' => 'Sulfatos',
                'limite_cuantificacion' => '2,0',
                'unidad' => 'mg/l',
            ],
            [
                'nombre' => 'Cromo hexavalente.-Cr+6',
                'limite_cuantificacion' => '0,01',
                'unidad' => 'mg/l',
            ],
            [
                'nombre' => 'Sulfuros - S=',
                'limite_cuantificacion' => '0,0103',
                'unidad' => 'mg/l',
            ],
        ];

        foreach ($updates as $param) {
            DB::table('parametros')
                ->where('nombre', $param['nombre'])
                ->update([
                    'limite_cuantificacion' => $param['limite_cuantificacion'],
                    'unidad' => $param['unidad'],
                ]);
        }
    }

    public function down(): void
    {
        $nombres = [
            'Solidos Suspendidos T.', 'Aceites y Grasas', 'DBO5',
            'Coliformes Fecales', 'E. Coli', 'Cianuro Libre',
            'Sulfatos', 'Cromo hexavalente.-Cr+6', 'Sulfuros - S=',
        ];
        DB::table('parametros')
            ->whereIn('nombre', $nombres)
            ->update([
                'limite_cuantificacion' => null,
                'unidad' => null,
            ]);
    }
};
