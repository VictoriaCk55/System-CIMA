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
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Solo tecnico puede acceder
        if ($user->role !== 'tecnico') {
            return redirect()->route('home')
                ->with('error', '⛔ Acceso denegado. Solo el Técnico puede realizar esta acción.');
        }

        return $next($request);
    }
}
