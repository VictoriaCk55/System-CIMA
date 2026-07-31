<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    @php
        $cfg = \App\Models\Documento::whereSlug('solicitud-ensayo')->first() ?? new \App\Models\Documento;
        $logo = $cfg->config('logo_path');
    @endphp
    <title>REPORTE {{ $reporte->codigoGases() }}</title>
    <style>
        @page { margin: 10mm 25.4mm 10mm 25.4mm; }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 52pt 0 0 0;
            background: #fff;
        }
        .header-line1 { text-align: center; font-size: 10pt; font-weight: bold; margin: 0; line-height: 1.1; }
        .header-line2 { text-align: center; font-size: 10pt; font-weight: bold; margin: 0; line-height: 1.1; }
        .header-line3 { text-align: center; font-size: 11pt; font-weight: bold; margin: 0; line-height: 1.1; }
        .header-table { width: 100%; margin-bottom: 6pt; }
        .header-table .logo { width: 65px; height: auto; max-height: 65px; }
        .header-table .logo-cell { width: 18%; text-align: center; vertical-align: middle; }
        .titulo-ppal { text-align: center; font-size: 12pt; font-weight: bold; text-decoration: underline; margin: 0; }
        .titulo-sec { text-align: center; font-size: 11pt; font-weight: bold; text-decoration: underline; margin: 0 0 3pt 0; }
        .info-grid { width: 100%; border-collapse: collapse; margin-bottom: 8pt; }
        .info-grid td { padding: 0.5pt 4pt; vertical-align: top; font-size: 10pt; line-height: 1.2; }
        .info-grid .label { font-weight: bold; width: 32%; white-space: nowrap; }
        .info-grid .value { width: 68%; }
        .section-title { text-align: center; font-size: 10pt; font-weight: bold; margin: 8pt 0 3pt 0; }
        .tabla { width: 100%; border-collapse: collapse; margin-bottom: 8pt; }
        .tabla th, .tabla td { border: 1px solid #000; padding: 1.5pt 5pt; font-size: 10pt; text-align: center; vertical-align: middle; line-height: 1.1; }
        .tabla th { background-color: #8bc34a; color: #000; font-weight: bold; }
        .tabla td.num { font-weight: bold; }
        .tabla td.left { text-align: left; }
        .tabla-pm { width: 100%; border-collapse: collapse; margin-bottom: 8pt; }
        .tabla-pm th, .tabla-pm td { border: 1px solid #000; padding: 1.5pt 3pt; font-size: 10pt; vertical-align: middle; }
        .tabla-pm th { background-color: #8bc34a; color: #000; font-weight: bold; text-align: center; }
        .tabla-pm td.left { text-align: left; }
        .comentario-box { border: 1px solid #8bc34a; padding: 8pt 10pt; margin: 8pt 0 20pt 0; }
        .comentarios { text-align: justify; font-size: 10pt; line-height: 1.5; margin: 4pt 0 0 0; padding: 0; white-space: pre-wrap; }
        .firmas-section { width: 100%; margin-top: 30pt; }
        .firmas-section td { width: 45%; text-align: center; padding: 6pt; font-size: 10pt; vertical-align: bottom; }
        .firmas-section .sello-cell { width: 10%; text-align: center; vertical-align: middle; }
        .firma-line { border-top: 1px solid #8bc34a; padding-top: 4pt; margin-top: 40pt; display: inline-block; }
        .sello-img { width: 55px; opacity: 0.5; }
        .sello-ovalado { width: 50px; opacity: 0.5; }
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
        $pm = array_values(array_filter($pm, fn($pt) => (!isset($pt['categoria']) || $pt['categoria'] === 'GASES') && (!empty($pt['descripcion']) || !empty($pt['valor1']) || !empty($pt['valor2']))));
        $puntosCount = max(count($rg), count($pm));
    @endphp

    <!-- ENCABEZADO INSTITUCIONAL -->
    <div style="position: fixed; top: -12pt; left: 0; width: 468pt; background: #fff; z-index: 1000; padding-bottom: 3pt; border-bottom: 1.5pt solid #8bc34a;">
    <table style="border-collapse: collapse; width: 100%;">
        <tr>
            <td style="text-align: center; vertical-align: middle; width: 1%; white-space: nowrap;">
                @if($logo && file_exists(storage_path('app/public/' . $logo)))
                    <img src="{{ storage_path('app/public/' . $logo) }}" style="width: 65px; height: auto; max-height: 65px;" alt="Logo">
                @elseif(file_exists(public_path('images/logo-cima.jpg')))
                    <img src="{{ public_path('images/logo-cima.jpg') }}" style="width: 65px; height: auto; max-height: 65px;" alt="Logo CIMA">
                @elseif(file_exists(public_path('images/logo-cima.png')))
                    <img src="{{ public_path('images/logo-cima.png') }}" style="width: 65px; height: auto; max-height: 65px;" alt="Logo CIMA">
                @endif
            </td>
            <td style="text-align: center; vertical-align: middle;">
                <div class="header-line1">CENTRO DE INVESTIGACIÓN MINERO AMBIENTAL</div>
                <div class="header-line2">UNIVERSIDAD AUTÓNOMA TOMÁS FRÍAS</div>
                <div class="header-line3">"CIMA - UATF"</div>
            </td>
            <td style="text-align: center; vertical-align: middle; width: 1%; white-space: nowrap;">
                @php $logoUatf = public_path('images/uatf.png'); @endphp
                @if(file_exists($logoUatf))
                    <img src="{{ $logoUatf }}" style="width: 55px; height: auto; max-height: 55px;" alt="Logo UATF">
                @endif
            </td>
        </tr>
    </table>
    </div>

    <!-- TÍTULO -->
    <div class="titulo-ppal">REPORTE</div>
    <div class="titulo-sec">MEDICIÓN DE GASES</div>

    <!-- INFORMACIÓN GENERAL -->
    @php $info = $reporte->info('GASES'); @endphp
    <table class="info-grid">
        <tr><td class="label">NOMBRE CLIENTE:</td><td class="value" colspan="3">{{ strtoupper($c->razon_social) }}</td></tr>
        <tr><td class="label">CÓDIGO REPORTE:</td><td class="value" colspan="3">{{ $info['codigo_reporte'] ?? $reporte->codigoGases() }}</td></tr>
        <tr><td class="label">FECHA EMISIÓN DE REPORTE:</td><td class="value" colspan="3">{{ !empty($info['fecha_emision']) ? \Carbon\Carbon::parse($info['fecha_emision'])->format('Y/m/d') : '' }}</td></tr>
        <tr><td class="label">FECHA DE MEDICIÓN:</td><td class="value" colspan="3">{{ !empty($info['fecha_medicion']) ? \Carbon\Carbon::parse($info['fecha_medicion'])->format('Y/m/d') : '' }}</td></tr>
        <tr><td class="label">TIPO DE MEDICIÓN:</td><td class="value" colspan="3">{{ $info['tipo_medicion'] ?? '' }}</td></tr>
        <tr><td class="label">MEDICIÓN EFECTUADA POR:</td><td class="value" colspan="3">{{ $info['medicion_efectuada_por'] ?? '' }}</td></tr>
        <tr><td class="label">EQUIPO USADO PARA MEDICIÓN:</td><td class="value" colspan="3">{{ $info['equipo_usado'] ?? '' }}</td></tr>
        <tr><td class="label">CONDICIONES DE MUESTREO:</td><td class="value" colspan="3">{{ $info['condiciones_muestreo'] ?? '' }}</td></tr>
        <tr><td class="label">CONDICIONES REPORTE DE RESULTADOS:</td><td class="value" colspan="3">{{ $info['condiciones_reporte'] ?? '' }}</td></tr>
    </table>

    @php
        $paramsGases = $p->parametros()->where('categoria', 'GASES')->get();
        $gasesMetodo = '';
        foreach ($paramsGases as $pg) {
            if ($pg->pivot->metodo) {
                $gasesMetodo = $pg->pivot->metodo;
                break;
            }
        }
        $gasesNombres = $gasesMetodo ? array_map('trim', explode(',', $gasesMetodo)) : [];
        if (!empty($gasesNombres)) {
            $todosGases = \App\Models\Parametro::where('categoria', 'GASES')->get();
            $paramsGases = $todosGases->filter(fn($g) => in_array($g->nombre, $gasesNombres));
        }
    @endphp

    <!-- TABLA DE RESULTADOS -->
    @if(count($rg) > 0)
    <div class="section-title">RESULTADOS DE MEDICIÓN DE GASES</div>
    <table class="tabla">
        <thead>
            <tr>
                <th style="width: 10%;">CÓDIGO</th>
                <th style="width: 15%;">PERIODO</th>
                @foreach($paramsGases as $p)
                 @php
                     $unidad = $p->unidad_default ?? '';
                     foreach ($rg as $r) {
                         if (! empty($r[$p->nombre]['unidad'] ?? '')) {
                             $unidad = $r[$p->nombre]['unidad'];
                             break;
                         }
                     }
                 @endphp
                 <th>{{ strtoupper($p->nombre_completo ?? $p->nombre) }} ({{ $unidad }})</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rg as $r)
            <tr>
                <td>{{ $r['codigo'] ?? '' }}</td>
                <td>{{ $r['periodo'] ?? '' }}</td>
                @foreach($paramsGases as $p)
                <td class="num">{{ $r[$p->nombre]['valor'] ?? $r['concentracion'] ?? '' }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- DESCRIPCIÓN DE PUNTOS DE MEDICIÓN -->
    @if($puntosCount > 0)
    <div class="section-title">DESCRIPCIÓN REFERENCIAL DE LOS PUNTOS DE MEDICIÓN</div>
    <table class="tabla-pm">
        <thead>
            <tr>
                <th style="width: 18%;">CÓDIGO</th>
                <th style="width: 42%;">DESCRIPCIÓN DEL PUNTO</th>
                <th colspan="4" style="width: 40%;">UBICACIÓN<br><span style="font-size: 8pt; font-weight: normal;">ZONA {{ $pm[0]['zona'] ?? '' }}</span></th>
            </tr>
        </thead>
        <tbody>
            @for($i = 0; $i < $puntosCount; $i++)
            @php $r = $rg[$i] ?? []; $pt = $pm[$i] ?? []; @endphp
            <tr>
                <td style="text-align: center;">{{ $r['codigo'] ?? $pt['codigo'] ?? '' }}</td>
                <td class="left">{{ $pt['descripcion'] ?? '' }}</td>
                <td style="text-align: center;">{{ $pt['direccion1'] ?? 'N' }}</td>
                <td style="text-align: center;">{{ $pt['valor1'] ?? $pt['norte'] ?? '' }}</td>
                <td style="text-align: center;">{{ $pt['direccion2'] ?? 'E' }}</td>
                <td style="text-align: center;">{{ $pt['valor2'] ?? $pt['este'] ?? '' }}</td>
            </tr>
            @endfor
        </tbody>
    </table>
    @endif

    <!-- COMENTARIOS -->
    @if($reporte->observaciones_gases)
    <div class="comentario-box">
        <strong style="font-size: 10pt;">COMENTARIO:</strong>
        <div class="comentarios">{{ $reporte->observaciones_gases }}</div>
    </div>
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

    <div style="text-align: center; margin-top: 30pt; font-size: 10pt; color: #666; white-space: nowrap;">
        ——— FIN DEL INFORME ———
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $fontBold = $fontMetrics->getFont("times", "bold");
            $h = $pdf->get_height();
            $w = $pdf->get_width();
            $green = array(0.545, 0.765, 0.290);
            $black = array(0, 0, 0);

            $linea1 = "Av. Arce esq. Villazón s/n. Edificio Facultad de Ingeniería Minera bloque 1. Segundo piso: Teléfono/Fax: 62-29711";
            $linea2 = "E-MAIL: cima.uatf@uatf.edu.bo";

            $pdf->page_line(72, $h - 50, 540, $h - 50, $green, 1.5);
            $x1 = ($w - $fontMetrics->getTextWidth($linea1, $fontBold, 10)) / 2;
            $x2 = ($w - $fontMetrics->getTextWidth($linea2, $fontBold, 10)) / 2;
            $pdf->page_text($x1, $h - 44, $linea1, $fontBold, 10, $black);
            $pdf->page_text($x2, $h - 32, $linea2, $fontBold, 10, $black);
            $pdf->page_text(($w - $fontMetrics->getTextWidth("Página 99 de 99", $fontBold, 10)) / 2, $h - 20, "Página {PAGE_NUM} de {PAGE_COUNT}", $fontBold, 10, $black);
        }
    </script>
</body>
</html>
