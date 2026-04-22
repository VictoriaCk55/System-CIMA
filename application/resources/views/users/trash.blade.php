@extends('layouts.app')

@section('title', 'Usuarios Inactivos')

@section('content')
<div class="page-header">
    <h1>
        <i class="fas fa-trash-restore text-secondary"></i> Usuarios Inactivos
    </h1>
    <p class="page-subtitle">Usuarios desactivados - pueden ser restaurados</p>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-list me-2"></i> Papelera de Usuarios</span>
        <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-arrow-left me-1"></i> Volver a Activos
        </a>
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
                        <th>Desactivado</th>
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
                                <span class="badge bg-danger">Administrador</span>
                            @else
                                <span class="badge bg-info">Técnico</span>
                            @endif
                        </td>
                        <td>{{ $user->deleted_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info" title="Ver">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('users.restore', $user->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Activar este usuario?')">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-sm btn-success" title="Activar">
                                    <i class="fas fa-user-check"></i>
                                </button>
                            </form>
                            <form action="{{ route('users.force-delete', $user->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('⚠️ ¿ELIMINAR PERMANENTEMENTE? Esta acción no se puede deshacer.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar Permanentemente">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
        @else
        <div class="alert alert-info text-center mb-0">
            <i class="fas fa-info-circle me-2"></i> No hay usuarios inactivos.
        </div>
        @endif
    </div>
</div>
@endsection