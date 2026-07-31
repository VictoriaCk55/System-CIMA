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

    <style>
        .view-mode input:not([type="hidden"]):not(.btn):not([type="submit"]),
        .view-mode select,
        .view-mode textarea {
            pointer-events: none;
            background-color: #f0f0f0 !important;
            opacity: 0.85;
        }
        .view-mode .btn-agregar-fila,
        .view-mode .btn-eliminar-fila {
            display: none !important;
        }
    </style>

    <div id="form-wrapper" class="{{ $reporte && $reporte->exists ? 'view-mode' : '' }}">
    <form action="{{ route('reportes.ambiental.store', $proforma) }}" method="POST">
        @csrf
        <input type="hidden" name="categoria" value="AIRE">
        @php $info = $reporte->info('AIRE'); @endphp

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
                               name="codigo_reporte" value="{{ old('codigo_reporte', 'UIA-REP-PRT-'.last(explode('-', $proforma->codigo)).'/'.now()->format('y')) }}" readonly>
                        @error('codigo_reporte')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha de Emisión de Reporte</label>
                        <input type="date" class="form-control @error('fecha_emision') is-invalid @enderror"
                               name="fecha_emision" value="{{ old('fecha_emision', $info['fecha_emision'] ?? date('Y-m-d')) }}">
                        @error('fecha_emision')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha de Inicio de Muestreo</label>
                        <input type="date" class="form-control @error('fecha_inicio_muestreo') is-invalid @enderror"
                               name="fecha_inicio_muestreo" value="{{ old('fecha_inicio_muestreo', $info['fecha_inicio_muestreo'] ?? '') }}">
                        @error('fecha_inicio_muestreo')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha Final de Muestreo</label>
                        <input type="date" class="form-control @error('fecha_fin_muestreo') is-invalid @enderror"
                               name="fecha_fin_muestreo" value="{{ old('fecha_fin_muestreo', $info['fecha_fin_muestreo'] ?? '') }}">
                        @error('fecha_fin_muestreo')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipo de Muestreo</label>
                        <input type="text" class="form-control @error('tipo_muestreo') is-invalid @enderror"
                               name="tipo_muestreo" value="{{ old('tipo_muestreo', $info['tipo_muestreo'] ?? '') }}"
                               placeholder="Ej: Puntual, Continuo, Compuesto">
                        @error('tipo_muestreo')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Medición Efectuada por</label>
                        <input type="text" class="form-control @error('medicion_efectuada_por') is-invalid @enderror"
                               name="medicion_efectuada_por" value="{{ old('medicion_efectuada_por', $info['medicion_efectuada_por'] ?? '') }}" placeholder="Nombre del responsable">
                        @error('medicion_efectuada_por')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Equipo Usado</label>
                        <input type="text" class="form-control @error('equipo_usado') is-invalid @enderror"
                               name="equipo_usado" value="{{ old('equipo_usado', $info['equipo_usado'] ?? '') }}" placeholder="Ej: Bomba de muestreo TAS">
                        @error('equipo_usado')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Condiciones de Muestreo</label>
                        <textarea class="form-control @error('condiciones_muestreo') is-invalid @enderror"
                                  name="condiciones_muestreo" rows="3" placeholder="Ej: Temperatura, presión, condiciones climáticas...">{{ old('condiciones_muestreo', $info['condiciones_muestreo'] ?? '') }}</textarea>
                        @error('condiciones_muestreo')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Condiciones de Reporte de Resultados</label>
                        <textarea class="form-control @error('condiciones_reporte') is-invalid @enderror"
                                  name="condiciones_reporte" rows="3" placeholder="Ej: Base seca, condiciones normales...">{{ old('condiciones_reporte', $info['condiciones_reporte'] ?? '') }}</textarea>
                        @error('condiciones_reporte')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- RESULTADOS -->
        @php
            $parametrosAire = $proforma->parametros()->where('categoria', 'AIRE')->get();

            $ra = old('resultados_aire', $reporte->resultados_aire ?? []);
            if (is_string($ra)) $ra = json_decode($ra, true) ?? [];

            // backward compat: old format {parametro, concentracion, unidad} → new {periodo, PARAM: {valor}}
            $converted = false;
            foreach ($ra as &$row) {
                if (isset($row['parametro'])) {
                    $row['periodo'] = $row['parametro'];
                    $converted = true;
                }
                if (isset($row['concentracion']) && $parametrosAire->count() > 0) {
                    $params = $parametrosAire->values();
                    if (isset($params[0])) {
                        $row[$params[0]->nombre] = ['valor' => $row['concentracion']];
                    }
                    if (isset($params[1]) && isset($row['unidad'])) {
                        $row[$params[1]->nombre] = ['valor' => $row['unidad']];
                    }
                }
                // ensure all params exist in row
                foreach ($parametrosAire as $p) {
                    if (!isset($row[$p->nombre])) {
                        $row[$p->nombre] = ['valor' => ''];
                    }
                }
            }
            unset($row);
            if ($converted) {
                foreach ($ra as &$row) {
                    unset($row['parametro'], $row['concentracion'], $row['unidad'], $row['metodo']);
                }
                unset($row);
            }

            $hasRa = count($ra) > 0;
            $numMuestras = count($ra);
            if ($numMuestras === 0) {
                $aireParam = $proforma->parametros()->where('categoria', 'AIRE')->first();
                $numMuestras = $aireParam ? ($aireParam->pivot->cantidad_muestras ?? 1) : 1;
            }

            $unidadPorParam = [];
            foreach ($parametrosAire as $p) {
                $unidadPorParam[$p->nombre] = old("resultados_unidades.{$p->nombre}",
                    $hasRa
                        ? ($ra[0][$p->nombre]['unidad'] ?? $p->unidad_default ?? '')
                        : ($p->unidad_default ?? '')
                );
            }
        @endphp

        <div class="card section-card aire">
            <div class="card-header"><i class="fas fa-table me-2"></i> RESULTADOS DE MEDICIÓN DE AIRE</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-dinamica" id="tabla-aire">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 10%;">CÓDIGO</th>
                                <th rowspan="2" style="width: 20%;">PERIODO DE MUESTREO</th>
                                @foreach($parametrosAire as $p)
                                <th rowspan="2" style="text-align: center; vertical-align: middle;">
                                    {{ $p->nombre_completo ?? $p->nombre }} - {{ $p->nombre }}<br>
                                    <select class="form-select form-select-sm mx-auto" name="resultados_unidades[{{ $p->nombre }}]" style="width: 90px; font-weight: normal; font-size: 0.75rem;">
                                        <option value="">Unidad</option>
                                        <option value="ppm" {{ ($unidadPorParam[$p->nombre] ?? '') == 'ppm' ? 'selected' : '' }}>ppm</option>
                                        <option value="%" {{ ($unidadPorParam[$p->nombre] ?? '') == '%' ? 'selected' : '' }}>%</option>
                                        <option value="mg/m³" {{ ($unidadPorParam[$p->nombre] ?? '') == 'mg/m³' ? 'selected' : '' }}>mg/m³</option>
                                        <option value="µg/m³" {{ ($unidadPorParam[$p->nombre] ?? '') == 'µg/m³' ? 'selected' : '' }}>µg/m³</option>
                                        <option value="dB(A)" {{ ($unidadPorParam[$p->nombre] ?? '') == 'dB(A)' ? 'selected' : '' }}>dB(A)</option>
                                        <option value="dB" {{ ($unidadPorParam[$p->nombre] ?? '') == 'dB' ? 'selected' : '' }}>dB</option>
                                    </select>
                                </th>
                                @endforeach
                                <th rowspan="2" style="width: 40px;"></th>
                            </tr>
                        </thead>
                        <tbody id="aire-body">
                            @forelse($ra as $i => $r)
                            <tr class="fila-aire">
                                <td><input type="text" class="form-control form-control-sm" name="resultados_aire[{{ $i }}][codigo]" value="{{ $r['codigo'] ?? '' }}" readonly></td>
                                <td><input type="text" class="form-control form-control-sm" name="resultados_aire[{{ $i }}][periodo]" value="{{ $r['periodo'] ?? '' }}" placeholder="Ej: Diurno"></td>
                                @foreach($parametrosAire as $p)
                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm" name="resultados_aire[{{ $i }}][{{ $p->nombre }}][valor]" value="{{ $r[$p->nombre]['valor'] ?? '' }}" placeholder="{{ $p->nombre_completo ?? $p->nombre }}"></td>
                                @endforeach
                                <td class="text-center"><button type="button" class="btn-eliminar-fila" onclick="this.closest('tr').remove()"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @empty
                            @for($mi = 0; $mi < $numMuestras; $mi++)
                            <tr class="fila-aire">
                                <td><input type="text" class="form-control form-control-sm" name="resultados_aire[{{ $mi }}][codigo]" value="AI-{{ str_pad($mi + 1, 2, '0', STR_PAD_LEFT) }}" readonly></td>
                                <td><input type="text" class="form-control form-control-sm" name="resultados_aire[{{ $mi }}][periodo]" placeholder="Ej: Diurno"></td>
                                @foreach($parametrosAire as $p)
                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm" name="resultados_aire[{{ $mi }}][{{ $p->nombre }}][valor]" placeholder="{{ $p->nombre_completo ?? $p->nombre }}"></td>
                                @endforeach
                                <td class="text-center"><button type="button" class="btn-eliminar-fila" onclick="this.closest('tr').remove()"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @endfor
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn-agregar-fila" onclick="agregarFila()">
                    <i class="fas fa-plus me-1"></i> Agregar medición
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
                    @php
                        $pmZonaRaw = old('puntos_medicion', $reporte->puntos_medicion ?? []);
                        if (is_string($pmZonaRaw)) $pmZonaRaw = json_decode($pmZonaRaw, true) ?? [];
                        $zonaActual = '19K';
                        foreach ($pmZonaRaw as $pt) {
                            if ((!isset($pt['categoria']) || $pt['categoria'] === 'AIRE') && !empty($pt['zona'])) {
                                $zonaActual = $pt['zona'];
                                break;
                            }
                        }
                    @endphp
                    <table class="table table-bordered" id="tabla-puntos" style="border-color: #ffc107;">
                        <thead>
                            <tr>
                                <th style="width: 12%;">Código</th>
                                <th style="width: 33%;">Descripción del Punto</th>
                                <th class="text-center" style="width: 40%;">UBICACIÓN
                                    <select class="form-select form-select-sm d-block mx-auto mt-1" id="zona-header" style="width: 140px;" onchange="actualizarZonas(this.value)">
                                        <option value="19K" {{ $zonaActual === '19K' ? 'selected' : '' }}>ZONA 19K</option>
                                        <option value="20K" {{ $zonaActual === '20K' ? 'selected' : '' }}>ZONA 20K</option>
                                        <option value="21K" {{ $zonaActual === '21K' ? 'selected' : '' }}>ZONA 21K</option>
                                    </select>
                                </th>
                                <th style="width: 40px;"></th>
                            </tr>
                        </thead>
                        <tbody id="puntos-body">
                            @php
                                $pmRaw = old('puntos_medicion', $reporte->puntos_medicion ?? []);
                                if (is_string($pmRaw)) $pmRaw = json_decode($pmRaw, true) ?? [];
                                $puntos = array_values(array_filter($pmRaw, fn($pt) => (!isset($pt['categoria']) || $pt['categoria'] === 'AIRE') && (!empty($pt['descripcion']) || !empty($pt['valor1']) || !empty($pt['valor2']))));
                                $puntosCount = max(count($ra), count($puntos));
                                if ($puntosCount === 0) { $puntosCount = $numMuestras; }
                            @endphp
                            @for($pi = 0; $pi < $puntosCount; $pi++)
                            @php $p = $puntos[$pi] ?? []; @endphp
                            @php $r = $ra[$pi] ?? []; @endphp
                            @php $codigo = $r['codigo'] ?? $p['codigo'] ?? (count($ra) === 0 && count($puntos) === 0 ? 'AI-' . str_pad($pi + 1, 2, '0', STR_PAD_LEFT) : ''); @endphp
                            <tr class="fila-punto">
                                <td><input type="text" class="form-control form-control-sm" name="puntos_medicion[{{ $pi }}][codigo]" value="{{ $codigo }}"></td>
                                <td><input type="text" class="form-control form-control-sm" name="puntos_medicion[{{ $pi }}][descripcion]" value="{{ $p['descripcion'] ?? '' }}" placeholder="Ej: Área buzón de lavado"></td>
                                <td>
                                    <div class="d-flex flex-column gap-1" style="min-width: 280px;">
                                        <input type="hidden" name="puntos_medicion[{{ $pi }}][zona]" value="{{ $p['zona'] ?? '19K' }}">
                                        <div class="d-flex gap-1 align-items-center">
                                            <select class="form-select form-select-sm" name="puntos_medicion[{{ $pi }}][direccion1]" style="width: 100px;">
                                                <option value="N" {{ ($p['direccion1'] ?? 'N') == 'N' ? 'selected' : '' }}>N</option>
                                                <option value="S" {{ ($p['direccion1'] ?? '') == 'S' ? 'selected' : '' }}>S</option>
                                                <option value="E" {{ ($p['direccion1'] ?? '') == 'E' ? 'selected' : '' }}>E</option>
                                                <option value="O" {{ ($p['direccion1'] ?? '') == 'O' ? 'selected' : '' }}>O</option>
                                            </select>
                                            <input type="text" inputmode="decimal" class="form-control form-control-sm" name="puntos_medicion[{{ $pi }}][valor1]" value="{{ $p['valor1'] ?? $p['norte'] ?? '' }}" placeholder="Valor">
                                            <select class="form-select form-select-sm" name="puntos_medicion[{{ $pi }}][direccion2]" style="width: 100px;">
                                                <option value="E" {{ ($p['direccion2'] ?? 'E') == 'E' ? 'selected' : '' }}>E</option>
                                                <option value="N" {{ ($p['direccion2'] ?? '') == 'N' ? 'selected' : '' }}>N</option>
                                                <option value="S" {{ ($p['direccion2'] ?? '') == 'S' ? 'selected' : '' }}>S</option>
                                                <option value="O" {{ ($p['direccion2'] ?? '') == 'O' ? 'selected' : '' }}>O</option>
                                            </select>
                                            <input type="text" inputmode="decimal" class="form-control form-control-sm" name="puntos_medicion[{{ $pi }}][valor2]" value="{{ $p['valor2'] ?? $p['este'] ?? '' }}" placeholder="Valor">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center"><button type="button" class="btn-eliminar-fila" onclick="this.closest('tr').remove()"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @endfor
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
                <textarea class="form-control @error('observaciones_aire') is-invalid @enderror"
                          name="observaciones_aire" rows="4" placeholder="Observaciones y comentarios técnicos generales...">{{ old('observaciones_aire', $reporte->observaciones_aire ?? $reporte->comentarios ?? '') }}</textarea>
                @error('observaciones_aire')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>

        @include('reportes._firmas_publicar', ['reporte' => $reporte, 'proforma' => $proforma, 'categoria' => 'AIRE'])
    </form>
</div>
</div>
@endsection

@push('scripts')
<script>
    const paramsAire = @json($parametrosAire->map(fn($p) => ['nombre' => $p->nombre, 'nombre_completo' => $p->nombre_completo]));
    let idx = {{ max($hasRa ? count(old('resultados_aire', $ra)) : 0, $puntosCount) ?: max($numMuestras, 1) }};
    function agregarFila() {
        const tbody = document.getElementById('aire-body');
        const tr = document.createElement('tr'); tr.className = 'fila-aire';
        const codigo = 'AI-' + String(idx + 1).padStart(2, '0');
        let cols = `
            <td><input type="text" class="form-control form-control-sm" name="resultados_aire[${idx}][codigo]" value="${codigo}" readonly></td>
            <td><input type="text" class="form-control form-control-sm" name="resultados_aire[${idx}][periodo]" placeholder="Ej: Diurno"></td>`;
        paramsAire.forEach(p => {
            cols += `<td><input type="text" inputmode="decimal" class="form-control form-control-sm" name="resultados_aire[${idx}][${p.nombre}][valor]" placeholder="${p.nombre_completo || p.nombre}"></td>`;
        });
        cols += `<td class="text-center"><button type="button" class="btn-eliminar-fila" onclick="this.closest('tr').remove()"><i class="fas fa-times"></i></button></td>`;
        tr.innerHTML = cols;
        tbody.appendChild(tr);
        const tbP = document.getElementById('puntos-body');
        const trP = document.createElement('tr'); trP.className = 'fila-punto';
        const zona = document.getElementById('zona-header').value;
        trP.innerHTML = `
            <td><input type="text" class="form-control form-control-sm" name="puntos_medicion[${idx}][codigo]" value="${codigo}"></td>
            <td><input type="text" class="form-control form-control-sm" name="puntos_medicion[${idx}][descripcion]" placeholder="Ej: Área buzón de lavado"></td>
            <td>
                <div class="d-flex flex-column gap-1" style="min-width: 280px;">
                    <input type="hidden" name="puntos_medicion[${idx}][zona]" value="${zona}">
                    <div class="d-flex gap-1 align-items-center">
                        <select class="form-select form-select-sm" name="puntos_medicion[${idx}][direccion1]" style="width: 100px;">
                            <option value="N">N</option><option value="S">S</option><option value="E">E</option><option value="O">O</option>
                        </select>
                        <input type="text" inputmode="decimal" class="form-control form-control-sm" name="puntos_medicion[${idx}][valor1]" placeholder="Valor">
                        <select class="form-select form-select-sm" name="puntos_medicion[${idx}][direccion2]" style="width: 100px;">
                            <option value="E">E</option><option value="N">N</option><option value="S">S</option><option value="O">O</option>
                        </select>
                        <input type="text" inputmode="decimal" class="form-control form-control-sm" name="puntos_medicion[${idx}][valor2]" placeholder="Valor">
                    </div>
                </div>
            </td>
            <td class="text-center"><button type="button" class="btn-eliminar-fila" onclick="this.closest('tr').remove()"><i class="fas fa-times"></i></button></td>`;
        tbP.appendChild(trP); idx++;
    }
    function actualizarZonas(valor) {
        document.querySelectorAll('#puntos-body input[name$="[zona]"]').forEach(function(el) {
            el.value = valor;
        });
    }
    const agregarFilaPunto = agregarFila;

    const zonaHeaderSel = document.getElementById('zona-header');
    if (zonaHeaderSel) { actualizarZonas(zonaHeaderSel.value); }
</script>
@endpush
