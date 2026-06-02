@extends('layouts.app')

@section('title', 'Nuevo Permiso')

@section('content')
<div class="page-header">
    <h1>
        <i class="fas fa-shield-alt text-success"></i> Nuevo Permiso
    </h1>
    <p class="page-subtitle">Crear un nuevo permiso en el sistema</p>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-edit me-2"></i> Datos del Permiso
    </div>
    <div class="card-body">
        <form action="{{ route('permissions.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nombre del permiso *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name') }}" required
                           placeholder="ej: editar informes">
                    <small class="text-muted">Usa formato: ver/crear/editar/eliminar + nombre del módulo</small>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Guardar Permiso
                </button>
                <a href="{{ route('permissions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
