<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usar firstOrCreate para evitar duplicados
        User::firstOrCreate(
            ['email' => 'admin@cima.edu.bo'],
            [
                'name' => 'Administrador CIMA',
                'password' => Hash::make('CIMA-2026'),
                'role' => 'admin'
            ]
        );

        User::firstOrCreate(
            ['email' => 'tecnico@cima.edu.bo'],
            [
                'name' => 'Técnico de Laboratorio',
                'password' => Hash::make('tecnico123'),
                'role' => 'tecnico'
            ]
        );

        $this->command->info('✅ Usuarios verificados/creados correctamente');
    }
}