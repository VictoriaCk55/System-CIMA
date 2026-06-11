@extends('layouts.app')

@section('title', 'Reporte de Aire')

@push('styles')
<style>
    .table-dinamica th { background-color: #0d6efd; color: #fff; font-size: 0.85rem; white-space: nowrap; }
    .btn-agregar-fila { background-color: #28a745; color: white; border: none; border-radius: 30px; padding: 6px 18px; font-size: 0.85rem; }
    .btn-agregar-fila:hover { background-color: #218838; }
    .btn-eliminar-fila { background: none; border: none; color: #dc3545; cursor: pointer; font-size: 1.1rem; }
    .btn-eliminar-fila:hover { color: #a71d2a; }
    .section-card { border-radius: 10px; margin-bottom: 1.5rem; }
    .section-card .card-header { font-weight: 600; border-radius: 10px 10px 0 0; }
    .section-card.aire .card-header { background-color: #0d6efd; color: #fff; }
</style>
@endpush

@section('content')
<div class="container-main">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-wind" style="color: #0d6efd;"></i> Reporte de Aire</h1>
                <p class="page-subtitle">Proforma: <strong>{{ $proforma->codigo }}</strong> — {{ $proforma->cliente->razon_social }}</p>
            </div>
            <a href="{{ route('reportes.ambiental.index', $proforma) }}" class="btn btn-outline-secondary" style="border-radius: 30px; padding: 8px 20px;">
                <i class="fas fa-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('reportes.ambiental.store', $proforma) }}" method="POST">
        @csrf
        <input type="hidden" name="categoria" value="AIRE">

        <!-- INFORMACIÓN GENERAL -->
        <div class="card section-card aire">
            <div class="card-header"><i class="fas fa-info-circle me-2"></i> Información General — Aire</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nombre del Cliente</label>
                        <input type="text" class="form-control" value="{{ $proforma->cliente->razon_social }}" disabled>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Código de Reporte</label>
                        <input type="text" class="form-control @error('codigo_reporte') is-invalid @enderror"
                               name="codigo_reporte" value="{{ old('codigo_reporte', $reporte->codigo_reporte ?? $reporte->codigoAire()) }}" readonly>
                        @error('codigo_reporte')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha de Emisión de Reporte</label>
                        <input type="date" class="form-control @error('fecha_emision') is-invalid @enderror"
                               name="fecha_emision" value="{{ old('fecha_emision', optional(optional($reporte)->fecha_emision)->format('Y-m-d') ?? date('Y-m-d')) }}">
                        @error('fecha_emision')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha de Inicio de Muestreo</label>
                        <input type="date" class="form-control @error('fecha_inicio_muestreo') is-invalid @enderror"
                               name="fecha_inicio_muestreo" value="{{ old('fecha_inicio_muestreo', optional(optional($reporte)->fecha_inicio_muestreo)->format('Y-m-d') ?? '') }}">
                        @error('fecha_inicio_muestreo')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha Final de Muestreo</label>
                        <input type="date" class="form-control @error('fecha_fin_muestreo') is-invalid @enderror"
                               name="fecha_fin_muestreo" value="{{ old('fecha_fin_muestreo', optional(optional($reporte)->fecha_fin_muestreo)->format('Y-m-d') ?? '') }}">
                        @error('fecha_fin_muestreo')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipo de Muestreo</label>
                        <input type="text" class="form-control @error('tipo_muestreo') is-invalid @enderror"
                               name="tipo_muestreo" value="{{ old('tipo_muestreo', $reporte->tipo_muestreo ?? '') }}"
                               placeholder="Ej: Puntual, Continuo, Compuesto">
                        @error('tipo_muestreo')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Medición Efectuada por</label>
                        <input type="text" class="form-control @error('medicion_efectuada_por') is-invalid @enderror"
                               name="medicion_efectuada_por" value="{{ old('medicion_efectuada_por', $reporte->medicion_efectuada_por ?? '') }}" placeholder="Nombre del responsable">
                        @error('medicion_efectuada_por')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Equipo Usado</label>
                        <input type="text" class="form-control @error('equipo_usado') is-invalid @enderror"
                               name="equipo_usado" value="{{ old('equipo_usado', $reporte->equipo_usado ?? '') }}" placeholder="Ej: Bomba de muestreo TAS">
                        @error('equipo_usado')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Condiciones de Muestreo</label>
                        <textarea class="form-control @error('condiciones_muestreo') is-invalid @enderror"
                                  name="condiciones_muestreo" rows="3" placeholder="Ej: Temperatura, presión, condiciones climáticas...">{{ old('condiciones_muestreo', $reporte->condiciones_muestreo ?? '') }}</textarea>
                        @error('condiciones_muestreo')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Condiciones de Reporte de Resultados</label>
                        <textarea class="form-control @error('condiciones_reporte') is-invalid @enderror"
                                  name="condiciones_reporte" rows="3" placeholder="Ej: Base seca, condiciones normales...">{{ old('condiciones_reporte', $reporte->condiciones_reporte ?? '') }}</textarea>
                        @error('condiciones_reporte')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- RESULTADOS -->
        <div class="card section-card aire">
            <div class="card-header"><i class="fas fa-table me-2"></i> RESULTADO DE MUESTREO DE PARTÍCULAS SUSPENDIDAS</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-dinamica" id="tabla-aire">
                        <thead>
                            <tr>
                                <th>CÓDIGO</th>
                                <th>PERIODO DE MUESTREO</th>
                                <th>PARTICULAS SUSPENDIDAS <br> MENORES A 10 MICRAS - PM-10<br>(µg/m³)</th>
                                <th>PARTICULAS SUSPENDIDAS <br> TOTALES - PTS<br>(µg/m³)</th>
                                <!-- <th>Método</th> -->
                                <th style="width: 40px;"></th>
                            </tr>
                        </thead>
                        <tbody id="aire-body">
                            @php
                                $puntosAire = old('puntos_medicion', $reporte->puntos_medicion ?? []);
                                if (is_string($puntosAire)) $puntosAire = json_decode($puntosAire, true) ?? [];
                                $numMuestras = count($puntosAire);
                                if ($numMuestras === 0) {
                                    $numMuestras = $proforma->parametros()->where('categoria', 'AIRE')->count();
                                }

                                $ra = old('resultados_aire', $reporte->resultados_aire ?? []);
                                if (is_string($ra)) $ra = json_decode($ra, true) ?? [];
                                $hasRa = count($ra) > 0;
                            @endphp
                            @forelse($ra as $i => $r)
                            <tr class="fila-aire">
                                <td><input type="text" class="form-control form-control-sm" name="resultados_aire[{{ $i }}][codigo]" value="{{ $r['codigo'] ?? '' }}" readonly></td>
                                <td><input type="text" class="form-control form-control-sm" name="resultados_aire[{{ $i }}][parametro]" value="{{ $r['parametro'] ?? '' }}"></td>
                                <td><input type="number" step="0.01" class="form-control form-control-sm" name="resultados_aire[{{ $i }}][concentracion]" value="{{ $r['concentracion'] ?? '' }}"></td>
                                <td><input type="text" class="form-control form-control-sm" name="resultados_aire[{{ $i }}][unidad]" value="{{ $r['unidad'] ?? '' }}"></td>
                                <td class="text-center"><button type="button" class="btn-eliminar-fila" onclick="this.closest('tr').remove()"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @empty
                            @for($mi = 0; $mi < $numMuestras; $mi++)
                            <tr class="fila-aire">
                                <td><input type="text" class="form-control form-control-sm" name="resultados_aire[{{ $mi }}][codigo]" value="AI-{{ str_pad($mi + 1, 2, '0', STR_PAD_LEFT) }}" readonly></td>
                                <td><input type="text" class="form-control form-control-sm" name="resultados_aire[{{ $mi }}][parametro]" placeholder="Periodo de muestreo"></td>
                                <td><input type="number" step="0.01" class="form-control form-control-sm" name="resultados_aire[{{ $mi }}][concentracion]"></td>
                                <td><input type="text" class="form-control form-control-sm" name="resultados_aire[{{ $mi }}][unidad]"></td>
                                <td class="text-center"><button type="button" class="btn-eliminar-fila" onclick="this.closest('tr').remove()"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @endfor
                            @if($numMuestras === 0)
                            <tr class="fila-aire">
                                <td><input type="text" class="form-control form-control-sm" name="resultados_aire[0][codigo]" placeholder="Ej: PA-01" readonly></td>
                                <td><input type="text" class="form-control form-control-sm" name="resultados_aire[0][parametro]" placeholder="Ej: PTS"></td>
                                <td><input type="number" step="0.01" class="form-control form-control-sm" name="resultados_aire[0][concentracion]"></td>
                                <td><input type="text" class="form-control form-control-sm" name="resultados_aire[0][unidad]" placeholder="Ej: µg/m³"></td>
                                <td class="text-center"><button type="button" class="btn-eliminar-fila" onclick="this.closest('tr').remove()"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @endif
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn-agregar-fila" onclick="agregarFila()">
                    <i class="fas fa-plus me-1"></i> Agregar fila
                </button>
            </div>
        </div>

        <!-- DESCRIPCIÓN DE PUNTOS DE MEDICIÓN -->
        <div class="card section-card general" style="border: 1px solid #ffc107; border-radius: 10px; margin-bottom: 1.5rem;">
            <div class="card-header" style="background-color: #ffc107; color: #000; font-weight: 600; border-radius: 10px 10px 0 0;">
                <i class="fas fa-map-marker-alt me-2"></i> Descripción de Puntos de Medición
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="tabla-puntos" style="border-color: #ffc107;">
                        <thead>
                            <tr>
                                <th style="width: 12%;">Código</th>
                                <th style="width: 33%;">Descripción del Punto</th>
                                <th class="text-center" style="width: 40%;">UBICACIÓN
                                    <select class="form-select form-select-sm d-block mx-auto mt-1" id="zona-header" style="width: 140px;" onchange="actualizarZonas(this.value)">
                                        <option value="19K">ZONA 19K</option>
                                        <option value="20K">ZONA 20K</option>
                                        <option value="21K">ZONA 21K</option>
                                    </select>
                                </th>
                                <th style="width: 40px;"></th>
                            </tr>
                        </thead>
                        <tbody id="puntos-body">
                            @php
                                $puntos = old('puntos_medicion', $reporte->puntos_medicion ?? []);
                                if (is_string($puntos)) $puntos = json_decode($puntos, true) ?? [];
                                $hasPuntos = count($puntos) > 0;
                            @endphp
                            @forelse($puntos as $i => $p)
                            <tr class="fila-punto">
                                <td><input type="text" class="form-control form-control-sm" name="puntos_medicion[{{ $i }}][codigo]" value="{{ $p['codigo'] ?? '' }}"></td>
                                <td><input type="text" class="form-control form-control-sm" name="puntos_medicion[{{ $i }}][descripcion]" value="{{ $p['descripcion'] ?? '' }}"></td>
                                <td>
                                    <div class="d-flex flex-column gap-1" style="min-width: 280px;">
                                        <input type="hidden" name="puntos_medicion[{{ $i }}][zona]" value="{{ $p['zona'] ?? '19K' }}">
                                        <div class="d-flex gap-1 align-items-center">
                                            <select class="form-select form-select-sm" name="puntos_medicion[{{ $i }}][direccion1]" style="width: 100px;">
                                                <option value="N" {{ ($p['direccion1'] ?? 'N') == 'N' ? 'selected' : '' }}>N</option>
                                                <option value="S" {{ ($p['direccion1'] ?? '') == 'S' ? 'selected' : '' }}>S</option>
                                                <option value="E" {{ ($p['direccion1'] ?? '') == 'E' ? 'selected' : '' }}>E</option>
                                                <option value="O" {{ ($p['direccion1'] ?? '') == 'O' ? 'selected' : '' }}>O</option>
                                            </select>
                                            <input type="number" step="0.01" class="form-control form-control-sm" name="puntos_medicion[{{ $i }}][valor1]" value="{{ $p['valor1'] ?? $p['norte'] ?? '' }}" placeholder="Valor">
                                            <select class="form-select form-select-sm" name="puntos_medicion[{{ $i }}][direccion2]" style="width: 100px;">
                                                <option value="E" {{ ($p['direccion2'] ?? 'E') == 'E' ? 'selected' : '' }}>E</option>
                                                <option value="N" {{ ($p['direccion2'] ?? '') == 'N' ? 'selected' : '' }}>N</option>
                                                <option value="S" {{ ($p['direccion2'] ?? '') == 'S' ? 'selected' : '' }}>S</option>
                                                <option value="O" {{ ($p['direccion2'] ?? '') == 'O' ? 'selected' : '' }}>O</option>
                                            </select>
                                            <input type="number" step="0.01" class="form-control form-control-sm" name="puntos_medicion[{{ $i }}][valor2]" value="{{ $p['valor2'] ?? $p['este'] ?? '' }}" placeholder="Valor">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center"><button type="button" class="btn-eliminar-fila" onclick="this.closest('tr').remove()"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @empty
                            @for($pi = 0; $pi < $numMuestras; $pi++)
                            <tr class="fila-punto">
                                <td><input type="text" class="form-control form-control-sm" name="puntos_medicion[{{ $pi }}][codigo]" value="AI-{{ str_pad($pi + 1, 2, '0', STR_PAD_LEFT) }}"></td>
                                <td><input type="text" class="form-control form-control-sm" name="puntos_medicion[{{ $pi }}][descripcion]" placeholder="Ej: Área buzón de lavado"></td>
                                <td>
                                    <div class="d-flex flex-column gap-1" style="min-width: 280px;">
                                        <input type="hidden" name="puntos_medicion[{{ $pi }}][zona]" value="19K">
                                        <div class="d-flex gap-1 align-items-center">
                                            <select class="form-select form-select-sm" name="puntos_medicion[{{ $pi }}][direccion1]" style="width: 100px;">
                                                <option value="N" selected>N</option>
                                                <option value="S">S</option>
                                                <option value="E">E</option>
                                                <option value="O">O</option>
                                            </select>
                                            <input type="number" step="0.01" class="form-control form-control-sm" name="puntos_medicion[{{ $pi }}][valor1]" placeholder="Valor">
                                            <select class="form-select form-select-sm" name="puntos_medicion[{{ $pi }}][direccion2]" style="width: 100px;">
                                                <option value="E" selected>E</option>
                                                <option value="N">N</option>
                                                <option value="S">S</option>
                                                <option value="O">O</option>
                                            </select>
                                            <input type="number" step="0.01" class="form-control form-control-sm" name="puntos_medicion[{{ $pi }}][valor2]" placeholder="Valor">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center"><button type="button" class="btn-eliminar-fila" onclick="this.closest('tr').remove()"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @endfor
                            @if($numMuestras === 0)
                            <tr class="fila-punto">
                                <td><input type="text" class="form-control form-control-sm" name="puntos_medicion[0][codigo]" placeholder="Ej: PT-01"></td>
                                <td><input type="text" class="form-control form-control-sm" name="puntos_medicion[0][descripcion]" placeholder="Ej: Área buzón de lavado"></td>
                                <td>
                                    <div class="d-flex flex-column gap-1" style="min-width: 280px;">
                                        <input type="hidden" name="puntos_medicion[0][zona]" value="19K">
                                        <div class="d-flex gap-1 align-items-center">
                                            <select class="form-select form-select-sm" name="puntos_medicion[0][direccion1]" style="width: 100px;">
                                                <option value="N" selected>N</option>
                                                <option value="S">S</option>
                                                <option value="E">E</option>
                                                <option value="O">O</option>
                                            </select>
                                            <input type="number" step="0.01" class="form-control form-control-sm" name="puntos_medicion[0][valor1]" placeholder="Valor">
                                            <select class="form-select form-select-sm" name="puntos_medicion[0][direccion2]" style="width: 100px;">
                                                <option value="E" selected>E</option>
                                                <option value="N">N</option>
                                                <option value="S">S</option>
                                                <option value="O">O</option>
                                            </select>
                                            <input type="number" step="0.01" class="form-control form-control-sm" name="puntos_medicion[0][valor2]" placeholder="Valor">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center"><button type="button" class="btn-eliminar-fila" onclick="this.closest('tr').remove()"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @endif
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn-agregar-fila" onclick="agregarFilaPunto()" style="background-color: #28a745; color: white; border: none; border-radius: 30px; padding: 6px 18px; font-size: 0.85rem;">
                    <i class="fas fa-plus me-1"></i> Agregar fila
                </button>
            </div>
        </div>

        <!-- COMENTARIOS GENERALES -->
        <div class="card section-card general" style="border: 1px solid #ffc107; border-radius: 10px; margin-bottom: 1.5rem;">
            <div class="card-header" style="background-color: #ffc107; color: #000; font-weight: 600; border-radius: 10px 10px 0 0;">
                <i class="fas fa-sticky-note me-2"></i> Comentarios Generales
            </div>
            <div class="card-body">
                <textarea class="form-control @error('comentarios') is-invalid @enderror"
                          name="comentarios" rows="4" placeholder="Observaciones y comentarios técnicos generales...">{{ old('comentarios', $reporte->comentarios ?? '') }}</textarea>
                @error('comentarios')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>

        @include('reportes._firmas_publicar', ['reporte' => $reporte, 'proforma' => $proforma, 'categoria' => 'AIRE'])
    </form>
</div>
@endsection

@push('scripts')
<script>
    let idx = {{ $hasRa ? count(old('resultados_aire', $ra)) : max($numMuestras, 1) }};
    let idxPunto = {{ $hasPuntos ? count(old('puntos_medicion', $puntos)) : max($numMuestras, 1) }};
    function agregarFila() {
        const tbody = document.getElementById('aire-body');
        const tr = document.createElement('tr'); tr.className = 'fila-aire';
        const codigo = 'AI-' + String(idx + 1).padStart(2, '0');
        tr.innerHTML = `
            <td><input type="text" class="form-control form-control-sm" name="resultados_aire[${idx}][codigo]" value="${codigo}" readonly></td>
            <td><input type="text" class="form-control form-control-sm" name="resultados_aire[${idx}][parametro]" placeholder="Ej: PTS"></td>
            <td><input type="number" step="0.01" class="form-control form-control-sm" name="resultados_aire[${idx}][concentracion]"></td>
            <td><input type="text" class="form-control form-control-sm" name="resultados_aire[${idx}][unidad]" placeholder="Ej: µg/m³"></td>
            <td class="text-center"><button type="button" class="btn-eliminar-fila" onclick="this.closest('tr').remove()"><i class="fas fa-times"></i></button></td>`;
        tbody.appendChild(tr); idx++;
    }
    function actualizarZonas(valor) {
        document.querySelectorAll('#puntos-body input[name$="[zona]"]').forEach(function(el) {
            el.value = valor;
        });
    }
    function agregarFilaPunto() {
        const tbody = document.getElementById('puntos-body');
        const tr = document.createElement('tr'); tr.className = 'fila-punto';
        const zona = document.getElementById('zona-header').value;
        const codigo = 'AI-' + String(idxPunto + 1).padStart(2, '0');
        tr.innerHTML = `
            <td><input type="text" class="form-control form-control-sm" name="puntos_medicion[${idxPunto}][codigo]" value="${codigo}"></td>
            <td><input type="text" class="form-control form-control-sm" name="puntos_medicion[${idxPunto}][descripcion]" placeholder="Ej: Área buzón de lavado"></td>
            <td>
                <div class="d-flex flex-column gap-1" style="min-width: 280px;">
                    <input type="hidden" name="puntos_medicion[${idxPunto}][zona]" value="${zona}">
                    <div class="d-flex gap-1 align-items-center">
                        <select class="form-select form-select-sm" name="puntos_medicion[${idxPunto}][direccion1]" style="width: 100px;">
                            <option value="N">N</option><option value="S">S</option><option value="E">E</option><option value="O">O</option>
                        </select>
                        <input type="number" step="0.01" class="form-control form-control-sm" name="puntos_medicion[${idxPunto}][valor1]" placeholder="Valor">
                        <select class="form-select form-select-sm" name="puntos_medicion[${idxPunto}][direccion2]" style="width: 100px;">
                            <option value="E">E</option><option value="N">N</option><option value="S">S</option><option value="O">O</option>
                        </select>
                        <input type="number" step="0.01" class="form-control form-control-sm" name="puntos_medicion[${idxPunto}][valor2]" placeholder="Valor">
                    </div>
                </div>
            </td>
            <td class="text-center"><button type="button" class="btn-eliminar-fila" onclick="this.closest('tr').remove()"><i class="fas fa-times"></i></button></td>`;
        tbody.appendChild(tr); idxPunto++;
    }
</script>
@endpush
