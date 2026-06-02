@extends('layouts.app')

@section('title', 'Permisos')

@section('content')
<div class="page-header">
    <h1>
        <i class="fas fa-shield-alt text-primary"></i> Gestión de Permisos
    </h1>
    <p class="page-subtitle">Administrar permisos del sistema (Spatie)</p>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-list me-2"></i> Permisos</span>
        <a href="{{ route('permissions.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Permiso
        </a>
    </div>
    <div class="card-body">
        @if($permissions->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Guard</th>
                        <th>Roles asignados</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $perm)
                    <tr>
                        <td>{{ $perm->id }}</td>
                        <td><code>{{ $perm->name }}</code></td>
                        <td><code>{{ $perm->guard_name }}</code></td>
                        <td>
                            @foreach($perm->roles as $role)
                                <span class="badge bg-secondary me-1">{{ $role->name }}</span>
                            @endforeach
                            @if($perm->roles->count() === 0)
                                <span class="text-muted">Sin asignar</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('permissions.edit', $perm) }}" class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('permissions.destroy', $perm) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Eliminar el permiso &quot;{{ $perm->name }}&quot;?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $permissions->links() }}
        @else
        <div class="alert alert-info text-center mb-0">
            <i class="fas fa-info-circle me-2"></i> No hay permisos registrados.
        </div>
        @endif
    </div>
</div>
@endsection
