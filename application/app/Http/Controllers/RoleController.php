<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->orderBy('name')->paginate(10);

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get();

        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->filled('permissions')) {
            $role->syncPermissions(Permission::whereIn('id', $request->permissions)->get());
        }

        return redirect()->route('roles.index')
            ->with('success', "Rol '{$role->name}' creado exitosamente.");
    }

    public function edit(Role $role)
    {
        if ($role->name === 'admin') {
            return redirect()->route('roles.index')
                ->with('warning', 'El rol administrador no puede ser editado.');
        }

        $permissions = Permission::orderBy('name')->get();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        if ($role->name === 'admin') {
            return redirect()->route('roles.index')
                ->with('error', 'El rol administrador no puede ser modificado.');
        }

        $request->validate([
            'name' => "required|string|max:255|unique:roles,name,{$role->id}",
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update(['name' => $request->name]);

        if ($request->filled('permissions')) {
            $role->syncPermissions(Permission::whereIn('id', $request->permissions)->get());
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('roles.index')
            ->with('success', "Rol '{$role->name}' actualizado exitosamente.");
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'admin') {
            return redirect()->route('roles.index')
                ->with('error', 'El rol administrador no puede ser eliminado.');
        }

        $name = $role->name;
        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', "Rol '{$name}' eliminado exitosamente.");
    }
}
