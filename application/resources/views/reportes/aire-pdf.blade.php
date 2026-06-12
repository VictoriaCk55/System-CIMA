<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    @php $cfg = \App\Models\Documento::whereSlug('solicitud-ensayo')->first() ?? new \App\Models\Documento; @endphp
    <title>REPORTE {{ $reporte->codigoAire() }}</title>
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
        .header-separator { border: none; border-top: 1.5px solid #000; margin: 8pt 0 14pt 0; }
        .titulo-ppal { text-align: center; font-size: 14pt; font-weight: bold; margin: 0 0 4pt 0; }
        .titulo-sec { text-align: center; font-size: 12pt; font-weight: bold; text-decoration: underline; margin: 0 0 18pt 0; }
        .info-grid { width: 100%; border-collapse: collapse; margin-bottom: 16pt; }
        .info-grid td { padding: 1.5pt 4pt; vertical-align: top; font-size: 9pt; }
        .info-grid .label { font-weight: bold; width: 32%; white-space: nowrap; }
        .info-grid .value { width: 68%; }
        .section-title { text-align: center; font-size: 10pt; font-weight: bold; margin: 14pt 0 6pt 0; }
        .tabla { width: 100%; border-collapse: collapse; margin-bottom: 14pt; }
        .tabla th, .tabla td { border: 1px solid #000; padding: 4pt 5pt; font-size: 9pt; text-align: center; vertical-align: middle; }
        .tabla th { background-color: #d9d9d9; font-weight: bold; }
        .tabla td.num { font-weight: bold; }
        .tabla td.left { text-align: left; }
        .comentarios { text-align: justify; font-size: 9pt; line-height: 1.5; margin: 8pt 0 20pt 0; padding: 0 2pt; }
        .firmas-section { width: 100%; margin-top: 30pt; }
        .firmas-section td { width: 45%; text-align: center; padding: 6pt; font-size: 9pt; vertical-align: bottom; }
        .firmas-section .sello-cell { width: 10%; text-align: center; vertical-align: middle; }
        .firma-line { border-top: 1px solid #000; padding-top: 4pt; margin-top: 40pt; display: inline-block; }
        .sello-img { width: 55px; opacity: 0.5; }
        .sello-ovalado { width: 50px; opacity: 0.5; }
        .footer { text-align: center; font-size: 9pt; font-style: italic; border-top: 1px solid #999; padding-top: 4pt; margin-top: 20pt; }
    </style>
</head>
<body>
    @php
        $p = $reporte->proforma;
        $c = $p->cliente;
        $parametrosAire = $p->parametros()->where('categoria', 'AIRE')->get();
        $ra = $reporte->resultados_aire ?? [];
        if (is_string($ra)) { $ra = json_decode($ra, true) ?? []; }
        // backward compat old format {codigo, parametro, concentracion, unidad}
        foreach ($ra as &$row) {
            if (isset($row['parametro'])) {
                $row['periodo'] = $row['parametro'];
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
        }
        unset($row);
        $pm = $reporte->puntos_medicion ?? [];
        if (is_string($pm)) { $pm = json_decode($pm, true) ?? []; }
    @endphp

    <!-- ENCABEZADO INSTITUCIONAL -->
    <table class="header-table">
        <tr>
            <td style="width: 15%; text-align: left; vertical-align: top;">
                @if($cfg->config('logo'))<img src="{{ storage_path('app/public/' . $logo) }}" class="logo" alt="Logo">@endif
            </td>
            <td style="width: 70%; text-align: center; vertical-align: middle;">
                <div class="header-line1">CENTRO DE INVESTIGACIÓN MINERO AMBIENTAL</div>
                <div class="header-line2">UNIVERSIDAD AUTÓNOMA TOMÁS FRÍAS</div>
                <div class="header-line3">"CIMA - UATF"</div>
            </td>
            <td style="width: 15%; text-align: right; vertical-align: top;">
                @if($cfg->config('logo'))<img src="{{ storage_path('app/public/' . $logo) }}" class="logo" alt="Logo">@endif
            </td>
        </tr>
    </table>
    <hr class="header-separator">

    <!-- TÍTULO -->
    <div class="titulo-ppal">REPORTE</div>
    <div class="titulo-sec">MUESTREO DE PARTÍCULAS SUSPENDIDAS</div>

    <!-- INFORMACIÓN GENERAL -->
    <table class="info-grid">
        <tr><td class="label">NOMBRE CLIENTE:</td><td class="value" colspan="3">{{ strtoupper($c->razon_social) }}</td></tr>
        <tr><td class="label">CÓDIGO REPORTE:</td><td class="value" colspan="3">{{ $reporte->codigoAire() }}</td></tr>
        <tr><td class="label">FECHA EMISIÓN DE REPORTE:</td><td class="value" colspan="3">{{ $reporte->fecha_emision ? strtoupper($reporte->fecha_emision->locale('es')->isoFormat('DD [DE] MMMM [DE] YYYY')) : '' }}</td></tr>
        <tr><td class="label">FECHA INICIO DE MUESTREO:</td><td class="value">{{ $reporte->fecha_inicio_muestreo ? $reporte->fecha_inicio_muestreo->format('d/m/Y') : '' }}</td><td class="label" style="width:20%;">FECHA FINAL DE MUESTREO:</td>
        <td class="value" style="width:22%;">{{ $reporte->fecha_fin_muestreo ? $reporte->fecha_fin_muestreo->format('d/m/Y') : '' }}</td></tr>
        <tr><td class="label">TIPO DE MUESTREO:</td><td class="value" colspan="3">{{ strtoupper($reporte->tipo_muestreo ?? '') }}</td></tr>
        <tr><td class="label">MUESTREO EFECTUADO POR:</td><td class="value" colspan="3">{{ strtoupper($reporte->medicion_efectuada_por ?? '') }}</td></tr>
        <tr><td class="label">EQUIPO USADO PARA MUESTREO:</td><td class="value" colspan="3">{{ strtoupper($reporte->equipo_usado ?? '') }}</td></tr>
        <tr><td class="label">CONDICIONES DE MUESTREO:</td><td class="value" colspan="3">{{ strtoupper($reporte->condiciones_muestreo ?? '') }}</td></tr>
        <tr><td class="label">CONDICIONES REPORTE DE RESULTADOS:</td><td class="value" colspan="3">{{ strtoupper($reporte->condiciones_reporte ?? '') }}</td></tr>
    </table>

    <!-- TABLA DE RESULTADOS -->
    @if(count($ra) > 0)
    <div class="section-title">RESULTADOS DE MEDICIÓN DE AIRE</div>
    <table class="tabla">
        <thead>
            <tr>
                <th style="width: 15%;">CÓDIGO</th>
                <th style="width: 20%;">PERIODO DE MUESTREO</th>
                @foreach($parametrosAire as $p)
                <th style="text-align: center;">
                    {{ $p->nombre_completo ?? $p->nombre }}<br>
                    <span style="font-weight: normal; font-size: 8pt;">{{ $p->metodo ?? '' }}</span>
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($ra as $r)
            <tr>
                <td>{{ $r['codigo'] ?? '' }}</td>
                <td>{{ $r['periodo'] ?? '' }}</td>
                @foreach($parametrosAire as $p)
                <td class="num">{{ $r[$p->nombre]['valor'] ?? '' }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- DESCRIPCIÓN DE PUNTOS DE MUESTREO -->
    @if(count($pm) > 0)
    <div class="section-title">DESCRIPCIÓN REFERENCIAL DE LOS PUNTOS DE MUESTREO</div>
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
    @if($reporte->observaciones_aire)
    <div>
        <strong style="font-size: 9pt;">COMENTARIOS:</strong>
        <div class="comentarios">{{ $reporte->observaciones_aire }}</div>
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
                <div style="margin-top: 2pt;">{{ $reporte->cargo_responsable ?? 'RESPONSABLE UIA' }}</div>
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
