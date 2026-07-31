@extends('layouts.app')

@section('title', 'Configuraciones - ' . ($documento->nombre ?? ''))

@push('styles')
<style>
.config-layout { display: flex; gap: 24px; align-items: flex-start; }
.config-sidebar { width: 300px; min-width: 300px; position: sticky; top: 84px; }
.config-content { flex: 1; min-width: 0; }

.doc-nav { list-style: none; padding: 0; margin: 0; }
.doc-nav li { border-left: 3px solid transparent; transition: all 0.2s; }
.doc-nav li.active { border-left-color: #ffc107; background: #fffbe6; border-radius: 0 8px 8px 0; }
.doc-nav li:not(.active):hover { border-left-color: #e2e8f0; background: #f8fafc; border-radius: 0 8px 8px 0; }
.doc-nav a { display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: #334155; text-decoration: none; font-size: 0.9rem; }
.doc-nav li.active a { color: #1a1a2e; font-weight: 600; }
.doc-nav .doc-code { font-size: 0.75rem; color: #94a3b8; font-weight: 500; }
.doc-nav i { width: 20px; text-align: center; color: #94a3b8; }
.doc-nav li.active i { color: #ffc107; }

@media (max-width: 991.98px) {
    .config-layout { flex-direction: column; }
    .config-sidebar { width: 100%; min-width: auto; position: static; }
    .doc-nav { display: flex; overflow-x: auto; gap: 4px; padding-bottom: 4px; }
    .doc-nav li { border-left: none; border-bottom: 3px solid transparent; white-space: nowrap; flex-shrink: 0; }
    .doc-nav li.active { border-bottom-color: #ffc107; border-left-color: transparent; }
}
</style>
@endpush

@section('content')
<div class="container-main py-4">
    <div class="page-header">
        <h1><i class="fas fa-cog me-2"></i> Configuraciones del Sistema</h1>
        <p class="page-subtitle">Gestor de plantillas PDF — seleccione un documento para editarlo.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="config-layout">
        {{-- SIDEBAR --}}
        <div class="card config-sidebar">
            <div class="card-header">
                <i class="fas fa-file-pdf me-2"></i> Documentos PDF
            </div>
            <div class="card-body p-0">
                <ul class="doc-nav">
                    @foreach($documentos as $doc)
                        <li class="{{ $doc->id === $documento->id ? 'active' : '' }}">
                            <a href="{{ route('configuraciones.index', $doc->slug) }}">
                                <i class="fas fa-file-alt"></i>
                                <div>
                                    <div>{{ $doc->nombre }}</div>
                                    @if($doc->codigo_documento)
                                        <div class="doc-code">{{ $doc->codigo_documento }} / V{{ $doc->version ?? '?' }}</div>
                                    @endif
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- FORMULARIO --}}
        <div class="config-content">
            <form id="formConfiguracion" method="POST" action="{{ route('configuraciones.update', $documento->slug) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @php
                    $esProforma = in_array($documento->slug, ['solicitud-ensayo', 'solicitud-ensayo-ambiental']);
                    $conExtras = $esProforma || in_array($documento->slug, ['informe-final', 'informe-resultados']);
                    $conCabecera = $documento->slug === 'informe-resultados';
                @endphp

                <div class="row g-4">
                    {{-- CABECERA DEL DOCUMENTO --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-tag me-2"></i> Información del Documento
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Código del documento</label>
                                        <input type="text" name="codigo_documento" class="form-control @error('codigo_documento') is-invalid @enderror"
                                               value="{{ old('codigo_documento', $documento->codigo_documento) }}" placeholder="Ej: PO01-FR02">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Versión</label>
                                        <input type="text" name="version" class="form-control @error('version') is-invalid @enderror"
                                               value="{{ old('version', $documento->version) }}" placeholder="Ej: 06">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Fecha del documento</label>
                                        <input type="text" name="fecha_documento" class="form-control @error('fecha_documento') is-invalid @enderror"
                                               value="{{ old('fecha_documento', $documento->fecha_documento) }}" placeholder="Ej: 2025-01-01">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($conCabecera)
                    {{-- CABECERA --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-heading me-2"></i> Cabecera
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Laboratorio</label>
                                        <input type="text" name="laboratorio_nombre" class="form-control"
                                               value="{{ old('laboratorio_nombre', $documento->config('laboratorio_nombre')) }}" placeholder="Ej: CENTRO DE INVESTIGACIÓN MINERO AMBIENTAL">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Institución</label>
                                        <input type="text" name="institucion_nombre" class="form-control"
                                               value="{{ old('institucion_nombre', $documento->config('institucion_nombre')) }}" placeholder="Ej: Centro de Investigación Minero Ambiental">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Universidad</label>
                                        <input type="text" name="universidad_nombre" class="form-control"
                                               value="{{ old('universidad_nombre', $documento->config('universidad_nombre')) }}" placeholder="Ej: UNIVERSIDAD AUTONOMA TOMAS FRIAS">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Sigla</label>
                                        <input type="text" name="institucion_sigla" class="form-control"
                                               value="{{ old('institucion_sigla', $documento->config('institucion_sigla')) }}" placeholder="Ej: CIMA-UATF">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($conExtras)
                    {{-- PIE DE PÁGINA --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-grip-lines me-2"></i> Pie de página
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Nota personalizada</label>
                                        <textarea name="footer_texto" class="form-control" rows="2" placeholder="Texto opcional para el pie">{{ old('footer_texto', $documento->config('footer_texto')) }}</textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Dirección</label>
                                        <input type="text" name="footer_direccion" class="form-control"
                                               value="{{ old('footer_direccion', $documento->config('footer_direccion')) }}" placeholder="Dirección en el pie">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Teléfono</label>
                                        <input type="text" name="footer_telefono" class="form-control"
                                               value="{{ old('footer_telefono', $documento->config('footer_telefono')) }}" placeholder="Teléfono en el pie">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Correo electrónico</label>
                                        <input type="email" name="footer_email" class="form-control"
                                               value="{{ old('footer_email', $documento->config('footer_email')) }}" placeholder="correo@institucion.edu.bo">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- FIRMAS --}}
                    @if($conExtras)
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-pen me-2"></i> Firmas
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre del responsable técnico</label>
                                        <input type="text" name="responsable_nombre" class="form-control"
                                               value="{{ old('responsable_nombre', $documento->config('responsable_nombre')) }}" placeholder="Nombre completo">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Cargo del responsable técnico</label>
                                        <input type="text" name="responsable_cargo" class="form-control"
                                               value="{{ old('responsable_cargo', $documento->config('responsable_cargo')) }}" placeholder="Ej: Responsable Técnico">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre del director / encargado</label>
                                        <input type="text" name="director_nombre" class="form-control"
                                               value="{{ old('director_nombre', $documento->config('director_nombre')) }}" placeholder="Nombre completo">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Cargo del director / encargado</label>
                                        <input type="text" name="director_cargo" class="form-control"
                                               value="{{ old('director_cargo', $documento->config('director_cargo')) }}" placeholder="Ej: Director(a) CIMA - UATF">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- NOTAS --}}
                    @if($conExtras)
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-sticky-note me-2"></i> Notas
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Nota 1</label>
                                        <textarea name="nota1" class="form-control" rows="2" placeholder="Ej: Para realizar el análisis se debe dejar cancelado el 100% del monto total.">{{ old('nota1', $documento->config('nota1')) }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Nota 2</label>
                                        <textarea name="nota2" class="form-control" rows="2" placeholder="Ej: El laboratorio no realiza declaraciones de conformidad sobre los resultados que se reportan.">{{ old('nota2', $documento->config('nota2')) }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Nota 3</label>
                                        <textarea name="nota3" class="form-control" rows="2" placeholder="Ej: Los resultados estarán disponibles dentro de los plazos establecidos según el tipo de análisis.">{{ old('nota3', $documento->config('nota3')) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- VISTA PREVIA --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-eye me-2"></i> Vista previa del contenido
                            </div>
                            <div class="card-body">
                                @if($esProforma)
                                <div style="font-family: 'Times New Roman', serif; font-size: 12px; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fafafa;">
                                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                                        <tr>
                                            <td style="padding: 6px 10px; font-weight: bold; width: 40%; border: 1px solid #ddd;">Código del documento</td>
                                            <td style="padding: 6px 10px; border: 1px solid #ddd;"><span data-campo="codigo_documento">{{ $documento->codigo_documento }}</span></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 6px 10px; font-weight: bold; border: 1px solid #ddd;">Versión</td>
                                            <td style="padding: 6px 10px; border: 1px solid #ddd;"><span data-campo="version" data-formato="V{version}">V{{ $documento->version }}</span></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 6px 10px; font-weight: bold; border: 1px solid #ddd;">Fecha del documento</td>
                                            <td style="padding: 6px 10px; border: 1px solid #ddd;"><span data-campo="fecha_documento">{{ $documento->fecha_documento }}</span></td>
                                        </tr>
                                    </table>
                                    <div style="display: flex; justify-content: space-between; margin-top: 15px;">
                                        <div style="text-align: center; flex: 1;">
                                            <div style="font-size: 11px; font-weight: bold;"><span data-campo="responsable_nombre">{{ $documento->config('responsable_nombre', '') }}</span></div>
                                            <div style="font-size: 10px; color: #666;"><span data-campo="responsable_cargo">{{ $documento->config('responsable_cargo', '') }}</span></div>
                                        </div>
                                        <div style="text-align: center; flex: 1;">
                                            <div style="font-size: 11px; font-weight: bold;"><span data-campo="director_nombre">{{ $documento->config('director_nombre', '') }}</span></div>
                                            <div style="font-size: 10px; color: #666;"><span data-campo="director_cargo">{{ $documento->config('director_cargo', '') }}</span></div>
                                        </div>
                                    </div>
                                    <hr style="border: none; border-top: 1px dashed #ccc;">
                                    <div style="text-align: center; font-size: 10px; color: #666; margin-top: 10px;">
                                        <div><span data-campo="footer_texto">{{ $documento->config('footer_texto', '') }}</span></div>
                                        <div><span data-campo="footer_direccion">{{ $documento->config('footer_direccion', '') }}</span> | <span data-campo="footer_telefono">{{ $documento->config('footer_telefono', '') }}</span> | <span data-campo="footer_email">{{ $documento->config('footer_email', '') }}</span></div>
                                    </div>
                                    <hr style="border: none; border-top: 1px dashed #ccc;">
                                    <div style="font-size: 10px; color: #666; margin-top: 10px; text-align: left;">
                                        <p style="margin: 2px 0;"><strong>Nota 1:</strong> <span data-campo="nota1">{{ $documento->config('nota1', '') }}</span></p>
                                        <p style="margin: 2px 0;"><strong>Nota 2:</strong> <span data-campo="nota2">{{ $documento->config('nota2', '') }}</span></p>
                                        <p style="margin: 2px 0;"><strong>Nota 3:</strong> <span data-campo="nota3">{{ $documento->config('nota3', '') }}</span></p>
                                    </div>
                                </div>
                                @else
                                <div style="font-family: 'Times New Roman', serif; font-size: 12px; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fafafa;">
                                    <div style="font-size: 14px; font-weight: bold; text-align: center; margin-bottom: 15px;">{{ $documento->nombre }}</div>
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <tr>
                                            <td style="padding: 6px 10px; font-weight: bold; width: 40%; border: 1px solid #ddd;">Código del documento</td>
                                            <td style="padding: 6px 10px; border: 1px solid #ddd;"><span data-campo="codigo_documento">{{ $documento->codigo_documento }}</span></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 6px 10px; font-weight: bold; border: 1px solid #ddd;">Versión</td>
                                            <td style="padding: 6px 10px; border: 1px solid #ddd;"><span data-campo="version" data-formato="V{version}">V{{ $documento->version }}</span></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 6px 10px; font-weight: bold; border: 1px solid #ddd;">Fecha del documento</td>
                                            <td style="padding: 6px 10px; border: 1px solid #ddd;"><span data-campo="fecha_documento">{{ $documento->fecha_documento }}</span></td>
                                        </tr>
                                    </table>
                                    @if($conCabecera)
                                    <div style="text-align: center; margin-top: 10px;">
                                        <div style="font-size: 12px; font-weight: bold;"><span data-campo="laboratorio_nombre">{{ $documento->config('laboratorio_nombre', '') }}</span></div>
                                        <div style="font-size: 11px;"><span data-campo="universidad_nombre">{{ $documento->config('universidad_nombre', '') }}</span></div>
                                        <div style="font-size: 11px; font-weight: bold;"><span data-campo="institucion_sigla">{{ $documento->config('institucion_sigla', '') }}</span></div>
                                        <div style="font-size: 10px; color: #666; margin-top: 4px;"><span data-campo="codigo_compuesto" data-formato="{codigo_documento}/Ver. {version}/{fecha_documento}">{{ $documento->codigo_documento }}/Ver. {{ $documento->version }}/{{ $documento->fecha_documento }}</span></div>
                                    </div>
                                    @endif
                                    @if($conExtras)
                                    <hr style="border: none; border-top: 1px dashed #ccc;">
                                    <div style="text-align: center; font-size: 10px; color: #666; margin-top: 10px;">
                                        <div><strong><span data-campo="institucion_nombre">{{ $documento->config('institucion_nombre', '') }}</span></strong></div>
                                        <div><span data-campo="footer_direccion">{{ $documento->config('footer_direccion', '') }}</span></div>
                                        <div><span data-campo="footer_telefono">{{ $documento->config('footer_telefono', '') }}</span> | <span data-campo="footer_email">{{ $documento->config('footer_email', '') }}</span></div>
                                    </div>
                                    <hr style="border: none; border-top: 1px dashed #ccc;">
                                    <div style="display: flex; justify-content: space-between; margin-top: 15px;">
                                        <div style="text-align: center; flex: 1;">
                                            <div style="font-size: 11px; font-weight: bold;"><span data-campo="responsable_nombre">{{ $documento->config('responsable_nombre', '') }}</span></div>
                                            <div style="font-size: 10px; color: #666;"><span data-campo="responsable_cargo">{{ $documento->config('responsable_cargo', '') }}</span></div>
                                        </div>
                                        <div style="text-align: center; flex: 1;">
                                            <div style="font-size: 11px; font-weight: bold;"><span data-campo="director_nombre">{{ $documento->config('director_nombre', '') }}</span></div>
                                            <div style="font-size: 10px; color: #666;"><span data-campo="director_cargo">{{ $documento->config('director_cargo', '') }}</span></div>
                                        </div>
                                    </div>
                                    <hr style="border: none; border-top: 1px dashed #ccc;">
                                    <div style="font-size: 10px; color: #666; margin-top: 10px; text-align: left;">
                                        <p style="margin: 2px 0;"><strong>Nota 1:</strong> <span data-campo="nota1">{{ $documento->config('nota1', '') }}</span></p>
                                        <p style="margin: 2px 0;"><strong>Nota 2:</strong> <span data-campo="nota2">{{ $documento->config('nota2', '') }}</span></p>
                                        <p style="margin: 2px 0;"><strong>Nota 3:</strong> <span data-campo="nota3">{{ $documento->config('nota3', '') }}</span></p>
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- BOTONES EDICIÓN / VISTA PREVIA / GUARDAR --}}
                    <div class="col-12 text-end mb-4">
                        <button type="button" id="btnEditar" class="btn btn-outline-primary btn-lg px-4 me-2">
                            <i class="fas fa-pen me-2"></i> Editar
                        </button>
                        <button type="button" id="btnActualizar" class="btn btn-primary btn-lg px-4 me-2 d-none">
                            <i class="fas fa-sync-alt me-2"></i> Actualizar
                        </button>
                        <button type="button" id="btnCancelar" class="btn btn-outline-secondary btn-lg px-4 me-2 d-none">
                            <i class="fas fa-times me-2"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-warning btn-lg px-5">
                            <i class="fas fa-save me-2"></i> Guardar «{{ $documento->nombre }}»
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('formConfiguracion');
    if (!form) return;

    var btnEditar = document.getElementById('btnEditar');
    var btnActualizar = document.getElementById('btnActualizar');
    var btnCancelar = document.getElementById('btnCancelar');
    var inputs = form.querySelectorAll('input[name], textarea[name]');
    var spans = form.querySelectorAll('[data-campo]');

    var valoresOriginales = {};
    inputs.forEach(function (input) { valoresOriginales[input.name] = input.value; });
    var spansOriginales = {};
    spans.forEach(function (span) { spansOriginales[span.getAttribute('data-campo')] = span.textContent; });

    function modoLectura() {
        inputs.forEach(function (input) { input.readOnly = true; });
        btnEditar.classList.remove('d-none');
        btnActualizar.classList.add('d-none');
        btnCancelar.classList.add('d-none');
    }

    function modoEdicion() {
        inputs.forEach(function (input) { input.readOnly = false; });
        btnEditar.classList.add('d-none');
        btnActualizar.classList.remove('d-none');
        btnCancelar.classList.remove('d-none');
    }

    function restaurar() {
        inputs.forEach(function (input) { input.value = valoresOriginales[input.name]; });
        spans.forEach(function (span) { span.textContent = spansOriginales[span.getAttribute('data-campo')]; });
    }

    btnEditar.addEventListener('click', modoEdicion);

    btnCancelar.addEventListener('click', function () {
        restaurar();
        modoLectura();
    });

    btnActualizar.addEventListener('click', function () {
        inputs.forEach(function (input) {
            var span = form.querySelector('[data-campo="' + input.getAttribute('name') + '"]');
            if (span && !span.hasAttribute('data-formato')) {
                span.textContent = input.value;
            }
        });

        form.querySelectorAll('[data-campo][data-formato]').forEach(function (span) {
            var formato = span.getAttribute('data-formato');
            span.textContent = formato.replace(/\{(\w+)\}/g, function (match, key) {
                var fuente = form.querySelector('[name="' + key + '"]');
                return fuente ? fuente.value : '';
            });
        });
    });

    modoLectura();
});
</script>
@endpush
