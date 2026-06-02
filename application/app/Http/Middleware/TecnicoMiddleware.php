<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TecnicoMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Solo tecnico puede acceder
        // Verifica tanto columna role como Spatie roles
        $hasColumnRole = $user->role === 'tecnico';
        $hasSpatieRole = $user->hasRole('tecnico');

        if (! $hasColumnRole && ! $hasSpatieRole) {
            return redirect()->route('home')
                ->with('error', '⛔ Acceso denegado. Solo el Técnico puede realizar esta acción.');
        }

        return $next($request);
    }
}
