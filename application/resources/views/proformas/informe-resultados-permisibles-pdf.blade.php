{{-- INFORME DE RESULTADOS PDF CON DATOS PERMISIBLES --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <style>
        @page{
            /* margin: 20px 12px; */
            /* size: letter portrait; */
            /* margin: 20mm; */
            margin-top: 4cm;
            margin-bottom: 6cm;
            margin-left: 2.5cm;
            margin-right: 2.5cm;
        }

        body{
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* ====================================================== */
        /* HEADER Y FOOTER */
        /* ====================================================== */

        header{
            padding:0.5cm;
            position: fixed;
            top: -4cm;
            left: 0;
            right:0;
            /* height: 3.5cm; */
            margin-bottom: 0.3cm;
        }

        footer{
            position: fixed;
            bottom: -6cm;
            left: 0;
            right: 0;
            margin-bottom: 0.3cm;
            padding:0.5cm;
            /* height: 3cm; */ */
        }

        .header-table{
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table{
            width: 100%;
            border-collapse: collapse;
        }

        .header-logo{
            width: 85px;
        }

        .header-title{
            text-align: center;
        }

        .footer-text{
            font-size: 8px;
            text-align: center;
            line-height: 1.2;
        }

        .pagenum:before{
            content: counter(page);
        }

        .pagecount:before{
            content: counter(pages);
        }

        .page-content{
            /* width: 100%; */
            padding-bottom: 140px;
        }

        *{
            box-sizing: border-box;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
        }

        td, th{
            /* border: 1px solid #000; */
            /* padding: 4px 6px; */
            padding: 0;
            text-align: center;
            vertical-align: middle;
            overflow: hidden;
        }

        .encabezado-tabla td{
            font-size: 12px;
            padding: 2px 4px;
            line-height: 1.1;
        }

        .sin-borde, .sin-borde td, .sin-borde th{
            border: none !important;
            font-size: 11px;
        }

        .left{
            text-align: left;
        }

        .center{
            text-align: center;
            font-size: 16px;
        }

        .logo{
            width: 80px;
            height: auto;
        }

        .titulo{
            font-size: 16px;
            font-weight: bold;
        }

        .subtitulo{
            font-size: 12px;
            font-weight: bold;
        }

        .small{
            font-size: 10px;
            border: 1px solid #000;
            pading: 2px 3px;
        }

        .gris{
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .firma{
            height: 70px;
            padding-top: 20px;
        }

        .vertical-text{
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            white-space: nowrap;
            text-align: center;
        }

    </style>

</head>
<body>

    @php $cfg = \App\Models\Documento::whereSlug('informe-resultados')->first() ?? new \App\Models\Documento; @endphp

    <!-- ====================================================== -->
    <!-- HEADER -->
    <!-- ====================================================== -->

    <header>
        <table class="header-table">
            <tr>
                <!-- LOGO -->
                <td width="18%" style="border: none;">
                    @php
                        $logo = $cfg->config('logo_path');
                    @endphp
                    @if($logo && file_exists(storage_path('app/public/' . $logo)))
                        <img src="{{ storage_path('app/public/' . $logo) }}" class="header-logo">
                    @else
                        <img src="{{ public_path('images/logo-cima.jpg') }}" class="header-logo">
                    @endif
                </td>

                <!-- TITULO -->
                <td width="64%" class="header-title" style="border: none; color: #003366;">
                    <div style="font-size: 13px; font-weight: bold; line-height: 1.2;">
                        {{ strtoupper($cfg->config('laboratorio_nombre')) }}
                    </div>

                    <div style="font-size: 13px; font-weight: bold; line-height: 1.2;">
                        {{ strtoupper($cfg->config('universidad_nombre')) }}
                    </div>

                    <div style="font-size: 18px; font-weight: bold; margin-top: 2px;">
                        {{ strtoupper($cfg->config('institucion_sigla')) }}
                    </div>

                    <div style="display: inline-block; padding: 2px 6px; margin-top: 2px; font-size: 8px;">
                        {{ $cfg->codigo_documento }}/Ver. {{ $cfg->version }}/{{ $cfg->fecha_documento }}
                    </div>
                </td>

                <!-- VACIO -->
                <td width="18%" style="border: none;"></td>

            </tr>

        </table>
        <!-- LINEA -->
        <div style="border-top: 2px solid #2d5ea8; margin-top: 4px;"></div>

    </header>

    @php
        $maxMuestras = 0;
        foreach($proforma->parametros as $p) {
            $cant = $p->pivot->cantidad_muestras ?? 1;
            if($cant > $maxMuestras) $maxMuestras = $cant;
        }
        if($maxMuestras == 0) $maxMuestras = 1;
        $totalBloques = (int)ceil($maxMuestras / 2);
    @endphp

    @for($bloque = 0; $bloque < $totalBloques; $bloque++)
    @php
        $m1 = $bloque * 2 + 1;
        $m2 = $bloque * 2 + 2;
        $tieneM2 = $m2 <= $maxMuestras;
    @endphp
    <div @if($bloque > 0) style="page-break-before: always;" @endif>

    <!-- TÍTULO -->
    <table>
        <tr>
            <td class="titulo center" style="font-size: 18px;">
                INFORME DE ENSAYO
            </td>
        </tr>
        <tr>
            <td style="text-align: right; color: #ef1111; font-size: 16px;">
                <strong>Nº:</strong> {{ $proforma->numero_recepcion ?? $proforma->codigo }}
            </td>
        </tr>
    </table>

    <!-- ===================================================== -->
    <!-- DATOS DEL CLIENTE Y MUESTRA -->
    <!-- ===================================================== -->

    <br>

    <table style="width: 100%; border-collapse: collapse; color: #003366; font-size: 11px; font-family: "Times New Roman", Times, serif; table-layout: fixed;">

    <!-- RAZÓN SOCIAL -->
    <tr>
        <td colspan="2" style="padding: 6px; font-weight: bold; text-align: left;">
            
            RAZÓN SOCIAL/CLIENTE:
            <span style="font-weight: normal; ">
                {{ $proforma->cliente->razon_social ?? '---' }}
            </span>
        </td>
    </tr>

    <!-- CUERPO PRINCIPAL -->
    <tr>

        <!-- IZQUIERDA -->
        <td style="width: 60%; vertical-align: top; padding: 3px; text-align: left;">

            <table style="width: 100%; border-collapse: collapse; table-layout: fixed; text-align: left;">

                <tr>
                    <td style="width: 60%; font-weight: bold; padding: 3px; text-align: left;">CONTACTO CLIENTE:</td>
                    <td style="width: 60%; padding: 3px; text-align: left;">{{ $proforma->cliente->persona_contacto ?? '---' }}</td>
                </tr>

                <tr>
                    <td style="font-weight: bold; padding: 3px; text-align: left;">TIPO DE MUESTRA:</td>
                    <td style="padding: 3px; text-align: left;">{{ $proforma->tipo_muestra ?? '---' }}</td>
                </tr>

                <tr>
                    <td style="font-weight: bold; padding: 3px; text-align: left;">MUESTREADO POR:</td>
                    <td style="padding: 3px; text-align: left;">{{ $proforma->muestreado_por ?? 'CLIENTE' }}</td>
                </tr>

                <tr>
                    <td style="font-weight: bold; padding: 3px; text-align: left;">PROCEDENCIA:</td>
                    <td style="padding: 3px; text-align: left;">{{ $proforma->procedencia ?? '---' }}</td>
                </tr>

            </table>

        </td>

        <!-- DERECHA -->
        <td style="width: 40%; vertical-align: top; padding: 3px; text-align: left;">

            <table style="width: 100%; border-collapse: collapse; table-layout: fixed; text-align: left;">

                <tr>
                    <td style="width: 40%; font-weight: bold; padding: 3px; text-align: left;">FECHA RECEPCIÓN:</td>
                    <td style="width: 40%; padding: 3px; text-align: left;">
                        {{ optional($proforma->fecha_recepcion)->format('Y-m-d') ?? '---' }}
                    </td>
                </tr>

                <tr>
                    <td style="font-weight: bold; padding: 3px; text-align: left;">INICIO ENSAYO:</td>
                    <td style="padding: 3px; text-align: left;">{{ $proforma->fecha_recepcion->format('d/m/Y') }}</td>  //{{ optional($proforma->fecha_inicio_ensayo)->format('Y-m-d') ?? '---' }}
                </tr>

                <tr>
                    <td style="font-weight: bold; padding: 3px; text-align: left;">CONCLUSIÓN ENSAYO:</td> 
                    <td style="padding: 3px; text-align: left;">{{ optional($proforma->fecha_conclusion_ensayo)->format('Y-m-d') ?? '---' }}</td>
                </tr>

                <tr>
                    <td style="font-weight: bold; padding: 3px; text-align: left;">FECHA EMISIÓN:</td>
                    <td style="padding: 3px; text-align: left;">{{ now()->format('Y-m-d') }}</td>
                </tr>

            </table>

        </td>

    </tr>

</table>

    <br>
    <table style="width: 96%; margin: 0 auto; border-collapse: collapse; table-layout: fixed; border: 2px solid #000; font-size: 10px;">
        <!-- FILA SUPERIOR -->
        <tr>
            <td rowspan="5" style="width: 15%; border: 1px solid #000; text-align: center; vertical-align: middle; font-weight: bold; background: #f5f5f5;">
                DATOS DE<br>LA MUESTRA<br>
                <span style="font-size: 10px;">(M{{ $m1 }}@if($tieneM2)-M{{ $m2 }}@endif)</span>
            </td>

            <td colspan="3" style="border: 1px solid #000; background: #9bd9e6; font-weight: bold; height: 26px;">
                CÓDIGO DE LABORATORIO:
            </td>

            @for($n = $m1; $n <= ($tieneM2 ? $m2 : $m1); $n++)
            <td style="border: 1px solid #000; background: #9bd9e6;">
                {{ $proforma->generarCodigoLaboratorio($n) ?? '---' }}
            </td>
            @endfor
            <td style="border: 1px solid #000; background: #9bd9e6;">&nbsp;</td>

        </tr>

        <!-- FILA -->
        <tr>

            <td colspan="3" style="border: 1px solid #000; background: #9bd9e6; font-weight: bold; height: 18px;">
                CÓDIGO CLIENTE:
            </td>

            @for($n = $m1; $n <= ($tieneM2 ? $m2 : $m1); $n++)
            <td style="border: 1px solid #000; background: #9bd9e6;">
                {{ $proforma->codigo_cliente[$n - 1] ?? '---' }}
            </td>
            @endfor
            <td style="border: 1px solid #000; background: #9bd9e6;">&nbsp;</td>

        </tr>

        <!-- FILA -->
        <tr>

            <td colspan="3" style="border: 1px solid #000; background: #9bd9e6; font-weight: bold; height: 18px;">
                FECHA DE MUESTREO:
            </td>

            @for($n = $m1; $n <= ($tieneM2 ? $m2 : $m1); $n++)
            <td style="border: 1px solid #000; background: #9bd9e6;">
                {{ $proforma->fecha_emision->format('d/m/Y') }}
            </td>
            @endfor
            <td style="border: 1px solid #000; background: #9bd9e6;">&nbsp;</td>

        </tr>

        <!-- COORDENADAS -->
        <tr>

            <td rowspan="2" colspan="2" style="border: 1px solid #000; background: #9bd9e6; font-weight: bold; height: 34px;">
                COORDENADAS DE PUNTO DE MUESTREO: {{ $muestreo->zona_utm ?? 'ZONA 19K' }}
            </td>

            <td style="border: 1px solid #000; background: #9bd9e6; font-weight: bold; height: 18px;">
                {{ $muestreo->punto_cardinal_1 ?? 'E' }}
            </td>

            @for($n = $m1; $n <= ($tieneM2 ? $m2 : $m1); $n++)
            <td style="border: 1px solid #000; background: #9bd9e6;">
                {{ $muestreo->valor_cardinal_1 ?? '---' }}
            </td>
            @endfor
            <td style="border: 1px solid #000; background: #9bd9e6;">&nbsp;</td>

        </tr>

        <!-- N -->
        <tr>

            <td style="border: 1px solid #000; background: #9bd9e6; font-weight: bold; height: 18px;">
                {{ $muestreo->punto_cardinal_2 ?? 'N' }}
            </td>

            @for($n = $m1; $n <= ($tieneM2 ? $m2 : $m1); $n++)
            <td style="border: 1px solid #000; background: #9bd9e6;">
                {{ $muestreo->valor_cardinal_2 ?? '---' }}
            </td>
            @endfor
            <td style="border: 1px solid #000; background: #9bd9e6;">&nbsp;</td>

        </tr>

        <!-- CABECERA – FILA AGRUPADA -->
        <tr>
            <td rowspan="2" style="border: 1px solid #000; background: #9bd9e6; font-weight: bold; height: 32px; vertical-align: middle;">
                PARAMETRO
            </td>

            <td rowspan="2" style="border: 1px solid #000; background: #9bd9e6; font-weight: bold; vertical-align: middle; font-size: 9px;">
                POE / TÉCNICA
            </td>

            <td rowspan="2" style="border: 1px solid #000; background: #9bd9e6; font-weight: bold; vertical-align: middle;">
                LIM. CUANTIFIC.
            </td>

            <td rowspan="2" style="border: 1px solid #000; background: #9bd9e6; font-weight: bold; vertical-align: middle;">
                UNIDAD
            </td>

            @for($n = $m1; $n <= ($tieneM2 ? $m2 : $m1); $n++)
            <td style="border: 1px solid #000; background: #9bd9e6; font-weight: bold; text-align: center; vertical-align: middle;">
                MUESTRA {{ $n }}
            </td>
            @endfor
            <td rowspan="2" style="border: 1px solid #000; background: #9bd9e6; font-weight: bold; text-align: center; vertical-align: middle;">
                LÍM. PERM.
            </td>

        </tr>

        <!-- CABECERA – SUB-FILA -->
        <tr>
            @for($n = $m1; $n <= ($tieneM2 ? $m2 : $m1); $n++)
            <td style="border: 1px solid #000; background: #9bd9e6; font-weight: bold; text-align: center;">
                RESULT.
            </td>
            @endfor

        </tr>

        <!-- FILAS DINAMICAS -->
        @foreach($proforma->parametros as $p)

        <tr>
            <td style="border: 1px solid #000; height: 28px; text-align: center; vertical-align: middle;">
                {{ $p->nombre }}
            </td>

            <td style="border: 1px solid #000; text-align: center; vertical-align: middle; font-size: 9px;">
                {{ $p->codigo_poe ?? '---' }} - {{ $p->tecnica ?? '---' }}
            </td>

            <td style="border: 1px solid #000; text-align: center; vertical-align: middle;">
                {{ $p->limite_cuantificacion ?? '---' }}
            </td>

            <td style="border: 1px solid #000; text-align: center; vertical-align: middle;">
                {{ $p->unidad ?? '---' }}
            </td>

            @for($n = $m1; $n <= ($tieneM2 ? $m2 : $m1); $n++)
            <td style="border: 1px solid #000; text-align: center; vertical-align: middle;">
                {{ $resultados[$n][$p->id] ?? '---' }}
            </td>
            @endfor
            <td style="border: 1px solid #000; text-align: center; vertical-align: middle;">
                @php $lp = $limitesMap[$p->nombre] ?? null; @endphp
                @if($lp)
                    @if($tipo === 'ANEXO_A-2')
                        D: {{ $lp->limite_diario ?? '—' }}<br>
                        M: {{ $lp->limite_mes ?? '—' }}
                    @else
                        {{ $lp->limite_permisible }}
                    @endif
                @else
                    ---
                @endif
            </td>

        </tr>

        @endforeach

    </table>
    <br>
    <table>
        <tr>
            <td>
                <b>POE:</b> Procedimiento Operativo de Ensayo, describo con detalle en la pista general de ensayos ofertados.<br>
            </td>
        </tr>
    </table>
    <div style="font-size: 10px; margin-top: 15px; padding: 8px; background-color: #f8f9fa; border-radius: 3px; border-left: 3px solid #2c5282;">
        <p style="text-align: left;"> {{ $cfg->config('nota1', 'La información del presente informe corresponde a los resultados de ensayos en la muestra recepcionada.') }}</p>
        <p style="text-align: left;"> {{ $cfg->config('nota2', '"CIMA-UATF", NO asume ninguna responsabilidad sobre la información proporcionada por el cliente, que pueda afectar la validez de los resultados.') }}</p>
        <p style="text-align: left;"> {{ $cfg->config('nota3', '"CIMA-UATF", solo reconoce como válidos, informes de ensayo emitidos en soporte físico, con las firmas y sellos autorizados.') }}</p>
    </div>
    </div>
    @endfor

    <footer>
        <table class="sin-borde">
            <tr class="sin-borde">
                <td class="sin-borde center">
                    <br><br><br>
                    _________________________
                    <br>
                    {{ $cfg->config('responsable_nombre') }}
                    <br>
                    <strong>{{ $cfg->config('responsable_cargo') }}</strong>
                </td>
                <td class="sin-borde center">
                    <br><br><br>
                    _________________________
                    <br>
                    {{ $cfg->config('director_nombre') }}
                    <br>
                    <strong>{{ $cfg->config('director_cargo') }}</strong>
                </td>
            </tr>
        </table>
        <br><br>

        <div style="border-top: 2px solid #2d5ea8; margin-bottom: 4px;">
        </div>

        <table class="footer-table">

            <tr>

                <td class="footer-text" style="border: none; text-align: center; font-size: 8px; line-height: 1.1;">
                    {{ $cfg->config('footer_direccion') }} Edificio facultad de Ingenieria Minera bloque 1. segundo piso; Telefono/Fax:62-29711

                    <br>

                    {{ $cfg->config('footer_telefono') }}; {{ $cfg->config('footer_email') }}

                    <br>

                    <strong>
                        Página
                        <span class="pagenum"></span>
                        de
                        <span class="pagenum"></span>
                    </strong>
                    <br><br>

                </td>

            </tr>

        </table>

    </footer>
    <br><br>
</body>
</html>
