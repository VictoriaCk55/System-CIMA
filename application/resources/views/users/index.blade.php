@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="page-header">
    <h1>
        <i class="fas fa-users-cog text-primary"></i> Gestión de Usuarios
    </h1>
    <p class="page-subtitle">Administrar usuarios del sistema</p>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-list me-2"></i> Usuarios Activos</span>
        <div>
            <a href="{{ route('users.trash') }}" class="btn btn-sm btn-secondary me-2">
                <i class="fas fa-trash-restore me-1"></i> Inactivos
            </a>
            <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> Nuevo Usuario
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Registrado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge bg-danger">
                                    <i class="fas fa-shield-alt me-1"></i> Administrador
                                </span>
                            @elseif($user->role === 'tecnico')
                                <span class="badge bg-info">
                                    <i class="fas fa-user-cog me-1"></i> Técnico
                                </span>
                            @elseif($user->role === 'analista')
                                <span class="badge bg-success">
                                    <i class="fas fa-flask me-1"></i> Analista
                                </span>
                            @else
                                <span class="badge bg-secondary">{{ $user->role }}</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info" title="Ver">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Desactivar este usuario? Podrá restaurarlo después.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-secondary" title="Desactivar">
                                    <i class="fas fa-user-slash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
        @else
        <div class="alert alert-info text-center mb-0">
            <i class="fas fa-info-circle me-2"></i> No hay usuarios registrados.
        </div>
        @endif
    </div>
</div>
@endsection
