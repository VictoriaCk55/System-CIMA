@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="page-header">
    <h1>
        <i class="fas fa-user-edit text-warning"></i> Editar Usuario
    </h1>
    <p class="page-subtitle">Modificar datos del usuario</p>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-edit me-2"></i> Datos de {{ $user->name }}
    </div>
    <div class="card-body">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nombre completo *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Correo electrónico *</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="role" class="form-label">Rol *</label>
                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                        @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role', $user->role) == $role->name ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                        @endforeach
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr>
            <h5 class="mt-3">Cambiar Contraseña (opcional)</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Nueva Contraseña</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password">
                    <small class="text-muted">Dejar en blanco para mantener la actual</small>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Actualizar Usuario
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
