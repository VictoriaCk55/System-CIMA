<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Parametro;
use App\Models\Proforma;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProformaSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📝 Creando proformas...');

        // Verificar que hay clientes y parámetros
        $clientes = Cliente::all();
        $parametros = Parametro::all();

        if ($clientes->isEmpty()) {
            $this->command->error('❌ No hay clientes. Ejecuta primero ClienteSeeder');

            return;
        }

        if ($parametros->isEmpty()) {
            $this->command->error('❌ No hay parámetros. Ejecuta primero ParametroSeeder');

            return;
        }

        $this->command->info("📊 Clientes disponibles: {$clientes->count()}");
        $this->command->info("📊 Parámetros disponibles: {$parametros->count()}");

        // Verificar cuántas proformas ya existen
        $existentes = Proforma::count();
        $this->command->info("📊 Proformas existentes: {$existentes}");

        // Definir cuántas proformas crear
        $totalAcrear = 3;

        if ($existentes >= $totalAcrear) {
            $this->command->info("⚠️ Ya existen {$existentes} proformas. No se crearán más.");

            return;
        }

        $tipos = ['AMBIENTAL', 'AGUA', 'INVESTIGACION'];
        $creadas = 0;

        for ($i = $existentes + 1; $i <= $totalAcrear; $i++) {
            $tipoAleatorio = $tipos[array_rand($tipos)];
            $codigo = str_pad($i, 3, '0', STR_PAD_LEFT).'-'.substr($tipoAleatorio, 0, 3);

            // Verificar si ya existe este código
            if (Proforma::where('codigo', $codigo)->exists()) {
                $this->command->warn("⚠️ La proforma {$codigo} ya existe. Buscando otro código...");
                // Generar código alternativo
                $codigo = str_pad($i + 10, 3, '0', STR_PAD_LEFT).'-'.substr($tipoAleatorio, 0, 3);
                if (Proforma::where('codigo', $codigo)->exists()) {
                    $this->command->error('❌ No se pudo generar código único');

                    continue;
                }
            }

            $cliente = $clientes->random();

            // Calcular subtotal con parámetros aleatorios
            $paramsSeleccionados = $parametros->random(rand(2, 4));
            $subtotal = 0;
            $items = [];

            foreach ($paramsSeleccionados as $param) {
                $cantidad = rand(1, 3);
                $precio = $param->precio_unitario;
                $subtotal += $cantidad * $precio;
                $items[$param->id] = [
                    'cantidad_muestras' => $cantidad,
                    'precio_unitario' => $precio,
                ];
            }

            $descuento = ($tipoAleatorio == 'INVESTIGACION') ? $subtotal * 0.20 : 0;
            $total = $subtotal - $descuento;

            // Crear proforma
            $proforma = Proforma::create([
                'codigo' => $codigo,
                'tipo' => $tipoAleatorio,
                'cliente_id' => $cliente->id,
                'user_id' => 1,
                'subtotal' => $subtotal,
                'descuento' => $descuento,
                'total' => $total,
                'adelanto' => $total * 0.3,
                'saldo' => $total * 0.7,
                'fecha_recepcion' => now()->subDays(rand(1, 10))->format('Y-m-d'),
                'fecha_emision' => now()->format('Y-m-d'),
                'estado' => collect(['BORRADOR', 'ENVIADA', 'APROBADA', 'RECHAZADA', 'FINALIZADA'])->random(),
                'tipo_muestra' => 'MUESTRA ESTÁNDAR',
                'created_at' => now()->subDays(rand(1, 5)),
                'updated_at' => now(),
            ]);

            // Adjuntar parámetros
            foreach ($items as $parametroId => $data) {
                DB::table('proforma_parametro')->insert([
                    'proforma_id' => $proforma->id,
                    'parametro_id' => $parametroId,
                    'cantidad_muestras' => $data['cantidad_muestras'],
                    'precio_unitario' => $data['precio_unitario'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $this->command->info("✅ Proforma {$proforma->codigo} creada - Total: Bs. {$total}");
            $creadas++;
        }

        if ($creadas > 0) {
            $this->command->info("🎉 {$creadas} proformas nuevas creadas");
        } else {
            $this->command->info('ℹ️ No se crearon proformas nuevas');
        }
    }
}
