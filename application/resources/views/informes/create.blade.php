@extends('layouts.app')

@section('title', 'Nuevo Informe Técnico')

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container--default .select2-selection--single {
    height: 38px;
    border: 1px solid #ced4da;
    border-radius: 6px;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
    padding-left: 12px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
}

.select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #C2F527;
    box-shadow: 0 0 0 3px rgba(194, 245, 39, 0.15);
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #C2F527 !important;
    color: #000000 !important;
}

.select2-container--default .select2-search--dropdown .select2-search__field:focus {
    border-color: #C2F527 !important;
    outline: none;
}

.select2-dropdown {
    border-color: #ced4da;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* Estilo para el mensaje de búsqueda */
.select2-container--default .select2-search--dropdown .select2-search__field::placeholder {
    color: #999;
    font-style: italic;
}

/* Estilo para el botón de guardar */
.btn[style*="background-color: #C2F527"] {
    transition: all 0.3s ease !important;
}

.btn[style*="background-color: #C2F527"]:hover {
    background-color: #a8d420 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 5px 15px rgba(194, 245, 39, 0.3) !important;
}

/* Estilo para el botón de cancelar */
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

/* Estilo para el botón Volver al listado */
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

.form-control:focus, .form-select:focus {
    border-color: #C2F527 !important;
    box-shadow: 0 0 0 3px rgba(194, 245, 39, 0.15) !important;
}


/* Reforzar color del icono de informes */
.fa-file-alt {
    color: #C2F527 !important;
}

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
    
    .d-flex.justify-content-between.pt-3.border-top {
        flex-direction: column !important;
        gap: 10px !important;
    }
    
    .d-flex.justify-content-between.pt-3.border-top .btn {
        width: 100% !important;
        padding: 12px 20px !important;
        font-size: 1rem !important;
    }
    
    .card-body {
        padding: 1rem !important;
    }
    
    .form-control, .form-select, .select2-container--default .select2-selection--single {
        font-size: 16px !important;
    }
}
</style>
@endpush

@section('content')
<div class="container-main">
    <!-- Encabezado de página -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>
                    <i class="fas fa-file-medical" style="color: #C2F527;"></i>
                    Nuevo Informe Técnico
                </h1>
                <p class="page-subtitle">
                    Crear un nuevo informe técnico basado en una proforma existente
                </p>
            </div>
            <a href="{{ route('informes.index') }}" class="btn btn-outline-secondary btn-volver" style="border-radius: 30px; padding: 8px 20px;">
                <i class="fas fa-arrow-left me-2"></i>
                Volver al listado
            </a>
        </div>
    </div>

    <!-- Formulario -->
    <div class="card">
        <div class="card-header" style="background-color: #C2F527; border-bottom: none;">
            <h5 class="mb-0" style="color: #000000;">
                <i class="fas fa-edit me-2" style="color: #000000;"></i>
                Datos del Informe
            </h5>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('informes.store') }}" method="POST" enctype="multipart/form-data" id="informeForm">
                @csrf
                
                <!-- Proforma Asociada -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-file-invoice-dollar me-2 text-primary"></i>
                        Proforma Asociada
                    </h6>
                    
                    @if(isset($proforma) && $proforma)
                        <!-- Si viene de una proforma específica -->
                        <div class="alert alert-info">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Proforma seleccionada:</strong> {{ $proforma->codigo ?? 'N/A' }}
                                    <br>
                                    <small class="text-muted">
                                        Cliente: {{ $proforma->cliente->razon_social ?? $proforma->cliente->nombre ?? 'No especificado' }}
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        Tipo: {{ $proforma->tipo_muestra ?? $proforma->tipo ?? 'No especificado' }}
                                    </small>
                                </div>
                                <a href="{{ route('proformas.show', $proforma) }}" 
                                   class="btn btn-sm btn-outline-info" 
                                   target="_blank">
                                    <i class="fas fa-external-link-alt me-1"></i> Ver proforma
                                </a>
                            </div>
                        </div>
                        <input type="hidden" name="proforma_id" value="{{ $proforma->id }}">
                    @else
                        <!-- Selector de proforma con Select2 -->
                        <div class="mb-3">
                            <label for="proforma_id" class="form-label">
                                <i class="fas fa-search me-1 text-danger"></i>
                                Buscar Proforma <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('proforma_id') is-invalid @enderror" 
                                    id="proforma_id" 
                                    name="proforma_id" 
                                    required style="width: 100%;">
                                <option value="">🔍 Buscar proforma por código, cliente o tipo...</option>
                                @if(old('proforma_id'))
                                    @php
                                        $proformaSeleccionada = \App\Models\Proforma::with('cliente')->find(old('proforma_id'));
                                    @endphp
                                    @if($proformaSeleccionada)
                                        <option value="{{ $proformaSeleccionada->id }}" selected>
                                            {{ $proformaSeleccionada->codigo }} - 
                                            {{ $proformaSeleccionada->cliente->razon_social ?? 'Sin cliente' }} 
                                            ({{ $proformaSeleccionada->tipo_muestra ?? $proformaSeleccionada->tipo ?? 'N/A' }})
                                        </option>
                                    @endif
                                @endif
                            </select>
                            @error('proforma_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Solo se muestran proformas que no tienen informe asociado.
                                </small>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Fechas -->
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
                               value="{{ old('fecha_emision', date('Y-m-d')) }}" 
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
                               value="{{ old('fecha_entrega') }}">
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
                               value="{{ old('fecha_analisis') }}">
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
                               value="{{ old('fecha_revision') }}">
                        @error('fecha_revision')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Prioridad -->
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
                                <option value="BAJA" {{ old('prioridad') == 'BAJA' ? 'selected' : '' }}>
                                    🔵 Baja
                                </option>
                                <option value="MEDIA" {{ old('prioridad') == 'MEDIA' ? 'selected' : '' }}>
                                    🟡 Media
                                </option>
                                <option value="ALTA" {{ old('prioridad') == 'ALTA' ? 'selected' : '' }}>
                                    🟠 Alta
                                </option>
                                <option value="URGENTE" {{ old('prioridad') == 'URGENTE' ? 'selected' : '' }}>
                                    🔴 Urgente
                                </option>
                            </select>
                            @error('prioridad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">
                                <i class="fas fa-circle me-1"></i>
                                Estado Inicial
                            </label>
                            <select class="form-select @error('estado') is-invalid @enderror" 
                                    id="estado" 
                                    name="estado">
                                <option value="BORRADOR" {{ old('estado', 'BORRADOR') == 'BORRADOR' ? 'selected' : '' }}>
                                    📝 Borrador
                                </option>
                                <option value="EN_PROCESO" {{ old('estado') == 'EN_PROCESO' ? 'selected' : '' }}>
                                    ⚙️ En Proceso
                                </option>
                            </select>
                            <small class="form-text text-muted">
                                El informe comienza como Borrador por defecto
                            </small>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contenido Técnico -->
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
                                  placeholder="Ingrese los resultados del análisis...">{{ old('resultado') }}</textarea>
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
                                  placeholder="Ingrese las conclusiones del informe...">{{ old('conclusiones') }}</textarea>
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
                                  placeholder="Ingrese recomendaciones...">{{ old('recomendaciones') }}</textarea>
                        @error('recomendaciones')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Archivos Adjuntos -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-paperclip me-2 text-primary"></i>
                        Archivos Adjuntos
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="archivo_adjunto" class="form-label">
                                <i class="fas fa-file-pdf me-1 text-danger"></i>
                                Informe PDF (Escaneado)
                            </label>
                            <input type="file" 
                                   class="form-control @error('archivo_adjunto') is-invalid @enderror" 
                                   id="archivo_adjunto" 
                                   name="archivo_adjunto" 
                                   accept=".pdf">
                            <small class="form-text text-muted">
                                Máximo 10MB. Solo archivos PDF.
                            </small>
                            @error('archivo_adjunto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="archivo_resultados" class="form-label">
                                <i class="fas fa-file-excel me-1 text-success"></i>
                                Archivo de Resultados
                            </label>
                            <input type="file" 
                                   class="form-control @error('archivo_resultados') is-invalid @enderror" 
                                   id="archivo_resultados" 
                                   name="archivo_resultados" 
                                   accept=".csv,.xlsx,.xls,.txt">
                            <small class="form-text text-muted">
                                Máximo 5MB. Formatos: CSV, Excel, TXT.
                            </small>
                            @error('archivo_resultados')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Observaciones -->
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
                                  placeholder="Observaciones importantes sobre el informe...">{{ old('observaciones') }}</textarea>
                        @error('observaciones')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Información adicional oculta -->
                <input type="hidden" name="creado_por" value="{{ auth()->id() }}">

                <!-- Botones de acción -->
                <div class="d-flex justify-content-between pt-3 border-top">
                    <a href="{{ route('informes.index') }}" class="btn btn-secondary" style="border-radius: 30px; padding: 10px 25px;">
                        <i class="fas fa-times me-2"></i>
                        Cancelar
                    </a>
                    
                    <button type="submit" class="btn" 
                            style="background-color: #C2F527; border-radius: 30px; padding: 10px 25px; color: #000000; border: none; transition: all 0.3s ease; font-weight: 500;">
                        <i class="fas fa-save me-2"></i>
                        Guardar Informe
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery y Select2 desde CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    console.log('Inicializando Select2 para búsqueda de proformas');
    
    // ===== SELECT DE PROFORMAS CON SELECT2 =====
    $('#proforma_id').select2({
        placeholder: '🔍 Buscar proforma por código, cliente o tipo...',
        minimumInputLength: 2,
        allowClear: true,
        language: {
            inputTooShort: function() {
                return 'Ingrese al menos 2 caracteres para buscar';
            },
            searching: function() {
                return 'Buscando proformas...';
            },
            noResults: function() {
                return 'No se encontraron proformas disponibles';
            },
            errorLoading: function() {
                return 'Error al cargar resultados';
            }
        },
        ajax: {
            url: '{{ route("informes.buscar-proformas") }}',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                console.log('Proformas encontradas:', data);
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    // Establecer fecha mínima para fecha_entrega como fecha_emision
    const fechaEmision = document.getElementById('fecha_emision');
    const fechaEntrega = document.getElementById('fecha_entrega');
    
    if (fechaEmision && fechaEntrega) {
        fechaEmision.addEventListener('change', function() {
            fechaEntrega.min = this.value;
            // Si la fecha de entrega es menor a la fecha de emisión, actualizar
            if (fechaEntrega.value && fechaEntrega.value < this.value) {
                fechaEntrega.value = this.value;
            }
        });
        
        // Trigger inicial si hay valores
        if (fechaEmision.value) {
            fechaEntrega.min = fechaEmision.value;
        }
    }

    // Validación de archivos
    const archivoAdjunto = document.getElementById('archivo_adjunto');
    if (archivoAdjunto) {
        archivoAdjunto.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileSize = file.size / 1024 / 1024; // MB
                if (fileSize > 10) {
                    alert('El archivo no puede superar los 10MB');
                    this.value = '';
                }
            }
        });
    }

    const archivoResultados = document.getElementById('archivo_resultados');
    if (archivoResultados) {
        archivoResultados.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileSize = file.size / 1024 / 1024; // MB
                if (fileSize > 5) {
                    alert('El archivo no puede superar los 5MB');
                    this.value = '';
                }
            }
        });
    }
});
</script>
@endpush