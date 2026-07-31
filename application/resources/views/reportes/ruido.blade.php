@extends('layouts.app')

@section('title', 'Reporte de Ruido')

@push('styles')
<style>
    .table-dinamica th { background-color: #fd7e14; color: #fff; font-size: 0.85rem; white-space: nowrap; }
    .btn-agregar-fila { background-color: #28a745; color: white; border: none; border-radius: 30px; padding: 6px 18px; font-size: 0.85rem; }
    .btn-agregar-fila:hover { background-color: #218838; }
    .btn-eliminar-fila { background: none; border: none; color: #dc3545; cursor: pointer; font-size: 1.1rem; }
    .btn-eliminar-fila:hover { color: #a71d2a; }
    .section-card { border-radius: 10px; margin-bottom: 1.5rem; }
    .section-card .card-header { font-weight: 600; border-radius: 10px 10px 0 0; }
    .section-card.ruido .card-header { background-color: #fd7e14; color: #fff; }
</style>
@endpush

@section('content')
<div class="container-main">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-volume-up" style="color: #fd7e14;"></i> Reporte de Ruido</h1>
                <p class="page-subtitle">Proforma: <strong>{{ $proforma->codigo }}</strong> — {{ $proforma->cliente->razon_social }}</p>
            </div>
            <a href="{{ route('reportes.ambiental.index', $proforma) }}" class="btn btn-outline-secondary" style="border-radius: 30px; padding: 8px 20px;">
                <i class="fas fa-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>

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

    <div id="form-wrapper" class="{{ $reporte && $reporte->exists ? 'view-mode' : '' }}">
    <form action="{{ route('reportes.ambiental.store', $proforma) }}" method="POST">
        @csrf
        <input type="hidden" name="categoria" value="RUIDO">
        @php $info = $reporte->info('RUIDO'); @endphp

        <!-- INFORMACIÓN GENERAL -->
        <div class="card section-card ruido">
            <div class="card-header"><i class="fas fa-info-circle me-2"></i> INFORMACIÓN GENERAL — RUIDO</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nombre del Cliente</label>
                        <input type="text" class="form-control" value="{{ $proforma->cliente->razon_social }}" disabled>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Código de Reporte</label>
                        <input type="text" class="form-control @error('codigo_reporte') is-invalid @enderror"
                               name="codigo_reporte" value="{{ old('codigo_reporte', 'UIA-REP-'.(str_contains($info['subtipo_ruido'] ?? '', 'INDUSTRIAL') ? 'RUIND' : 'RUAM').'-'.last(explode('-', $proforma->codigo)).'/'.now()->format('y')) }}" readonly>
                        @error('codigo_reporte')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha de Emisión</label>
                        <input type="date" class="form-control @error('fecha_emision') is-invalid @enderror"
                               name="fecha_emision" value="{{ old('fecha_emision', $info['fecha_emision'] ?? date('Y-m-d')) }}">
                        @error('fecha_emision')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha de Medición</label>
                        <input type="date" class="form-control @error('fecha_medicion') is-invalid @enderror"
                               name="fecha_medicion" value="{{ old('fecha_medicion', $info['fecha_medicion'] ?? '') }}">
                        @error('fecha_medicion')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Periodo de Medición</label>
                        <input type="text" class="form-control @error('periodo_medicion') is-invalid @enderror"
                               name="periodo_medicion" value="{{ old('periodo_medicion', $info['periodo_medicion'] ?? '') }}" placeholder="Ej: Diurno, Nocturno, 24 horas">
                        @error('periodo_medicion')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Medición Efectuada por</label>
                        <input type="text" class="form-control @error('medicion_efectuada_por') is-invalid @enderror"
                               name="medicion_efectuada_por" value="{{ old('medicion_efectuada_por', $info['medicion_efectuada_por'] ?? '') }}" placeholder="Nombre del responsable">
                        @error('medicion_efectuada_por')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Equipo Usado</label>
                        <input type="text" class="form-control @error('equipo_usado') is-invalid @enderror"
                               name="equipo_usado" value="{{ old('equipo_usado', $info['equipo_usado'] ?? '') }}" placeholder="Ej: Sonómetro">
                        @error('equipo_usado')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipo de Medición</label>
                        <input type="text" class="form-control @error('subtipo_ruido') is-invalid @enderror"
                               name="subtipo_ruido" value="{{ old('subtipo_ruido', $info['subtipo_ruido'] ?? '') }}"
                               placeholder="Ej: Ruido Ambiental, Ruido Industrial">
                        @error('subtipo_ruido')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- RESULTADOS -->
        <div class="card section-card ruido">
            <div class="card-header"><i class="fas fa-table me-2"></i> RESULTADOS DE MEDICIÓN DEL NPS</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-dinamica" id="tabla-ruido">
                        <thead>
                            <tr>
                                <th style="width: 15%;">Código</th>
                                <th style="width: 15%;">Hora Inicial</th>
                                <th style="width: 15%;">Hora Final</th>
                                <th style="width: 15%;">Tipo de Ruido</th>
                                <th style="width: 12%;">MAXIMO (Lmáx.)<br>
                                    <select class="form-select form-select-sm mx-auto" name="resultados_unidad_ruido[lmax]" style="width: 80px; font-weight: normal; font-size: 0.75rem;">
                                        <option value="dB" {{ old('resultados_unidad_ruido.lmax', $reporte->unidad_ruido['lmax'] ?? 'dB') == 'dB' ? 'selected' : '' }}>dB</option>
                                        <option value="dB(A)" {{ old('resultados_unidad_ruido.lmax', $reporte->unidad_ruido['lmax'] ?? '') == 'dB(A)' ? 'selected' : '' }}>dB(A)</option>
                                    </select>
                                </th>
                                <th style="width: 12%;">MÍNIMO (Lmín.)<br>
                                    <select class="form-select form-select-sm mx-auto" name="resultados_unidad_ruido[lmin]" style="width: 80px; font-weight: normal; font-size: 0.75rem;">
                                        <option value="dB" {{ old('resultados_unidad_ruido.lmin', $reporte->unidad_ruido['lmin'] ?? 'dB') == 'dB' ? 'selected' : '' }}>dB</option>
                                        <option value="dB(A)" {{ old('resultados_unidad_ruido.lmin', $reporte->unidad_ruido['lmin'] ?? '') == 'dB(A)' ? 'selected' : '' }}>dB(A)</option>
                                    </select>
                                </th>
                                <th style="width: 12%;">EQUIVALENTES (Leq)<br>
                                    <select class="form-select form-select-sm mx-auto" name="resultados_unidad_ruido[leq]" style="width: 80px; font-weight: normal; font-size: 0.75rem;">
                                        <option value="dB" {{ old('resultados_unidad_ruido.leq', $reporte->unidad_ruido['leq'] ?? 'dB') == 'dB' ? 'selected' : '' }}>dB</option>
                                        <option value="dB(A)" {{ old('resultados_unidad_ruido.leq', $reporte->unidad_ruido['leq'] ?? '') == 'dB(A)' ? 'selected' : '' }}>dB(A)</option>
                                    </select>
                                </th>
                                <th style="width: 40px;"></th>
                            </tr>
                        </thead>
                        <tbody id="ruido-body">
                            @php
                                $pmRaw2 = old('puntos_medicion', $reporte->puntos_medicion ?? []);
                                if (is_string($pmRaw2)) $pmRaw2 = json_decode($pmRaw2, true) ?? [];
                                $puntosRuido = array_values(array_filter($pmRaw2, fn($pt) => !isset($pt['categoria']) || $pt['categoria'] === 'RUIDO'));
                                $numMuestras = count($puntosRuido);
                                if ($numMuestras === 0) {
                                    $numMuestras = $proforma->parametros()->where('categoria', 'RUIDO')->count();
                                }

                                $rr = old('resultados_ruido', $reporte->resultados_ruido ?? []);
                                if (is_string($rr)) $rr = json_decode($rr, true) ?? [];
                                $hasRr = count($rr) > 0;
                            @endphp
                            @forelse($rr as $i => $r)
                            <tr class="fila-ruido">
                                <td><input type="text" class="form-control form-control-sm" name="resultados_ruido[{{ $i }}][codigo]" value="{{ $r['codigo'] ?? '' }}" readonly></td>
                                <td><input type="time" class="form-control form-control-sm" name="resultados_ruido[{{ $i }}][hora_inicial]" value="{{ $r['hora_inicial'] ?? '' }}"></td>
                                <td><input type="time" class="form-control form-control-sm" name="resultados_ruido[{{ $i }}][hora_final]" value="{{ $r['hora_final'] ?? '' }}"></td>
                                <td><input type="text" class="form-control form-control-sm" name="resultados_ruido[{{ $i }}][tipo_ruido]" value="{{ $r['tipo_ruido'] ?? '' }}" placeholder="AMB/IND"></td>
                                <td><input type="number" step="0.1" class="form-control form-control-sm" name="resultados_ruido[{{ $i }}][lmax]" value="{{ $r['lmax'] ?? '' }}"></td>
                                <td><input type="number" step="0.1" class="form-control form-control-sm" name="resultados_ruido[{{ $i }}][lmin]" value="{{ $r['lmin'] ?? '' }}"></td>
                                <td><input type="number" step="0.1" class="form-control form-control-sm" name="resultados_ruido[{{ $i }}][leq]" value="{{ $r['leq'] ?? '' }}"></td>
                                <td class="text-center"><button type="button" class="btn-eliminar-fila" onclick="this.closest('tr').remove()"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @empty
                            @for($mi = 0; $mi < $numMuestras; $mi++)
                            <tr class="fila-ruido">
                                <td><input type="text" class="form-control form-control-sm" name="resultados_ruido[{{ $mi }}][codigo]" value="RU-{{ str_pad($mi + 1, 2, '0', STR_PAD_LEFT) }}" readonly></td>
                                <td><input type="time" class="form-control form-control-sm" name="resultados_ruido[{{ $mi }}][hora_inicial]"></td>
                                <td><input type="time" class="form-control form-control-sm" name="resultados_ruido[{{ $mi }}][hora_final]"></td>
                                <td><input type="text" class="form-control form-control-sm" name="resultados_ruido[{{ $mi }}][tipo_ruido]" placeholder="AMB/IND"></td>
                                <td><input type="number" step="0.1" class="form-control form-control-sm" name="resultados_ruido[{{ $mi }}][lmax]"></td>
                                <td><input type="number" step="0.1" class="form-control form-control-sm" name="resultados_ruido[{{ $mi }}][lmin]"></td>
                                <td><input type="number" step="0.1" class="form-control form-control-sm" name="resultados_ruido[{{ $mi }}][leq]"></td>
                                <td class="text-center"><button type="button" class="btn-eliminar-fila" onclick="this.closest('tr').remove()"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @endfor
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
                <i class="fas fa-map-marker-alt me-2"></i> DESCRIPCIÓN REFERENCIAL DE LOS PUNTOS DE MEDICIÓN DEL NPS.
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @php
                        $pmRawZ = old('puntos_medicion', $reporte->puntos_medicion ?? []);
                        if (is_string($pmRawZ)) $pmRawZ = json_decode($pmRawZ, true) ?? [];
                        $zonaActual = '19K';
                        foreach ($pmRawZ as $pt) {
                            if ((!isset($pt['categoria']) || $pt['categoria'] === 'RUIDO') && !empty($pt['zona'])) {
                                $zonaActual = $pt['zona'];
                                break;
                            }
                        }
                    @endphp
                    <table class="table table-bordered" id="tabla-puntos" style="border-color: #ffc107;">
                        <thead>
                            <tr>
                                <th style="width: 12%;">CODIGO</th>
                                <th style="width: 33%;">DESCRIPCIÓN DEL PUNTO</th>
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
                                $puntos = array_values(array_filter($pmRaw, fn($pt) => (!isset($pt['categoria']) || $pt['categoria'] === 'RUIDO') && (!empty($pt['descripcion']) || !empty($pt['valor1']) || !empty($pt['valor2']))));
                                $puntosCount = max(count($rr), count($puntos));
                                if ($puntosCount === 0) { $puntosCount = $numMuestras; }
                            @endphp
                            @for($pi = 0; $pi < $puntosCount; $pi++)
                            @php $p = $puntos[$pi] ?? []; @endphp
                            @php $r = $rr[$pi] ?? []; @endphp
                            @php $codigo = $r['codigo'] ?? $p['codigo'] ?? (count($rr) === 0 && count($puntos) === 0 ? 'RU-' . str_pad($pi + 1, 2, '0', STR_PAD_LEFT) : ''); @endphp
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
                                            <input type="number" step="0.01" class="form-control form-control-sm" name="puntos_medicion[{{ $pi }}][valor1]" value="{{ $p['valor1'] ?? $p['norte'] ?? '' }}" placeholder="Valor">
                                            <select class="form-select form-select-sm" name="puntos_medicion[{{ $pi }}][direccion2]" style="width: 100px;">
                                                <option value="E" {{ ($p['direccion2'] ?? 'E') == 'E' ? 'selected' : '' }}>E</option>
                                                <option value="N" {{ ($p['direccion2'] ?? '') == 'N' ? 'selected' : '' }}>N</option>
                                                <option value="S" {{ ($p['direccion2'] ?? '') == 'S' ? 'selected' : '' }}>S</option>
                                                <option value="O" {{ ($p['direccion2'] ?? '') == 'O' ? 'selected' : '' }}>O</option>
                                            </select>
                                            <input type="number" step="0.01" class="form-control form-control-sm" name="puntos_medicion[{{ $pi }}][valor2]" value="{{ $p['valor2'] ?? $p['este'] ?? '' }}" placeholder="Valor">
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
                <i class="fas fa-sticky-note me-2"></i> COMENTARIOS GENERALES
            </div>
            <div class="card-body">
                <textarea class="form-control @error('observaciones_ruido') is-invalid @enderror"
                          name="observaciones_ruido" rows="4" placeholder="Observaciones y comentarios técnicos generales...">{{ old('observaciones_ruido', $reporte->observaciones_ruido ?? $reporte->comentarios ?? '') }}</textarea>
                @error('observaciones_ruido')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>

        @include('reportes._firmas_publicar', ['reporte' => $reporte, 'proforma' => $proforma, 'categoria' => 'RUIDO'])
    </form>
</div>
</div>
@endsection

@push('scripts')
<script>
    let idx = {{ max($hasRr ? count(old('resultados_ruido', $rr)) : 0, $puntosCount) ?: max($numMuestras, 1) }};
    function agregarFila() {
        const tbody = document.getElementById('ruido-body');
        const tr = document.createElement('tr'); tr.className = 'fila-ruido';
        const codigo = 'RU-' + String(idx + 1).padStart(2, '0');
        tr.innerHTML = `
            <td><input type="text" class="form-control form-control-sm" name="resultados_ruido[${idx}][codigo]" value="${codigo}" readonly></td>
            <td><input type="time" class="form-control form-control-sm" name="resultados_ruido[${idx}][hora_inicial]"></td>
            <td><input type="time" class="form-control form-control-sm" name="resultados_ruido[${idx}][hora_final]"></td>
            <td><input type="text" class="form-control form-control-sm" name="resultados_ruido[${idx}][tipo_ruido]" placeholder="AMB/IND"></td>
            <td><input type="number" step="0.1" class="form-control form-control-sm" name="resultados_ruido[${idx}][lmax]"></td>
            <td><input type="number" step="0.1" class="form-control form-control-sm" name="resultados_ruido[${idx}][lmin]"></td>
            <td><input type="number" step="0.1" class="form-control form-control-sm" name="resultados_ruido[${idx}][leq]"></td>
            <td class="text-center"><button type="button" class="btn-eliminar-fila" onclick="this.closest('tr').remove()"><i class="fas fa-times"></i></button></td>`;
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
                        <input type="number" step="0.01" class="form-control form-control-sm" name="puntos_medicion[${idx}][valor1]" placeholder="Valor">
                        <select class="form-select form-select-sm" name="puntos_medicion[${idx}][direccion2]" style="width: 100px;">
                            <option value="E">E</option><option value="N">N</option><option value="S">S</option><option value="O">O</option>
                        </select>
                        <input type="number" step="0.01" class="form-control form-control-sm" name="puntos_medicion[${idx}][valor2]" placeholder="Valor">
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
</script>
@endpush
