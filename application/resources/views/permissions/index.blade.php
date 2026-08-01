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
        <div id="tablaPermisos">
            @include('permissions._tabla')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var contenedor = document.getElementById('tablaPermisos');
    if (!contenedor) return;

    var timeout = null;

    contenedor.addEventListener('input', function (e) {
        if (!e.target || e.target.id !== 'buscarPermiso') return;

        clearTimeout(timeout);
        timeout = setTimeout(function () {
            var input = contenedor.querySelector('#buscarPermiso');
            var texto = input.value.trim();
            var url = '{{ route('permissions.index') }}' + (texto ? '?search=' + encodeURIComponent(texto) : '');

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(function (respuesta) { return respuesta.json(); })
                .then(function (data) {
                    contenedor.innerHTML = data.html;
                });
        }, 400);
    });
});
</script>
@endpush
