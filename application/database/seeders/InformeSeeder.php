<?php

namespace Database\Seeders;

use App\Models\Informe;
use App\Models\Proforma;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InformeSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📄 Creando informes...');

        // Verificar si hay proformas
        $proformas = Proforma::all();

        if ($proformas->isEmpty()) {
            $this->command->error('❌ No hay proformas. Ejecuta primero ProformaSeeder');

            return;
        }

        $this->command->info("📊 Proformas disponibles: {$proformas->count()}");

        // Verificar cuántos informes ya existen
        $existentes = Informe::count();
        $this->command->info("📊 Informes existentes: {$existentes}");

        // Obtener IDs de proformas que YA TIENEN informe
        $proformasConInforme = Informe::pluck('proforma_id')->toArray();
        $this->command->info('📊 Proformas con informe: '.count($proformasConInforme));

        // Filtrar proformas que NO tienen informe
        $proformasSinInforme = $proformas->filter(function ($proforma) use ($proformasConInforme) {
            return ! in_array($proforma->id, $proformasConInforme);
        });

        $this->command->info('📊 Proformas sin informe disponibles: '.$proformasSinInforme->count());

        if ($proformasSinInforme->isEmpty()) {
            $this->command->info('✅ Todas las proformas ya tienen informe. No se crearán más.');

            return;
        }

        $contador = $existentes + 1;
        $creados = 0;

        foreach ($proformasSinInforme as $proforma) {
            // Generar código único
            $codigo = 'INF-'.str_pad($contador, 3, '0', STR_PAD_LEFT).'-2026';

            // Verificar si ya existe este código (por si acaso)
            while (Informe::where('codigo', $codigo)->exists()) {
                $contador++;
                $codigo = 'INF-'.str_pad($contador, 3, '0', STR_PAD_LEFT).'-2026';
            }

            $estado = collect(['BORRADOR', 'EN_PROCESO', 'REVISADO', 'APROBADO'])->random();

            DB::table('informes')->insert([
                'codigo' => $codigo,
                'proforma_id' => $proforma->id,
                'fecha_emision' => now(),
                'fecha_entrega' => now()->addDays(7),
                'fecha_analisis' => now()->addDays(2),
                'fecha_revision' => now()->addDays(3),
                'resultado' => 'Resultados dentro de parámetros normales',
                'conclusiones' => 'Las muestras analizadas cumplen con los estándares requeridos',
                'recomendaciones' => 'Continuar con el monitoreo periódico',
                'observaciones' => 'Sin observaciones adicionales',
                'estado' => $estado,
                'prioridad' => collect(['BAJA', 'MEDIA', 'ALTA'])->random(),
                'creado_por' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info("✅ Informe {$codigo} creado para proforma {$proforma->codigo}");
            $creados++;
            $contador++;
        }

        if ($creados > 0) {
            $this->command->info("🎉 {$creados} informes nuevos creados");
        } else {
            $this->command->info('ℹ️ No se crearon informes nuevos');
        }
    }
}
