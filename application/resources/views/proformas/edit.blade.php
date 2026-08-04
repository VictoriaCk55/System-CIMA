@extends('layouts.app')

@section('title', 'Editar Proforma')

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
    border-color: #ffc107;
    box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.15);
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #ffc107 !important;
    color: #000000 !important;
}

.select2-container--default .select2-search--dropdown .select2-search__field:focus {
    border-color: #ffc107 !important;
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

/* Mensaje personalizado en el placeholder */
.select2-selection__placeholder {
    color: #6c757d !important;
    font-style: italic;
}

/* Estilo para el botón de guardar */
.btn[style*="background-color: #ffc107"]:hover {
    background-color: #e6a800 !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
}

#add-parametro.btn[style*="background-color: #ffc107"] {
    background-color: #ffc107 !important;
    color: #000000 !important;
}

.btn-secondary {
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
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

.info-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.info-icon {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #ffc107, #ffb300);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #1a1a2e;
    font-size: 20px;
    flex-shrink: 0;
}

.info-text h4 {
    font-size: 12px;
    color: #666;
    margin-bottom: 4px;
}

.info-text p {
    font-size: 15px;
    font-weight: 700;
    color: #1a1a2e;
}

.info-item-stacked {
    align-items: flex-start;
}

.info-item-stacked .stacked-fields {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.modern-input {
    width: 85px;
    padding: 8px 10px;
    text-align: center;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 12px;
    transition: all 0.3s ease;
    background: white;
}

.modern-input:focus {
    outline: none;
    border-color: #ffc107;
    box-shadow: 0 0 0 3px rgba(255,193,7,0.2);
}

.modern-input:hover:not(:disabled) {
    border-color: #ffc107;
}

.modern-input:disabled {
    background: #f5f5f5;
    color: #999;
    cursor: not-allowed;
    border-color: #e0e0e0;
}

.form-control:focus, .form-select:focus, .select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #ffbf00 !important;
    box-shadow: 0 0 0 3px rgba(153, 132, 30, 0.15) !important;
}

/* Estilo para errores de validación */
.alert-duplicado-frontend {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
    padding: 12px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: none;
    font-weight: 500;
}

.alert-duplicado-frontend i {
    margin-right: 10px;
    color: #dc3545;
}


/* Reforzar color del icono de proformas */
.fa-file-invoice-dollar {
    color: #ffc107 !important;
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
                    <i class="fas fa-edit" style="color: #ffc107;"></i>
                    Editar Proforma
                </h1>
                <p class="page-subtitle">
                    Actualice la información de la proforma: {{ $proforma->codigo }}
                </p>
            </div>
            <a href="{{ route('proformas.index') }}" class="btn btn-outline-secondary btn-volver" style="border-radius: 30px; padding: 8px 20px;">
                <i class="fas fa-arrow-left me-2"></i>
                Volver al listado
            </a>
        </div>
    </div>

    <!-- Mensajes de error -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Por favor corrija los siguientes errores:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Formulario -->
    <div class="card">
        <div class="card-header" style="background-color: #ffc107; border-bottom: none;">
            <h5 class="mb-0" style="color: #000000;">
                <i class="fas fa-edit me-2" style="color: #000000;"></i>
                Formulario de Edición
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('proformas.update', $proforma) }}" method="POST" id="proformaForm">
                @csrf
                @method('PUT')
                
                <!-- SECCIÓN 1: DATOS CLIENTE -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-user-tie me-2" style="color: #ffc107;"></i>
                        Datos del Cliente
                    </h6>
                    
                    <div class="mb-3">
                        <label for="cliente_id" class="form-label">Cliente *</label>
                        <select class="form-select @error('cliente_id') is-invalid @enderror" 
                                id="cliente_id" name="cliente_id" required style="width: 100%;">
                            <option value="">Buscar cliente...</option>
                            @if(old('cliente_id', $proforma->cliente_id))
                                @php
                                    $clienteSeleccionado = \App\Models\Cliente::find(old('cliente_id', $proforma->cliente_id));
                                @endphp
                                @if($clienteSeleccionado)
                                    <option value="{{ $clienteSeleccionado->id }}" selected>
                                        {{ $clienteSeleccionado->razon_social }} - {{ $clienteSeleccionado->persona_contacto }}
                                    </option>
                                @endif
                            @endif
                        </select>
                        @error('cliente_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- SECCIÓN 2: DATOS BÁSICOS -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-info-circle me-2" style="color: #ffc107;"></i>
                        Datos Básicos de la Proforma
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tipo" class="form-label">Tipo de Proforma *</label>
                            <select class="form-select @error('tipo') is-invalid @enderror" 
                                    id="tipo" name="tipo" required 
                                    onchange="calcularTotalesEstimados()">
                                <option value="AMBIENTAL" {{ old('tipo', $proforma->tipo) == 'AMBIENTAL' ? 'selected' : '' }}>AMBIENTAL</option>
                                <option value="AGUA" {{ old('tipo', $proforma->tipo) == 'AGUA' ? 'selected' : '' }}>AGUA</option>
                                <option value="INVESTIGACION" {{ old('tipo', $proforma->tipo) == 'INVESTIGACION' ? 'selected' : '' }}>INVESTIGACIÓN (20% descuento)</option>
                            </select>
                            @error('tipo')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="tipo_muestra" class="form-label">Tipo de Muestra *</label>
                            <input type="text" 
                                   class="form-control @error('tipo_muestra') is-invalid @enderror" 
                                   id="tipo_muestra" 
                                   name="tipo_muestra" 
                                   value="{{ old('tipo_muestra', $proforma->tipo_muestra) }}"
                                   placeholder="Ej: AGUA RESIDUAL, SUELO, SEDIMENTO"
                                   required>
                            <small class="text-muted">Ingrese el tipo de muestra libremente</small>
                            @error('tipo_muestra')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- CAMPO UNIDAD - NUEVO -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="unidad" class="form-label">Unidad (Opcional)</label>
                            <select class="form-select @error('unidad') is-invalid @enderror" 
                                    id="unidad" name="unidad">
                                <option value="">Seleccionar unidad...</option>
                                <option value="UIA" {{ old('unidad', $proforma->unidad) == 'UIA' ? 'selected' : '' }}>UIA - Unidad de Investigación Ambiental</option>
                                <option value="UAQ" {{ old('unidad', $proforma->unidad) == 'UAQ' ? 'selected' : '' }}>UAQ - Unidad de Análisis Químico</option>
                            </select>
                            <small class="text-muted">Seleccione la unidad responsable de la proforma</small>
                            @error('unidad')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="fecha_emision" class="form-label">Fecha de Emisión *</label>
                            <input type="date" class="form-control @error('fecha_emision') is-invalid @enderror" 
                                   id="fecha_emision" name="fecha_emision" 
                                   value="{{ old('fecha_emision', $proforma->fecha_emision->format('Y-m-d')) }}" required>
                            @error('fecha_emision')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="fecha_recepcion" class="form-label">Fecha de Recepción *</label>
                            <input type="date" class="form-control @error('fecha_recepcion') is-invalid @enderror" 
                                   id="fecha_recepcion" name="fecha_recepcion" 
                                   value="{{ old('fecha_recepcion', $proforma->fecha_recepcion->format('Y-m-d')) }}" required>
                            @error('fecha_recepcion')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="numero_recepcion" class="form-label">Nro. de Recepción</label>
                            <input type="text" class="form-control @error('numero_recepcion') is-invalid @enderror" 
                                   id="numero_recepcion" name="numero_recepcion" 
                                   value="{{ old('numero_recepcion', $proforma->numero_recepcion) }}"
                                   placeholder="Ej: 001">
                            @error('numero_recepcion')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Códigos de Cliente</label>
                            <div id="codigos-cliente-container">
                                @php $oldCodigos = old('codigo_cliente', $proforma->codigo_cliente ?? ['']); @endphp
                                @foreach ($oldCodigos as $i => $codigo)
                                    <div class="codigo-cliente-row d-flex gap-2 mb-2">
                                        <input type="text" class="form-control @error('codigo_cliente.'.$i) is-invalid @enderror" 
                                               name="codigo_cliente[]" value="{{ $codigo }}" placeholder="Ej: CL-001">
                                        <button type="button" class="btn btn-outline-danger btn-sm eliminar-codigo" 
                                                style="{{ $loop->first ? 'display:none;' : '' }}">&times;</button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm mt-1" id="agregar-codigo-cliente">
                                <i class="fas fa-plus"></i> Agregar código
                            </button>
                            @error('codigo_cliente')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Documento</label>
                            <div class="border rounded p-3" style="background: #f8f9fa;">
                                @php $tds = old('tipo_documento', $proforma->tipo_documento ?? []); @endphp
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="tipo_documento[]" value="PROFORMA" 
                                           id="td-proforma" {{ in_array('PROFORMA', $tds) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="td-proforma">PROFORMA</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="tipo_documento[]" value="COTIZACION" 
                                           id="td-cotizacion" {{ in_array('COTIZACION', $tds) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="td-cotizacion">COTIZACIÓN</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="tipo_documento[]" value="CONTRATO" 
                                           id="td-contrato" {{ in_array('CONTRATO', $tds) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="td-contrato">CONTRATO</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="tipo_documento[]" value="CONTRATO MODIFICADO" 
                                           id="td-contrato-mod" {{ in_array('CONTRATO MODIFICADO', $tds) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="td-contrato-mod">CONTRATO MODIFICADO</label>
                                </div>
                            </div>
                            @error('tipo_documento')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 3: DATOS DE CONTACTO -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-address-card me-2" style="color: #ffc107;"></i>
                        Datos de Contacto
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="persona_contacto" class="form-label">Persona de Contacto</label>
                            <input type="text" class="form-control @error('persona_contacto') is-invalid @enderror" 
                                   id="persona_contacto" name="persona_contacto" 
                                   value="{{ old('persona_contacto', $proforma->persona_contacto) }}">
                            @error('persona_contacto')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="telefono_contacto" class="form-label">Teléfono de Contacto</label>
                            <input type="text" class="form-control @error('telefono_contacto') is-invalid @enderror" 
                                   id="telefono_contacto" name="telefono_contacto" 
                                   value="{{ old('telefono_contacto', $proforma->telefono_contacto) }}">
                            @error('telefono_contacto')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 4: DATOS DE MUESTREO -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-map-marker-alt me-2" style="color: #ffc107;"></i>
                        Datos de Muestreo
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="procedencia" class="form-label">Procedencia</label>
                            <input type="text" class="form-control @error('procedencia') is-invalid @enderror" 
                                   id="procedencia" name="procedencia" 
                                   value="{{ old('procedencia', $proforma->procedencia) }}">
                            @error('procedencia')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Coordenadas</label>
                            <div style="display: flex; flex-wrap: wrap; gap: 24px; align-items: flex-end; border: 1px solid #e0e0e0; border-radius: 12px; padding: 12px;">
                                <div>
                                    <h4 style="font-size: 12px; color: #666; margin-bottom: 4px;">PUNTO CARDINAL 1</h4>
                                    <div style="display: flex; gap: 8px;">
                                        <select id="puntoCardinal1" name="punto_cardinal_1" class="modern-input" style="width: auto; min-width: 80px;">
                                            <option value="">--</option>
                                            <option value="E" {{ old('punto_cardinal_1', $proforma->punto_cardinal_1) == 'E' ? 'selected' : '' }}>Este (E)</option>
                                            <option value="N" {{ old('punto_cardinal_1', $proforma->punto_cardinal_1) == 'N' ? 'selected' : '' }}>Norte (N)</option>
                                            <option value="O" {{ old('punto_cardinal_1', $proforma->punto_cardinal_1) == 'O' ? 'selected' : '' }}>Oeste (O)</option>
                                            <option value="S" {{ old('punto_cardinal_1', $proforma->punto_cardinal_1) == 'S' ? 'selected' : '' }}>Sur (S)</option>
                                        </select>
                                        <input type="text" id="valorCardinal1" name="valor_cardinal_1"
                                            class="modern-input" placeholder="Coord. 1" style="width: 100px;"
                                            value="{{ old('valor_cardinal_1', $proforma->valor_cardinal_1) }}">
                                    </div>
                                </div>

                                <div>
                                    <h4 style="font-size: 12px; color: #666; margin-bottom: 4px;">PUNTO CARDINAL 2</h4>
                                    <div style="display: flex; gap: 8px;">
                                        <select id="puntoCardinal2" name="punto_cardinal_2" class="modern-input" style="width: auto; min-width: 80px;">
                                            <option value="">--</option>
                                            <option value="E" {{ old('punto_cardinal_2', $proforma->punto_cardinal_2) == 'E' ? 'selected' : '' }}>Este (E)</option>
                                            <option value="N" {{ old('punto_cardinal_2', $proforma->punto_cardinal_2) == 'N' ? 'selected' : '' }}>Norte (N)</option>
                                            <option value="O" {{ old('punto_cardinal_2', $proforma->punto_cardinal_2) == 'O' ? 'selected' : '' }}>Oeste (O)</option>
                                            <option value="S" {{ old('punto_cardinal_2', $proforma->punto_cardinal_2) == 'S' ? 'selected' : '' }}>Sur (S)</option>
                                        </select>
                                        <input type="text" id="valorCardinal2" name="valor_cardinal_2"
                                            class="modern-input" placeholder="Coord. 2" style="width: 100px;"
                                            value="{{ old('valor_cardinal_2', $proforma->valor_cardinal_2) }}">
                                    </div>
                                </div>
                            </div>
                            @error('punto_cardinal_1')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            @error('valor_cardinal_1')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            @error('punto_cardinal_2')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            @error('valor_cardinal_2')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="muestreado_por" class="form-label">Muestreado por</label>
                            <select class="form-select @error('muestreado_por') is-invalid @enderror" 
                                    id="muestreado_por" name="muestreado_por">
                                <option value="">Seleccionar...</option>
                                @foreach($muestreadoPorOpciones as $opcion)
                                    <option value="{{ $opcion }}" {{ old('muestreado_por', $proforma->muestreado_por) == $opcion ? 'selected' : '' }}>
                                        {{ $opcion }}
                                    </option>
                                @endforeach
                            </select>
                            @error('muestreado_por')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="adelanto" class="form-label">Adelanto (Bs.)</label>
                            <div class="input-group">
                                <span class="input-group-text">Bs.</span>
                                <input type="number" class="form-control @error('adelanto') is-invalid @enderror" 
                                       id="adelanto" name="adelanto" 
                                       value="{{ old('adelanto', $proforma->adelanto) }}" min="0" step="0.01"
                                       oninput="calcularTotalesEstimados()">
                            </div>
                            @error('adelanto')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 5: PARÁMETROS -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-flask me-2" style="color: #ffc107;"></i>
                        Parámetros a Analizar
                    </h6>

                    <!-- Selector de categoría para AMBIENTAL -->
                    <div id="ambient-categoria-wrapper" style="display: none;" class="mb-3">
                        <label class="form-label">Categoría del parámetro</label>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary categoria-btn flex-fill" data-categoria="AIRE">
                                <i class="fas fa-wind me-1"></i> Muestreo de Partículas (Aire)
                            </button>
                            <button type="button" class="btn btn-outline-primary categoria-btn flex-fill" data-categoria="RUIDO">
                                <i class="fas fa-volume-up me-1"></i> Medición de Ruido
                            </button>
                            <button type="button" class="btn btn-outline-primary categoria-btn flex-fill" data-categoria="GASES">
                                <i class="fas fa-industry me-1"></i> Medición de Gases
                            </button>
                        </div>
                        <div id="ambient-params-picker" style="display: none;" class="mt-2">
                            <!-- Selector único para AIRE / RUIDO -->
                            <div id="ambient-single-select" class="row">
                                <div class="col-md-10">
                                    <select id="ambient-param-select" class="form-select">
                                        <option value="">-- Seleccionar --</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id="add-ambient-param" class="btn btn-success w-100">
                                        <i class="fas fa-plus"></i> Agregar
                                    </button>
                                </div>
                            </div>
                            <!-- Checkboxes para GASES -->
                            <div id="ambient-gases-checkbox" style="display: none;">
                                <div id="ambient-gases-list" class="d-flex flex-wrap gap-3 mb-2"></div>
                                <button type="button" id="add-ambient-gases" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Agregar seleccionados
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div id="parametros-container">
                        @php $paramIndex = 0; @endphp
                        
                        @foreach($proforma->parametros as $parametro)
                        <div class="parametro-row mb-3 border p-3 rounded" id="parametro-row-{{ $paramIndex }}">
                            <div class="row align-items-center">
                                <div class="col-md-5 mb-2 mb-md-0">
                                    <label class="form-label small">Parámetro *</label>
                                    <select name="parametros[{{ $paramIndex }}][id]" 
                                            class="form-control parametro-select" 
                                            id="parametro-select-{{ $paramIndex }}"
                                            style="width: 100%;"
                                            data-row-id="{{ $paramIndex }}"
                                            required>
                                        <option value="">Buscar parámetro...</option>
                                        @if($parametro)
                                            <option value="{{ $parametro->id }}" selected
                                                    data-precio="{{ $parametro->precio_unitario }}"
                                                    data-metodo="{{ $parametro->metodo }}">
                                                {{ $parametro->categoria === 'RUIDO' ? 'RUIDO' : ($parametro->categoria === 'GASES' ? 'GASES' : $parametro->nombre) }} ({{ $parametro->tipo }}) - Bs. {{ number_format($parametro->precio_unitario, 2) }}
                                            </option>
                                        @endif
                                    </select>
                                </div>
                                
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <label class="form-label small">N° Muestras *</label>
                                    <input type="number" class="form-control muestra-input" 
                                           name="parametros[{{ $paramIndex }}][cantidad]" 
                                           value="{{ old('parametros.' . $paramIndex . '.cantidad', $parametro->pivot->cantidad_muestras) }}" 
                                           min="1" 
                                           oninput="calcularTotalesEstimados()" required>
                                </div>
                                
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <label class="form-label small">Precio Unitario</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs.</span>
                                        <input type="text" class="form-control precio-unitario" 
                                               id="precio-{{ $paramIndex }}" 
                                               value="{{ number_format($parametro->pivot->precio_unitario, 2) }}" readonly>
                                    </div>
                                </div>
                                
                                <div class="col-md-1 text-center">
                                    <label class="form-label small">&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-sm remove-parametro" 
                                            onclick="eliminarParametro(this)"
                                            {{ $proforma->parametros->count() <= 1 ? 'disabled' : '' }}>
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Método de ensayo -->
                            <div class="row mt-2 metodo-container" id="metodo-{{ $paramIndex }}" style="display: {{ $parametro->categoria === 'GASES' ? 'none' : 'block' }};">
                                <div class="col-12">
                                    <small class="text-muted">
                                        <i class="fas fa-microscope me-1"></i> 
                                        Método: <span class="metodo-text">{{ $parametro->categoria === 'GASES' ? ($parametro->pivot->metodo ?? '') : $parametro->metodo }}</span>
                                    </small>
                                </div>
                            </div>

                            <!-- Método editable para GASES -->
                            <div class="row mt-2 metodo-gas-container" id="metodo-gas-{{ $paramIndex }}" style="display: {{ $parametro->categoria === 'GASES' ? 'block' : 'none' }};">
                                <div class="col-md-6">
                                    <label class="form-label small">Método (equipo utilizado) *</label>
                                    <input type="text" class="form-control metodo-gas-input"
                                           name="parametros[{{ $paramIndex }}][metodo]"
                                           value="{{ $parametro->pivot->metodo ?? '' }}"
                                           placeholder="Ej: CO, O₂, H₂S...">
                                </div>
                            </div>
                        </div>
                        @php $paramIndex++; @endphp
                        @endforeach
                    </div>
                    
                    <button type="button" id="add-parametro" class="btn" 
                            style="background-color: #ffc107; color: #000000; border: none; border-radius: 30px; padding: 8px 20px; font-weight: 500; transition: all 0.3s ease;">
                        <i class="fas fa-plus me-1"></i> Agregar parámetro
                    </button>
                </div>

                <!-- SECCIÓN 6: LOGÍSTICA DE MUESTREO (solo AMBIENTAL) -->
                <div class="mb-4" id="logistica-muestreo" style="display: none;">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-truck me-2" style="color: #ffc107;"></i>
                        Logística de Muestreo
                    </h6>
                    <div id="logisticas-container">
                        @php $logIndex = 0; @endphp
                        @forelse($proforma->logisticasMuestreo as $log)
                        <div class="logistica-row mb-3 border p-3 rounded" id="logistica-row-{{ $logIndex }}">
                            <div class="row align-items-center">
                                <div class="col-md-4 mb-2 mb-md-0">
                                    <label class="form-label small">Concepto Logístico *</label>
                                    <select name="logisticas[{{ $logIndex }}][id]" class="form-select logistica-select" required>
                                        <option value="">Seleccionar concepto...</option>
                                        @foreach($logisticasMuestreo as $opt)
                                            <option value="{{ $opt->id }}" data-costo="{{ $opt->costo }}" data-categoria="{{ $opt->categoria }}"
                                                {{ $log->id == $opt->id ? 'selected' : '' }}>
                                                {{ $opt->categoria }} - {{ $opt->descripcion }} (Bs. {{ number_format($opt->costo, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <label class="form-label small">Descripción para la Proforma</label>
                                    <textarea class="form-control logistica-descripcion"
                                              name="logisticas[{{ $logIndex }}][descripcion]"
                                              rows="2"
                                              style="resize: vertical; min-height: 38px; white-space: normal; overflow-wrap: break-word;"
                                              placeholder="Logística de Muestreo de: ...">{{ old('logisticas.' . $logIndex . '.descripcion', $log->pivot->descripcion ?? '') }}</textarea>
                                </div>
                                <div class="col-md-2 mb-2 mb-md-0">
                                    <label class="form-label small">Cantidad</label>
                                    <input type="number" class="form-control logistica-cantidad"
                                           name="logisticas[{{ $logIndex }}][cantidad]" value="{{ $log->pivot->cantidad }}" min="1" required>
                                </div>
                                <div class="col-md-2 mb-2 mb-md-0">
                                    <label class="form-label small">Costo Unitario</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs.</span>
                                        <input type="text" class="form-control logistica-costo" value="{{ number_format($log->costo, 2) }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-1 text-center">
                                    <label class="form-label small">&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-sm remove-logistica"
                                            onclick="if(confirm('¿Eliminar este concepto logístico?')){$(this).closest('.logistica-row').remove();}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @php $logIndex++; @endphp
                        @empty
                        <div class="logistica-row mb-3 border p-3 rounded" id="logistica-row-0">
                            <div class="row align-items-center">
                                <div class="col-md-4 mb-2 mb-md-0">
                                    <label class="form-label small">Concepto Logístico *</label>
                                    <select name="logisticas[0][id]" class="form-select logistica-select" required>
                                        <option value="">Seleccionar concepto...</option>
                                        @foreach($logisticasMuestreo as $opt)
                                            <option value="{{ $opt->id }}" data-costo="{{ $opt->costo }}" data-categoria="{{ $opt->categoria }}">
                                                {{ $opt->categoria }} - {{ $opt->descripcion }} (Bs. {{ number_format($opt->costo, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <label class="form-label small">Descripción para la Proforma</label>
                                    <textarea class="form-control logistica-descripcion"
                                              name="logisticas[0][descripcion]"
                                              rows="2"
                                              style="resize: vertical; min-height: 38px; white-space: normal; overflow-wrap: break-word;"
                                              placeholder="Logística de Muestreo de: ..."></textarea>
                                </div>
                                <div class="col-md-2 mb-2 mb-md-0">
                                    <label class="form-label small">Cantidad</label>
                                    <input type="number" class="form-control logistica-cantidad"
                                           name="logisticas[0][cantidad]" value="1" min="1" required>
                                </div>
                                <div class="col-md-2 mb-2 mb-md-0">
                                    <label class="form-label small">Costo Unitario</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs.</span>
                                        <input type="text" class="form-control logistica-costo" value="0.00" readonly>
                                    </div>
                                </div>
                                <div class="col-md-1 text-center">
                                    <label class="form-label small">&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-sm remove-logistica" disabled>
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforelse
                    </div>
                    <button type="button" id="add-logistica" class="btn"
                            style="background-color: #ffc107; color: #000000; border: none; border-radius: 30px; padding: 8px 20px; font-weight: 500; transition: all 0.3s ease;">
                        <i class="fas fa-plus me-1"></i> Agregar concepto logístico
                    </button>
                </div>

                <!-- SECCIÓN 7: OBSERVACIONES -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-sticky-note me-2" style="color: #ffc107;"></i>
                        Observaciones
                    </h6>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                                  id="observaciones" name="observaciones" rows="3">{{ old('observaciones', $proforma->observaciones) }}</textarea>
                        @error('observaciones')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- SECCIÓN 8: JUSTIFICACIÓN DE MODIFICACIÓN -->
                <div id="justificacion-section" style="display: none;" class="mb-4">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Modificación de Parámetros Detectada</strong>
                        <p class="mb-0 mt-2">Ha realizado cambios en los parámetros de la proforma. Esto requiere una justificación:</p>
                    </div>
                    
                    <div class="mb-3">
                        <label for="justificacion_modificacion" class="form-label">
                            Justificación de Modificación <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('justificacion_modificacion') is-invalid @enderror" 
                                  id="justificacion_modificacion" 
                                  name="justificacion_modificacion" 
                                  rows="3"
                                  placeholder="Explique detalladamente por qué se están modificando los parámetros de esta proforma...">{{ old('justificacion_modificacion') }}</textarea>
                        <small class="text-muted">Esta justificación quedará registrada en el sistema y será visible en la vista de la proforma.</small>
                        @error('justificacion_modificacion')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- SECCIÓN 9: RESUMEN FINANCIERO -->
                <div class="card mt-4 border-warning">
                    <div class="card-header" style="background-color: #ffc107; border-bottom: none;">
                        <h6 class="mb-0" style="color: #000000;">
                            <i class="fas fa-calculator me-2" style="color: #000000;"></i> Resumen Financiero
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 text-center">
                                <p class="mb-1"><strong>Subtotal</strong></p>
                                <h4 class="text-primary">Bs. <span id="subtotal-estimado">{{ number_format($proforma->subtotal, 2) }}</span></h4>
                            </div>
                            <div class="col-md-2 text-center">
                                <p class="mb-1"><strong>Descuento</strong></p>
                                <h4 class="text-danger">Bs. <span id="descuento-estimado">{{ number_format($proforma->descuento, 2) }}</span></h4>
                                <small class="text-muted" id="descuento-nota">
                                    {{ $proforma->tipo == 'INVESTIGACION' ? '(20% descuento aplicado)' : '(No aplica)' }}
                                </small>
                            </div>
                            <div class="col-md-3 text-center">
                                <p class="mb-1"><strong>Total</strong></p>
                                <h3 class="text-success">Bs. <span id="total-estimado">{{ number_format($proforma->total, 2) }}</span></h3>
                            </div>
                            <div class="col-md-3 text-center">
                                <p class="mb-1"><strong>Saldo</strong></p>
                                <h4 class="text-info">Bs. <span id="saldo-estimado">{{ number_format($proforma->saldo, 2) }}</span></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 10: BOTONES -->
                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <a href="{{ route('proformas.show', $proforma) }}" class="btn btn-secondary" style="border-radius: 30px; padding: 10px 25px;">
                        <i class="fas fa-times me-2"></i> Cancelar
                    </a>
                    <button type="submit" class="btn" style="background-color: #ffc107; border-radius: 30px; padding: 10px 25px; color: #000000; border: none; transition: all 0.3s ease;">
                        <i class="fas fa-save me-2"></i> Actualizar Proforma
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
    border-color: #ffc107;
    box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.15);
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #ffc107 !important;
    color: #000000 !important;
}

/* Estilo para el mensaje de búsqueda */
.select2-container--default .select2-search--dropdown .select2-search__field::placeholder {
    color: #999;
    font-style: italic;
}

/* Mensaje personalizado en el placeholder */
.select2-selection__placeholder {
    color: #6c757d !important;
    font-style: italic;
}
</style>

<script>
$(document).ready(function() {
    console.log('Documento listo - iniciando Select2 en edición');
    
    // Array para IDs seleccionados (evitar duplicados)
    let parametrosSeleccionados = [];
    
    // Inicializar array con los parámetros existentes
    $('.parametro-select').each(function() {
        const valor = $(this).val();
        if (valor) {
            parametrosSeleccionados.push(parseInt(valor));
        }
    });
    console.log('Parámetros iniciales:', parametrosSeleccionados);
    
    // ===== SELECT DE CLIENTES CON SELECT2 =====
    $('#cliente_id').select2({
        placeholder: '🔍 Buscar cliente por nombre o contacto...',
        minimumInputLength: 2,
        allowClear: true,
        language: {
            inputTooShort: function() {
                return 'Ingrese al menos 2 caracteres para buscar';
            },
            searching: function() {
                return 'Buscando...';
            },
            noResults: function() {
                return 'No se encontraron clientes';
            },
            errorLoading: function() {
                return 'Error al cargar resultados';
            }
        },
        ajax: {
            url: '{{ route("clientes.buscar") }}',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return { q: params.term };
            },
            processResults: function(data) {
                return { results: data };
            }
        }
    });
    
    // ===== FUNCIÓN PARA INICIALIZAR SELECT2 EN PARÁMETROS =====
    function initParametroSelect(selector) {
        console.log('Inicializando Select2 en:', selector);
        
        $(selector).select2({
            placeholder: '🔬 Buscar parámetro por nombre...',
            minimumInputLength: 2,
            allowClear: true,
            language: {
                inputTooShort: function() {
                    return 'Ingrese al menos 2 caracteres para buscar el parámetro';
                },
                searching: function() {
                    return '🔍 Buscando parámetros...';
                },
                noResults: function() {
                    return '❌ No se encontraron parámetros';
                },
                errorLoading: function() {
                    return '⚠️ Error al cargar resultados';
                }
            },
            ajax: {
                url: '{{ route("parametros.buscar") }}',
                dataType: 'json',
                delay: 300,
                data: function(params) {
                    return { q: params.term };
                },
                processResults: function(data) {
                    console.log('Parámetros recibidos:', data);
                    
                    // Filtrar parámetros ya seleccionados
                    let resultadosFiltrados = data.filter(item => {
                        return !parametrosSeleccionados.includes(item.id);
                    });
                    
                    console.log('Parámetros disponibles:', resultadosFiltrados);
                    return { results: resultadosFiltrados };
                }
            }
        });
    }
    
    // Enlaza el evento de selección para actualizar precio y método
    function bindParametroSelectEvents(select) {
        select.off('select2:select').on('select2:select', function(e) {
            const data = e.params.data;
            const row = $(this).closest('.parametro-row');
            const rowId = row.attr('id').split('-')[2];
            const valorAnterior = $(this).data('valor-anterior');
            
            // Si había un valor anterior, removerlo de la lista
            if (valorAnterior) {
                const index = parametrosSeleccionados.indexOf(parseInt(valorAnterior));
                if (index > -1) {
                    parametrosSeleccionados.splice(index, 1);
                }
            }
            
            // Verificar si el nuevo valor ya está seleccionado
            if (parametrosSeleccionados.includes(data.id)) {
                alert('⚠️ Este parámetro ya ha sido seleccionado. Por favor, elija otro.');
                $(this).val(valorAnterior).trigger('change');
                return;
            }
            
            // Agregar nuevo valor
            parametrosSeleccionados.push(data.id);
            $(this).data('valor-anterior', data.id);
            
            // Actualizar precio y método
            $('#precio-' + rowId).val(parseFloat(data.precio_unitario).toFixed(2));
            
            const metodoText = row.find('.metodo-text');
            const metodoContainer = row.find('.metodo-container');
            
            metodoText.text(data.metodo || '');
            metodoContainer.show();
            
            calcularTotalesEstimados();
            detectarCambiosParametros();
        });
        
        // Guardar valor inicial
        select.data('valor-anterior', select.val());
    }

    // Inicializar Select2 en todos los parámetros existentes
    $('.parametro-select').each(function() {
        const select = $(this);
        const valor = select.val();
        
        // Inicializar Select2
        initParametroSelect(select);
        
        // Si ya tiene un valor seleccionado, aseguramos que se muestre correctamente
        if (valor) {
            const row = select.closest('.parametro-row');
            const rowId = row.attr('id').split('-')[2];
            
            // Actualizar si es necesario (los datos ya están en el HTML)
            setTimeout(() => {
                const precio = select.find('option:selected').data('precio');
                const metodo = select.find('option:selected').data('metodo');
                if (precio) {
                    $('#precio-' + rowId).val(parseFloat(precio).toFixed(2));
                }
                if (metodo) {
                    $('#metodo-' + rowId + ' .metodo-text').text(metodo);
                }
            }, 100);
        }
        
        // Enlazar eventos de selección (precio, método, duplicados)
        bindParametroSelectEvents(select);
    });
    
    // ===== BOTÓN AGREGAR PARÁMETRO =====
    // Plantilla de fila cuando la proforma no tiene parámetros
    const parametroRowTemplate =
        '<div class="parametro-row mb-3 border p-3 rounded" id="parametro-row-0">' +
        '    <div class="row align-items-center">' +
        '        <div class="col-md-5 mb-2 mb-md-0">' +
        '            <label class="form-label small">Parámetro *</label>' +
        '            <select name="parametros[0][id]" class="form-control parametro-select" id="parametro-select-0" style="width: 100%;" data-row-id="0" required>' +
        '                <option value="">Buscar parámetro...</option>' +
        '            </select>' +
        '        </div>' +
        '        <div class="col-md-3 mb-2 mb-md-0">' +
        '            <label class="form-label small">N° Muestras *</label>' +
        '            <input type="number" class="form-control muestra-input" name="parametros[0][cantidad]" value="1" min="1" oninput="calcularTotalesEstimados()" required>' +
        '        </div>' +
        '        <div class="col-md-3 mb-2 mb-md-0">' +
        '            <label class="form-label small">Precio Unitario</label>' +
        '            <div class="input-group">' +
        '                <span class="input-group-text">Bs.</span>' +
        '                <input type="text" class="form-control precio-unitario" id="precio-0" value="0.00" readonly>' +
        '            </div>' +
        '        </div>' +
        '        <div class="col-md-1 text-center">' +
        '            <label class="form-label small">&nbsp;</label>' +
        '            <button type="button" class="btn btn-danger btn-sm remove-parametro" onclick="eliminarParametro(this)"><i class="fas fa-times"></i></button>' +
        '        </div>' +
        '    </div>' +
        '    <div class="row mt-2 metodo-container" id="metodo-0" style="display: none;">' +
        '        <div class="col-12">' +
        '            <small class="text-muted"><i class="fas fa-microscope me-1"></i> Método: <span class="metodo-text"></span></small>' +
        '        </div>' +
        '    </div>' +
        '    <div class="row mt-2 metodo-gas-container" id="metodo-gas-0" style="display: none;">' +
        '        <div class="col-md-6">' +
        '            <label class="form-label small">Método (equipo utilizado) *</label>' +
        '            <input type="text" class="form-control metodo-gas-input" name="parametros[0][metodo]" placeholder="Ej: CO, O2, H2S...">' +
        '        </div>' +
        '    </div>' +
        '</div>';

    $('#add-parametro').click(function() {
        const container = $('#parametros-container');
        const index = container.find('.parametro-row').length;
        const firstRow = $('.parametro-row:first');
        const newRow = firstRow.length ? firstRow.clone() : $(parametroRowTemplate);
        
        // Actualizar IDs
        newRow.attr('id', 'parametro-row-' + index);
        
        // Limpiar select
        const newSelect = newRow.find('.parametro-select');
        newSelect.attr('id', 'parametro-select-' + index)
                .attr('name', 'parametros[' + index + '][id]')
                .val('')
                .removeAttr('data-select2-id')
                .removeData('valor-anterior')
                .removeAttr('data-valor-anterior')
                .next('.select2-container').remove();
        
        // Limpiar opciones
        newSelect.empty().append('<option value="">Buscar parámetro...</option>');
        
        // Limpiar otros campos
        newRow.find('.muestra-input')
              .attr('name', 'parametros[' + index + '][cantidad]')
              .val(1);
        
        newRow.find('.precio-unitario')
              .attr('id', 'precio-' + index)
              .val('0.00');
        
        newRow.find('.metodo-container')
              .attr('id', 'metodo-' + index)
              .hide();
        
        newRow.find('.metodo-text').text('');
        
        // Botón eliminar
        const removeBtn = newRow.find('.remove-parametro');
        removeBtn.prop('disabled', false)
                .off('click')
                .click(function() { eliminarParametro(this); });
        
        container.append(newRow);
        
        // Inicializar Select2 para el nuevo select
        setTimeout(() => {
            initParametroSelect(newSelect);
            bindParametroSelectEvents(newSelect);
        }, 100);
        
        calcularTotalesEstimados();
        setTimeout(detectarCambiosParametros, 200);
    });
    
    // ===== ELIMINAR PARÁMETRO =====
    window.eliminarParametro = function(btn) {
        if ($('.parametro-row').length <= 1) {
            alert('⚠️ Debe haber al menos un parámetro');
            return;
        }
        
        if (confirm('¿Eliminar este parámetro?')) {
            const row = $(btn).closest('.parametro-row');
            const select = row.find('.parametro-select');
            
            // Obtener el ID del parámetro seleccionado para removerlo de la lista
            const selectedId = select.val();
            if (selectedId) {
                const idNum = parseInt(selectedId);
                const idIndex = parametrosSeleccionados.indexOf(idNum);
                if (idIndex > -1) {
                    parametrosSeleccionados.splice(idIndex, 1);
                }
            }
            
            // Destruir Select2 antes de eliminar
            if (select.data('select2')) {
                select.select2('destroy');
            }
            
            row.remove();
            console.log('Parámetros restantes:', parametrosSeleccionados);
            calcularTotalesEstimados();
            detectarCambiosParametros();
            actualizarDescripcionLogistica();
        }
    };
    
    // ===== FUNCIÓN PARA DETECTAR CAMBIOS EN PARÁMETROS =====
    function detectarCambiosParametros() {
        // Obtener parámetros actuales (originales)
        let parametrosOriginales = [];
        @foreach($proforma->parametros as $parametro)
            parametrosOriginales.push({{ $parametro->id }});
        @endforeach
        
        // Obtener parámetros nuevos del formulario
        let parametrosNuevos = [];
        $('.parametro-select').each(function() {
            let valor = $(this).val();
            if (valor) {
                parametrosNuevos.push(parseInt(valor));
            }
        });
        
        // Comparar arrays
        let parametrosAgregados = parametrosNuevos.filter(x => !parametrosOriginales.includes(x));
        let parametrosEliminados = parametrosOriginales.filter(x => !parametrosNuevos.includes(x));
        
        // Si hay cambios, mostrar la sección de justificación
        if (parametrosAgregados.length > 0 || parametrosEliminados.length > 0) {
            $('#justificacion-section').slideDown();
            $('#justificacion_modificacion').prop('required', true);
        } else {
            $('#justificacion-section').slideUp();
            $('#justificacion_modificacion').prop('required', false);
        }
    }
    
    // ===== VALIDACIÓN FINAL ANTES DE ENVIAR =====
    $('#proformaForm').on('submit', function(e) {
        console.log('Validando formulario antes de enviar...');
        
        // Obtener todos los IDs de parámetros seleccionados
        let parametrosEnFormulario = [];
        let duplicados = false;
        let mensajeError = '';
        
        $('.parametro-select').each(function() {
            const valor = $(this).val();
            if (valor) {
                const idNum = parseInt(valor);
                if (parametrosEnFormulario.includes(idNum)) {
                    duplicados = true;
                    mensajeError = '❌ Error: Hay parámetros duplicados en el formulario.';
                    console.error('Parámetro duplicado encontrado:', idNum);
                } else {
                    parametrosEnFormulario.push(idNum);
                }
            }
        });
        
        if (duplicados) {
            e.preventDefault();
            
            const alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                '<i class="fas fa-exclamation-triangle me-2"></i>' +
                mensajeError + ' Por favor, revise que todos los parámetros sean únicos.' +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                '</div>');
            
            $('.card-body').prepend(alertDiv);
            
            $('html, body').animate({
                scrollTop: $('.card-body').offset().top - 100
            }, 500);
            
            return false;
        }
        
        // Verificar justificación si hay cambios
        let parametrosOriginales = [];
        @foreach($proforma->parametros as $parametro)
            parametrosOriginales.push({{ $parametro->id }});
        @endforeach
        
        let parametrosNuevos = [];
        $('.parametro-select').each(function() {
            let valor = $(this).val();
            if (valor) {
                parametrosNuevos.push(parseInt(valor));
            }
        });
        
        let parametrosAgregados = parametrosNuevos.filter(x => !parametrosOriginales.includes(x));
        let parametrosEliminados = parametrosOriginales.filter(x => !parametrosNuevos.includes(x));
        
        if ((parametrosAgregados.length > 0 || parametrosEliminados.length > 0) && !$('#justificacion_modificacion').val()) {
            e.preventDefault();
            alert('⚠️ Debe proporcionar una justificación para modificar los parámetros.');
            $('#justificacion-section').slideDown();
            $('#justificacion_modificacion').focus();
            return false;
        }
        
        console.log('Validación exitosa - No hay duplicados');
        return true;
    });
    
    // ===== TOGGLE LOGÍSTICA DE MUESTREO =====
    function toggleLogisticaMuestreo() {
        const tipo = $('#tipo').val();
        if (tipo === 'AMBIENTAL') {
            $('#logistica-muestreo').show();
            $('.logistica-select').prop('required', true).prop('disabled', false);
            $('.logistica-cantidad').prop('required', true).prop('disabled', false);
            $('.logistica-descripcion').prop('disabled', false);
        } else {
            $('#logistica-muestreo').hide();
            $('.logistica-select').prop('required', false).prop('disabled', true);
            $('.logistica-cantidad').prop('required', false).prop('disabled', true);
            $('.logistica-descripcion').prop('disabled', true);
        }
    }
    $('#tipo').on('change', toggleLogisticaMuestreo);
    toggleLogisticaMuestreo();

    // ===== LOGÍSTICA DE MUESTREO - FILAS DINÁMICAS =====
    function calcularSubtotalesLogistica() {
        $('.logistica-row').each(function() {
            const costo = parseFloat($(this).find('.logistica-costo').val()) || 0;
            const cantidad = parseInt($(this).find('.logistica-cantidad').val()) || 0;
            $(this).find('.logistica-costo').val(costo.toFixed(2));
        });
    }

    $(document).on('change', '.logistica-select', function() {
        const row = $(this).closest('.logistica-row');
        const selected = $(this).find('option:selected');
        if (selected.val()) {
            row.find('.logistica-costo').val(parseFloat(selected.data('costo')).toFixed(2));
        } else {
            row.find('.logistica-costo').val('0.00');
        }
        calcularSubtotalesLogistica();
        calcularTotalesEstimados();
    });

    $(document).on('input', '.logistica-cantidad', function() {
        calcularSubtotalesLogistica();
        calcularTotalesEstimados();
    });

    $('#add-logistica').click(function() {
        const container = $('#logisticas-container');
        const index = container.find('.logistica-row').length;
        const firstRow = $('.logistica-row:first');
        const newRow = firstRow.clone();

        newRow.attr('id', 'logistica-row-' + index);

        const newSelect = newRow.find('.logistica-select');
        newSelect.attr('name', 'logisticas[' + index + '][id]').val('');

        newRow.find('.logistica-cantidad')
              .attr('name', 'logisticas[' + index + '][cantidad]').val(1);

        newRow.find('.logistica-costo').val('0.00');

        newRow.find('.logistica-descripcion')
              .attr('name', 'logisticas[' + index + '][descripcion]').val('');

        const removeBtn = newRow.find('.remove-logistica');
        removeBtn.prop('disabled', false)
                .off('click')
                .click(function() {
                    if ($('.logistica-row').length <= 1) {
                        alert('Debe haber al menos un concepto logístico');
                        return;
                    }
                    if (confirm('¿Eliminar este concepto logístico?')) {
                        $(this).closest('.logistica-row').remove();
                        calcularTotalesEstimados();
                    }
                });

        container.append(newRow);
        calcularTotalesEstimados();
        actualizarDescripcionLogistica();
    });

    // ===== AUTO-POBLAR DESCRIPCIÓN LOGÍSTICA =====
    // Marcar como editado manualmente cuando el usuario escriba
    $(document).on('input', '.logistica-descripcion', function() {
        $(this).attr('data-user-edited', 'true');
    });

    function actualizarDescripcionLogistica() {
        const nombres = [];
        // Leer servicios agregados dinámicamente (plaintext)
        $('.parametro-row:visible .form-control-plaintext').each(function() {
            let t = $(this).text().trim();
            t = t.replace(/\s*\(.*$/, '');
            if (nombres.indexOf(t) === -1) nombres.push(t);
        });
        // Leer servicios existentes (Select2)
        $('.parametro-row:visible .parametro-select option:selected').each(function() {
            if ($(this).val()) {
                let t = $(this).text().trim();
                t = t.replace(/\s*\(.*$/, '');
                if (nombres.indexOf(t) === -1) nombres.push(t);
            }
        });
        if (nombres.length > 0) {
            const texto = 'Logística de Muestreo de: ' + nombres.join(', ');
            $('.logistica-descripcion').each(function() {
                if ($(this).attr('data-user-edited') !== 'true') {
                    $(this).val(texto);
                }
            });
        }
    }

    // ===== CALCULAR TOTALES =====
    window.calcularTotalesEstimados = function() {
        let subtotal = 0;
        
        $('.parametro-row').each(function() {
            const precio = parseFloat($(this).find('.precio-unitario').val()) || 0;
            const cantidad = parseInt($(this).find('.muestra-input').val()) || 0;
            subtotal += precio * cantidad;
        });

        $('#logistica-muestreo:visible .logistica-costo').each(function() {
            subtotal += parseFloat($(this).val()) || 0;
        });
        
        const tipo = $('#tipo').val();
        const descuento = (tipo === 'INVESTIGACION') ? subtotal * 0.20 : 0;
        const total = subtotal - descuento;
        const adelanto = parseFloat($('#adelanto').val()) || 0;
        const saldo = total - adelanto;
        
        $('#subtotal-estimado').text(subtotal.toFixed(2));
        $('#descuento-estimado').text(descuento.toFixed(2));
        $('#total-estimado').text(total.toFixed(2));
        $('#saldo-estimado').text(saldo.toFixed(2));
        $('#descuento-nota').text((tipo === 'INVESTIGACION') ? '(20% descuento aplicado)' : '(No aplica)');
    };
    
    // ===== EVENTOS =====
    $('#tipo, #adelanto').on('change keyup', calcularTotalesEstimados);
    $(document).on('input', '.muestra-input', calcularTotalesEstimados);
    $(document).on('change', '.parametro-select', function() {
        setTimeout(detectarCambiosParametros, 100);
    });
    
    // Calcular totales inicial y detectar cambios
    calcularTotalesEstimados();
    setTimeout(detectarCambiosParametros, 500);

    // ===== AMBIENTAL: DATOS DE PARÁMETROS POR CATEGORÍA =====
    const parametrosAmbientales = @json($parametrosAmbientales);

    let ambientCategoria = '';

    function toggleAmbientParamUI() {
        const tipo = $('#tipo').val();
        if (tipo === 'AMBIENTAL') {
            $('#ambient-categoria-wrapper').show();
            $('#add-parametro').hide();
            resetAmbientPicker();
        } else {
            $('#ambient-categoria-wrapper').hide();
            $('#ambient-params-picker').hide();
            $('#add-parametro').show();
        }
    }

    function resetAmbientPicker() {
        ambientCategoria = '';
        $('#ambient-params-picker').hide();
        $('#ambient-single-select').show();
        $('#ambient-gases-checkbox').hide();
        $('#ambient-gases-list').empty();
        $('#ambient-param-select').empty().append('<option value="">-- Seleccionar --</option>');
        $('.categoria-btn').removeClass('active btn-primary').addClass('btn-outline-primary');
    }

    $(document).on('click', '.categoria-btn', function() {
        $('.categoria-btn').removeClass('active btn-primary').addClass('btn-outline-primary');
        $(this).addClass('active btn-primary').removeClass('btn-outline-primary');

        ambientCategoria = $(this).data('categoria');
        const filtrados = parametrosAmbientales.filter(function(p) {
            return p.categoria === ambientCategoria;
        });

        if (ambientCategoria === 'GASES') {
            $('#ambient-single-select').hide();
            $('#ambient-gases-checkbox').show();
            const list = $('#ambient-gases-list');
            list.empty();
            filtrados.forEach(function(p) {
                list.append('<label class="form-check form-check-inline"><input type="checkbox" class="form-check-input gas-checkbox" value="' + p.id + '" data-nombre="' + p.nombre + '" data-precio="' + p.precio_unitario + '"> <span class="form-check-label">' + p.nombre + ' (Bs. ' + parseFloat(p.precio_unitario).toFixed(2) + ')</span></label>');
            });
            list.find('.gas-checkbox').on('change', actualizarDescripcionLogistica);
        } else {
            $('#ambient-single-select').show();
            $('#ambient-gases-checkbox').hide();
            $('#ambient-gases-list').empty();
            const select = $('#ambient-param-select');
            select.empty().append('<option value="">-- Seleccionar --</option>');
            filtrados.forEach(function(p) {
                select.append('<option value="' + p.id + '" data-precio="' + p.precio_unitario + '" data-metodo="' + (p.metodo || '') + '">' + p.nombre + ' (Bs. ' + parseFloat(p.precio_unitario).toFixed(2) + ')</option>');
            });
        }

        $('#ambient-params-picker').show();
    });

    // Add single param (AIRE / RUIDO)
    $('#add-ambient-param').click(function() {
        const select = $('#ambient-param-select');
        const selected = select.find('option:selected');
        if (!selected.val()) {
            alert('Seleccione un parámetro');
            return;
        }

        const id = parseInt(selected.val());
        const nombre = selected.text();
        const precio = parseFloat(selected.data('precio'));
        const metodo = selected.data('metodo') || '';

        agregarFilaAmbient(id, nombre, precio, metodo, false);

        select.val('');
    });

    // Add multiple gases
    // Helper: agrega una fila de parámetro ambiental
    function agregarFilaAmbient(id, nombre, precio, metodo, esGas) {
        const container = $('#parametros-container');
        const index = container.find('.parametro-row:visible').length;
        const template = $('#parametro-row-0');
        const newRow = template.clone();

        newRow.attr('id', 'parametro-row-' + index).show();

        const newSelect = newRow.find('.parametro-select');
        newSelect.attr('id', 'parametro-select-' + index)
                .attr('name', 'parametros[' + index + '][id]')
                .prop('disabled', false).removeAttr('disabled');
        if (newSelect.data('select2')) {
            newSelect.select2('destroy');
        }
        newSelect.next('.select2-container').remove();
        newSelect.empty().append('<option value="' + id + '" selected>' + nombre + '</option>');
        newSelect.hide().after('<input type="hidden" name="parametros[' + index + '][id]" value="' + id + '"><span class="form-control-plaintext">' + nombre + '</span>');

        const metodoContainer = newRow.find('.metodo-container');
        const metodoText = newRow.find('.metodo-text');
        metodoText.text(metodo);
        metodoContainer.show();

        const metodoGasContainer = newRow.find('.metodo-gas-container');
        const metodoGasInput = newRow.find('.metodo-gas-input');
        if (esGas) {
            metodoGasContainer.attr('id', 'metodo-gas-' + index).show();
            metodoGasInput.attr('name', 'parametros[' + index + '][metodo]').val(metodo).prop('disabled', false).removeAttr('disabled');
        } else {
            metodoGasContainer.attr('id', 'metodo-gas-' + index).hide();
            metodoGasInput.attr('name', 'parametros[' + index + '][metodo]').val('').prop('disabled', false).removeAttr('disabled');
        }

        newRow.find('.muestra-input')
              .attr('name', 'parametros[' + index + '][cantidad]')
              .val(1)
              .prop('disabled', false).removeAttr('disabled');
        newRow.find('.precio-unitario')
              .attr('id', 'precio-' + index)
              .val(precio.toFixed(2));

        const removeBtn = newRow.find('.remove-parametro');
        removeBtn.prop('disabled', false)
                .off('click')
                .click(function() { eliminarParametro(this); });

        container.append(newRow);
        calcularTotalesEstimados();
        actualizarDescripcionLogistica();
    }

    // Add multiple gases — single GASES row with combined names
    $('#add-ambient-gases').click(function() {
        const checked = $('#ambient-gases-list .gas-checkbox:checked');
        if (checked.length === 0) {
            alert('Seleccione al menos un gas');
            return;
        }

        const first = $(checked[0]);
        const id = parseInt(first.val());
        const precio = parseFloat(first.data('precio'));
        const gases = [];
        checked.each(function() { gases.push($(this).data('nombre')); });
        const metodo = gases.join(', ');

        agregarFilaAmbient(id, 'GASES', precio, metodo, true);
        checked.prop('checked', false);
    });

    // ===== CÓDIGOS DE CLIENTE DINÁMICOS =====
    $('#agregar-codigo-cliente').on('click', function() {
        var container = $('#codigos-cliente-container');
        var row = container.find('.codigo-cliente-row').first().clone();
        row.find('input').val('');
        row.find('.eliminar-codigo').show();
        container.append(row);
    });

    $(document).on('click', '.eliminar-codigo', function() {
        $(this).closest('.codigo-cliente-row').remove();
    });

    $('#tipo').on('change', function() {
        toggleLogisticaMuestreo();
        toggleAmbientParamUI();
    });
    toggleAmbientParamUI();
});
</script>
@endpush