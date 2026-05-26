<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use database\seeders\UserSeeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ── CREAR PERMISOS ──────────────────────────────────────────────

        // Usuarios
        Permission::firstOrCreate(['name' => 'ver usuarios']);
        Permission::firstOrCreate(['name' => 'crear usuarios']);
        Permission::firstOrCreate(['name' => 'editar usuarios']);
        Permission::firstOrCreate(['name' => 'eliminar usuarios']);

        // Clientes
        Permission::firstOrCreate(['name' => 'ver clientes']);
        Permission::firstOrCreate(['name' => 'crear clientes']);
        Permission::firstOrCreate(['name' => 'editar clientes']);
        Permission::firstOrCreate(['name' => 'eliminar clientes']);

        // Parámetros
        Permission::firstOrCreate(['name' => 'ver parametros']);
        Permission::firstOrCreate(['name' => 'crear parametros']);
        Permission::firstOrCreate(['name' => 'editar parametros']);
        Permission::firstOrCreate(['name' => 'eliminar parametros']);

        // Proformas
        Permission::firstOrCreate(['name' => 'ver proformas']);
        Permission::firstOrCreate(['name' => 'crear proformas']);
        Permission::firstOrCreate(['name' => 'editar proformas']);
        Permission::firstOrCreate(['name' => 'eliminar proformas']);

        // Informes
        Permission::firstOrCreate(['name' => 'ver informes']);
        Permission::firstOrCreate(['name' => 'crear informes']);
        Permission::firstOrCreate(['name' => 'editar informes']);
        Permission::firstOrCreate(['name' => 'eliminar informes']);

        // Resultados de ensayo
        Permission::firstOrCreate(['name' => 'ver resultados']);
        Permission::firstOrCreate(['name' => 'crear resultados']);
        Permission::firstOrCreate(['name' => 'editar resultados']);

        // Financiero
        Permission::firstOrCreate(['name' => 'ver financiero']);

        // ── CREAR ROLES Y ASIGNAR PERMISOS ─────────────────────────────

        // 👑 ADMIN — acceso total
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        // 🔧 TÉCNICO — clientes, parámetros, proformas, informes
        $tecnico = Role::firstOrCreate(['name' => 'tecnico']);
        $tecnico->syncPermissions([
            'ver usuarios',
            'ver clientes', 'crear clientes', 'editar clientes', 'eliminar clientes',
            'ver parametros', 'crear parametros', 'editar parametros', 'eliminar parametros',
            'ver proformas', 'crear proformas', 'editar proformas', 'eliminar proformas',
            'ver informes', 'crear informes', 'editar informes', 'eliminar informes',
            'ver financiero',
        ]);

        // 🔬 ANALISTA — solo lectura + resultados de ensayo
        $analista = Role::firstOrCreate(['name' => 'analista']);
        $analista->syncPermissions([
            'ver clientes',
            'ver parametros',
            'ver proformas',
            'ver informes',
            'ver resultados', 'crear resultados', 'editar resultados',
            'ver financiero',
        ]);

        // ── ASIGNAR ROLES A USUARIOS ────────────────────────────────────

        // Admin
        $user = User::where('email', 'marcela@cima.edu.bo')->first();
        if ($user) $user->syncRoles('admin');

        // Técnicos
        foreach (['carla@cima.edu.bo', 'tatiana@cima.edu.bo', 'felix@cima.edu.bo'] as $email) {
            $user = User::where('email', $email)->first();
            if ($user) $user->syncRoles('tecnico');
        }

        // Analistas
        foreach (['mayra@cima.edu.bo', 'elena@cima.edu.bo', 'yasmin@cima.edu.bo'] as $email) {
            $user = User::where('email', $email)->first();
            if ($user) $user->syncRoles('analista');
        }

        $this->command->info('✅ Roles y permisos creados y asignados correctamente');
    }
}