<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMINISTRADOR
        User::firstOrCreate(
            ['email' => 'admin@cima.edu.bo'],
            [
                'name' => 'Carla Salinas',
                'password' => Hash::make('cima2026'),
                'role' => 'admin',
            ]
        );

        // TÉCNICOS
        User::firstOrCreate(
            ['email' => 'carla@cima.edu.bo'],
            [
                'name' => 'Carla S',
                'password' => Hash::make('tecnico123'),
                'role' => 'tecnico',
            ]
        );
        User::firstOrCreate(
            ['email' => 'tatiana@cima.edu.bo'],
            [
                'name' => 'Tatiana Canaza',
                'password' => Hash::make('tecnico123'),
                'role' => 'tecnico',
            ]
        );
        User::firstOrCreate(
            ['email' => 'felix@cima.edu.bo'],
            [
                'name' => 'Felix Rodriguez',
                'password' => Hash::make('tecnico123'),
                'role' => 'tecnico',
            ]
        );

        // ANALISTAS
        User::firstOrCreate(
            ['email' => 'mayra@cima.edu.bo'],
            [
                'name' => 'Mayra Calderon',
                'password' => Hash::make('analista123'),
                'role' => 'analista',
            ]
        );
        User::firstOrCreate(
            ['email' => 'elena@cima.edu.bo'],
            [
                'name' => 'Elena Uño',
                'password' => Hash::make('analista123'),
                'role' => 'analista',
            ]
        );
        User::firstOrCreate(
            ['email' => 'yasmin@cima.edu.bo'],
            [
                'name' => 'Yasmin Choque',
                'password' => Hash::make('analista123'),
                'role' => 'analista',
            ]
        );

        $this->command->info('✅ Usuarios verificados/creados correctamente');
    }
}
