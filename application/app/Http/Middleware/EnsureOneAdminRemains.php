<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class EnsureOneAdminRemains
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Verificar después de la ejecución de la ruta
        $adminCount = User::where('role', 'admin')
            ->whereNull('deleted_at')
            ->count();

        if ($adminCount === 0) {
            // Buscar el primer administrador incluso inactivo y restaurarlo
            $lastAdmin = User::where('role', 'admin')
                ->withTrashed()
                ->first();

            if ($lastAdmin && $lastAdmin->trashed()) {
                $lastAdmin->restore();

                // Redirigir con advertencia
                return redirect()->route('users.index')
                    ->with('warning', '⚠️ Se restauró automáticamente al último administrador del sistema.');
            }
        }

        return $response;
    }
}
