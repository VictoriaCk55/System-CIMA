@extends('layouts.app')

@section('title', 'Proformas')

@section('content')
<div class="container-main">
    <!-- Encabezado de página -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>
                    <i class="fas fa-file-invoice-dollar" style="color: #ffc107;"></i>
                    Gestión de Proformas
                </h1>
                <p class="page-subtitle">
                    Listado de proformas generadas en el sistema CIMA
                </p>
            </div>
            
            <div class="d-flex gap-2">
                @auth

                    @can('crear proformas')
                        <a href="{{ route('proformas.create') }}" class="btn btn-primary" style="background-color: #ffc107; border-radius: 30px; padding: 10px 25px; color: #000; border: none; transition: all 0.3s ease;">
                            <i class="fas fa-plus-circle"></i>
                            Nueva Proforma
                        </a>
                    @else
                        <div class="alert alert-info mb-0 py-2 px-3">
                            <i class="fas fa-eye me-1"></i> Modo solo lectura
                        </div>
                    @endcan
                @endauth
            </div>
        </div>
    </div>

    <!-- BUSCADOR Y FILTROS -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('proformas.index') }}" class="row">
                <!-- Buscador general -->
                <div class="col-md-12 mb-3">
                    <label for="search" class="form-label fw-semibold">
                        <i class="fas fa-search me-2" style="color: #ffc107;"></i>
                        Buscar Proforma
                    </label>
                    <div class="input-group">
                        <span class="input-group-text" style="background-color: #ffc107; color: #000; border: none;">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Buscar por código, cliente o tipo..."
                               style="border-left: none;">
                        @if(request('search'))
                        <a href="{{ route('proformas.index') }}" class="btn btn-outline-secondary" style="border-radius: 0 30px 30px 0;">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                        @endif
                    </div>
                    <small class="text-muted mt-1 d-block">
                        <i class="fas fa-info-circle me-1"></i>
                        Puede buscar por código de proforma, nombre del cliente o tipo (AMBIENTAL, AGUA, INVESTIGACION). La búsqueda no distingue mayúsculas/minúsculas.
                    </small>
                </div>
                
                <!-- Filtros existentes -->
                <div class="col-md-3">
                    <label for="mes" class="form-label">
                        <i class="fas fa-calendar-alt me-1" style="color: #ffc107;"></i> Mes
                    </label>
                    <select name="mes" id="mes" class="form-select">
                        <option value="">Todos los meses</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->locale('es')->monthName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="anio" class="form-label">
                        <i class="fas fa-calendar me-1" style="color: #ffc107;"></i> Año
                    </label>
                    <select name="anio" id="anio" class="form-select">
                        <option value="">Todos los años</option>
                        @if(isset($añosDisponibles) && count($añosDisponibles) > 0)
                            @foreach($añosDisponibles as $año)
                                <option value="{{ $año }}" {{ request('anio') == $año ? 'selected' : '' }}>
                                    {{ $año }}
                                </option>
                            @endforeach
                        @else
                            @for($a = date('Y'); $a >= date('Y')-5; $a--)
                                <option value="{{ $a }}" {{ request('anio') == $a ? 'selected' : '' }}>
                                    {{ $a }}
                                </option>
                            @endfor
                        @endif
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="estado" class="form-label">
                        <i class="fas fa-flag me-1" style="color: #ffc107;"></i> Estado
                    </label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="BORRADOR" {{ request('estado') == 'BORRADOR' ? 'selected' : '' }}>📝 Borrador</option>
                        <option value="ENVIADA" {{ request('estado') == 'ENVIADA' ? 'selected' : '' }}>⏳ Enviada</option>
                        <option value="APROBADA" {{ request('estado') == 'APROBADA' ? 'selected' : '' }}>✅ Aprobada</option>
                        <option value="RECHAZADA" {{ request('estado') == 'RECHAZADA' ? 'selected' : '' }}>❌ Rechazada</option>
                        <option value="FINALIZADA" {{ request('estado') == 'FINALIZADA' ? 'selected' : '' }}>🏁 Finalizada</option>
                    </select>
                </div>
                
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2" style="background-color: #ffc107; border-radius: 30px; padding: 8px 20px; color: #000; border: none; transition: all 0.3s ease;">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <a href="{{ route('proformas.index') }}" 
                       class="btn btn-secondary" 
                       style="border-radius: 30px; padding: 8px 20px; transition: all 0.3s ease;">
                        <i class="fas fa-eraser"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
        
        @if(request()->has('search') || request()->has('mes') || request()->has('anio') || request()->has('estado'))
            <div class="card-footer bg-light py-2">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1" style="color: #ffc107;"></i>
                    Mostrando resultados para:
                    @if(request('search'))
                        búsqueda "<strong>{{ request('search') }}</strong>"
                    @endif
                    @if(request('mes') && request('anio'))
                        @php
                            $mesNumerico = (int)request('mes');
                            $anioNumerico = (int)request('anio');
                            $nombreMes = \Carbon\Carbon::createFromDate($anioNumerico, $mesNumerico, 1)->locale('es')->monthName;
                        @endphp
                        de {{ $nombreMes }} de {{ request('anio') }}
                    @elseif(request('mes'))
                        @php
                            $mesNumerico = (int)request('mes');
                            $nombreMes = \Carbon\Carbon::create()->month($mesNumerico)->locale('es')->monthName;
                        @endphp
                        de {{ $nombreMes }}
                    @elseif(request('anio'))
                        del año {{ request('anio') }}
                    @endif
                    
                    @if(request('estado'))
                        @php
                            $estados = ['BORRADOR' => 'Borrador', 'ENVIADA' => 'Enviada', 'APROBADA' => 'Aprobada', 'RECHAZADA' => 'Rechazada', 'FINALIZADA' => 'Finalizada'];
                        @endphp
                        con estado <strong>{{ $estados[request('estado')] ?? request('estado') }}</strong>
                    @endif
                </small>
            </div>
        @endif
    </div>

    <!-- Tabla de proformas -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2" style="color: #ffc107;"></i>
                Listado de Proformas
            </h5>
            <span class="badge" style="background-color: #ffc107; color: #000; padding: 8px 15px; border-radius: 20px; font-weight: 500;">
                {{ $proformas->total() }} registros
            </span>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($proformas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover" style="font-size: 0.75rem;">
                        <thead>
                            <tr style="background-color: #ffc107; color: #000;">
                                <th width="100" style="background-color: #ffc107; color: #000; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; padding: 12px 15px;">Código</th>
                                <th style="background-color: #ffc107; color: #000; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; padding: 12px 15px;">Cliente</th>
                                <th width="100" style="background-color: #ffc107; color: #000; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; padding: 12px 15px;">Tipo</th>
                                <th width="120" style="background-color: #ffc107; color: #000; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; padding: 12px 15px;">Estado</th>
                                <th width="120" style="background-color: #ffc107; color: #000; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; padding: 12px 15px;">Tipo Muestra</th>
                                <th width="100" style="background-color: #ffc107; color: #000; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; padding: 12px 15px;">Fecha</th>
                                <th width="120" class="text-end" style="background-color: #ffc107; color: #000; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; padding: 12px 15px;">Total</th>
                                <th width="120" class="text-end" style="background-color: #ffc107; color: #000; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; padding: 12px 15px;">Adelanto</th>
                                <th width="140" class="text-center" style="background-color: #ffc107; color: #000; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; padding: 12px 15px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proformas as $proforma)
                                <tr>
                                    <td>
                                        <span style="font-weight: bold;">
                                            {{ $proforma->codigo }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $proforma->cliente->razon_social }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            {{ $proforma->persona_contacto ?? $proforma->cliente->persona_contacto ?? 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill 
                                            @if($proforma->tipo == 'AMBIENTAL') bg-warning text-dark
                                            @elseif($proforma->tipo == 'AGUA') bg-info
                                            @else bg-secondary
                                            @endif">
                                            <i class="fas 
                                                @if($proforma->tipo == 'AMBIENTAL') fa-leaf
                                                @elseif($proforma->tipo == 'AGUA') fa-tint
                                                @else fa-flask
                                                @endif me-1"></i>
                                            {{ $proforma->tipo }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $estado = $proforma->estado;
                                            $bgColor = '';
                                            $textColor = '';
                                            $icono = '';
                                            
                                            switch($estado) {
                                                case 'BORRADOR':
                                                    $bgColor = 'bg-secondary';
                                                    $textColor = '#ffffff';
                                                    $icono = 'fa-pencil-alt';
                                                    break;
                                                case 'ENVIADA':
                                                    $bgColor = 'bg-info';
                                                    $textColor = '#ffffff';
                                                    $icono = 'fa-paper-plane';
                                                    break;
                                                case 'APROBADA':
                                                    $bgColor = 'bg-success';
                                                    $textColor = '#ffffff';
                                                    $icono = 'fa-check-circle';
                                                    break;
                                                case 'RECHAZADA':
                                                    $bgColor = 'bg-danger';
                                                    $textColor = '#ffffff';
                                                    $icono = 'fa-times-circle';
                                                    break;
                                                case 'FINALIZADA':
                                                    $bgColor = 'bg-white';
                                                    $textColor = '#000000';
                                                    $icono = 'fa-flag-checkered';
                                                    break;
                                                default:
                                                    $bgColor = 'bg-secondary';
                                                    $textColor = '#ffffff';
                                                    $icono = 'fa-question-circle';
                                            }
                                        @endphp
                                        
                                        <span class="badge rounded-pill {{ $bgColor }}" style="color: {{ $textColor }}; padding: 8px 12px; {{ $estado === 'FINALIZADA' ? 'border: 1px solid #ddd;' : '' }}">
                                            <!-- <i class="fas {{ $icono }} me-1" style="color: {{ $textColor }};"></i> -->
                                            {{ $estado }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $proforma->tipo_muestra }}</small>
                                    </td>
                                    <td>
                                        <small>
                                            <!-- <i class="far fa-calendar me-1"></i> -->
                                            {{ $proforma->fecha_emision->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td class="text-end fw-semibold text-success">
                                        <i class="fas fa-dollar-sign me-1"></i>
                                        {{ number_format($proforma->total, 2) }}
                                    </td>
                                    <td class="text-end fw-semibold" style="color: #ffc107;">
                                        <i class="fas fa-hand-holding-usd me-1"></i>
                                        {{ number_format($proforma->adelanto, 2) }}
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-xs btn-secondary dropdown-toggle" 
                                                    type="button" 
                                                    data-bs-toggle="dropdown" 
                                                    aria-expanded="false"
                                                    style="border-radius: 6px; padding: 0.25rem 0.6rem; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 5px;">
                                                <i class="fas fa-bars-staggered" style="font-size: 0.7rem;"></i>
                                                Opciones
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <!-- Ver detalles -->
                                                <li>
                                                    <a class="dropdown-item" 
                                                    href="{{ route('proformas.show', $proforma) }}"
                                                    title="Ver detalles de la proforma">
                                                        <i class="fas fa-eye me-2" style="color: #0dcaf0;"></i>
                                                        Ver detalles
                                                    </a>
                                                </li>
                                                
                                                <!-- Editar (solo admin y borrador) -->
                                                @auth
                                                    @if(Auth::user()->hasAnyRole(['admin', 'tecnico', 'analista']) && $proforma->estado == 'BORRADOR')
                                                        <li>
                                                            @can('editar proformas')
                                                            <a class="dropdown-item" 
                                                            href="{{ route('proformas.edit', $proforma) }}"
                                                            title="Editar proforma">
                                                                <i class="fas fa-edit me-2" style="color: #ffc107;"></i>
                                                                Editar
                                                            </a>
                                                            @endcan
                                                        </li>
                                                        
                                                        <!-- Eliminar -->
                                                        <li>
                                                            @can('eliminar proformas')
                                                            <form action="{{ route('proformas.destroy', $proforma) }}" 
                                                                method="POST" 
                                                                class="d-inline"
                                                                onsubmit="return confirm('¿Está seguro de eliminar la proforma {{ $proforma->codigo }}?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="dropdown-item"
                                                                        style="background: none; border: none; width: 100%; text-align: left;">
                                                                    <i class="fas fa-trash me-2" style="color: #dc3545;"></i>
                                                                    Eliminar
                                                                </button>
                                                            </form>
                                                            @endcan
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                    @endif
                                                @endauth
                                                
                                                <!-- Generar PDF -->
                                                <li>
                                                    <a class="dropdown-item" 
                                                    href="{{ route('proformas.pdf', $proforma) }}"
                                                    target="_blank"
                                                    title="Generar PDF">
                                                        <i class="fas fa-file-pdf me-2" style="color: #dc3545;"></i>
                                                        Generar PDF
                                                    </a>
                                                </li>

                                                @if($proforma->tipo === 'AMBIENTAL')
                                                @hasanyrole('admin|tecnico')
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('reportes.ambiental.index', $proforma) }}" title="Reporte Ambiental">
                                                        <i class="fas fa-file-signature me-2" style="color: #6f42c1;"></i>Reporte Ambiental
                                                    </a>
                                                </li>
                                                @endhasanyrole
                                                @else
                                                <!-- Cadena de Custodia (solo AGUA / INVESTIGACIÓN) -->
                                                @can('generar cadena custodia')
                                                <li>
                                                    <a class="dropdown-item" 
                                                    href="{{ route('proformas.cadena-custodia', $proforma) }}"
                                                    target="_blank"
                                                    title="Generar Cadena de Custodia">
                                                        <i class="fas fa-clipboard-list me-2" style="color: #198754;"></i>
                                                        Cadena de Custodia
                                                    </a>
                                                </li>
                                                @endcan
                                                
                                                <!-- Formulario de resultados (solo AGUA / INVESTIGACIÓN) -->
                                                @can('ver resultados')
                                                <li>
                                                    <a class="dropdown-item" 
                                                    href="{{ route('resultados.index', $proforma->id) }}"
                                                    title="Abrir formulario de resultados">
                                                        <i class="fas fa-file-alt me-2" style="color: #0d6efd;"></i>
                                                        Formulario de resultados
                                                    </a>
                                                </li>
                                                @endcan
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Botón de Papelera y texto de registros centrado -->
                <div class="d-flex align-items-center justify-content-center position-relative mt-3">
                    @auth
                        @can('ver papelera proformas')
                            <a href="{{ route('proformas.trash') }}" 
                               class="btn btn-icon-circle position-absolute start-0"
                               style="width: 35px; height: 35px; border-radius: 50%; background-color: #6c757d; color: white; display: inline-flex; align-items: center; justify-content: center; transition: all 0.3s ease; text-decoration: none;"
                               data-bs-toggle="tooltip"
                               title="Ver proformas eliminadas"
                               onmouseover="this.style.backgroundColor='#5a6268'; this.style.transform='scale(1.1)';"
                               onmouseout="this.style.backgroundColor='#6c757d'; this.style.transform='scale(1)';">
                                <i class="fas fa-trash-alt" style="font-size: 1rem;"></i>
                            </a>
                        @endcan
                    @endauth
                    
                    <div style="color: #ffc107; font-weight: 500;">
                        <i class="fas fa-database me-1"></i> 
                        Mostrando {{ $proformas->firstItem() }} a {{ $proformas->lastItem() }} de {{ $proformas->total() }} registros
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-invoice-dollar fa-4x mb-4" style="color: #ffc107;"></i>
                    <h4 class="mb-3" style="color: #334155;">No hay proformas registradas</h4>
                    <p class="text-muted mb-4">
                        @if(request()->has('search') || request()->has('mes') || request()->has('anio') || request()->has('estado'))
                            No hay proformas para los filtros seleccionados.
                            <a href="{{ route('proformas.index') }}">Ver todas</a>
                        @else
                            Comience creando su primera proforma.
                        @endif
                    </p>
                    
                    @auth
                        @if(Auth::user()->hasAnyRole(['admin', 'tecnico']))
                            <a href="{{ route('proformas.create') }}" class="btn btn-primary" style="background-color: #ffc107; border-radius: 30px; padding: 10px 25px; color: #000; border: none; transition: all 0.3s ease;">
                                <i class="fas fa-plus-circle me-2"></i>
                                Crear primera proforma
                            </a>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Solo el administrador puede crear nuevas proformas.
                            </div>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
        
        @if($proformas->count() > 0)
        <div class="card-footer bg-light">
            <div class="row">
                <div class="col-md-6">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1" style="color: #ffc107;"></i>
                        Mostrando {{ $proformas->count() }} de {{ $proformas->total() }} proformas
                    </small>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">
                        <i class="fas fa-calculator me-1" style="color: #ffc107;"></i>
                        Total General: 
                        <strong>Bs. {{ number_format($proformas->sum('total'), 2) }}</strong>
                        | Adelantos: 
                        <strong>Bs. {{ number_format($proformas->sum('adelanto'), 2) }}</strong>
                    </small>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Estilos adicionales -->
<style>
/* Estilo para el botón de nueva proforma */
.btn-primary[style*="background-color: #ffc107"]:hover {
    background-color: #e6a800 !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
}

/* Estilo para el botón de papelera en círculo */
.btn-icon-circle {
    width: 35px !important;
    height: 35px !important;
    border-radius: 50% !important;
    padding: 0 !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.3s ease !important;
}

.btn-icon-circle:hover {
    transform: scale(1.1) !important;
}

/* Estilos para la paginación */
.pagination .page-link {
    color: #ffc107 !important;
    border-radius: 8px;
    margin: 0 3px;
}

.pagination .page-item.active .page-link {
    background-color: #ffc107 !important;
    border-color: #ffc107 !important;
    color: #000 !important;
}

/* Estilo para los botones de acción */
.btn-outline-warning,
.btn-outline-danger,
.btn-outline-success {
    transition: all 0.2s ease;
}

.btn-outline-warning:hover,
.btn-outline-danger:hover,
.btn-outline-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* Estilo para el input de búsqueda */
.input-group-text {
    border-radius: 30px 0 0 30px !important;
}

.form-control {
    border-radius: 0 !important;
}

/* Estilo para el foco de inputs y selects en proformas */
.form-control:focus,
.form-select:focus,
.input-group-text:focus,
.btn:focus {
    border-color: #ffc107 !important;
    box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.25) !important;
    outline: none !important;
}

/* Específico para el input de búsqueda */
#search:focus {
    border-color: #ffc107 !important;
    box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.25) !important;
}

/* Para los selects de filtros */
select.form-select:focus {
    border-color: #ffc107 !important;
    box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.25) !important;
}

/* Para el botón de filtrar */
button[type="submit"]:focus {
    border-color: #ffc107 !important;
    box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.25) !important;
}

/* Reforzar color del icono de proformas */
.fa-file-invoice-dollar {
    color: #ffc107 !important;
}

/* ========== CORRECCIÓN PARA MÓVIL ========== */
@media (max-width: 768px) {
    .page-header .d-flex {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 15px !important;
    }
    
    .page-header .btn {
        width: 100% !important;
        justify-content: center !important;
    }
    
    .page-header h1 {
        font-size: 1.5rem !important;
    }
    
    .page-subtitle {
        font-size: 0.9rem !important;
    }
    
    .card-header {
        flex-direction: column !important;
        gap: 10px !important;
        align-items: flex-start !important;
    }
    
    .btn-group {
        flex-wrap: wrap !important;
        justify-content: center !important;
    }
    
    .btn-group .btn {
        margin: 2px !important;
    }
    
    .input-group {
        flex-wrap: wrap !important;
    }
    
    .input-group .form-control,
    .input-group .btn {
        width: 100% !important;
        border-radius: 30px !important;
        margin: 5px 0 !important;
    }
    
    .input-group-text {
        display: none !important;
    }
    
    /* Ajuste para móvil - papelera y texto */
    .d-flex.align-items-center.justify-content-center.position-relative {
        flex-direction: column !important;
        padding-top: 40px !important;
    }
    
    .btn-icon-circle.position-absolute.start-0 {
        position: relative !important;
        left: auto !important;
        margin-bottom: 10px !important;
    }
}
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tooltips de Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                placement: 'auto',
                trigger: 'hover',
                delay: { show: 50, hide: 50 },
                boundary: 'viewport'
            });
        });
        
        // Auto-submit del formulario de búsqueda al escribir
        let searchTimeout;
        const searchInput = document.getElementById('search');
        const searchForm = document.getElementById('searchForm');
        
        if (searchInput && searchForm) {
            searchInput.addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    if (searchInput.value.length >= 2 || searchInput.value.length === 0) {
                        searchForm.submit();
                    }
                }, 800);
            });
        }
    });
</script>
@endpush
@endsection