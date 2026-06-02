<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de login
     */
    public function showLoginForm()
    {
        // Si ya está autenticado, redirigir al home
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Procesa el login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        // dd($credentials);
        // Intentar autenticación
        // dd(Auth::attempt($credentials, $request->filled('remember')));
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Redirigir al dashboard
            return redirect()->intended('/')
                ->with('success', '¡Bienvenido al sistema CIMA!');
        }

        // Si falla la autenticación
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son válidas.',
        ])->onlyInput('email');
    }

    /**
     * Cierra la sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'Sesión cerrada exitosamente.');
    }
}
