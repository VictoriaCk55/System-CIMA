<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class RestoreLastAdmin extends Command
{
    protected $signature = 'admins:restore-last';

    protected $description = 'Restaura al último administrador eliminado';

    public function handle()
    {
        $lastAdmin = User::where('role', 'admin')
            ->onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->first();

        if ($lastAdmin) {
            $lastAdmin->restore();
            $this->info("✅ Administrador '{$lastAdmin->name}' ha sido restaurado.");
            $this->info("Email: {$lastAdmin->email}");
            $this->warn('Si olvidó la contraseña, cámbiela desde el perfil.');
        } else {
            $this->error('No hay administradores inactivos para restaurar.');
        }
    }
}
