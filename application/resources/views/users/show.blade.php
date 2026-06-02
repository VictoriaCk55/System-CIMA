@extends('layouts.app')

@section('title', 'Detalle de Usuario')

@section('content')
<div class="page-header">
    <h1>
        <i class="fas fa-user-circle text-info"></i> Detalle de Usuario
    </h1>
    <p class="page-subtitle">Información completa del usuario</p>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-info-circle me-2"></i> {{ $user->name }}</span>
        <div>
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">ID:</th>
                        <td>{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <th>Nombre:</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Rol:</th>
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
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Estado:</th>
                        <td>
                            @if($user->trashed())
                                <span class="badge bg-secondary">
                                    <i class="fas fa-user-slash me-1"></i> Inactivo
                                </span>
                            @else
                                <span class="badge bg-success">
                                    <i class="fas fa-user-check me-1"></i> Activo
                                </span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Registrado:</th>
                        <td>{{ $user->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    @if($user->updated_at != $user->created_at)
                    <tr>
                        <th>Actualizado:</th>
                        <td>{{ $user->updated_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    @endif
                    @if($user->deleted_at)
                    <tr>
                        <th>Desactivado:</th>
                        <td>{{ $user->deleted_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection