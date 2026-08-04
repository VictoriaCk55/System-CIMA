@extends('layouts.app')

@section('title', 'Proforma ' . $proforma->codigo)

@section('content')
<div class="container-main">
    <!-- Encabezado de página -->
    <div class="page-header mt-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="d-flex align-items-center gap-3 mb-2">
                    <h1 class="mb-0">
                        <i class="fas fa-file-invoice-dollar" style="color: #ffc107;"></i>
                        Proforma {{ $proforma->codigo }}
                    </h1>
                    <!-- BADGE DE ESTADO -->
                    <span class="badge bg-{{ $proforma->estado_color }} fs-6 px-3 py-2">
                        <i class="fas {{ $proforma->estado_icono }} me-1"></i>
                        {{ $proforma->estado_texto }}
                    </span>
                </div>
                <p class="page-subtitle">
                    Detalles completos de la proforma
                </p>
            </div>
            <div>
                <a href="{{ route('proformas.index') }}" class="btn btn-outline-secondary btn-volver" style="border-radius: 30px; padding: 8px 20px;">
                    <i class="fas fa-arrow-left me-2"></i>
                    Volver al listado
                </a>
            </div>
        </div>
    </div>

    <!-- Mensajes -->
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

    <!-- Contenido -->
    <div class="row">
        <!-- Información de la proforma -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header" style="background-color: #ffc107; border-bottom: none;">
                    <h5 class="mb-0" style="color: #000000;">
                        <i class="fas fa-info-circle me-2" style="color: #000000;"></i>
                        Información de la Proforma
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Código</label>
                            <p class="h5">
                                <span class="badge bg-info">
                                    <i class="fas fa-hashtag me-1"></i>
                                    {{ $proforma->codigo }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Tipo</label>
                            <p class="h5">
                                <span class="badge 
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
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Fecha de Emisión</label>
                            <p class="h5">
                                <i class="far fa-calendar me-1" style="color: #ffc107;"></i>
                                {{ $proforma->fecha_emision->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Fecha de Recepción</label>
                            <p class="h5">
                                @if($proforma->fecha_recepcion)
                                    <i class="far fa-calendar-check me-1" style="color: #ffc107;"></i>
                                    {{ $proforma->fecha_recepcion->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">No especificada</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Tipo de Muestra</label>
                            <p class="h5">
                                <i class="fas fa-flask me-1" style="color: #ffc107;"></i>
                                {{ $proforma->tipo_muestra }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Muestreado por</label>
                            <p class="h5">
                                <i class="fas fa-user-check me-1" style="color: #ffc107;"></i>
                                {{ $proforma->muestreado_por ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Procedencia</label>
                            <p class="h5">
                                <i class="fas fa-map-marker-alt me-1" style="color: #ffc107;"></i>
                                {{ $proforma->procedencia ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Coordenadas</label>
                            <p class="h5">
                                <i class="fas fa-globe-americas me-1" style="color: #ffc107;"></i>
                                @php
                                    $coords = [];
                                    if ($proforma->punto_cardinal_1 && $proforma->valor_cardinal_1) {
                                        $coords[] = '<b>' . e($proforma->punto_cardinal_1) . ':</b> ' . e($proforma->valor_cardinal_1);
                                    }
                                    if ($proforma->punto_cardinal_2 && $proforma->valor_cardinal_2) {
                                        $coords[] = '<b>' . e($proforma->punto_cardinal_2) . ':</b> ' . e($proforma->valor_cardinal_2);
                                    }
                                @endphp
                                {!! !empty($coords) ? implode('&nbsp;&nbsp;&nbsp;&nbsp;', $coords) : 'N/A' !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== NUEVA SECCIÓN: INFORMACIÓN DEL SISTEMA ===== -->
            <div class="card mb-4">
                <div class="card-header" style="background-color: #ffc107; border-bottom: none;">
                    <h5 class="mb-0" style="color: #000000;">
                        <i class="fas fa-info-circle me-2" style="color: #000000;"></i>
                        Información del Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-muted small">Fecha de Creación</label>
                            <p class="h5">
                                <i class="far fa-calendar-plus me-2" style="color: #ffc107;"></i>
                                {{ $proforma->created_at->format('d/m/Y H:i:s') }}
                            </p>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i> Hace {{ $proforma->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Última Actualización</label>
                            <p class="h5">
                                <i class="far fa-calendar-check me-2" style="color: #ffc107;"></i>
                                {{ $proforma->updated_at->format('d/m/Y H:i:s') }}
                            </p>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i> Hace {{ $proforma->updated_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalles del Cliente -->
            <div class="card mb-4">
                <div class="card-header" style="background-color: #ffc107; border-bottom: none;">
                    <h5 class="mb-0" style="color: #000000;">
                        <i class="fas fa-building me-2" style="color: #000000;"></i>
                        Datos del Cliente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Razón Social</label>
                            <p class="h5">{{ $proforma->cliente->razon_social }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">NIT</label>
                            <p class="h5">{{ $proforma->cliente->nit ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Contacto</label>
                            <p>
                                <i class="fas fa-user me-1" style="color: #ffc107;"></i>
                                {{ $proforma->persona_contacto ?? $proforma->cliente->persona_contacto ?? 'N/A' }}
                                <br>
                                <i class="fas fa-phone me-1" style="color: #ffc107;"></i>
                                {{ $proforma->telefono_contacto ?? $proforma->cliente->telefono ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Dirección</label>
                            <p>
                                <i class="fas fa-map-marker-alt me-1" style="color: #ffc107;"></i>
                                {{ $proforma->cliente->direccion ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- CAMPO UNIDAD - DESPUÉS DE DIRECCIÓN -->
                    @if($proforma->unidad)
                    <div class="row mt-2 pt-2 border-top">
                        <div class="col-12">
                            <label class="text-muted small">Unidad</label>
                            <p class="h5">
                                <i class="fas fa-building me-1" style="color: #ffc107;"></i>
                                @if($proforma->unidad == 'UIA')
                                    UIA - Unidad de Investigación Ambiental
                                @elseif($proforma->unidad == 'UAQ')
                                    UAQ - Unidad de Análisis Químico
                                @else
                                    {{ $proforma->unidad }}
                                @endif
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- ALERTA DE MODIFICACIÓN DE PARÁMETROS -->
            @if($proforma->parametros_modificados && $proforma->justificacion_modificacion)
            <div class="card mb-4 border-warning">
                <div class="card-header" style="background-color: #ffc107; border-bottom: none;">
                    <h5 class="mb-0" style="color: #000000;">
                        <i class="fas fa-exclamation-triangle me-2" style="color: #000000;"></i>
                        Modificación de Parámetros
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-gavel me-2"></i>
                        <strong>⚠️ Atención:</strong> Esta proforma está bajo contrato para modificación de parámetros.
                        <br><br>
                        <strong>Justificación:</strong> {{ $proforma->justificacion_modificacion }}
                        @if($proforma->usuarioModificacion)
                        <br>
                        <small class="text-muted mt-2 d-block">
                            <i class="fas fa-user-clock me-1"></i>
                            Modificado por: {{ $proforma->usuarioModificacion->name }}
                            <br>
                            <i class="fas fa-calendar-alt me-1"></i>
                            Fecha: {{ $proforma->updated_at->format('d/m/Y H:i:s') }}
                        </small>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Servicios/Parámetros -->
            <div class="card mb-4">
                <div class="card-header" style="background-color: #ffc107; border-bottom: none;">
                    <h5 class="mb-0" style="color: #000000;">
                        <i class="fas fa-flask me-2" style="color: #000000;"></i>
                        Servicios Solicitados
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th class="text-center">Método</th>
                                    <th class="text-center">Muestras</th>
                                    <th class="text-end">Precio Unit.</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($proforma->parametros as $parametro)
                                <tr>
                                    <td>
                                        <strong>{{ $parametro->categoria === 'RUIDO' ? 'RUIDO' : ($parametro->categoria === 'GASES' ? 'GASES' : $parametro->nombre) }}</strong>
                                    </td>
                                    <td class="text-center">{{ $proforma->tipo === 'AGUA' ? ($parametro->tecnica ?? 'N/A') : ($parametro->pivot->metodo ?: $parametro->metodo ?? 'N/A') }}</td>
                                    <td class="text-center">{{ $parametro->pivot->cantidad_muestras }}</td>
                                    <td class="text-end">Bs. {{ number_format($parametro->pivot->precio_unitario, 2) }}</td>
                                    <td class="text-end">Bs. {{ number_format($parametro->pivot->precio_unitario * $parametro->pivot->cantidad_muestras, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Subtotal:</td>
                                    <td class="text-end fw-bold">Bs. {{ number_format($proforma->parametros->sum(fn($p) => $p->pivot->precio_unitario * $p->pivot->cantidad_muestras), 2) }}</td>
                                </tr>
                                @if($proforma->descuento > 0)
                                <tr>
                                    <td colspan="4" class="text-end fw-bold text-danger">
                                        Descuento Institucional (20%):
                                    </td>
                                    <td class="text-end fw-bold text-danger">
                                        - Bs. {{ number_format($proforma->descuento, 2) }}
                                    </td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            @if($proforma->logisticasMuestreo->count() > 0)
            <div class="card mb-4">
                <div class="card-header" style="background-color: #ffc107; border-bottom: none;">
                    <h5 class="mb-0" style="color: #000000;">
                        <i class="fas fa-truck me-2" style="color: #000000;"></i>
                        Logística de Muestreo
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Concepto</th>
                                    <th>Descripción</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Costo Unit.</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($proforma->logisticasMuestreo as $log)
                                <tr>
                                    <td>{{ $log->categoria }} - {{ $log->descripcion }}</td>
                                    <td>{{ $log->pivot->descripcion ?? '' }}</td>
                                    <td class="text-center">{{ $log->pivot->cantidad }}</td>
                                    <td class="text-end">Bs. {{ number_format($log->costo, 2) }}</td>
                                    <td class="text-end">Bs. {{ number_format($log->pivot->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- ===== RESUMEN FINANCIERO ===== -->
            <div class="card mb-4">
                <div class="card-header" style="background-color: #ffc107; border-bottom: none;">
                    <h5 class="mb-0" style="color: #000000;">
                        <i class="fas fa-calculator me-2" style="color: #000000;"></i>
                        Resumen Financiero
                    </h5>
                </div>
                <div class="card-body py-0">
                    <div class="table-responsive" style="margin-bottom: 0;">
                        <table class="table table-sm" style="margin-bottom: 0;">
                            <tbody>
                                <tr>
                                    <td class="text-end fw-bold">Total:</td>
                                    <td class="text-end fw-bold text-success">
                                        Bs. {{ number_format($proforma->total, 2) }}
                                    </td>
                                </tr>
                                @if($proforma->adelanto > 0)
                                <tr>
                                    <td class="text-end">Adelanto:</td>
                                    <td class="text-end">Bs. {{ number_format($proforma->adelanto, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-end fw-bold">Saldo Pendiente:</td>
                                    <td class="text-end fw-bold {{ $proforma->saldo > 0 ? 'text-danger' : 'text-success' }}">
                                        Bs. {{ number_format($proforma->saldo, 2) }}
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if($proforma->observaciones)
            <div class="card mb-4">
                <div class="card-header" style="background-color: #ffc107; border-bottom: none;">
                    <h5 class="mb-0" style="color: #000000;">
                        <i class="fas fa-sticky-note me-2" style="color: #000000;"></i>
                        Observaciones
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $proforma->observaciones }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Panel lateral -->
        <div class="col-md-4">
            <!-- Card acciones -->
            <div class="card mb-4">
                <div class="card-header" style="background-color: #ffc107; border-bottom: none;">
                    <h5 class="mb-0" style="color: #000000;">
                        <i class="fas fa-cogs me-2" style="color: #000000;"></i>
                        Acciones
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($proforma->tipo === 'AMBIENTAL')
                            <div style="text-align: center; margin-top: 20px;">
                                <a href="{{ route('reportes.ambiental.index', $proforma->id) }}" 
                                class="btn"
                               style="color: #000000; border: 2px solid #6b0d7b; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500; text-decoration: none; display: block; text-align: center;"
                               onmouseover="this.style.backgroundColor='#6b0d7b'; this.style.color='#ffffff'; this.style.borderColor='#6b0d7b';"
                               onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#6b0d7b';">
                                    📝 Reporte Ambiental
                                </a>
                            </div>
                        @else
                            <div style="text-align: center; margin-top: 20px;">
                                <a href="{{ route('resultados.index', $proforma->id) }}" 
                                class="btn"
                               style="color: #000000; border: 2px solid #6b0d7b; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500; text-decoration: none; display: block; text-align: center;"
                               onmouseover="this.style.backgroundColor='#6b0d7b'; this.style.color='#ffffff'; this.style.borderColor='#6b0d7b';"
                               onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#6b0d7b';">
                                    📝 Resultados de Ensayo
                                </a>
                            </div>
                            <div style="text-align: center; margin-top: 12px;">
                                <a href="{{ route('proformas.cadena-custodia', $proforma->id) }}" 
                                class="btn"
                               style="color: #000000; border: 2px solid #17a2b8; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500; text-decoration: none; display: block; text-align: center;"
                               onmouseover="this.style.backgroundColor='#17a2b8'; this.style.color='#ffffff'; this.style.borderColor='#17a2b8';"
                               onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#17a2b8';">
                                    🔗 Cadena de Custodia
                                </a>
                            </div>
                        @endif
                                        <!-- PDF - Verde outline con texto negro, hover verde sólido texto blanco -->
                        <a href="{{ route('proformas.pdf', $proforma) }}" 
                           class="btn"
                           style="color: #000000; border: 2px solid #198754; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500; text-decoration: none; display: block; text-align: center;"
                           onmouseover="this.style.backgroundColor='#198754'; this.style.color='#ffffff'; this.style.borderColor='#198754';"
                           onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#198754';"
                           target="_blank">
                            <i class="fas fa-file-pdf me-2"></i>
                            Generar PDF
                        </a>
                        
                        @auth
                            @if(Auth::user()->hasAnyRole(['admin', 'tecnico', 'analista']))
                                
                                @if($proforma->estado == 'BORRADOR')
                                    <!-- Editar Proforma Completa - Amarillo outline -->
                                    <a href="{{ route('proformas.edit', $proforma) }}" 
                                       class="btn"
                                       style="color: #000000; border: 2px solid #ffc107; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500; text-decoration: none; display: block; text-align: center;"
                                       onmouseover="this.style.backgroundColor='#ffc107'; this.style.color='#000000'; this.style.borderColor='#ffc107';"
                                       onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#ffc107';">
                                        <i class="fas fa-edit me-2"></i>
                                        Editar Proforma Completa
                                    </a>
                                    
                                    <!-- ENVIAR A REVISIÓN -->
                                    @can('revision de proformas')
                                    <button type="button" 
                                            class="btn"
                                            style="color: #000000; border: 2px solid #0dcaf0; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500; width: 100%; text-align: center;"
                                            onmouseover="this.style.backgroundColor='#0dcaf0'; this.style.color='#ffffff'; this.style.borderColor='#0dcaf0';"
                                            onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#0dcaf0';"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#cambiarEstadoModal"
                                            data-estado="ENVIADA">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Enviar a Revisión
                                    </button>
                                    @endcan
                                    <!-- Rechazar Proforma -->
                                    <button type="button" 
                                            class="btn"
                                            style="color: #000000; border: 2px solid #dc3545; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500; width: 100%; text-align: center;"
                                            onmouseover="this.style.backgroundColor='#dc3545'; this.style.color='#ffffff'; this.style.borderColor='#dc3545';"
                                            onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#dc3545';"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#cambiarEstadoModal"
                                            data-estado="RECHAZADA">
                                        <i class="fas fa-times-circle me-2"></i>
                                        Rechazar Proforma
                                    </button>
                                    
                                    <!-- Eliminar Proforma -->
                                     @can('eliminar proformas')
                                    <form action="{{ route('proformas.destroy', $proforma) }}" 
                                          method="POST" 
                                          class="d-grid mt-2"
                                          onsubmit="return confirm('¿Está seguro de eliminar la proforma {{ $proforma->codigo }}? Esta acción no se puede deshacer.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn" style="background-color: #dc3545; border: 2px solid #dc3545; border-radius: 30px; padding: 10px 25px; color: #ffffff; font-weight: 500; transition: all 0.3s ease;"
                                                onmouseover="this.style.backgroundColor='#bb2d3b'; this.style.borderColor='#bb2d3b'; this.style.color='#000000';"
                                                onmouseout="this.style.backgroundColor='#dc3545'; this.style.borderColor='#dc3545'; this.style.color='#000000';">
                                            <i class="fas fa-trash me-2"></i>
                                            Eliminar Proforma
                                        </button>
                                    </form>
                                    @endcan
                                    
                                @elseif($proforma->estado == 'ENVIADA')
                                    <div class="alert alert-warning text-center">
                                        <i class="fas fa-clock me-2"></i>
                                        Proforma en revisión
                                    </div>
                                    
                                    <!-- Botón para editar SOLO ADELANTO (ENVIADA) -->
                                    <button type="button" 
                                            class="btn"
                                            style="color: #000000; border: 2px solid #ffc107; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500; width: 100%; text-align: center;"
                                            onmouseover="this.style.backgroundColor='#ffc107'; this.style.color='#000000'; this.style.borderColor='#ffc107';"
                                            onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#ffc107';"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editarAdelantoModal">
                                        <i class="fas fa-money-bill-wave me-2"></i>
                                        Editar Adelanto
                                    </button>
                                    
                                    <!-- Aprobar Proforma -->
                                    <button type="button" 
                                            class="btn"
                                            style="color: #000000; border: 2px solid #198754; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500; width: 100%; text-align: center;"
                                            onmouseover="this.style.backgroundColor='#198754'; this.style.color='#ffffff'; this.style.borderColor='#198754';"
                                            onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#198754';"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#cambiarEstadoModal"
                                            data-estado="APROBADA">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Aprobar Proforma
                                    </button>
                                    
                                    <!-- Rechazar Proforma -->
                                    <button type="button" 
                                            class="btn"
                                            style="color: #000000; border: 2px solid #dc3545; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500; width: 100%; text-align: center;"
                                            onmouseover="this.style.backgroundColor='#dc3545'; this.style.color='#ffffff'; this.style.borderColor='#dc3545';"
                                            onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#dc3545';"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#cambiarEstadoModal"
                                            data-estado="RECHAZADA">
                                        <i class="fas fa-times-circle me-2"></i>
                                        Rechazar Proforma
                                    </button>
                                    
                                @elseif($proforma->estado == 'APROBADA')
                                    <div class="alert alert-success text-center">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Proforma aprobada
                                    </div>
                                    
                                    <!-- Botón para editar SOLO ADELANTO (APROBADA) -->
                                    @can('editar adelanto de proformas')
                                    <button type="button" 
                                            class="btn"
                                            style="color: #000000; border: 2px solid #ffc107; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500; width: 100%; text-align: center;"
                                            onmouseover="this.style.backgroundColor='#ffc107'; this.style.color='#000000'; this.style.borderColor='#ffc107';"
                                            onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#ffc107';"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editarAdelantoModal">
                                        <i class="fas fa-money-bill-wave me-2"></i>
                                        Editar Adelanto
                                    </button>
                                    @endcan
                                    
                                    @if($proforma->informe)
                                        <!-- Ver Informe Asociado -->
                                        <a href="{{ route('informes.show', $proforma->informe) }}" 
                                           class="btn"
                                           style="color: #000000; border: 2px solid #ffc107; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500; text-decoration: none; display: block; text-align: center;"
                                           onmouseover="this.style.backgroundColor='#ffc107'; this.style.color='#000000'; this.style.borderColor='#ffc107';"
                                           onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#ffc107';">
                                            <i class="fas fa-file-alt me-2"></i>
                                            Ver Informe Asociado
                                        </a>
                                    @else
                                        <!-- Crear Informe -->
                                        <a href="{{ route('informes.create', ['proforma_id' => $proforma->id]) }}" 
                                           class="btn"
                                           style="color: #000000; border: 2px solid #0dcaf0; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500; text-decoration: none; display: block; text-align: center;"
                                           onmouseover="this.style.backgroundColor='#0dcaf0'; this.style.color='#ffffff'; this.style.borderColor='#0dcaf0';"
                                           onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#0dcaf0';">
                                            <i class="fas fa-file-medical me-2"></i>
                                            Crear Informe
                                        </a>
                                    @endif
                                    
                                @elseif($proforma->estado == 'FINALIZADA')
                                    <div class="alert alert-secondary text-center">
                                        <i class="fas fa-flag-checkered me-2"></i>
                                        Proforma finalizada
                                    </div>
                                    
                                    <!-- FINALIZADA - NO SE PUEDE EDITAR NADA -->
                                    <div class="alert alert-info mt-2">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Las proformas finalizadas no pueden ser modificadas.
                                    </div>
                                    
                                    @if($proforma->informe)
                                        <a href="{{ route('informes.show', $proforma->informe) }}" 
                                           class="btn"
                                           style="color: #000000; border: 2px solid #ffc107; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500; text-decoration: none; display: block; text-align: center;"
                                           onmouseover="this.style.backgroundColor='#ffc107'; this.style.color='#000000'; this.style.borderColor='#ffc107';"
                                           onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#ffc107';">
                                            <i class="fas fa-file-alt me-2"></i>
                                            Ver Informe
                                        </a>
                                    @endif
                                    
                                @elseif($proforma->estado == 'RECHAZADA')
                                    <div class="alert alert-danger text-center">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Proforma rechazada
                                    </div>
                                    
                                    <!-- Volver a Borrador -->
                                    <button type="button" 
                                            class="btn"
                                            style="color: #000000; border: 2px solid #ffc107; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500; width: 100%; text-align: center;"
                                            onmouseover="this.style.backgroundColor='#ffc107'; this.style.color='#000000'; this.style.borderColor='#ffc107';"
                                            onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#ffc107';"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#cambiarEstadoModal"
                                            data-estado="BORRADOR">
                                        <i class="fas fa-undo me-2"></i>
                                        Volver a Borrador
                                    </button>
                                @endif
                                
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Información de Informe -->
            <div class="card">
                <div class="card-header" style="background-color: #ffc107; border-bottom: none;">
                    <h5 class="mb-0" style="color: #000000;">
                        <i class="fas fa-file-alt me-2" style="color: #000000;"></i>
                        Informe Asociado
                    </h5>
                </div>
                <div class="card-body">
                    @if($proforma->informe)
                        <div class="text-center">
                            <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                            <h5>Informe {{ $proforma->informe->codigo }}</h5>
                            <p class="text-muted">
                                Estado: 
                                <span class="badge bg-{{ $proforma->informe->estado_color }}">
                                    {{ $proforma->informe->estado_texto }}
                                </span>
                            </p>
                            <!-- Ver Informe -->
                            <a href="{{ route('informes.show', $proforma->informe) }}" 
                               class="btn"
                               style="color: #000000; border: 2px solid #ffc107; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500; text-decoration: none; display: inline-block;"
                               onmouseover="this.style.backgroundColor='#ffc107'; this.style.color='#000000'; this.style.borderColor='#ffc107';"
                               onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#ffc107';">
                                <i class="fas fa-eye me-1"></i> Ver Informe
                            </a>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-file-alt text-muted fa-3x mb-3"></i>
                            <p class="text-muted">Esta proforma no tiene un informe asociado</p>
                            @auth
                                @if(Auth::user()->hasAnyRole(['admin', 'tecnico', 'analista']) && $proforma->estado == 'APROBADA')
                                    <a href="{{ route('informes.create', ['proforma_id' => $proforma->id]) }}" 
                                       class="btn"
                                       style="color: #000000; border: 2px solid #0dcaf0; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500; text-decoration: none; display: inline-block;"
                                       onmouseover="this.style.backgroundColor='#0dcaf0'; this.style.color='#ffffff'; this.style.borderColor='#0dcaf0';"
                                       onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#0dcaf0';">
                                        <i class="fas fa-plus-circle me-1"></i> Generar Informe
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar estado -->
@auth
    @if(Auth::user()->hasAnyRole(['admin', 'tecnico', 'analista']))
        <div class="modal fade" id="cambiarEstadoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #ffc107; border-bottom: none;">
                        <h5 class="modal-title" style="color: #000000;">
                            <i class="fas fa-exchange-alt me-2" style="color: #000000;"></i>
                            Cambiar Estado de la Proforma
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: invert(1);"></button>
                    </div>
                    <form action="{{ route('proformas.cambiar-estado', $proforma) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="estado" class="form-label">
                                    <i class="fas fa-flag me-1 text-danger"></i>
                                    Nuevo Estado <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="">-- Seleccione estado --</option>
                                    <option value="BORRADOR">📝 Borrador</option>
                                    <option value="ENVIADA">⏳ Enviada</option>
                                    <option value="APROBADA">✅ Aprobada</option>
                                    <option value="RECHAZADA">❌ Rechazada</option>
                                    <option value="FINALIZADA">🏁 Finalizada</option>
                                </select>
                                <small class="form-text text-muted">
                                    Estado actual: <strong>{{ $proforma->estado_texto }}</strong>
                                </small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="comentario" class="form-label">
                                    <i class="fas fa-comment me-1"></i>
                                    Comentario (Opcional)
                                </label>
                                <textarea class="form-control" 
                                          id="comentario" 
                                          name="comentario" 
                                          rows="3"
                                          placeholder="Justificación del cambio de estado..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 30px; padding: 8px 20px;">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </button>
                            <button type="submit" class="btn" style="background-color: #ffc107; border-radius: 30px; padding: 8px 20px; color: #000000; border: none;">
                                <i class="fas fa-save me-2"></i>
                                Guardar Cambio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Modal para editar SOLO ADELANTO -->
         @can('editar adelanto de proformas')
        <div class="modal fade" id="editarAdelantoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #ffc107; border-bottom: none;">
                        <h5 class="modal-title" style="color: #000000;">
                            <i class="fas fa-money-bill-wave me-2" style="color: #000000;"></i>
                            Actualizar Adelanto
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: invert(1);"></button>
                    </div>
                    <form action="{{ route('proformas.actualizar-adelanto', $proforma) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Información actual:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Total: <strong>Bs. {{ number_format($proforma->total, 2) }}</strong></li>
                                    <li>Adelanto actual: <strong>Bs. {{ number_format($proforma->adelanto, 2) }}</strong></li>
                                    <li>Saldo actual: <strong class="{{ $proforma->saldo > 0 ? 'text-danger' : 'text-success' }}">
                                        Bs. {{ number_format($proforma->saldo, 2) }}
                                    </strong></li>
                                </ul>
                            </div>
                            
                            <div class="mb-3">
                                <label for="nuevo_adelanto" class="form-label">
                                    <i class="fas fa-hand-holding-usd me-1 text-warning"></i>
                                    Nuevo Adelanto (Bs.) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Bs.</span>
                                    <input type="number" 
                                           class="form-control" 
                                           id="nuevo_adelanto" 
                                           name="adelanto" 
                                           value="{{ $proforma->adelanto }}" 
                                           min="0" 
                                           max="{{ $proforma->total }}"
                                           step="0.01"
                                           required>
                                </div>
                                <small class="text-muted">
                                    El adelanto no puede ser mayor al total (Bs. {{ number_format($proforma->total, 2) }})
                                </small>
                            </div>
                            
                            <div class="mb-3" id="nuevoSaldoPreview">
                                @php
                                    $nuevoSaldoPreview = $proforma->total - $proforma->adelanto;
                                @endphp
                                <label class="form-label text-muted">Nuevo saldo estimado:</label>
                                <p class="h5 {{ $nuevoSaldoPreview > 0 ? 'text-danger' : 'text-success' }}">
                                    <i class="fas {{ $nuevoSaldoPreview > 0 ? 'fa-exclamation-triangle' : 'fa-check-circle' }} me-2"></i>
                                    Bs. <span id="saldoPreview">{{ number_format($nuevoSaldoPreview, 2) }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 30px; padding: 8px 20px;">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </button>
                            <button type="submit" class="btn" style="background-color: #ffc107; border-radius: 30px; padding: 8px 20px; color: #000000; border: none;">
                                <i class="fas fa-save me-2"></i>
                                Actualizar Adelanto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endcan
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal para cambiar estado
            const estadoModal = document.getElementById('cambiarEstadoModal');
            if (estadoModal) {
                estadoModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    if (button) {
                        const estado = button.getAttribute('data-estado');
                        if (estado) {
                            const select = document.getElementById('estado');
                            if (select) {
                                select.value = estado;
                            }
                        }
                    }
                });
            }
            
            // Preview de saldo en modal de adelanto
            const adelantoInput = document.getElementById('nuevo_adelanto');
            const saldoPreview = document.getElementById('saldoPreview');
            const totalProforma = {{ $proforma->total }};
            
            if (adelantoInput && saldoPreview) {
                adelantoInput.addEventListener('input', function() {
                    const nuevoAdelanto = parseFloat(this.value) || 0;
                    const nuevoSaldo = totalProforma - nuevoAdelanto;
                    
                    saldoPreview.textContent = nuevoSaldo.toFixed(2);
                    
                    // Cambiar color según el saldo
                    const previewElement = document.getElementById('nuevoSaldoPreview').querySelector('p');
                    if (nuevoSaldo > 0) {
                        previewElement.className = 'h5 text-danger';
                        previewElement.querySelector('i').className = 'fas fa-exclamation-triangle me-2';
                    } else if (nuevoSaldo < 0) {
                        previewElement.className = 'h5 text-warning';
                        previewElement.querySelector('i').className = 'fas fa-arrow-up me-2';
                    } else {
                        previewElement.className = 'h5 text-success';
                        previewElement.querySelector('i').className = 'fas fa-check-circle me-2';
                    }
                });
            }
        });
        </script>
    @endif
@endauth

<style>
/* ========== ESTILOS ADICIONALES ========== */
.fa-file-invoice-dollar {
    color: #ffc107 !important;
}

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

/* ========== CORRECCIÓN PARA MÓVIL ========== */
@media (max-width: 768px) {
    .page-header .d-flex {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 15px !important;
    }
    
    .page-header .btn-volver {
        width: 100% !important;
        justify-content: center !important;
        margin-top: 10px !important;
    }
    
    .d-flex.align-items-center.gap-3 {
        flex-wrap: wrap !important;
        gap: 10px !important;
    }
    
    .d-flex.align-items-center.gap-3 h1 {
        font-size: 1.5rem !important;
        width: 100% !important;
    }
    
    .badge.fs-6 {
        font-size: 0.85rem !important;
        padding: 5px 12px !important;
    }
    
    .col-md-8, .col-md-4 {
        width: 100% !important;
    }
    
    .d-grid.gap-2 .btn {
        width: 100% !important;
        margin-bottom: 5px !important;
    }
    
    .table-responsive {
        overflow-x: auto !important;
    }
    
    .card-body .row .col-md-6 {
        width: 100% !important;
    }
    
    .fa-2x {
        font-size: 1.5rem !important;
    }
}

@media (min-width: 769px) and (max-width: 991px) {
    .col-md-8, .col-md-4 {
        width: 100% !important;
    }
}
</style>
@endsection