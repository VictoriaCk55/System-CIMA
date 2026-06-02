@extends('layouts.app')

@section('title', 'Roles')

@section('content')
<div class="page-header">
    <h1>
        <i class="fas fa-user-tag text-primary"></i> Gestión de Roles
    </h1>
    <p class="page-subtitle">Administrar roles del sistema (Spatie)</p>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-list me-2"></i> Roles</span>
        <a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Rol
        </a>
    </div>
    <div class="card-body">
        @if($roles->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Guard</th>
                        <th>Permisos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>
                            @if($role->name === 'admin')
                                <span class="badge bg-danger">{{ $role->name }}</span>
                            @elseif($role->name === 'tecnico')
                                <span class="badge bg-info">{{ $role->name }}</span>
                            @elseif($role->name === 'analista')
                                <span class="badge bg-success">{{ $role->name }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $role->name }}</span>
                            @endif
                        </td>
                        <td><code>{{ $role->guard_name }}</code></td>
                        <td>
                            @foreach($role->permissions as $perm)
                                <span class="badge bg-light text-dark me-1">{{ $perm->name }}</span>
                            @endforeach
                            @if($role->permissions->count() === 0)
                                <span class="text-muted">Sin permisos</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($role->name !== 'admin')
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Eliminar el rol &quot;{{ $role->name }}&quot;? Los usuarios con este rol quedarán sin rol.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $roles->links() }}
        @else
        <div class="alert alert-info text-center mb-0">
            <i class="fas fa-info-circle me-2"></i> No hay roles registrados.
        </div>
        @endif
    </div>
</div>
@endsection
