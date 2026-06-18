<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $updates = [
            ['nombre' => 'Solidos Suspendidos T.', 'codigo_poe' => 'POE-1-912'],
            ['nombre' => 'Aceites y Grasas',       'codigo_poe' => 'POE-1-962'],
            ['nombre' => 'DBO5',                    'codigo_poe' => 'POE-1-803'],
            ['nombre' => 'Coliformes Fecales',      'codigo_poe' => 'POE-1-103'],
            ['nombre' => 'E. Coli',                 'codigo_poe' => 'POE-1-105'],
            ['nombre' => 'Cianuro Libre',           'codigo_poe' => 'POE-1-780'],
            ['nombre' => 'Sulfatos',                'codigo_poe' => 'POE-1-942'],
            ['nombre' => 'Cromo hexavalente.-Cr+6', 'codigo_poe' => 'POE-1-660'],
            ['nombre' => 'Sulfuros - S=',           'codigo_poe' => 'POE-1-720'],
        ];

        foreach ($updates as $param) {
            DB::table('parametros')
                ->where('nombre', $param['nombre'])
                ->whereNull('codigo_poe')
                ->update(['codigo_poe' => $param['codigo_poe']]);
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
            ->update(['codigo_poe' => null]);
    }
};
