@extends('layouts.app')

@section('title', 'Nueva Proforma')

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
/* Estilos personalizados para Select2 */
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

.select2-container--default .select2-selection--multiple {
    border: 1px solid #ced4da;
    border-radius: 6px;
    min-height: 38px;
}

.select2-container--default.select2-container--focus .select2-selection--multiple {
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

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #ffc107;
    border-color: #e6a800;
    color: #000000;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #000000;
    margin-right: 5px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    background-color: transparent;
    color: #ff0000;
}

/* Estilo para el botón de guardar */
.btn[style*="background-color: #ffc107"] {
    transition: all 0.3s ease !important;
}

.btn[style*="background-color: #ffc107"]:hover {
    background-color: #e6a800 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3) !important;
}

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
    border-color: #ffbf00 !important;
    box-shadow: 0 0 0 3px rgba(153, 132, 30, 0.15) !important;
}

.btn-nuevo-cliente {
    background-color: #ffc107;
    color: #000000;
    border: none;
    border-radius: 8px;
    padding: 0.375rem 0.75rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-nuevo-cliente:hover {
    background-color: #e6a800;
    transform: translateY(-2px);
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
                    <i class="fas fa-file-medical" style="color: #ffc107;"></i>
                    Nueva Proforma
                </h1>
                <p class="page-subtitle">
                    Complete el formulario para crear una nueva proforma
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

    <!-- Formulario principal -->
    <div class="card">
        <div class="card-header" style="background-color: #ffc107; border-bottom: none;">
            <h5 class="mb-0" style="color: #000000;">
                <i class="fas fa-edit me-2" style="color: #000000;"></i>
                Formulario de Proforma
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('proformas.store') }}" method="POST" id="proformaForm">
                @csrf
                
                <!-- SECCIÓN 1: DATOS CLIENTE -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-user-tie me-2" style="color: #ffc107;"></i>
                        Datos del Cliente
                    </h6>
                    
                    <div class="mb-3">
                        <label for="cliente_id" class="form-label">Cliente *</label>
                        <div class="input-group">
                            <select class="form-select @error('cliente_id') is-invalid @enderror" 
                                    id="cliente_id" name="cliente_id" required style="width: 100%;">
                                <option value="">Buscar cliente...</option>
                                @if(old('cliente_id'))
                                    @php
                                        $clienteSeleccionado = \App\Models\Cliente::find(old('cliente_id'));
                                    @endphp
                                    @if($clienteSeleccionado)
                                        <option value="{{ $clienteSeleccionado->id }}" selected>
                                            {{ $clienteSeleccionado->razon_social }} - {{ $clienteSeleccionado->persona_contacto }}
                                        </option>
                                    @endif
                                @endif
                            </select>
                            <button type="button" class="btn btn-nuevo-cliente" onclick="abrirModalCliente()">
                                <i class="fas fa-user-plus"></i> Nuevo
                            </button>
                        </div>
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
                                    id="tipo" name="tipo" required onchange="calcularTotalesEstimados()">
                                <option value="">Seleccionar tipo...</option>
                                <option value="AMBIENTAL" {{ old('tipo') == 'AMBIENTAL' ? 'selected' : '' }}>AMBIENTAL</option>
                                <option value="AGUA" {{ old('tipo') == 'AGUA' ? 'selected' : '' }}>AGUA</option>
                                <option value="INVESTIGACION" {{ old('tipo') == 'INVESTIGACION' ? 'selected' : '' }}>INVESTIGACIÓN (20% descuento)</option>
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
                                   value="{{ old('tipo_muestra') }}"
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
                                <option value="UIA" {{ old('unidad') == 'UIA' ? 'selected' : '' }}>UIA - Unidad de Investigación Ambiental</option>
                                <option value="UAQ" {{ old('unidad') == 'UAQ' ? 'selected' : '' }}>UAQ - Unidad de Análisis Químico</option>
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
                                   value="{{ old('fecha_emision', date('Y-m-d')) }}" required>
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
                                   value="{{ old('fecha_recepcion', date('Y-m-d')) }}" required>
                            @error('fecha_recepcion')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="hora_recepcion" class="form-label">Hora</label>
                            <input type="time" class="form-control @error('hora_recepcion') is-invalid @enderror" 
                                   id="hora_recepcion" name="hora_recepcion" 
                                   value="{{ old('hora_recepcion', date('H:i')) }}">
                            @error('hora_recepcion')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="numero_recepcion" class="form-label">Nro. de Recepción</label>
                            <input type="text" class="form-control @error('numero_recepcion') is-invalid @enderror" 
                                   id="numero_recepcion" name="numero_recepcion" 
                                   value="{{ old('numero_recepcion') }}"
                                   placeholder="Ej: 001">
                            @error('numero_recepcion')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="codigo_cliente" class="form-label">Código de Cliente</label>
                            <input type="text" class="form-control @error('codigo_cliente') is-invalid @enderror" 
                                   id="codigo_cliente" name="codigo_cliente" 
                                   value="{{ $cliente->codigo_cliente ?? '---' }}"
                                   placeholder="Ej: CL-001">
                            @error('codigo_cliente')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Documento</label>
                            <div class="border rounded p-3" style="background: #f8f9fa;">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="tipo_documento[]" value="PROFORMA" 
                                           id="td-proforma" {{ in_array('PROFORMA', old('tipo_documento', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="td-proforma">PROFORMA</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="tipo_documento[]" value="COTIZACION" 
                                           id="td-cotizacion" {{ in_array('COTIZACION', old('tipo_documento', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="td-cotizacion">COTIZACIÓN</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="tipo_documento[]" value="CONTRATO" 
                                           id="td-contrato" {{ in_array('CONTRATO', old('tipo_documento', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="td-contrato">CONTRATO</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="tipo_documento[]" value="CONTRATO MODIFICADO" 
                                           id="td-contrato-mod" {{ in_array('CONTRATO MODIFICADO', old('tipo_documento', [])) ? 'checked' : '' }}>
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
                                   placeholder="Ej: ING. JORGE LUIS MAMANI"
                                   value="{{ old('persona_contacto') }}">
                            @error('persona_contacto')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="telefono_contacto" class="form-label">Teléfono de Contacto</label>
                            <input type="text" class="form-control @error('telefono_contacto') is-invalid @enderror" 
                                   id="telefono_contacto" name="telefono_contacto" 
                                   placeholder="Ej: 72377218"
                                   value="{{ old('telefono_contacto') }}">
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
                                   placeholder="Ej: TUPIZA - TOROPALCA"
                                   value="{{ old('procedencia') }}">
                            @error('procedencia')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="coordenadas" class="form-label">Coordenadas</label>
                            <input type="text" class="form-control @error('coordenadas') is-invalid @enderror" 
                                   id="coordenadas" name="coordenadas" 
                                   placeholder="Ej: E 214561, N 7836327"
                                   value="{{ old('coordenadas') }}">
                            @error('coordenadas')
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
                                    <option value="{{ $opcion }}" {{ old('muestreado_por') == $opcion ? 'selected' : '' }}>
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
                                       value="{{ old('adelanto', 0) }}" min="0" step="0.01"
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
                    
                    <div id="parametros-container">
                        <!-- Parámetro inicial -->
                        <div class="parametro-row mb-3 border p-3 rounded" id="parametro-row-0">
                            <div class="row align-items-center">
                                <div class="col-md-5 mb-2 mb-md-0">
                                    <label class="form-label small">Parámetro *</label>
                                    <select name="parametros[0][id]" class="form-control parametro-select" 
                                            id="parametro-select-0" style="width: 100%;" required>
                                        <option value="">Buscar parámetro...</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <label class="form-label small">N° Muestras *</label>
                                    <input type="number" class="form-control muestra-input" 
                                           name="parametros[0][cantidad]" value="1" min="1" 
                                           oninput="calcularTotalesEstimados()" required>
                                </div>
                                
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <label class="form-label small">Precio Unitario</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs.</span>
                                        <input type="text" class="form-control precio-unitario" 
                                               id="precio-0" value="0.00" readonly>
                                    </div>
                                </div>
                                
                                <div class="col-md-1 text-center">
                                    <label class="form-label small">&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-sm remove-parametro" 
                                            onclick="eliminarParametro(this)" disabled>
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Método de ensayo -->
                            <div class="row mt-2 metodo-container" id="metodo-0" style="display: none;">
                                <div class="col-12">
                                    <small class="text-muted">
                                        <i class="fas fa-microscope me-1"></i> 
                                        Método: <span class="metodo-text"></span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" id="add-parametro" class="btn" 
                            style="background-color: #ffc107; color: #000000; border: none; border-radius: 30px; padding: 8px 20px; font-weight: 500;">
                        <i class="fas fa-plus me-1"></i> Agregar parámetro
                    </button>
                </div>

                <!-- SECCIÓN 6: OBSERVACIONES -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-sticky-note me-2" style="color: #ffc107;"></i>
                        Observaciones
                    </h6>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                                  id="observaciones" name="observaciones" rows="3"
                                  placeholder="Observaciones importantes...">{{ old('observaciones') }}</textarea>
                        @error('observaciones')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- SECCIÓN 7: RESUMEN FINANCIERO -->
                <div class="card mt-4 border-warning">
                    <div class="card-header" style="background-color: #ffc107; border-bottom: none;">
                        <h6 class="mb-0" style="color: #000000;">
                            <i class="fas fa-calculator me-2" style="color: #000000;"></i> Resumen Financiero Estimado
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <p class="mb-1"><strong>Subtotal</strong></p>
                                <h4 class="text-primary">Bs. <span id="subtotal-estimado">0.00</span></h4>
                            </div>
                            <div class="col-md-3 text-center">
                                <p class="mb-1"><strong>Descuento</strong></p>
                                <h4 class="text-danger">Bs. <span id="descuento-estimado">0.00</span></h4>
                                <small class="text-muted" id="descuento-nota">(No aplica)</small>
                            </div>
                            <div class="col-md-3 text-center">
                                <p class="mb-1"><strong>Total</strong></p>
                                <h3 class="text-success">Bs. <span id="total-estimado">0.00</span></h3>
                            </div>
                            <div class="col-md-3 text-center">
                                <p class="mb-1"><strong>Saldo</strong></p>
                                <h4 class="text-info">Bs. <span id="saldo-estimado">0.00</span></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 8: BOTONES -->
                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <a href="{{ route('proformas.index') }}" class="btn btn-secondary" style="border-radius: 30px; padding: 10px 25px;">
                        <i class="fas fa-times me-2"></i> Cancelar
                    </a>
                    <button type="submit" class="btn" 
                            style="background-color: #ffc107; border-radius: 30px; padding: 10px 25px; color: #000000; border: none;">
                        <i class="fas fa-save me-2"></i> Guardar Proforma
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL PARA CREAR CLIENTE -->
<div class="modal fade" id="crearClienteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #ffc107; border-bottom: none;">
                <h5 class="modal-title" style="color: #000000;">
                    <i class="fas fa-user-plus me-2" style="color: #000000;"></i> Nuevo Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nueva_razon_social" class="form-label">Razón Social *</label>
                    <input type="text" class="form-control" id="nueva_razon_social" required>
                </div>
                <div class="mb-3">
                    <label for="nueva_persona_contacto" class="form-label">Persona de Contacto *</label>
                    <input type="text" class="form-control" id="nueva_persona_contacto" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nueva_telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="nueva_telefono">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nueva_nit" class="form-label">NIT</label>
                        <input type="text" class="form-control" id="nueva_nit">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="nueva_direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="nueva_direccion">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 30px; padding: 8px 20px;">
                    <i class="fas fa-times me-2"></i> Cancelar
                </button>
                <button type="button" class="btn" onclick="crearCliente()" style="background-color: #ffc107; border-radius: 30px; padding: 8px 20px; color: #000000; border: none;">
                    <i class="fas fa-save me-2"></i> Guardar
                </button>
            </div>
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
    console.log('Documento listo - iniciando Select2');
    
    // Array para IDs seleccionados (evitar duplicados)
    let parametrosSeleccionados = [];
    
    // ===== SELECT DE CLIENTES =====
    $('#cliente_id').select2({
        placeholder: '🔍 Buscar cliente por nombre o contacto...',
        minimumInputLength: 2,
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
    
    // ===== FUNCIÓN PARA INICIALIZAR SELECT DE PARÁMETROS =====
    function initParametroSelect(selector) {
        console.log('Inicializando:', selector);
        
        $(selector).select2({
            placeholder: '🔬 Buscar parámetro por nombre...',
            minimumInputLength: 2,
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
        }).on('select2:select', function(e) {
            const data = e.params.data;
            const row = $(this).closest('.parametro-row');
            const rowId = row.attr('id').split('-')[2];
            
            // Verificar si ya está seleccionado
            if (parametrosSeleccionados.includes(data.id)) {
                alert('⚠️ Este parámetro ya ha sido seleccionado. Por favor, elija otro parámetro.');
                $(this).val(null).trigger('change');
                return;
            }
            
            // Agregar ID a la lista de seleccionados
            parametrosSeleccionados.push(data.id);
            console.log('Parámetros seleccionados:', parametrosSeleccionados);
            
            // Actualizar precio y método
            $('#precio-' + rowId).val(parseFloat(data.precio_unitario).toFixed(2));
            
            const metodoText = row.find('.metodo-text');
            const metodoContainer = row.find('.metodo-container');
            
            metodoText.text(data.metodo || '');
            metodoContainer.show();
            
            calcularTotales();
        });
    }
    
    // ===== INICIALIZAR PRIMER PARÁMETRO =====
    setTimeout(function() {
        initParametroSelect('#parametro-select-0');
    }, 100);
    
    // ===== BOTÓN AGREGAR PARÁMETRO =====
    $('#add-parametro').click(function() {
        const container = $('#parametros-container');
        const index = container.find('.parametro-row').length;
        const firstRow = $('.parametro-row:first');
        const newRow = firstRow.clone();
        
        // Actualizar IDs
        newRow.attr('id', 'parametro-row-' + index);
        
        // Limpiar select
        const newSelect = newRow.find('.parametro-select');
        newSelect.attr('id', 'parametro-select-' + index)
                .attr('name', 'parametros[' + index + '][id]')
                .val('')
                .removeAttr('data-select2-id')
                .next('.select2-container').remove();
        
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
                .click(function() { eliminarParametro(this, index); });
        
        container.append(newRow);
        
        // Inicializar Select2 para el nuevo select
        setTimeout(function() {
            initParametroSelect('#parametro-select-' + index);
        }, 100);
        
        calcularTotales();
    });
    
    // ===== ELIMINAR PARÁMETRO =====
    window.eliminarParametro = function(btn, index) {
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
            calcularTotales();
        }
    };
    
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
        
        // Verificar también con el array de seleccionados
        if (parametrosSeleccionados.length !== parametrosEnFormulario.length) {
            duplicados = true;
            mensajeError = '❌ Error: Inconsistencia en los parámetros seleccionados.';
        }
        
        if (duplicados) {
            e.preventDefault();
            
            // Mostrar mensaje de error
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
        
        console.log('Validación exitosa - No hay duplicados');
        return true;
    });
    
    // ===== CALCULAR TOTALES =====
    function calcularTotales() {
        let subtotal = 0;
        
        $('.parametro-row').each(function() {
            const precio = parseFloat($(this).find('.precio-unitario').val()) || 0;
            const cantidad = parseInt($(this).find('.muestra-input').val()) || 0;
            subtotal += precio * cantidad;
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
    }
    
    // ===== EVENTOS =====
    $('#tipo, #adelanto').on('change keyup', calcularTotales);
    $(document).on('input', '.muestra-input', calcularTotales);
    
    // Calcular totales inicial
    calcularTotales();
    
    // ===== MODAL CLIENTE =====
    window.abrirModalCliente = function() {
        new bootstrap.Modal(document.getElementById('crearClienteModal')).show();
    };
    
    window.crearCliente = function() {
        const razonSocial = $('#nueva_razon_social').val();
        const personaContacto = $('#nueva_persona_contacto').val();
        
        if (!razonSocial || !personaContacto) {
            alert('⚠️ Razón Social y Persona de Contacto son obligatorios');
            return;
        }
        
        $.post('/clientes/api', {
            razon_social: razonSocial,
            persona_contacto: personaContacto,
            telefono: $('#nueva_telefono').val(),
            nit: $('#nueva_nit').val(),
            direccion: $('#nueva_direccion').val(),
            _token: '{{ csrf_token() }}'
        }, function(data) {
            if (data.success) {
                const newOption = new Option(
                    data.cliente.razon_social + ' - ' + data.cliente.persona_contacto,
                    data.cliente.id,
                    true,
                    true
                );
                $('#cliente_id').append(newOption).trigger('change');
                
                $('#crearClienteModal').modal('hide');
                $('#nueva_razon_social, #nueva_persona_contacto, #nueva_telefono, #nueva_nit, #nueva_direccion').val('');
                
                alert('✅ Cliente creado exitosamente');
            }
        }).fail(function(xhr) {
            alert('❌ Error al crear cliente: ' + (xhr.responseJSON?.message || 'Error desconocido'));
        });
    };
});
</script>
@endpush