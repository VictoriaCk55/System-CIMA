@extends('layouts.app')

@section('title', 'Detalles de Informe')

@section('content')
<div class="container-main">
    <!-- Encabezado de página con más espacio -->
    <div class="page-header mt-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>
                    <i class="fas fa-file-alt" style="color: #C2F527;"></i>
                    Detalles de Informe Técnico
                </h1>
                <p class="page-subtitle mt-2">
                    Información completa del informe {{ $informe->codigo }}
                </p>
            </div>
            
            <a href="{{ route('informes.index') }}"class="btn btn-outline-secondary btn-volver" style="border-radius: 30px; padding: 8px 20px;">
                <i class="fas fa-arrow-left me-2"></i>
                Volver al listado
            </a>
        </div>
    </div>

    <!-- Información principal -->
    <div class="row">
        <div class="col-md-8">
            <!-- Card principal -->
            <div class="card mb-4">
                <div class="card-header" style="background-color: #C2F527; border-bottom: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: #000000;">
                            <i class="fas fa-id-card me-2" style="color: #000000;"></i>
                            Informe Técnico: {{ $informe->codigo }}
                        </h5>
                        <div>
                            <span class="badge fs-6 px-3 py-2" 
                                  style="background-color: 
                                      @if($informe->estado_color == 'secondary') #6c757d
                                      @elseif($informe->estado_color == 'warning') #ffc107
                                      @elseif($informe->estado_color == 'info') #0dcaf0
                                      @elseif($informe->estado_color == 'success') #198754
                                      @else #212529
                                      @endif; 
                                      color: {{ in_array($informe->estado_color, ['warning']) ? '#000000' : '#ffffff' }};">
                                <i class="fas 
                                    @if($informe->estado == 'BORRADOR') fa-edit
                                    @elseif($informe->estado == 'EN_PROCESO') fa-spinner
                                    @elseif($informe->estado == 'REVISADO') fa-eye
                                    @elseif($informe->estado == 'APROBADO') fa-check
                                    @elseif($informe->estado == 'ENTREGADO') fa-check-double
                                    @else fa-file
                                    @endif me-1"></i>
                                {{ $informe->estado_texto }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- ALERTAS -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <!-- SECCIÓN INFORMACIÓN GENERAL -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-info-circle me-2" style="color: #C2F527;"></i>
                            Información General
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Código</label>
                                <p>
                                    <span class="badge fs-6" style="background-color: #0dcaf0; color: #000000;">
                                        <i class="fas fa-hashtag me-1"></i>
                                        {{ $informe->codigo }}
                                    </span>
                                </p>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Estado</label>
                                <p>
                                    <span class="badge" style="background-color: 
                                        @if($informe->estado_color == 'secondary') #6c757d
                                        @elseif($informe->estado_color == 'warning') #ffc107
                                        @elseif($informe->estado_color == 'info') #0dcaf0
                                        @elseif($informe->estado_color == 'success') #198754
                                        @else #212529
                                        @endif; 
                                        color: {{ in_array($informe->estado_color, ['warning']) ? '#000000' : '#ffffff' }};">
                                        <i class="fas 
                                            @if($informe->estado == 'BORRADOR') fa-edit
                                            @elseif($informe->estado == 'EN_PROCESO') fa-spinner
                                            @elseif($informe->estado == 'REVISADO') fa-eye
                                            @elseif($informe->estado == 'APROBADO') fa-check
                                            @elseif($informe->estado == 'ENTREGADO') fa-check-double
                                            @else fa-file
                                            @endif me-1"></i>
                                        {{ $informe->estado_texto }}
                                    </span>
                                </p>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Prioridad</label>
                                <p>
                                    <span class="badge" style="background-color: 
                                        @if($informe->prioridad_color == 'danger') #dc3545
                                        @elseif($informe->prioridad_color == 'warning') #ffc107
                                        @elseif($informe->prioridad_color == 'info') #0dcaf0
                                        @elseif($informe->prioridad_color == 'success') #198754
                                        @else #6c757d
                                        @endif; 
                                        color: {{ in_array($informe->prioridad_color, ['warning']) ? '#000000' : '#ffffff' }};">
                                        <i class="fas 
                                            @if($informe->prioridad == 'URGENTE') fa-exclamation-triangle
                                            @elseif($informe->prioridad == 'ALTA') fa-arrow-up
                                            @elseif($informe->prioridad == 'MEDIA') fa-equals
                                            @else fa-arrow-down
                                            @endif me-1"></i>
                                        {{ $informe->prioridad_texto }}
                                    </span>
                                </p>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Fecha Emisión</label>
                                <p class="fs-5">
                                    <i class="far fa-calendar me-2" style="color: #C2F527;"></i>
                                    {{ $informe->fecha_emision->format('d/m/Y') }}
                                </p>
                            </div>
                            
                            @if($informe->fecha_entrega)
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Fecha Entrega</label>
                                <p class="fs-5">
                                    <i class="fas fa-shipping-fast me-2" style="color: #C2F527;"></i>
                                    {{ \Carbon\Carbon::parse($informe->fecha_entrega)->format('d/m/Y') }}
                                </p>
                            </div>
                            @endif
                            
                            @if($informe->fecha_analisis)
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Fecha Análisis</label>
                                <p class="fs-5">
                                    <i class="fas fa-flask me-2" style="color: #C2F527;"></i>
                                    {{ \Carbon\Carbon::parse($informe->fecha_analisis)->format('d/m/Y') }}
                                </p>
                            </div>
                            @endif
                            
                            @if($informe->fecha_revision)
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Fecha Revisión</label>
                                <p class="fs-5">
                                    <i class="fas fa-search me-2" style="color: #C2F527;"></i>
                                    {{ \Carbon\Carbon::parse($informe->fecha_revision)->format('d/m/Y') }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- ===== NUEVA SECCIÓN: INFORMACIÓN DEL SISTEMA ===== -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-info-circle me-2" style="color: #C2F527;"></i>
                            Información del Sistema
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Fecha de Creación</label>
                                <p class="fs-5">
                                    <i class="far fa-calendar-plus me-2" style="color: #C2F527;"></i>
                                    {{ $informe->created_at->format('d/m/Y H:i:s') }}
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i> Hace {{ $informe->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Última Actualización</label>
                                <p class="fs-5">
                                    <i class="far fa-calendar-check me-2" style="color: #C2F527;"></i>
                                    {{ $informe->updated_at->format('d/m/Y H:i:s') }}
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i> Hace {{ $informe->updated_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- SECCIÓN PROFORMA ASOCIADA -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-file-invoice-dollar me-2" style="color: #C2F527;"></i>
                            Proforma Asociada
                        </h6>
                        
                        @if($informe->proforma)
                            <div class="alert alert-light">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small">Código Proforma</label>
                                        <p>
                                            <a href="{{ route('proformas.show', $informe->proforma) }}" 
                                               class="text-decoration-none">
                                                <span class="badge fs-6" style="background-color: #ffc107; color: #000000;">
                                                    <i class="fas fa-hashtag me-1"></i>
                                                    {{ $informe->proforma->codigo }}
                                                </span>
                                            </a>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small">Tipo de Muestra</label>
                                        <p class="fs-5">{{ $informe->proforma->tipo_muestra ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <label class="form-label text-muted small">Cliente</label>
                                        <p class="fs-5">
                                            <i class="fas fa-user-tie me-2" style="color: #C2F527;"></i>
                                            {{ $informe->proforma->cliente->razon_social ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i> No hay proforma asociada.
                            </div>
                        @endif
                    </div>
                    
                    <!-- SECCIÓN CONTENIDO TÉCNICO -->
                    @if($informe->resultado || $informe->conclusiones || $informe->recomendaciones)
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-microscope me-2" style="color: #C2F527;"></i>
                            Contenido Técnico
                        </h6>
                        
                        @if($informe->resultado)
                        <div class="mb-3">
                            <label class="form-label text-muted small">Resultados</label>
                            <div class="alert alert-light">
                                {!! nl2br(e($informe->resultado)) !!}
                            </div>
                        </div>
                        @endif
                        
                        @if($informe->conclusiones)
                        <div class="mb-3">
                            <label class="form-label text-muted small">Conclusiones</label>
                            <div class="alert alert-info">
                                {!! nl2br(e($informe->conclusiones)) !!}
                            </div>
                        </div>
                        @endif
                        
                        @if($informe->recomendaciones)
                        <div class="mb-3">
                            <label class="form-label text-muted small">Recomendaciones</label>
                            <div class="alert alert-warning">
                                {!! nl2br(e($informe->recomendaciones)) !!}
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                    
                    <!-- SECCIÓN RESPONSABLES -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-users me-2" style="color: #C2F527;"></i>
                            Responsables
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Creado por</label>
                                <p class="fs-5">
                                    <i class="fas fa-user-plus me-2" style="color: #C2F527;"></i>
                                    {{ $informe->creador->name ?? 'N/A' }}
                                </p>
                            </div>
                            
                            @if($informe->revisor)
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Revisado por</label>
                                <p class="fs-5">
                                    <i class="fas fa-eye me-2" style="color: #C2F527;"></i>
                                    {{ $informe->revisor->name }}
                                </p>
                            </div>
                            @endif
                            
                            @if($informe->aprobador)
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Aprobado por</label>
                                <p class="fs-5">
                                    <i class="fas fa-check-circle me-2" style="color: #C2F527;"></i>
                                    {{ $informe->aprobador->name }}
                                </p>
                            </div>
                            @endif
                            
                            @if($informe->entregador)
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Entregado por</label>
                                <p class="fs-5">
                                    <i class="fas fa-shipping-fast me-2" style="color: #C2F527;"></i>
                                    {{ $informe->entregador->name }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- SECCIÓN OBSERVACIONES -->
                    @if($informe->observaciones)
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-sticky-note me-2" style="color: #C2F527;"></i>
                                Observaciones
                            </h6>
                            <div class="alert alert-light">
                                {!! nl2br(e($informe->observaciones)) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Card archivos adjuntos -->
            <div class="card mb-4">
                <div class="card-header" style="background-color: #C2F527; border-bottom: none;">
                    <h5 class="mb-0" style="color: #000000;">
                        <i class="fas fa-paperclip me-2" style="color: #000000;"></i>
                        Archivos Adjuntos
                    </h5>
                </div>
                <div class="card-body">
                    @if($informe->archivo_adjunto)
                    <div class="mb-3">
                        <label class="form-label text-muted small">Informe PDF</label>
                        <div class="d-grid">
                            <a href="{{ route('informes.descargar', ['informe' => $informe->id, 'tipo' => 'adjunto']) }}" 
                               class="btn"
                               style="color: #dc3545; border: 2px solid #dc3545; background-color: transparent; border-radius: 30px; padding: 10px; transition: all 0.3s ease; font-weight: 500; text-decoration: none; display: block; text-align: center;"
                               onmouseover="this.style.backgroundColor='#dc3545'; this.style.color='white'; this.style.borderColor='#dc3545';"
                               onmouseout="this.style.backgroundColor='transparent'; this.style.color='#dc3545'; this.style.borderColor='#dc3545';">
                                <i class="fas fa-file-pdf me-2"></i>
                                Descargar PDF
                            </a>
                        </div>
                    </div>
                    @endif
                    
                    @if($informe->archivo_resultados)
                    <div class="mb-3">
                        <label class="form-label text-muted small">Archivo de Resultados</label>
                        <div class="d-grid">
                            <a href="{{ route('informes.descargar', ['informe' => $informe->id, 'tipo' => 'resultados']) }}" 
                               class="btn"
                               style="color: #198754; border: 2px solid #198754; background-color: transparent; border-radius: 30px; padding: 10px; transition: all 0.3s ease; font-weight: 500; text-decoration: none; display: block; text-align: center;"
                               onmouseover="this.style.backgroundColor='#198754'; this.style.color='white'; this.style.borderColor='#198754';"
                               onmouseout="this.style.backgroundColor='transparent'; this.style.color='#198754'; this.style.borderColor='#198754';">
                                <i class="fas fa-file-excel me-2"></i>
                                Descargar Resultados
                            </a>
                        </div>
                    </div>
                    @endif
                    
                    @if(!$informe->archivo_adjunto && !$informe->archivo_resultados)
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        No hay archivos adjuntos disponibles.
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Card acciones -->
            <div class="card mb-4">
                <div class="card-header" style="background-color: #C2F527; border-bottom: none;">
                    <h5 class="mb-0" style="color: #000000;">
                        <i class="fas fa-cogs me-2" style="color: #000000;"></i>
                        Acciones
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <!-- Botón de PDF -->
                        <a href="{{ route('informes.pdf', $informe) }}" 
                           class="btn"
                           style="color: #000000; border: 2px solid #198754; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500; text-decoration: none; display: block; text-align: center;"
                           onmouseover="this.style.backgroundColor='#198754'; this.style.color='#ffffff'; this.style.borderColor='#198754';"
                           onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#198754';"
                           target="_blank">
                            <i class="fas fa-file-pdf me-2"></i>
                            Generar PDF
                        </a>
                        
                        @auth
                            @if(Auth::user()->hasAnyRole(['admin', 'tecnico']))
                                <!-- Editar (solo para estados BORRADOR y EN_PROCESO) -->
                                @if(in_array($informe->estado, ['BORRADOR', 'EN_PROCESO']))
                                    <a href="{{ route('informes.edit', $informe) }}" 
                                       class="btn"
                                       style="color: #000000; border: 2px solid #ffc107; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500; text-decoration: none; display: block; text-align: center;"
                                       onmouseover="this.style.backgroundColor='#ffc107'; this.style.color='#000000'; this.style.borderColor='#ffc107';"
                                       onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#ffc107';">
                                        <i class="fas fa-edit me-2"></i>
                                        Editar Informe
                                    </a>
                                @endif
                                
                                <!-- Cambiar estado -->
                                <button type="button" 
                                        class="btn"
                                        style="color: #000000; border: 2px solid #0dcaf0; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500; width: 100%; text-align: center;"
                                        onmouseover="this.style.backgroundColor='#0dcaf0'; this.style.color='#ffffff'; this.style.borderColor='#0dcaf0';"
                                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#0dcaf0';"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#cambiarEstadoModal">
                                    <i class="fas fa-exchange-alt me-2"></i>
                                    Cambiar Estado
                                </button>
                                
                                <!-- Eliminar (solo BORRADOR) -->
                                @if($informe->estado == 'BORRADOR')
                                    <button type="button" 
                                            class="btn"
                                            style="background-color: #dc3545; border: 2px solid #dc3545; border-radius: 30px; padding: 10px 25px; color: #000000; font-weight: 500; transition: all 0.3s ease; width: 100%; text-align: center;"
                                            onmouseover="this.style.backgroundColor='#bb2d3b'; this.style.borderColor='#bb2d3b'; this.style.color='#000000';"
                                            onmouseout="this.style.backgroundColor='#dc3545'; this.style.borderColor='#dc3545'; this.style.color='#000000';"
                                            onclick="confirmarEliminacion()">
                                        <i class="fas fa-trash me-2"></i>
                                        Eliminar Informe
                                    </button>
                                    
                                    <!-- Formulario oculto para eliminar -->
                                    <form id="delete-form" 
                                          action="{{ route('informes.destroy', $informe) }}" 
                                          method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                            @endif
                        @endauth
                        
                        <!-- Ver proforma asociada -->
                        @if($informe->proforma)
                            <a href="{{ route('proformas.show', $informe->proforma) }}" 
                               class="btn"
                               style="color: #000000; border: 2px solid #C2F527; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500; text-decoration: none; display: block; text-align: center;"
                               onmouseover="this.style.backgroundColor='#C2F527'; this.style.color='#000000'; this.style.borderColor='#C2F527';"
                               onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#C2F527';">
                                <i class="fas fa-external-link-alt me-2"></i>
                                Ver Proforma
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Card información del sistema (ya no es necesaria porque agregamos la sección arriba) -->
        </div>
    </div>
</div>

<!-- Modal para cambiar estado -->
@auth
    @if(Auth::user()->hasAnyRole(['admin', 'tecnico']))
        <div class="modal fade" id="cambiarEstadoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #C2F527; border-bottom: none;">
                        <h5 class="modal-title" style="color: #000000;">
                            <i class="fas fa-exchange-alt me-2" style="color: #000000;"></i>
                            Cambiar Estado del Informe
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: invert(1);"></button>
                    </div>
                    <form action="{{ route('informes.cambiar-estado', $informe) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="estado" class="form-label">
                                    <i class="fas fa-flag me-1 text-danger"></i>
                                    Nuevo Estado <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="">-- Seleccione estado --</option>
                                    @php
                                        $transicionesPermitidas = [
                                            'BORRADOR' => ['EN_PROCESO'],
                                            'EN_PROCESO' => ['REVISADO', 'BORRADOR'],
                                            'REVISADO' => ['APROBADO', 'EN_PROCESO'],
                                            'APROBADO' => ['ENTREGADO', 'REVISADO'],
                                            'ENTREGADO' => ['APROBADO'],
                                        ];
                                        $estadosPosibles = $transicionesPermitidas[$informe->estado] ?? [];
                                    @endphp
                                    
                                    @foreach($estadosPosibles as $estadoPosible)
                                        <option value="{{ $estadoPosible }}">
                                            {{ \App\Models\Informe::ESTADOS[$estadoPosible] ?? $estadoPosible }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    Estado actual: <strong>{{ $informe->estado_texto }}</strong>
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
                            <button type="submit" class="btn" style="background-color: #C2F527; border-radius: 30px; padding: 8px 20px; color: #000000; border: none;"
                                    onmouseover="this.style.backgroundColor='#a8d420'; this.style.color='#000000';"
                                    onmouseout="this.style.backgroundColor='#C2F527'; this.style.color='#000000';">
                                <i class="fas fa-save me-2"></i>
                                Guardar Cambio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endauth

<!-- Estilos adicionales -->
<style>
/* Estilo para los iconos en la información */
.fa-file-alt {
    color: #C2F527 !important;
}

/* Estilo para el botón Volver al listado - BLANCO con texto NEGRO */
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
    box-shadow: 0 8px 20px rgba(128, 128, 128, 0.3) !important; /* Sombra gris más pronunciada */
}

/* Reforzar color del icono de informes */
.fa-file-alt {
    color: #C2F527 !important;
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
</style>


@push('scripts')
<script>
    // Función para confirmar eliminación
    function confirmarEliminacion() {
        if (confirm('¿Está seguro de eliminar este informe? Esta acción no se puede deshacer.')) {
            document.getElementById('delete-form').submit();
        }
    }
    
    // Inicializar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                placement: 'top',
                trigger: 'hover'
            });
        });
        console.log('✅ Tooltips inicializados en informe show');
    });
</script>
@endpush
@endsection