<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Solo admin y tecnico pueden pasar por rutas protegidas
        // Verifica tanto columna role como Spatie roles
        $allowedRoles = ['admin', 'tecnico', 'analista'];
        $hasColumnRole = in_array($user->role, $allowedRoles);
        $hasSpatieRole = $user->hasAnyRole($allowedRoles);

        if (! $hasColumnRole && ! $hasSpatieRole) {
            return redirect()->route('home')
                ->with('error', '⛔ Acceso denegado.');
        }

        return $next($request);
    }
}
