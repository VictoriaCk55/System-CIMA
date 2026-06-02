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
            <form method="POST" action="{{ route('configuraciones.update', $documento->slug) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

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

                    {{-- CABECERA PDF --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-header me-2"></i> Cabecera
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre de la institución</label>
                                        <input type="text" name="institucion_nombre" class="form-control"
                                               value="{{ old('institucion_nombre', $documento->config('institucion_nombre')) }}" placeholder="Ej: Universidad Autónoma Tomás Frías">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Sigla</label>
                                        <input type="text" name="institucion_sigla" class="form-control"
                                               value="{{ old('institucion_sigla', $documento->config('institucion_sigla')) }}" placeholder="Ej: CIMA-UATF">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Nombre del laboratorio</label>
                                        <input type="text" name="laboratorio_nombre" class="form-control"
                                               value="{{ old('laboratorio_nombre', $documento->config('laboratorio_nombre')) }}" placeholder="Ej: Centro de Investigación Minero Ambiental">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Dirección</label>
                                        <input type="text" name="direccion" class="form-control"
                                               value="{{ old('direccion', $documento->config('direccion')) }}" placeholder="Dirección de la institución">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Teléfono</label>
                                        <input type="text" name="telefono" class="form-control"
                                               value="{{ old('telefono', $documento->config('telefono')) }}" placeholder="Teléfono">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Correo electrónico</label>
                                        <input type="email" name="email" class="form-control"
                                               value="{{ old('email', $documento->config('email')) }}" placeholder="correo@institucion.edu.bo">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Logo institucional</label>
                                        <input type="file" name="logo" class="form-control" accept="image/jpeg,image/png">
                                        @if($documento->config('logo_path'))
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $documento->config('logo_path')) }}" alt="Logo" style="max-height:60px;border:1px solid #ddd;border-radius:4px;padding:4px;">
                                                <small class="text-muted d-block">Logo actual. Suba uno nuevo para reemplazarlo.</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- PIE DE PÁGINA --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-grip-lines me-2"></i> Pie de página
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Texto personalizado</label>
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

                    {{-- FIRMAS --}}
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
                                    <div class="col-md-6">
                                        <label class="form-label">Imagen de firma (opcional)</label>
                                        <input type="file" name="firma" class="form-control" accept="image/jpeg,image/png">
                                        @if($documento->config('firma_path'))
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $documento->config('firma_path')) }}" alt="Firma" style="max-height:50px;border:1px solid #ddd;border-radius:4px;padding:4px;">
                                                <small class="text-muted d-block">Firma actual. Suba una nueva para reemplazarla.</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- VISTA PREVIA --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-eye me-2"></i> Vista previa del contenido
                            </div>
                            <div class="card-body">
                                <div style="font-family: 'Times New Roman', serif; font-size: 12px; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fafafa;">
                                    <div style="text-align: center; margin-bottom: 15px;">
                                        @if($documento->config('logo_path'))
                                            <img src="{{ asset('storage/' . $documento->config('logo_path')) }}" style="max-height:50px;margin-bottom:8px;">
                                        @endif
                                        <div style="font-size: 14px; font-weight: bold;">{{ $documento->config('institucion_nombre', '—') }}</div>
                                        <div style="font-size: 12px;">{{ $documento->config('laboratorio_nombre', '') }}</div>
                                        <div style="font-size: 10px; color: #666;">{{ $documento->config('direccion', '') }} | {{ $documento->config('telefono', '') }} | {{ $documento->config('email', '') }}</div>
                                    </div>
                                    <hr style="border: none; border-top: 1px dashed #ccc;">
                                    <div style="text-align: center; font-size: 10px; color: #666; margin-top: 10px;">
                                        <div>{{ $documento->config('footer_texto', '') }}</div>
                                        <div>{{ $documento->config('footer_direccion', '') }} | {{ $documento->config('footer_telefono', '') }} | {{ $documento->config('footer_email', '') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BOTÓN GUARDAR --}}
                    <div class="col-12 text-end mb-4">
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
