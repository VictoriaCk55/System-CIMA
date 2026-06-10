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
        <input type="hidden" name="categoria" value="RUIDO">

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
                               name="codigo_reporte" value="{{ old('codigo_reporte', $reporte->codigo_reporte ?? $reporte->codigoRuido()) }}" readonly>
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
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Equipo Usado</label>
                        <input type="text" class="form-control @error('equipo_usado') is-invalid @enderror"
                               name="equipo_usado" value="{{ old('equipo_usado', $reporte->equipo_usado ?? '') }}" placeholder="Ej: Sonómetro">
                        @error('equipo_usado')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipo de Medición</label>
                        <input type="text" class="form-control" value="{{ ($reporte->subtipo_ruido ?? 'AMBIENTAL') === 'INDUSTRIAL' ? 'RUIDO INDUSTRIAL' : 'RUIDO AMBIENTAL' }}" disabled>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Subtipo de Ruido</label>
                        <select class="form-select @error('subtipo_ruido') is-invalid @enderror" name="subtipo_ruido">
                            <option value="AMBIENTAL" {{ old('subtipo_ruido', $reporte->subtipo_ruido ?? 'AMBIENTAL') == 'AMBIENTAL' ? 'selected' : '' }}>Ruido Ambiental (RUAM)</option>
                            <option value="INDUSTRIAL" {{ old('subtipo_ruido', $reporte->subtipo_ruido ?? '') == 'INDUSTRIAL' ? 'selected' : '' }}>Ruido Industrial (RUIND)</option>
                        </select>
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
                                <th style="width: 12%;">Lmáx</th>
                                <th style="width: 12%;">Lmín</th>
                                <th style="width: 12%;">Leq</th>
                                <th style="width: 40px;"></th>
                            </tr>
                        </thead>
                        <tbody id="ruido-body">
                            @php
                                $puntosRuido = old('puntos_medicion', $reporte->puntos_medicion ?? []);
                                if (is_string($puntosRuido)) $puntosRuido = json_decode($puntosRuido, true) ?? [];
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
                                <td><input type="text" class="form-control form-control-sm" name="resultados_ruido[{{ $i }}][tipo_ruido]" value="{{ $r['tipo_ruido'] ?? ($reporte->subtipo_ruido ?? 'AMBIENTAL') }}" placeholder="AMB/IND"></td>
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
                                <td><input type="text" class="form-control form-control-sm" name="resultados_ruido[{{ $mi }}][tipo_ruido]" placeholder="AMB/IND" value="{{ $reporte->subtipo_ruido ?? 'AMBIENTAL' }}"></td>
                                <td><input type="number" step="0.1" class="form-control form-control-sm" name="resultados_ruido[{{ $mi }}][lmax]"></td>
                                <td><input type="number" step="0.1" class="form-control form-control-sm" name="resultados_ruido[{{ $mi }}][lmin]"></td>
                                <td><input type="number" step="0.1" class="form-control form-control-sm" name="resultados_ruido[{{ $mi }}][leq]"></td>
                                <td class="text-center"><button type="button" class="btn-eliminar-fila" onclick="this.closest('tr').remove()"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @endfor
                            @if($numMuestras === 0)
                            <tr class="fila-ruido">
                                <td><input type="text" class="form-control form-control-sm" name="resultados_ruido[0][codigo]" placeholder="Ej: RU-01" readonly></td>
                                <td><input type="time" class="form-control form-control-sm" name="resultados_ruido[0][hora_inicial]"></td>
                                <td><input type="time" class="form-control form-control-sm" name="resultados_ruido[0][hora_final]"></td>
                                <td><input type="text" class="form-control form-control-sm" name="resultados_ruido[0][tipo_ruido]" placeholder="AMB/IND"></td>
                                <td><input type="number" step="0.1" class="form-control form-control-sm" name="resultados_ruido[0][lmax]"></td>
                                <td><input type="number" step="0.1" class="form-control form-control-sm" name="resultados_ruido[0][lmin]"></td>
                                <td><input type="number" step="0.1" class="form-control form-control-sm" name="resultados_ruido[0][leq]"></td>
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
                <i class="fas fa-map-marker-alt me-2"></i> DESCRIPCIÓN REFERENCIAL DE LOS PUNTOS DE MEDICIÓN DEL NPS.
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="tabla-puntos" style="border-color: #ffc107;">
                        <thead>
                            <tr>
                                <th style="width: 12%;">CODIGO</th>
                                <th style="width: 33%;">DESCRIPCIÓN DEL PUNTO</th>
                                <th style="width: 40%;">UBICACIÓN</th>
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
                                        <select class="form-select form-select-sm" name="puntos_medicion[{{ $i }}][zona]">
                                            <option value="19K" {{ ($p['zona'] ?? '19K') == '19K' ? 'selected' : '' }}>ZONA 19K</option>
                                            <option value="20K" {{ ($p['zona'] ?? '') == '20K' ? 'selected' : '' }}>ZONA 20K</option>
                                            <option value="21K" {{ ($p['zona'] ?? '') == '21K' ? 'selected' : '' }}>ZONA 21K</option>
                                        </select>
                                        <div class="d-flex gap-1 align-items-center">
                                            <select class="form-select form-select-sm" name="puntos_medicion[{{ $i }}][direccion1]" style="width: 70px;">
                                                <option value="N" {{ ($p['direccion1'] ?? 'N') == 'N' ? 'selected' : '' }}>N</option>
                                                <option value="S" {{ ($p['direccion1'] ?? '') == 'S' ? 'selected' : '' }}>S</option>
                                                <option value="E" {{ ($p['direccion1'] ?? '') == 'E' ? 'selected' : '' }}>E</option>
                                                <option value="O" {{ ($p['direccion1'] ?? '') == 'O' ? 'selected' : '' }}>O</option>
                                            </select>
                                            <input type="number" step="0.01" class="form-control form-control-sm" name="puntos_medicion[{{ $i }}][valor1]" value="{{ $p['valor1'] ?? $p['norte'] ?? '' }}" placeholder="Valor">
                                            <select class="form-select form-select-sm" name="puntos_medicion[{{ $i }}][direccion2]" style="width: 70px;">
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
                                <td><input type="text" class="form-control form-control-sm" name="puntos_medicion[{{ $pi }}][codigo]" value="RU-{{ str_pad($pi + 1, 2, '0', STR_PAD_LEFT) }}"></td>
                                <td><input type="text" class="form-control form-control-sm" name="puntos_medicion[{{ $pi }}][descripcion]" placeholder="Ej: Área buzón de lavado"></td>
                                <td>
                                    <div class="d-flex flex-column gap-1" style="min-width: 280px;">
                                        <select class="form-select form-select-sm" name="puntos_medicion[{{ $pi }}][zona]">
                                            <option value="19K" selected>ZONA 19K</option>
                                            <option value="20K">ZONA 20K</option>
                                            <option value="21K">ZONA 21K</option>
                                        </select>
                                        <div class="d-flex gap-1 align-items-center">
                                            <select class="form-select form-select-sm" name="puntos_medicion[{{ $pi }}][direccion1]" style="width: 70px;">
                                                <option value="N" selected>N</option>
                                                <option value="S">S</option>
                                                <option value="E">E</option>
                                                <option value="O">O</option>
                                            </select>
                                            <input type="number" step="0.01" class="form-control form-control-sm" name="puntos_medicion[{{ $pi }}][valor1]" placeholder="Valor">
                                            <select class="form-select form-select-sm" name="puntos_medicion[{{ $pi }}][direccion2]" style="width: 70px;">
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
                                        <select class="form-select form-select-sm" name="puntos_medicion[0][zona]">
                                            <option value="19K" selected>ZONA 19K</option>
                                            <option value="20K">ZONA 20K</option>
                                            <option value="21K">ZONA 21K</option>
                                        </select>
                                        <div class="d-flex gap-1 align-items-center">
                                            <select class="form-select form-select-sm" name="puntos_medicion[0][direccion1]" style="width: 70px;">
                                                <option value="N" selected>N</option>
                                                <option value="S">S</option>
                                                <option value="E">E</option>
                                                <option value="O">O</option>
                                            </select>
                                            <input type="number" step="0.01" class="form-control form-control-sm" name="puntos_medicion[0][valor1]" placeholder="Valor">
                                            <select class="form-select form-select-sm" name="puntos_medicion[0][direccion2]" style="width: 70px;">
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
                <i class="fas fa-sticky-note me-2"></i> COMENTARIOS GENERALES
            </div>
            <div class="card-body">
                <textarea class="form-control @error('comentarios') is-invalid @enderror"
                          name="comentarios" rows="4" placeholder="Observaciones y comentarios técnicos generales...">{{ old('comentarios', $reporte->comentarios ?? '') }}</textarea>
                @error('comentarios')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>

        @include('reportes._firmas_publicar', ['reporte' => $reporte, 'proforma' => $proforma, 'categoria' => 'RUIDO'])
    </form>
</div>
@endsection

@push('scripts')
<script>
    let idx = {{ $hasRr ? count(old('resultados_ruido', $rr)) : max($numMuestras, 1) }};
    let idxPunto = {{ $hasPuntos ? count(old('puntos_medicion', $puntos)) : max($numMuestras, 1) }};
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
        tbody.appendChild(tr); idx++;
    }
    function agregarFilaPunto() {
        const tbody = document.getElementById('puntos-body');
        const tr = document.createElement('tr'); tr.className = 'fila-punto';
        const codigo = 'RU-' + String(idxPunto + 1).padStart(2, '0');
        tr.innerHTML = `
            <td><input type="text" class="form-control form-control-sm" name="puntos_medicion[${idxPunto}][codigo]" value="${codigo}"></td>
            <td><input type="text" class="form-control form-control-sm" name="puntos_medicion[${idxPunto}][descripcion]" placeholder="Ej: Área buzón de lavado"></td>
            <td>
                <div class="d-flex flex-column gap-1" style="min-width: 280px;">
                    <select class="form-select form-select-sm" name="puntos_medicion[${idxPunto}][zona]">
                        <option value="19K">ZONA 19K</option>
                        <option value="20K">ZONA 20K</option>
                        <option value="21K">ZONA 21K</option>
                    </select>
                    <div class="d-flex gap-1 align-items-center">
                        <select class="form-select form-select-sm" name="puntos_medicion[${idxPunto}][direccion1]" style="width: 70px;">
                            <option value="N">N</option><option value="S">S</option><option value="E">E</option><option value="O">O</option>
                        </select>
                        <input type="number" step="0.01" class="form-control form-control-sm" name="puntos_medicion[${idxPunto}][valor1]" placeholder="Valor">
                        <select class="form-select form-select-sm" name="puntos_medicion[${idxPunto}][direccion2]" style="width: 70px;">
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
