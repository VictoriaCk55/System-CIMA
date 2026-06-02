<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
        ]);

        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            return redirect()->route('profile.edit')->with('success', 'Perfil actualizado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('profile.edit')->with('error', 'Error al actualizar: '.$e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return redirect()->route('profile.edit')->with('error', 'La contraseña actual es incorrecta');
        }

        try {
            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()->route('profile.edit')->with('success', 'Contraseña actualizada correctamente');
        } catch (\Exception $e) {
            return redirect()->route('profile.edit')->with('error', 'Error al cambiar contraseña');
        }
    }
}
