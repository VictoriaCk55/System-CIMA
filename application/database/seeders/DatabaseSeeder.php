<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * EJECUTA TODOS LOS SEEDERS EN EL ORDEN CORRECTO
     */
    public function run(): void
    {
        $this->command->info('🚀 INICIANDO SEEDERS DEL SISTEMA CIMA...');
        $this->command->line('----------------------------------------');
        
        // ===== 1. PRIMERO: TABLAS INDEPENDIENTES =====
        $this->command->info('📦 Ejecutando seeders de tablas independientes...');
        
        // Usuarios (independiente)
        $this->call(UserSeeder::class);
        
        // Parámetros (independiente)
        $this->call(ParametroSeeder::class);
        
        // Clientes (independiente)
        $this->call(ClienteSeeder::class);
        
        $this->command->line('----------------------------------------');
        
        // ===== 2. SEGUNDO: TABLAS QUE DEPENDEN DE OTROS =====
        $this->command->info('📊 Ejecutando seeders de tablas con dependencias...');
        
        // Proformas (necesita clientes y parámetros)
        $this->call(ProformaSeeder::class);
        
        // Informes (necesita proformas)
        $this->call(InformeSeeder::class);
        
        $this->command->line('----------------------------------------');
        $this->command->info('✅ ¡TODOS LOS SEEDERS COMPLETADOS EXITOSAMENTE!');
    }
}