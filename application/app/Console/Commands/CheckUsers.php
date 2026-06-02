<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckUsers extends Command
{
    protected $signature = 'cima:check-users';

    protected $description = 'Verificar usuarios del sistema CIMA';

    public function handle()
    {
        $this->info('=== USUARIOS DEL SISTEMA CIMA ===');

        $users = User::all();

        if ($users->count() === 0) {
            $this->error('No hay usuarios en el sistema.');

            return;
        }

        $headers = ['ID', 'Nombre', 'Email', 'Creado'];
        $data = [];

        foreach ($users as $user) {
            $data[] = [
                $user->id,
                $user->name,
                $user->email,
                $user->created_at->format('d/m/Y H:i'),
            ];
        }

        $this->table($headers, $data);

        $this->info("\nCredenciales para pruebas:");
        $this->line('👑 Administrador: admin@cima.edu.bo / CIMA-2026');
        $this->line('👷 Técnico: tecnico@cima.edu.bo / tecnico123');
    }
}
