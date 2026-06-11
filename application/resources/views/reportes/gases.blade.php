@extends('layouts.app')

@section('title', 'Reporte de Gases')

@push('styles')
<style>
    .table-dinamica th { background-color: #198754; color: #fff; font-size: 0.85rem; white-space: nowrap; }
    .btn-agregar-fila { background-color: #28a745; color: white; border: none; border-radius: 30px; padding: 6px 18px; font-size: 0.85rem; }
    .btn-agregar-fila:hover { background-color: #218838; }
    .btn-eliminar-fila { background: none; border: none; color: #dc3545; cursor: pointer; font-size: 1.1rem; }
    .btn-eliminar-fila:hover { color: #a71d2a; }
    .section-card { border-radius: 10px; margin-bottom: 1.5rem; }
    .section-card .card-header { font-weight: 600; border-radius: 10px 10px 0 0; }
    .section-card.gases .card-header { background-color: #198754; color: #fff; }
</style>
@endpush

@section('content')
<div class="container-main">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-flask" style="color: #198754;"></i> Reporte de Gases</h1>
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
        <input type="hidden" name="categoria" value="GASES">

        <!-- INFORMACIÓN GENERAL -->
        <div class="card section-card gases">
            <div class="card-header"><i class="fas fa-info-circle me-2"></i> Información General — Gases</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nombre del Cliente</label>
                        <input type="text" class="form-control" value="{{ $proforma->cliente->razon_social }}" disabled>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Código de Reporte</label>
                        <input type="text" class="form-control @error('codigo_reporte') is-invalid @enderror"
                               name="codigo_reporte" value="{{ old('codigo_reporte', $reporte->codigo_reporte ?? $proforma->codigo . '-R') }}"
                               placeholder="Ej: {{ $proforma->codigo }}-R01">
                        @error('codigo_reporte')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha de Emisión</label>
                        <input type="date" class="form-control @error('fecha_emision') is-invalid @enderror"
                               name="fecha_emision" value="{{ old('fecha_emision', optional(optional($reporte)->fecha_emision)->format('Y-m-d') ?? date('Y-m-d')) }}">
                        @error('fecha_emision')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha de Medición</label>
                        <input type="date" class="form-control @error('fecha_medicion') is-invalid @enderror"
                               name="fecha_medicion" value="{{ old('fecha_medicion', optional(optional($reporte)->fecha_medicion)->format('Y-m-d') ?? '') }}">
                        @error('fecha_medicion')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Periodo de Medición</label>
                        <input type="text" class="form-control @error('periodo_medicion') is-invalid @enderror"
                               name="periodo_medicion" value="{{ old('periodo_medicion', $reporte->periodo_medicion ?? '') }}" placeholder="Ej: Diurno, Nocturno, 24 horas">
                        @error('periodo_medicion')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Medición Efectuada por</label>
                        <input type="text" class="form-control @error('medicion_efectuada_por') is-invalid @enderror"
                               name="medicion_efectuada_por" value="{{ old('medicion_efectuada_por', $reporte->medicion_efectuada_por ?? '') }}" placeholder="Nombre del responsable">
                        @error('medicion_efectuada_por')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Equipo Usado</label>
                        <input type="text" class="form-control @error('equipo_usado') is-invalid @enderror"
                               name="equipo_usado" value="{{ old('equipo_usado', $reporte->equipo_usado ?? '') }}" placeholder="Ej: Detector de gases">
                        @error('equipo_usado')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- RESULTADOS -->
        @php
            $parametrosGases = $proforma->parametros()->where('categoria', 'GASES')->get();

            $rg = old('resultados_gases', $reporte->resultados_gases ?? []);
            if (is_string($rg)) $rg = json_decode($rg, true) ?? [];

            // backward compat: old format {gas, concentracion, unidad} → {PARAM: {valor, unidad}}
            $converted = false;
            foreach ($rg as &$row) {
                if (isset($row['gas']) && ! isset($row[$row['gas']])) {
                    $gasName = $row['gas'];
                    $row[$gasName] = [
                        'valor' => $row['concentracion'] ?? '',
                        'unidad' => $row['unidad'] ?? '',
                    ];
                    $converted = true;
                }
                // ensure all params exist in row
                foreach ($parametrosGases as $p) {
                    if (! isset($row[$p->nombre])) {
                        $row[$p->nombre] = ['valor' => '', 'unidad' => $p->unidad_default ?? ''];
                    }
                }
            }
            unset($row);
            if ($converted) {
                // remove old keys
                foreach ($rg as &$row) {
                    unset($row['gas'], $row['concentracion'], $row['unidad']);
                }
                unset($row);
            }

            $hasRg = count($rg) > 0;
            $numMuestras = count($rg);
            if ($numMuestras === 0) {
                $numMuestras = $parametrosGases->count();
            }
        @endphp

        <div class="card section-card gases">
            <div class="card-header"><i class="fas fa-table me-2"></i> RESULTADOS DE MEDICIÓN DE GASES</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-dinamica" id="tabla-gases">
                        <thead>
                            @php
                                $unidadPorParam = [];
                                foreach ($parametrosGases as $p) {
                                    $unidadPorParam[$p->nombre] = old("resultados_unidades.{$p->nombre}",
                                        $hasRg
                                            ? ($rg[0][$p->nombre]['unidad'] ?? $p->unidad_default ?? '')
                                            : ($p->unidad_default ?? '')
                                    );
                                }
                            @endphp
                            <tr>
                                <th rowspan="2" style="width: 10%;">CÓDIGO</th>
                                <th colspan="2" style="width: 20%;">PERIODO DE MEDICIÓN</th>
                                @foreach($parametrosGases as $p)
                                <th rowspan="2" style="text-align: center;">
                                    {{ $p->nombre_completo ?? $p->nombre }}<br>
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
                            <tr>
                                <th style="width: 10%;">Hora Inicial</th>
                                <th style="width: 10%;">Hora Final</th>
                            </tr>
                        </thead>
                        <tbody id="gases-body">
                            @forelse($rg as $i => $r)
                            <tr class="fila-gases">
                                <td><input type="text" class="form-control form-control-sm" name="resultados_gases[{{ $i }}][codigo]" value="{{ $r['codigo'] ?? '' }}" readonly></td>
                                <td><input type="time" class="form-control form-control-sm" name="resultados_gases[{{ $i }}][hora_inicial]" value="{{ $r['hora_inicial'] ?? '' }}"></td>
                                <td><input type="time" class="form-control form-control-sm" name="resultados_gases[{{ $i }}][hora_final]" value="{{ $r['hora_final'] ?? '' }}"></td>
                                @foreach($parametrosGases as $p)
                                <td><input type="number" step="0.01" class="form-control form-control-sm" name="resultados_gases[{{ $i }}][{{ $p->nombre }}][valor]" value="{{ $r[$p->nombre]['valor'] ?? '' }}" placeholder="{{ $p->nombre_completo ?? $p->nombre }}"></td>
                                @endforeach
                                <td class="text-center"><button type="button" class="btn-eliminar-fila" onclick="this.closest('tr').remove()"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @empty
                            @for($mi = 0; $mi < $numMuestras; $mi++)
                            <tr class="fila-gases">
                                <td><input type="text" class="form-control form-control-sm" name="resultados_gases[{{ $mi }}][codigo]" value="GS-{{ str_pad($mi + 1, 2, '0', STR_PAD_LEFT) }}" readonly></td>
                                <td><input type="time" class="form-control form-control-sm" name="resultados_gases[{{ $mi }}][hora_inicial]"></td>
                                <td><input type="time" class="form-control form-control-sm" name="resultados_gases[{{ $mi }}][hora_final]"></td>
                                @foreach($parametrosGases as $p)
                                <td><input type="number" step="0.01" class="form-control form-control-sm" name="resultados_gases[{{ $mi }}][{{ $p->nombre }}][valor]" placeholder="{{ $p->nombre_completo ?? $p->nombre }}"></td>
                                @endforeach
                                <td class="text-center"><button type="button" class="btn-eliminar-fila" onclick="this.closest('tr').remove()"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @endfor
                            @if($numMuestras === 0)
                            <tr class="fila-gases">
                                <td><input type="text" class="form-control form-control-sm" name="resultados_gases[0][codigo]" placeholder="Ej: GS-01" readonly></td>
                                <td><input type="time" class="form-control form-control-sm" name="resultados_gases[0][hora_inicial]"></td>
                                <td><input type="time" class="form-control form-control-sm" name="resultados_gases[0][hora_final]"></td>
                                @foreach($parametrosGases as $p)
                                <td><input type="number" step="0.01" class="form-control form-control-sm" name="resultados_gases[0][{{ $p->nombre }}][valor]" placeholder="{{ $p->nombre_completo ?? $p->nombre }}"></td>
                                @endforeach
                                <td class="text-center"><button type="button" class="btn-eliminar-fila" onclick="this.closest('tr').remove()"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @endif
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
                                <td><input type="text" class="form-control form-control-sm" name="puntos_medicion[{{ $pi }}][codigo]" value="GS-{{ str_pad($pi + 1, 2, '0', STR_PAD_LEFT) }}"></td>
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

        @include('reportes._firmas_publicar', ['reporte' => $reporte, 'proforma' => $proforma, 'categoria' => 'GASES'])
    </form>
</div>
@endsection

@push('scripts')
<script>
    const paramsGases = @json($parametrosGases->map(fn($p) => ['nombre' => $p->nombre, 'nombre_completo' => $p->nombre_completo, 'unidad_default' => $p->unidad_default]));
    let idx = {{ $hasRg ? count(old('resultados_gases', $rg)) : max($numMuestras, 1) }};
    let idxPunto = {{ $hasPuntos ? count(old('puntos_medicion', $puntos)) : max($numMuestras, 1) }};
    function agregarFila() {
        const tbody = document.getElementById('gases-body');
        const tr = document.createElement('tr'); tr.className = 'fila-gases';
        const codigo = 'GS-' + String(idx + 1).padStart(2, '0');
        let cols = `
            <td><input type="text" class="form-control form-control-sm" name="resultados_gases[${idx}][codigo]" value="${codigo}" readonly></td>
            <td><input type="time" class="form-control form-control-sm" name="resultados_gases[${idx}][hora_inicial]"></td>
            <td><input type="time" class="form-control form-control-sm" name="resultados_gases[${idx}][hora_final]"></td>`;
        paramsGases.forEach(p => {
            cols += `<td><input type="number" step="0.01" class="form-control form-control-sm" name="resultados_gases[${idx}][${p.nombre}][valor]" placeholder="${p.nombre_completo || p.nombre}"></td>`;
        });
        cols += `<td class="text-center"><button type="button" class="btn-eliminar-fila" onclick="this.closest('tr').remove()"><i class="fas fa-times"></i></button></td>`;
        tr.innerHTML = cols;
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
        const codigo = 'GS-' + String(idxPunto + 1).padStart(2, '0');
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
