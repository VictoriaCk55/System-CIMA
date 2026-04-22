<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // ✅ SOLO ADMIN PUEDE PASAR
        if ($user->email !== 'admin@cima.edu.bo') {
            
            $url = $request->path();
            
            // ========== 🎯 REDIRECCIÓN AL INDEX CON MENSAJE GENÉRICO ==========
            
            // CLIENTES - TODAS las rutas de admin van al index
            if (str_contains($url, 'clientes/')) {
                return redirect()->route('clientes.index')
                    ->with('error', '⛔ Acceso denegado. Solo el administrador puede realizar esta acción.');
            }
            
            // PARÁMETROS - TODAS las rutas de admin van al index
            if (str_contains($url, 'parametros/')) {
                return redirect()->route('parametros.index')
                    ->with('error', '⛔ Acceso denegado. Solo el administrador puede realizar esta acción.');
            }
            
            // PROFORMAS - TODAS las rutas de admin van al index
            if (str_contains($url, 'proformas/')) {
                return redirect()->route('proformas.index')
                    ->with('error', '⛔ Acceso denegado. Solo el administrador puede realizar esta acción.');
            }
            
            // INFORMES - TODAS las rutas de admin van al index
            if (str_contains($url, 'informes/')) {
                return redirect()->route('informes.index')
                    ->with('error', '⛔ Acceso denegado. Solo el administrador puede realizar esta acción.');
            }
            
            // API
            if (str_contains($url, 'clientes/api')) {
                return response()->json([
                    'success' => false,
                    'message' => '⛔ Acceso denegado. Solo el administrador puede realizar esta acción.'
                ], 403);
            }
            
            // CUALQUIER OTRA RUTA PROTEGIDA
            return redirect()->route('home')
                ->with('error', '⛔ Acceso denegado. Solo el administrador puede realizar esta acción.');
        }

        return $next($request);
    }
}