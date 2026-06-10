<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    @php $cfg = \App\Models\Documento::whereSlug('solicitud-ensayo')->first() ?? new \App\Models\Documento; @endphp
    <title>REPORTE {{ $reporte->codigoGases() }}</title>
    <style>
        @page { margin: 20mm 15mm 20mm 15mm; }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        .header-table { width: 100%; margin-bottom: 6pt; }
        .header-table .logo { width: 65px; }
        .header-line1 { text-align: center; font-size: 11pt; font-weight: bold; margin: 0; }
        .header-line2 { text-align: center; font-size: 10pt; font-weight: bold; margin: 2pt 0; }
        .header-line3 { text-align: center; font-size: 12pt; font-weight: bold; margin: 2pt 0 0 0; }
        .header-separator { border: none; border-top: 1.5px solid #2E7D32; margin: 8pt 0 14pt 0; }
        .titulo-ppal { text-align: center; font-size: 14pt; font-weight: bold; color: #2E7D32; margin: 0 0 4pt 0; }
        .titulo-sec { text-align: center; font-size: 12pt; font-weight: bold; color: #2E7D32; text-decoration: underline; margin: 0 0 18pt 0; }
        .info-grid { width: 100%; border-collapse: collapse; margin-bottom: 16pt; }
        .info-grid td { padding: 1.5pt 4pt; vertical-align: top; font-size: 9pt; }
        .info-grid .label { font-weight: bold; width: 32%; white-space: nowrap; }
        .info-grid .value { width: 68%; }
        .section-title { text-align: center; font-size: 10pt; font-weight: bold; color: #2E7D32; margin: 14pt 0 6pt 0; }
        .tabla { width: 100%; border-collapse: collapse; margin-bottom: 14pt; }
        .tabla th, .tabla td { border: 1px solid #2E7D32; padding: 4pt 5pt; font-size: 9pt; text-align: center; vertical-align: middle; }
        .tabla th { background-color: #2E7D32; color: #fff; font-weight: bold; }
        .tabla td.num { font-weight: bold; }
        .tabla td.left { text-align: left; }
        .comentarios-label { font-size: 9pt; font-weight: bold; color: #2E7D32; }
        .comentarios { text-align: justify; font-size: 9pt; line-height: 1.5; margin: 8pt 0 20pt 0; padding: 0 2pt; }
        .firmas-section { width: 100%; margin-top: 30pt; }
        .firmas-section td { width: 45%; text-align: center; padding: 6pt; font-size: 9pt; vertical-align: bottom; }
        .firmas-section .sello-cell { width: 10%; text-align: center; vertical-align: middle; }
        .firma-line { border-top: 1px solid #000; padding-top: 4pt; margin-top: 40pt; display: inline-block; }
        .sello-img { width: 55px; opacity: 0.5; }
        .sello-ovalado { width: 50px; opacity: 0.5; }
        .footer { text-align: center; font-size: 9pt; font-style: italic; color: #999; border-top: 1px solid #2E7D32; padding-top: 4pt; margin-top: 20pt; }
    </style>
</head>
<body>
    @php
        $p = $reporte->proforma;
        $c = $p->cliente;
        $rg = $reporte->resultados_gases ?? [];
        if (is_string($rg)) { $rg = json_decode($rg, true) ?? []; }
        $pm = $reporte->puntos_medicion ?? [];
        if (is_string($pm)) { $pm = json_decode($pm, true) ?? []; }
    @endphp

    <!-- ENCABEZADO INSTITUCIONAL -->
    <table class="header-table">
        <tr>
            <td style="width: 15%; text-align: left; vertical-align: top;">
                @if($cfg->config('logo_izquierdo'))<img src="{{ public_path($cfg->config('logo_izquierdo')) }}" class="logo" alt="Logo">@endif
            </td>
            <td style="width: 70%; text-align: center; vertical-align: middle;">
                <div class="header-line1">CENTRO DE INVESTIGACIÓN MINERO AMBIENTAL</div>
                <div class="header-line2">UNIVERSIDAD AUTÓNOMA TOMÁS FRÍAS</div>
                <div class="header-line3">"CIMA - UATF"</div>
            </td>
            <td style="width: 15%; text-align: right; vertical-align: top;">
                @if($cfg->config('logo_derecho'))<img src="{{ public_path($cfg->config('logo_derecho')) }}" class="logo" alt="Logo">@endif
            </td>
        </tr>
    </table>
    <hr class="header-separator">

    <!-- TÍTULO -->
    <div class="titulo-ppal">REPORTE</div>
    <div class="titulo-sec">MEDICIÓN DE GASES</div>

    <!-- INFORMACIÓN GENERAL -->
    <table class="info-grid">
        <tr><td class="label">NOMBRE CLIENTE:</td><td class="value" colspan="3">{{ strtoupper($c->razon_social) }}</td></tr>
        <tr><td class="label">CÓDIGO REPORTE:</td><td class="value" colspan="3">{{ $reporte->codigoGases() }}</td></tr>
        <tr><td class="label">FECHA EMISIÓN DE REPORTE:</td><td class="value" colspan="3">{{ $reporte->fecha_emision ? strtoupper($reporte->fecha_emision->locale('es')->isoFormat('DD [DE] MMMM [DE] YYYY')) : '' }}</td></tr>
        <tr><td class="label">FECHA INICIO DE MEDICIÓN:</td><td class="value" colspan="3">{{ $reporte->fecha_medicion ? $reporte->fecha_medicion->format('d/m/Y') : '' }}</td></tr>
        <tr><td class="label">TIPO DE MEDICIÓN:</td><td class="value" colspan="3">{{ strtoupper($reporte->periodo_medicion ?? 'MEDICIÓN DE GASES') }}</td></tr>
        <tr><td class="label">MEDICIÓN EFECTUADA POR:</td><td class="value" colspan="3">{{ strtoupper($reporte->medicion_efectuada_por ?? '') }}</td></tr>
        <tr><td class="label">EQUIPO USADO PARA MEDICIÓN:</td><td class="value" colspan="3">{{ strtoupper($reporte->equipo_usado ?? '') }}</td></tr>
        <tr><td class="label">CONDICIONES DE MUESTREO:</td><td class="value" colspan="3"></td></tr>
        <tr><td class="label">CONDICIONES REPORTE DE RESULTADOS:</td><td class="value" colspan="3"></td></tr>
    </table>

    @php
        $paramsGases = $p->parametros()->where('categoria', 'GASES')->get();
    @endphp

    <!-- TABLA DE RESULTADOS -->
    @if(count($rg) > 0)
    <div class="section-title">RESULTADOS DE MEDICIÓN DE GASES</div>
    <table class="tabla">
        <thead>
            <tr>
                <th style="width: 10%;">CÓDIGO</th>
                <th style="width: 10%;">Hora Inicial</th>
                <th style="width: 10%;">Hora Final</th>
                @foreach($paramsGases as $p)
                <th colspan="2">{{ $p->nombre_completo ?? $p->nombre }}</th>
                @endforeach
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                @foreach($paramsGases as $p)
                <th>Valor</th>
                <th>Unidad</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rg as $r)
            <tr>
                <td>{{ $r['codigo'] ?? '' }}</td>
                <td>{{ $r['hora_inicial'] ?? '' }}</td>
                <td>{{ $r['hora_final'] ?? '' }}</td>
                @foreach($paramsGases as $p)
                <td class="num">{{ $r[$p->nombre]['valor'] ?? $r['concentracion'] ?? '' }}</td>
                <td>{{ $r[$p->nombre]['unidad'] ?? $r['unidad'] ?? $p->unidad_default ?? '' }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- DESCRIPCIÓN DE PUNTOS DE MEDICIÓN -->
    @if(count($pm) > 0)
    <div class="section-title">DESCRIPCIÓN REFERENCIAL DE LOS PUNTOS DE MEDICIÓN</div>
    <table class="tabla">
        <thead>
            <tr>
                <th style="width: 15%;">CÓDIGO</th>
                <th style="width: 40%;">DESCRIPCIÓN DEL PUNTO</th>
                <th style="width: 45%;">UBICACIÓN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pm as $pt)
            <tr>
                <td>{{ $pt['codigo'] ?? '' }}</td>
                <td class="left">{{ $pt['descripcion'] ?? '' }}</td>
                <td class="left">ZONA {{ $pt['zona'] ?? '19K' }}<br>{{ $pt['direccion1'] ?? 'N' }} {{ $pt['valor1'] ?? $pt['norte'] ?? '' }}&emsp;{{ $pt['direccion2'] ?? 'E' }} {{ $pt['valor2'] ?? $pt['este'] ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- COMENTARIOS -->
    @if($reporte->observaciones_gases)
    <div>
        <div class="comentarios-label">COMENTARIOS:</div>
        <div class="comentarios">{{ $reporte->observaciones_gases }}</div>
    </div>
    @endif

    @if($reporte->comentarios)
    <div class="comentarios">{{ $reporte->comentarios }}</div>
    @endif

    <!-- FIRMAS -->
    <table class="firmas-section">
        <tr>
            <td>
                <div class="firma-line">
                    <strong>{{ $reporte->responsable_uia ?? '_________________________' }}</strong>
                </div>
                <div style="margin-top: 2pt;">{{ $reporte->cargo_responsable ?? 'RESPONSABLE - UIA' }}</div>
            </td>
            <td class="sello-cell">
                @if($cfg->config('sello'))
                <img src="{{ public_path($cfg->config('sello')) }}" class="sello-img" alt="Sello">
                @endif
            </td>
            <td>
                <div class="firma-line" style="margin-top: 40pt;">
                    <strong>{{ $reporte->directora_cima ?? '_________________________' }}</strong>
                </div>
                <div style="margin-top: 2pt;">{{ $reporte->cargo_directora ?? 'DIRECTORA CIMA - UATF' }}</div>
                @if($cfg->config('sello_ovalado'))
                <div style="text-align: right; margin-top: 4pt;">
                    <img src="{{ public_path($cfg->config('sello_ovalado')) }}" class="sello-ovalado" alt="Sello Ovalado">
                </div>
                @endif
            </td>
        </tr>
    </table>

    <!-- PIE DE PÁGINA -->
    <div class="footer">FIN DEL INFORME</div>
</body>
</html>
