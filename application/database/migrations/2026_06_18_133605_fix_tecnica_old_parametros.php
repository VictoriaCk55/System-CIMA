<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $updates = [
            ['nombre' => 'Solidos Suspendidos T.', 'tecnica' => 'GRAVIMETRIA'],
            ['nombre' => 'Aceites y Grasas',       'tecnica' => 'GRAVIMETRIA'],
            ['nombre' => 'DBO5',                    'tecnica' => 'VOLUMETRIA'],
            ['nombre' => 'Coliformes Fecales',      'tecnica' => 'BACTEREOLOGIA'],
            ['nombre' => 'E. Coli',                 'tecnica' => 'BACTEREOLOGIA'],
            ['nombre' => 'Cianuro Libre',           'tecnica' => 'IONOMETRIA'],
            ['nombre' => 'Sulfatos',                'tecnica' => 'GRAVIMETRIA'],
            ['nombre' => 'Cromo hexavalente.-Cr+6', 'tecnica' => 'UV-VISIBLE'],
            ['nombre' => 'Sulfuros - S=',           'tecnica' => 'UV-VISIBLE'],
            ['nombre' => 'Cloro Residual',          'tecnica' => 'FOTOMETRIA'],
            ['nombre' => 'ph',                      'tecnica' => 'POTENCIOMETRIA'],
        ];

        foreach ($updates as $param) {
            DB::table('parametros')
                ->where('nombre', $param['nombre'])
                ->whereNull('tecnica')
                ->update(['tecnica' => $param['tecnica']]);
        }
    }

    public function down(): void
    {
        $nombres = [
            'Solidos Suspendidos T.', 'Aceites y Grasas', 'DBO5',
            'Coliformes Fecales', 'E. Coli', 'Cianuro Libre',
            'Sulfatos', 'Cromo hexavalente.-Cr+6', 'Sulfuros - S=',
            'Cloro Residual', 'ph',
        ];

        DB::table('parametros')
            ->whereIn('nombre', $nombres)
            ->update(['tecnica' => null]);
    }
};
