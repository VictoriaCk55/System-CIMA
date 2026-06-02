@extends('layouts.app')

@section('title', 'Editar Permiso')

@section('content')
<div class="page-header">
    <h1>
        <i class="fas fa-shield-alt text-warning"></i> Editar Permiso
    </h1>
    <p class="page-subtitle">Modificar permiso y asignación a roles</p>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-edit me-2"></i> Permiso: {{ $permission->name }}
    </div>
    <div class="card-body">
        <form action="{{ route('permissions.update', $permission) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nombre del permiso *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $permission->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <h5 class="mb-3"><i class="fas fa-users me-2"></i> Asignar a roles</h5>
            <div class="row mb-3">
                @foreach($roles as $role)
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                               id="role_{{ $role->id }}"
                               class="form-check-input"
                               {{ in_array($role->id, old('roles', $assignedRoles)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="role_{{ $role->id }}">
                            {{ $role->name }}
                        </label>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Actualizar Permiso
                </button>
                <a href="{{ route('permissions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
