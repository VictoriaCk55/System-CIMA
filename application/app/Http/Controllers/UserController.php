<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Lista de usuarios (activos)
     */
    public function index()
    {
        $users = User::orderBy('name')->paginate(10);
        return view('users.index', compact('users'));
    }
    
    /**
     * Lista de usuarios inactivos (papelera)
     */
    public function trash()
    {
        $users = User::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(10);
        return view('users.trash', compact('users'));
    }
    
    /**
     * Formulario para crear usuario
     */
    public function create()
    {
        return view('users.create');
    }
    
    /**
     * Guardar nuevo usuario
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,tecnico,analista',
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        
        return redirect()->route('users.index')
            ->with('success', "Usuario '{$user->name}' creado exitosamente.");
    }
    
    /**
     * Mostrar detalle de usuario
     */
    public function show($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        return view('users.show', compact('user'));
    }
    
    /**
     * Formulario para editar usuario
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }
    
    /**
     * Actualizar usuario
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => 'required|in:admin,tecnico,analista',
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);
        
        // Si se proporciona nueva contraseña
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $user->update(['password' => Hash::make($request->password)]);
        }
        
        return redirect()->route('users.index')
            ->with('success', "Usuario '{$user->name}' actualizado exitosamente.");
    }
    
    /**
     * Desactivar usuario (soft delete)
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // No permitir desactivar el propio usuario
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'No puedes desactivar tu propio usuario.');
        }
        
        $name = $user->name;
        $user->delete();
        
        return redirect()->route('users.index')
            ->with('success', "Usuario '{$name}' ha sido desactivado.");
    }
    
    /**
     * Restaurar usuario (activar)
     */
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        
        return redirect()->route('users.trash')
            ->with('success', "Usuario '{$user->name}' ha sido activado nuevamente.");
    }
    
    /**
     * Eliminar permanentemente
     */
    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        
        // No permitir eliminar el propio usuario
        if ($user->id === Auth::id()) {
            return redirect()->route('users.trash')
                ->with('error', 'No puedes eliminar permanentemente tu propio usuario.');
        }
        
        $name = $user->name;
        $user->forceDelete();
        
        return redirect()->route('users.trash')
            ->with('success', "Usuario '{$name}' ha sido eliminado permanentemente.");
    }
}