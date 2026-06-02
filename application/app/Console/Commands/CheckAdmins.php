<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckAdmins extends Command
{
    protected $signature = 'admins:check';

    protected $description = 'Verifica que haya al menos un administrador activo';

    public function handle()
    {
        $adminCount = User::where('role', 'admin')
            ->whereNull('deleted_at')
            ->count();

        if ($adminCount === 0) {
            $this->error('⚠️ No hay administradores activos en el sistema!');

            // Buscar administradores inactivos
            $inactiveAdmins = User::where('role', 'admin')
                ->onlyTrashed()
                ->get();

            if ($inactiveAdmins->count() > 0) {
                $this->info('Administradores inactivos encontrados:');
                foreach ($inactiveAdmins as $admin) {
                    $this->line("  - {$admin->name} ({$admin->email}) - Desactivado: {$admin->deleted_at}");
                }
                $this->info('Ejecuta: php artisan admins:restore-last para restaurar al último admin');
            } else {
                $this->error('No hay ningún administrador registrado. Debes crear uno manualmente.');
            }

            return 1;
        }

        $this->info("✅ Sistema OK. Administradores activos: {$adminCount}");

        return 0;
    }
}
