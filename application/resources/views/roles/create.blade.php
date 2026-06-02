@extends('layouts.app')

@section('title', 'Nuevo Rol')

@push('styles')
<style>
.permission-group { margin-bottom: 1rem; }
.permission-group h6 { border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem; color: #475569; }
.permission-item { display: inline-flex; align-items: center; margin: 0.25rem 0.5rem 0.25rem 0; }
.permission-item label { margin-left: 0.35rem; font-weight: 400; cursor: pointer; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>
        <i class="fas fa-user-tag text-success"></i> Nuevo Rol
    </h1>
    <p class="page-subtitle">Crear un nuevo rol con permisos</p>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-edit me-2"></i> Datos del Rol
    </div>
    <div class="card-body">
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf

            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nombre del rol *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name') }}" required placeholder="ej: supervisor">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <h5 class="mb-3"><i class="fas fa-shield-alt me-2"></i> Permisos</h5>

            @php
                $groups = $permissions->groupBy(function($perm) {
                    $parts = explode(' ', $perm->name);
                    return $parts[0] ?? 'general';
                });
            @endphp

            <div class="row">
                @foreach($groups as $group => $perms)
                <div class="col-md-6 permission-group">
                    <h6><i class="fas fa-circle text-primary me-1" style="font-size: 0.5rem; vertical-align: middle;"></i> {{ ucfirst($group) }}</h6>
                    <div>
                        @foreach($perms as $perm)
                        <div class="permission-item">
                            <input type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                                   id="perm_{{ $perm->id }}"
                                   {{ in_array($perm->id, old('permissions', [])) ? 'checked' : '' }}>
                            <label for="perm_{{ $perm->id }}">{{ $perm->name }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Guardar Rol
                </button>
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
