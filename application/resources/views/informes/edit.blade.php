@extends('layouts.app')

@section('title', 'Editar Informe Técnico')

@section('content')
<div class="container-main">
    <!-- ======================================== -->
    <!-- ENCABEZADO DE PÁGINA                     -->
    <!-- ======================================== -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>
                    <i class="fas fa-edit" style="color: #C2F527;"></i>
                    Editar Informe Técnico
                </h1>
                <p class="page-subtitle">
                    Editar informe: {{ $informe->codigo }}
                </p>
            </div>
            <a href="{{ route('informes.show', $informe) }}" class="btn btn-outline-secondary btn-volver" style="border-radius: 30px; padding: 8px 20px;">
                <i class="fas fa-arrow-left me-2"></i>
                Volver al informe
            </a>
        </div>
    </div>

    <!-- ======================================== -->
    <!-- FORMULARIO PRINCIPAL                      -->
    <!-- ======================================== -->
    <div class="card">
        <div class="card-header" style="background-color: #C2F527; border-bottom: none;">
            <h5 class="mb-0" style="color: #000000;">
                <i class="fas fa-edit me-2" style="color: #000000;"></i>
                Editar datos del informe
            </h5>
        </div>
        
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('informes.update', $informe) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- ==================================== -->
                <!-- PROFORMA ASOCIADA                     -->
                <!-- ==================================== -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-file-invoice-dollar me-2 text-primary"></i>
                        Proforma Asociada
                    </h6>
                    
                    @if($informe->proforma)
                        <div class="alert alert-info">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Proforma:</strong> {{ $informe->proforma->codigo }}
                                    <br>
                                    <small class="text-muted">
                                        Cliente: {{ $informe->proforma->cliente->razon_social }}
                                    </small>
                                </div>
                                <a href="{{ route('proformas.show', $informe->proforma) }}" 
                                   class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-external-link-alt me-1"></i> Ver proforma
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- ==================================== -->
                <!-- FECHAS IMPORTANTES                    -->
                <!-- ==================================== -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="far fa-calendar-alt me-2 text-primary"></i>
                            Fechas Importantes
                        </h6>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="fecha_emision" class="form-label">
                            <i class="fas fa-calendar-day me-1 text-danger"></i>
                            Fecha Emisión <span class="text-danger">*</span>
                        </label>
                        <input type="date" 
                               class="form-control @error('fecha_emision') is-invalid @enderror" 
                               id="fecha_emision" 
                               name="fecha_emision" 
                               value="{{ old('fecha_emision', $informe->fecha_emision->format('Y-m-d')) }}" 
                               required>
                        @error('fecha_emision')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="fecha_entrega" class="form-label">
                            <i class="fas fa-shipping-fast me-1"></i>
                            Fecha Entrega Estimada
                        </label>
                        <input type="date" 
                               class="form-control @error('fecha_entrega') is-invalid @enderror" 
                               id="fecha_entrega" 
                               name="fecha_entrega" 
                               value="{{ old('fecha_entrega', $informe->fecha_entrega ? $informe->fecha_entrega->format('Y-m-d') : '') }}">
                        @error('fecha_entrega')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="fecha_analisis" class="form-label">
                            <i class="fas fa-flask me-1"></i>
                            Fecha Análisis
                        </label>
                        <input type="date" 
                               class="form-control @error('fecha_analisis') is-invalid @enderror" 
                               id="fecha_analisis" 
                               name="fecha_analisis" 
                               value="{{ old('fecha_analisis', $informe->fecha_analisis ? $informe->fecha_analisis->format('Y-m-d') : '') }}">
                        @error('fecha_analisis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="fecha_revision" class="form-label">
                            <i class="fas fa-search me-1"></i>
                            Fecha Revisión
                        </label>
                        <input type="date" 
                               class="form-control @error('fecha_revision') is-invalid @enderror" 
                               id="fecha_revision" 
                               name="fecha_revision" 
                               value="{{ old('fecha_revision', $informe->fecha_revision ? $informe->fecha_revision->format('Y-m-d') : '') }}">
                        @error('fecha_revision')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- ==================================== -->
                <!-- CONFIGURACIÓN DEL INFORME             -->
                <!-- ==================================== -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-flag me-2 text-primary"></i>
                        Configuración del Informe
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="prioridad" class="form-label">
                                <i class="fas fa-exclamation-circle me-1 text-danger"></i>
                                Prioridad <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('prioridad') is-invalid @enderror" 
                                    id="prioridad" 
                                    name="prioridad" 
                                    required>
                                <option value="">-- Seleccione prioridad --</option>
                                <option value="BAJA" {{ old('prioridad', $informe->prioridad) == 'BAJA' ? 'selected' : '' }}>
                                    🔵 Baja
                                </option>
                                <option value="MEDIA" {{ old('prioridad', $informe->prioridad) == 'MEDIA' ? 'selected' : '' }}>
                                    🟡 Media
                                </option>
                                <option value="ALTA" {{ old('prioridad', $informe->prioridad) == 'ALTA' ? 'selected' : '' }}>
                                    🟠 Alta
                                </option>
                                <option value="URGENTE" {{ old('prioridad', $informe->prioridad) == 'URGENTE' ? 'selected' : '' }}>
                                    🔴 Urgente
                                </option>
                            </select>
                            @error('prioridad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-info-circle me-1"></i>
                                Estado Actual
                            </label>
                            <div>
                                <span class="badge bg-{{ $informe->estado_color }} fs-6">
                                    {{ $informe->estado_texto }}
                                </span>
                                <small class="text-muted d-block mt-1">
                                    Para cambiar el estado, use el botón "Cambiar Estado" en la vista del informe.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================================== -->
                <!-- CONTENIDO TÉCNICO                     -->
                <!-- ==================================== -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-microscope me-2 text-primary"></i>
                        Contenido Técnico
                    </h6>
                    
                    <div class="mb-3">
                        <label for="resultado" class="form-label">
                            <i class="fas fa-clipboard-check me-1"></i>
                            Resultados
                        </label>
                        <textarea class="form-control @error('resultado') is-invalid @enderror" 
                                  id="resultado" 
                                  name="resultado" 
                                  rows="4"
                                  placeholder="Ingrese los resultados del análisis...">{{ old('resultado', $informe->resultado) }}</textarea>
                        @error('resultado')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="conclusiones" class="form-label">
                            <i class="fas fa-check-circle me-1"></i>
                            Conclusiones
                        </label>
                        <textarea class="form-control @error('conclusiones') is-invalid @enderror" 
                                  id="conclusiones" 
                                  name="conclusiones" 
                                  rows="3"
                                  placeholder="Ingrese las conclusiones del informe...">{{ old('conclusiones', $informe->conclusiones) }}</textarea>
                        @error('conclusiones')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="recomendaciones" class="form-label">
                            <i class="fas fa-lightbulb me-1"></i>
                            Recomendaciones
                        </label>
                        <textarea class="form-control @error('recomendaciones') is-invalid @enderror" 
                                  id="recomendaciones" 
                                  name="recomendaciones" 
                                  rows="3"
                                  placeholder="Ingrese recomendaciones...">{{ old('recomendaciones', $informe->recomendaciones) }}</textarea>
                        @error('recomendaciones')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- ==================================== -->
                <!-- ARCHIVOS ADJUNTOS                     -->
                <!-- ==================================== -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-paperclip me-2 text-primary"></i>
                        Archivos Adjuntos
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-file-pdf me-1 text-danger"></i>
                                Informe PDF Actual
                            </label>
                            @if($informe->archivo_adjunto)
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Archivo PDF adjunto
                                    <a href="{{ route('informes.descargar', ['informe' => $informe->id, 'tipo' => 'adjunto']) }}" 
                                       class="btn btn-sm btn-outline-success ms-2">
                                        <i class="fas fa-download me-1"></i> Descargar
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    No hay archivo PDF adjunto
                                </div>
                            @endif
                            <input type="file" 
                                   class="form-control @error('archivo_adjunto') is-invalid @enderror" 
                                   id="archivo_adjunto" 
                                   name="archivo_adjunto" 
                                   accept=".pdf">
                            <small class="form-text text-muted">
                                Dejar vacío para mantener el archivo actual. Máximo 10MB. Solo PDF.
                            </small>
                            @error('archivo_adjunto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-file-excel me-1 text-success"></i>
                                Archivo de Resultados Actual
                            </label>
                            @if($informe->archivo_resultados)
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Archivo de resultados adjunto
                                    <a href="{{ route('informes.descargar', ['informe' => $informe->id, 'tipo' => 'resultados']) }}" 
                                       class="btn btn-sm btn-outline-success ms-2">
                                        <i class="fas fa-download me-1"></i> Descargar
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    No hay archivo de resultados
                                </div>
                            @endif
                            <input type="file" 
                                   class="form-control @error('archivo_resultados') is-invalid @enderror" 
                                   id="archivo_resultados" 
                                   name="archivo_resultados" 
                                   accept=".csv,.xlsx,.xls,.txt">
                            <small class="form-text text-muted">
                                Dejar vacío para mantener el archivo actual. Máximo 5MB. Formatos: CSV, Excel, TXT.
                            </small>
                            @error('archivo_resultados')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- ==================================== -->
                <!-- OBSERVACIONES                         -->
                <!-- ==================================== -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-sticky-note me-2 text-primary"></i>
                        Observaciones
                    </h6>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">
                            <i class="fas fa-comment me-1"></i>
                            Observaciones Generales
                        </label>
                        <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                                  id="observaciones" 
                                  name="observaciones" 
                                  rows="3"
                                  placeholder="Observaciones importantes sobre el informe...">{{ old('observaciones', $informe->observaciones) }}</textarea>
                        @error('observaciones')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- ==================================== -->
                <!-- BOTONES DE ACCIÓN                     -->
                <!-- ==================================== -->
                <div class="d-flex justify-content-between pt-3 border-top">
                    <a href="{{ route('informes.show', $informe) }}" 
                       class="btn btn-secondary" 
                       style="border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease;">
                        <i class="fas fa-times me-2"></i>
                        Cancelar
                    </a>
                    
                    <button type="submit" 
                            class="btn" 
                            style="background-color: #C2F527; border-radius: 30px; padding: 10px 25px; color: #000000; border: none; transition: all 0.3s ease; font-weight: 500;"
                            onmouseover="this.style.backgroundColor='#a8d420'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(194, 245, 39, 0.3)';"
                            onmouseout="this.style.backgroundColor='#C2F527'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <i class="fas fa-save me-2"></i>
                        Actualizar Informe
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<!-- ======================================== -->
<!-- ESTILOS ADICIONALES                       -->
<!-- ======================================== -->
<style>

/* Estilo para el botón de actualizar - EFECTO COMPLETO */
.btn[style*="background-color: #C2F527"] {
    transition: all 0.3s ease !important;
}

.btn[style*="background-color: #C2F527"]:hover {
    background-color: #a8d420 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 5px 15px rgba(194, 245, 39, 0.3) !important;
}

/* Estilo para el botón de cancelar - EFECTO COMPLETO */
.btn-secondary {
    background-color: #6c757d !important;
    border: none !important;
    transition: all 0.3s ease !important;
}

.btn-secondary:hover {
    background-color: #5a6268 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3) !important;
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

.fa-file-alt {
    color: #C2F527 !important;
}

.form-control:focus, .form-select:focus {
    border-color: #33ff00 !important;
    box-shadow: 0 0 0 3px rgba(17, 86, 4, 0.15) !important;
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Establecer fecha mínima para fecha_entrega como fecha_emision
    const fechaEmision = document.getElementById('fecha_emision');
    const fechaEntrega = document.getElementById('fecha_entrega');
    
    if (fechaEmision && fechaEntrega) {
        fechaEmision.addEventListener('change', function() {
            fechaEntrega.min = this.value;
        });
    }
});
</script>
@endpush