<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Cuando se crea un usuario, asignar rol en Spatie basado en la columna 'role'
     */
    public function created(User $user)
    {
        if ($user->role) {
            $user->syncRoles([$user->role]);
        }
    }

    /**
     * Cuando se actualiza la columna 'role', sincronizar con Spatie
     */
    public function updated(User $user)
    {
        if ($user->wasChanged('role')) {
            $user->syncRoles([$user->role]);
        }
    }
}
