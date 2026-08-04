@extends('layouts.app')

@section('content')
<div class="container-main">
    <!-- Encabezado de página -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>
                    <i class="fas fa-edit" style="color: #2798F5;"></i>
                    Editar Cliente
                </h1>
                <p class="page-subtitle">
                    Actualice la información del cliente: {{ $cliente->razon_social }}
                </p>
            </div>
            <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary btn-volver" style="border-radius: 30px; padding: 8px 20px;">
                    <i class="fas fa-arrow-left me-2"></i>
                    Volver al listado
            </a>
        </div>
    </div>

    <!-- Formulario -->
    @can('editar clientes')
    <div class="card">
        <div class="card-header" style="background-color: #2798F5; border-bottom: none;">
            <h5 class="mb-0 text-white">
                <i class="fas fa-edit me-2"></i>
                Formulario de Edición
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="razon_social" class="form-label">
                            Razón Social *
                        </label>
                        <input type="text" 
                               class="form-control @error('razon_social') is-invalid @enderror" 
                               id="razon_social" 
                               name="razon_social" 
                               value="{{ old('razon_social', $cliente->razon_social) }}" 
                               required 
                               autofocus
                               placeholder="Ej: EMPRESA MINERA SANTA RITA S.A.">
                        @error('razon_social')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="persona_contacto" class="form-label">
                            Persona de Contacto *
                        </label>
                        <input type="text" 
                               class="form-control @error('persona_contacto') is-invalid @enderror" 
                               id="persona_contacto" 
                               name="persona_contacto" 
                               value="{{ old('persona_contacto', $cliente->persona_contacto) }}" 
                               required
                               placeholder="Ej: ING. JORGE LUIS MAMANI CONDORI">
                        @error('persona_contacto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="telefono" class="form-label">
                            Teléfono
                        </label>
                        <input type="text" 
                               class="form-control @error('telefono') is-invalid @enderror" 
                               id="telefono" 
                               name="telefono" 
                               value="{{ old('telefono', $cliente->telefono) }}" 
                               placeholder="Ej: 70123456">
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nit" class="form-label">
                            NIT
                        </label>
                        <input type="text" 
                               class="form-control @error('nit') is-invalid @enderror" 
                               id="nit" 
                               name="nit" 
                               value="{{ old('nit', $cliente->nit) }}" 
                               placeholder="Ej: 123456789">
                        @error('nit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-4">
                        <label for="direccion" class="form-label">
                            Dirección
                        </label>
                        <textarea class="form-control @error('direccion') is-invalid @enderror" 
                                  id="direccion" 
                                  name="direccion" 
                                  rows="3" 
                                  placeholder="Ej: Av. Blanco Galindo KM 5, Cochabamba">{{ old('direccion', $cliente->direccion) }}</textarea>
                        @error('direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @can('ver clientes')
                <div class="d-flex justify-content-between pt-3 border-top">
                    <a href="{{ route('clientes.show', $cliente->id) }}" class="btn btn-secondary" style="border-radius: 30px; padding: 10px 25px;">
                        <i class="fas fa-times me-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" class="btn" style="background-color: #2798F5; border-radius: 30px; padding: 10px 25px; color: white; border: none; transition: all 0.3s ease;">
                        <i class="fas fa-save me-2"></i>
                        Actualizar Cliente
                    </button>
                </div>
                @endcan
            </form>
        </div>
    </div>
    @endcan
</div>

<!-- Estilos adicionales específicos para la página de edición -->
<style>

/* Estilo para el botón de actualizar */
button[type="submit"][style*="background-color: #2798F5"]:hover {
    background-color: #1a7ac9 !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(39, 152, 245, 0.3);
}

/* Estilo para el botón de cancelar */
.btn-secondary {
    background-color: #6c757d;
    border: none;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background-color: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
}

/* Estilo para los iconos en el encabezado */
.fa-edit {
    color: #2798F5 !important;
}

/* Estilo para el icono de clientes en el encabezado */
.fa-users {
    color: #2798F5 !important;
}

/* Estilo para el botón volver */
.btn-volver {
    color: #000000 !important;
    border: 2px solid #ffffff !important;
    background-color: #ffffff !important;
    transition: all 0.3s ease !important;
    font-weight: 500 !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
}

.btn-volver:hover {
    background-color: #ffffff !important;
    color: #000000 !important;
    border-color: #ffffff !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 20px rgba(128, 128, 128, 0.3) !important;
}

/* Enfocar inputs con el color azul */
.form-control:focus, .form-select:focus {
    border-color: #2798F5 !important;
    box-shadow: 0 0 0 3px rgba(39, 152, 245, 0.15) !important;
}

@media (max-width: 768px) {
    /* Reorganizar el encabezado en móvil */
    .page-header .d-flex {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 15px !important;
    }
    
    /* El botón volver ocupa todo el ancho en móvil */
    .page-header .btn-volver {
        width: 100% !important;
        justify-content: center !important;
        margin-top: 10px !important;
    }
    
    /* Ajustar el título y badge */
    .d-flex.align-items-center.gap-3 {
        flex-wrap: wrap !important;
        gap: 10px !important;
    }
    
    .d-flex.align-items-center.gap-3 h1 {
        font-size: 1.5rem !important;
        width: 100% !important;
    }
    
    /* Badge ocupa su espacio */
    .badge.fs-6 {
        font-size: 0.85rem !important;
        padding: 5px 12px !important;
    }
    
    /* Ajustar columnas en móvil */
    .col-md-8, .col-md-4 {
        width: 100% !important;
    }
    
    /* Botones en el panel lateral */
    .d-grid.gap-2 .btn {
        width: 100% !important;
        margin-bottom: 5px !important;
    }
    
    /* Ajustar tablas en móvil */
    .table-responsive {
        overflow-x: auto !important;
    }
    
    /* Ajustar texto en tarjetas */
    .card-body .row .col-md-6 {
        width: 100% !important;
    }
    
    /* Ajustar iconos */
    .fa-2x {
        font-size: 1.5rem !important;
    }
}

/* Ajuste para tablets */
@media (min-width: 769px) and (max-width: 991px) {
    .col-md-8, .col-md-4 {
        width: 100% !important;
    }
}

/* ========== CORRECCIÓN PARA BOTONES DE FORMULARIO ========== */
@media (max-width: 768px) {
    /* Hacer los botones más pequeños y manejables en móvil */
    .d-flex.justify-content-between.pt-3.border-top,
    .d-flex.justify-content-between.mt-4.pt-3.border-top {
        flex-direction: column !important;
        gap: 10px !important;
    }
    
    /* Botones ocupan todo el ancho pero con mejor tamaño */
    .d-flex.justify-content-between.pt-3.border-top .btn,
    .d-flex.justify-content-between.mt-4.pt-3.border-top .btn {
        width: 100% !important;
        padding: 12px 20px !important; /* Un poco más pequeños que antes */
        font-size: 1rem !important;
        margin: 0 !important;
    }
    
    /* Para formularios con btn-group */
    .btn-group {
        width: 100% !important;
        display: flex !important;
        gap: 8px !important;
    }
    
    .btn-group .btn {
        flex: 1 !important;
        padding: 12px 15px !important;
    }
    
    /* Ajustar espaciado del formulario */
    .card-body {
        padding: 1rem !important;
    }
    
    /* Ajustar inputs para mejor visualización */
    .form-control, .form-select {
        font-size: 16px !important; /* Evita zoom automático en iOS */
        padding: 12px !important;
    }
    
    /* Ajustar labels */
    .form-label {
        font-size: 0.95rem !important;
        margin-bottom: 0.25rem !important;
    }
    
    /* Ajustar textos pequeños */
    small.text-muted {
        font-size: 0.8rem !important;
    }
    
    /* Ajustar títulos de sección */
    h6.border-bottom {
        font-size: 1rem !important;
        padding-bottom: 0.5rem !important;
    }
    
    /* Ajustar input groups */
    .input-group {
        flex-wrap: nowrap !important;
    }
    
    .input-group .form-control {
        font-size: 16px !important;
    }
    
    .input-group-text {
        padding: 12px !important;
    }
}

/* Ajuste para tablets */
@media (min-width: 769px) and (max-width: 991px) {
    .btn {
        padding: 10px 20px !important;
    }
}
</style>
@endsection