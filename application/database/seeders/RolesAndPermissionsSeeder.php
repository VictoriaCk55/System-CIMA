<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ── PERFILES ─────────────────────────────────────────────────────
        Permission::firstOrCreate(['name' => 'edit.profile']);
        Permission::firstOrCreate(['name' => 'update.profile']);
        Permission::firstOrCreate(['name' => 'update.password']);

        // ── USUARIOS ─────────────────────────────────────────────────────
        foreach (['ver', 'crear', 'editar', 'eliminar', 'restore', 'force-delete'] as $verb) {
            Permission::firstOrCreate(['name' => "$verb usuarios"]);
        }
        Permission::firstOrCreate(['name' => 'ver papelera usuarios']);

        // ── ROLES ────────────────────────────────────────────────────────
        foreach (['ver', 'crear', 'editar', 'eliminar'] as $verb) {
            Permission::firstOrCreate(['name' => "$verb roles"]);
        }

        // ── PERMISOS ─────────────────────────────────────────────────────
        foreach (['ver', 'crear', 'editar', 'eliminar'] as $verb) {
            Permission::firstOrCreate(['name' => "$verb permisos"]);
        }

        // ── CLIENTES ─────────────────────────────────────────────────────
        foreach (['ver', 'crear', 'editar', 'eliminar', 'restore', 'force-delete'] as $verb) {
            Permission::firstOrCreate(['name' => "$verb clientes"]);
        }
        Permission::firstOrCreate(['name' => 'ver papelera clientes']);
        Permission::firstOrCreate(['name' => 'registrar pago clientes']);
        Permission::firstOrCreate(['name' => 'actualizar saldo clientes']);

        // ── PARÁMETROS ───────────────────────────────────────────────────
        foreach (['ver', 'crear', 'editar', 'eliminar', 'restore', 'force-delete'] as $verb) {
            Permission::firstOrCreate(['name' => "$verb parametros"]);
        }
        Permission::firstOrCreate(['name' => 'ver papelera parametros']);

        // ── PROFORMAS ────────────────────────────────────────────────────
        foreach (['ver', 'crear', 'editar', 'eliminar', 'restore', 'force-delete'] as $verb) {
            Permission::firstOrCreate(['name' => "$verb proformas"]);
        }
        Permission::firstOrCreate(['name' => 'cambiar estado proformas']);
        Permission::firstOrCreate(['name' => 'actualizar adelanto proformas']);
        Permission::firstOrCreate(['name' => 'ver papelera proformas']);
        Permission::firstOrCreate(['name' => 'generar pdf proformas']);

        // ── RESULTADOS ───────────────────────────────────────────────────
        foreach (['ver', 'crear', 'editar'] as $verb) {
            Permission::firstOrCreate(['name' => "$verb resultados"]);
        }
        Permission::firstOrCreate(['name' => 'guardar resultados']);
        Permission::firstOrCreate(['name' => 'cargar resultados']);
        Permission::firstOrCreate(['name' => 'limpiar resultados']);
        Permission::firstOrCreate(['name' => 'generar pdf resultados']);
        Permission::firstOrCreate(['name' => 'generar informe resultados']);

        // ── INFORMES ─────────────────────────────────────────────────────
        foreach (['ver', 'crear', 'editar', 'eliminar', 'restore', 'force-delete'] as $verb) {
            Permission::firstOrCreate(['name' => "$verb informes"]);
        }
        Permission::firstOrCreate(['name' => 'cambiar estado informes']);
        Permission::firstOrCreate(['name' => 'ver papelera informes']);
        Permission::firstOrCreate(['name' => 'generar pdf informes']);
        Permission::firstOrCreate(['name' => 'descargar informes']);

        // ── FINANCIERO ───────────────────────────────────────────────────
        Permission::firstOrCreate(['name' => 'ver financiero']);
        Permission::firstOrCreate(['name' => 'exportar financiero']);

        // ── CADENA DE CUSTODIA ───────────────────────────────────────────
        Permission::firstOrCreate(['name' => 'generar cadena custodia']);

        // ── ASIGNAR PERMISOS A ROLES ─────────────────────────────────────

        // Administrador — acceso total
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        // Técnico — gestión completa excepto roles/permisos
        $tecnico = Role::firstOrCreate(['name' => 'tecnico']);
        $tecnico->syncPermissions([
            'edit.profile', 'update.profile', 'update.password',

            'ver usuarios', 'crear usuarios', 'editar usuarios', 'eliminar usuarios',
            'restore usuarios', 'force-delete usuarios', 'ver papelera usuarios',

            'ver clientes', 'crear clientes', 'editar clientes', 'eliminar clientes',
            'restore clientes', 'force-delete clientes', 'ver papelera clientes',
            'registrar pago clientes', 'actualizar saldo clientes',

            'ver parametros', 'crear parametros', 'editar parametros', 'eliminar parametros',
            'restore parametros', 'force-delete parametros', 'ver papelera parametros',

            'ver proformas', 'crear proformas', 'editar proformas', 'eliminar proformas',
            'cambiar estado proformas', 'actualizar adelanto proformas',
            'restore proformas', 'force-delete proformas', 'ver papelera proformas',
            'generar pdf proformas',

            'ver resultados', 'crear resultados', 'editar resultados',
            'guardar resultados', 'cargar resultados', 'limpiar resultados',
            'generar pdf resultados', 'generar informe resultados',

            'ver informes', 'crear informes', 'editar informes', 'eliminar informes',
            'cambiar estado informes', 'restore informes', 'force-delete informes',
            'ver papelera informes', 'generar pdf informes', 'descargar informes',

            'ver financiero', 'exportar financiero',
            'generar cadena custodia',
        ]);

        // Analista — solo lectura + resultados
        $analista = Role::firstOrCreate(['name' => 'analista']);
        $analista->syncPermissions([
            'edit.profile', 'update.profile', 'update.password',

            'ver usuarios',

            'ver clientes',
            'ver parametros',

            'ver proformas', 'generar pdf proformas',

            'ver resultados', 'crear resultados', 'editar resultados',
            'guardar resultados', 'cargar resultados', 'limpiar resultados',
            'generar pdf resultados', 'generar informe resultados',

            'ver informes', 'generar pdf informes',

            'ver financiero',
            'generar cadena custodia',
        ]);

        // ── ASIGNAR ROLES A USUARIOS ────────────────────────────────────

        foreach ([
            'admin@cima.edu.bo' => 'admin',
            'carla@cima.edu.bo' => 'tecnico',
            'tatiana@cima.edu.bo' => 'tecnico',
            'felix@cima.edu.bo' => 'tecnico',
            'mayra@cima.edu.bo' => 'analista',
            'elena@cima.edu.bo' => 'analista',
            'yasmin@cima.edu.bo' => 'analista',
        ] as $email => $role) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->syncRoles($role);
            }
        }

        $this->command->info('Roles y permisos creados y asignados correctamente');
    }
}
