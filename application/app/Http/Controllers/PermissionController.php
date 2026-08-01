<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissions = Permission::with('roles')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->whereRaw('LOWER(name) LIKE LOWER(?)', ['%'.$request->search.'%']);
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('permissions._tabla', compact('permissions'))->render(),
            ]);
        }

        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);

        return redirect()->route('permissions.index')
            ->with('success', "Permiso '{$request->name}' creado exitosamente.");
    }

    public function edit(Permission $permission)
    {
        $roles = Role::orderBy('name')->get();
        $assignedRoles = $permission->roles->pluck('id')->toArray();

        return view('permissions.edit', compact('permission', 'roles', 'assignedRoles'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => "required|string|max:255|unique:permissions,name,{$permission->id}",
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $permission->update(['name' => $request->name]);

        if ($request->filled('roles')) {
            $permission->syncRoles(Role::whereIn('id', $request->roles)->get());
        } else {
            $permission->syncRoles([]);
        }

        return redirect()->route('permissions.index')
            ->with('success', "Permiso '{$permission->name}' actualizado exitosamente.");
    }

    public function destroy(Permission $permission)
    {
        $name = $permission->name;
        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', "Permiso '{$name}' eliminado exitosamente.");
    }
}
