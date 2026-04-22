@extends('layouts.app')

@section('content')
<div class="container-main">
    <!-- Hero Section - MODIFICADO: Azul más oscuro (21 88 185) con cuerpo blanco -->
    <div class="card shadow-lg border-0 mb-4 overflow-hidden">
        <!-- Cabecera azul oscuro -->
        <div class="card-header" style="background-color: rgb(21, 88, 185); border-bottom: 1px solid rgba(255,255,255,0.2);">
            <h3 class="mb-0 text-white">
                <i class="fas fa-flask me-2"></i> Sistema CIMA - Gestión de Proformas
            </h3>
        </div>
        <!-- Cuerpo blanco -->
        <div class="card-body text-center py-4" style="background-color: white;">
            <i class="fas fa-file-invoice-dollar fa-4x mb-3" style="color: rgb(21, 88, 185); opacity: 0.7;"></i>
            <h2 class="mb-3" style="color: #1e293b;">Bienvenido al Sistema de Gestión CIMA</h2>
            <p class="lead mb-4" style="color: #64748b;">
                Sistema web para la gestión centralizada de proformas e informes del<br>
                Centro de Investigación Minero Ambiental (CIMA)
            </p>
        </div>
    </div>
    
    <!-- Tarjetas de estadísticas -->
    @auth
    {{-- USUARIO AUTENTICADO: 4 tarjetas normales --}}
    <div class="row mb-4">
        <!-- Clientes (Azul oscuro) -->
        <div class="col-md-3 mb-3">
            <div class="card text-white h-100" style="background-color: rgb(21, 88, 185); border: none;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0 text-white">Clientes</h6>
                        <i class="fas fa-users fa-2x opacity-50 text-white"></i>
                    </div>
                    <h2 class="mb-3 text-white">{{ $stats['clientes'] ?? 0 }}</h2>
                    <a href="{{ route('clientes.index') }}" class="text-white d-block mt-2 text-decoration-none">
                        <small>Ver todos <i class="fas fa-arrow-right ms-1"></i></small>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Parámetros (Rojo) -->
        <div class="col-md-3 mb-3">
            <div class="card text-white h-100" style="background-color: rgb(213, 94, 94); border: none;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0 text-white">Parámetros</h6>
                        <i class="fas fa-flask fa-2x opacity-50 text-white"></i>
                    </div>
                    <h2 class="mb-3 text-white">{{ $stats['parametros'] ?? 0 }}</h2>
                    <a href="{{ route('parametros.index') }}" class="text-white d-block mt-2 text-decoration-none">
                        <small>Ver todos <i class="fas fa-arrow-right ms-1"></i></small>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Proformas (Amarillo con texto NEGRO) -->
        <div class="col-md-3 mb-3">
            <div class="card h-100" style="background-color: #f8b803; border: none;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0" style="color: #000000; font-weight: 600;">Proformas</h6>
                        <i class="fas fa-file-invoice-dollar fa-2x" style="color: #000000; opacity: 0.7;"></i>
                    </div>
                    <h2 class="mb-3" style="color: #000000; font-weight: 700;">{{ $stats['proformas'] ?? 0 }}</h2>
                    <a href="{{ route('proformas.index') }}" class="d-block mt-2 text-decoration-none" style="color: #000000; font-weight: 500;">
                        <small>Ver todas <i class="fas fa-arrow-right ms-1"></i></small>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Total General Bs. -->
        <div class="col-md-3 mb-3">
            <div class="card text-white h-100 bg-info" style="border: none;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0 text-white">Total General Bs.</h6>
                        <i class="fas fa-dollar-sign fa-2x opacity-50 text-white"></i>
                    </div>
                    <h2 class="mb-3 text-white">Bs. {{ number_format($stats['total_bs'] ?? 0, 2) }}</h2>
                    <a href="{{ route('financiero.index') }}" class="text-white d-block mt-2 text-decoration-none">
                        <small>Ver reportes <i class="fas fa-arrow-right ms-1"></i></small>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    {{-- USUARIO NO AUTENTICADO: 3 tarjetas centradas --}}
    <div class="row justify-content-center mb-4">
        <!-- Clientes (Azul oscuro) -->
        <div class="col-md-3 mb-3">
            <div class="card text-white h-100" style="background-color: rgb(21, 88, 185); border: none;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0 text-white">Clientes</h6>
                        <i class="fas fa-users fa-2x opacity-50 text-white"></i>
                    </div>
                    <h2 class="mb-3 text-white">{{ $stats['clientes'] ?? 0 }}</h2>
                    <a href="{{ route('clientes.index') }}" class="text-white d-block mt-2 text-decoration-none">
                        <small>Ver todos <i class="fas fa-arrow-right ms-1"></i></small>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Parámetros (Rojo) -->
        <div class="col-md-3 mb-3">
            <div class="card text-white h-100" style="background-color: rgb(213, 94, 94); border: none;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0 text-white">Parámetros</h6>
                        <i class="fas fa-flask fa-2x opacity-50 text-white"></i>
                    </div>
                    <h2 class="mb-3 text-white">{{ $stats['parametros'] ?? 0 }}</h2>
                    <a href="{{ route('parametros.index') }}" class="text-white d-block mt-2 text-decoration-none">
                        <small>Ver todos <i class="fas fa-arrow-right ms-1"></i></small>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Proformas (Amarillo con texto NEGRO) -->
        <div class="col-md-3 mb-3">
            <div class="card h-100" style="background-color: #f8b803; border: none;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0" style="color: #000000; font-weight: 600;">Proformas</h6>
                        <i class="fas fa-file-invoice-dollar fa-2x" style="color: #000000; opacity: 0.7;"></i>
                    </div>
                    <h2 class="mb-3" style="color: #000000; font-weight: 700;">{{ $stats['proformas'] ?? 0 }}</h2>
                    <a href="{{ route('proformas.index') }}" class="d-block mt-2 text-decoration-none" style="color: #000000; font-weight: 500;">
                        <small>Ver todas <i class="fas fa-arrow-right ms-1"></i></small>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endauth
    
    <!-- Acciones rápidas -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-bolt me-2"></i> Acciones Rápidas
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <a href="{{ route('clientes.create') }}" class="btn w-100 py-3 text-white" style="background-color: rgb(21, 88, 185); border: none;">
                        <i class="fas fa-user-plus fa-lg me-2"></i>
                        <div class="d-block">
                            <strong>Nuevo Cliente</strong>
                            <small class="d-block text-white-50">Registrar nuevo cliente</small>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 mb-2">
                    <a href="{{ route('parametros.create') }}" class="btn w-100 py-3 text-white" style="background-color: rgb(213, 94, 94); border: none;">
                        <i class="fas fa-plus-circle fa-lg me-2"></i>
                        <div class="d-block">
                            <strong>Nuevo Parámetro</strong>
                            <small class="d-block text-white-50">Agregar parámetro de análisis</small>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 mb-2">
                    <a href="{{ route('proformas.create') }}" class="btn btn-warning w-100 py-3">
                        <i class="fas fa-file-invoice fa-lg me-2"></i>
                        <div class="d-block">
                            <strong>Nueva Proforma</strong>
                            <small class="d-block text-dark-50">Crear nueva proforma</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bienvenida -->
    <div class="card">
        <div class="card-body">
            <h4 class="mb-3">Bienvenido al Sistema de Gestión del Centro de Investigación Minero Ambiental</h4>
            <p class="mb-0 text-muted">
                Este sistema permite gestionar proformas, clientes y parámetros de análisis de manera centralizada, 
                reemplazando el uso de archivos Excel y documentos físicos. Todas las funcionalidades están diseñadas 
                según los formatos oficiales del CIMA.
            </p>
        </div>
    </div>
</div>

<style>
/* ========== CORRECCIÓN PARA DASHBOARD EN MÓVIL ========== */
@media (max-width: 768px) {
    /* Ajustar tarjetas */
    .col-md-3 {
        width: 100% !important;
        margin-bottom: 15px;
    }
    
    /* Centrar texto en tarjetas */
    .card-body {
        text-align: center;
    }
    
    .d-flex.justify-content-between {
        justify-content: center !important;
        gap: 15px;
    }
    
    /* Ajustar botones de acciones rápidas */
    .btn.w-100.py-3 {
        margin-bottom: 10px;
        text-align: center;
    }
    
    /* Ajustar hero section */
    .card-body.text-center.py-4 h2 {
        font-size: 1.5rem;
    }
    
    .card-body.text-center.py-4 p {
        font-size: 0.9rem;
        padding: 0 10px;
    }
    
    /* Ajustar iconos */
    .fa-2x {
        font-size: 1.5rem;
    }
}

/* Para tabletas - ajustar cuando hay 3 tarjetas centradas */
@media (min-width: 769px) and (max-width: 991px) {
    /* Para usuario no autenticado: 3 tarjetas centradas */
    .justify-content-center .col-md-3 {
        width: 33.333% !important;
    }
    
    /* Para usuario autenticado: 4 tarjetas, 2 por fila */
    @auth
    .col-md-3 {
        width: 50% !important;
    }
    @endauth
}

/* Para desktop */
@media (min-width: 992px) {
    .col-md-3 {
        width: 25% !important;
    }
}
</style>
@endsection