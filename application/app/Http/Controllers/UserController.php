<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(10);

        return view('users.index', compact('users'));
    }

    public function trash()
    {
        $users = User::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(10);

        return view('users.trash', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')
            ->with('success', "Usuario '{$user->name}' creado exitosamente.");
    }

    public function show($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('name')->get();

        return view('users.edit', compact('user', 'roles'));
    }

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
            'role' => 'required|exists:roles,name',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        $user->syncRoles([$request->role]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('users.index')
            ->with('success', "Usuario '{$user->name}' actualizado exitosamente.");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'No puedes desactivar tu propio usuario.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "Usuario '{$name}' ha sido desactivado.");
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('users.trash')
            ->with('success', "Usuario '{$user->name}' ha sido activado nuevamente.");
    }

    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);

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
