<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <style>

        @page{
            size: letter landscape;
            margin: 10mm 8mm 10mm 8mm;
        }

        body{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #000;
        }

        *{
            box-sizing: border-box;
        }

        /* =====================================================
           TABLAS
        ====================================================== */

        table{
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th{
            border: 1px solid #000;
            text-align: center;
            vertical-align: middle;
            padding: 1px;
            overflow: hidden;
        }

        .custodia-table{
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }
        .text-center{
            text-align: center;
            font-size: 16px;
        }

        /* =====================================================
           ENCABEZADO
        ====================================================== */

        .logo-container{
            width: 75px;
            height: 75px;
            border: 1px solid #aaa;
            margin: auto;
            text-align: center;
            background: #f5f5f5;
        }

        .logo-container img{
            max-width: 65px;
            max-height: 65px;
            margin-top: 5px;
        }

        .titulo-principal{
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #1c3d6e;
            margin: 0;
        }

        .codigo-cell{
            font-size: 9px;
            font-weight: bold;
            padding: 6px;
        }

        /* =====================================================
           CHECKBOX
        ====================================================== */

        .checkbox-row{
            text-align: center;
            margin: 12px 0;
        }

        .checkbox-option{
            display: inline-block;
            margin: 0 25px;
            font-size: 14px;
        }

        .checkbox-square{
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 1px solid #000;
            text-align: center;
            line-height: 16px;
            font-size: 12px;
            font-weight: bold;
            vertical-align: middle;
            margin-left: 5px;
        }

        /* =====================================================
           ALTURAS
        ====================================================== */

        .fila-superior{
            height: 12mm;
        }

        .fila-sub{
            height: 50mm;
        }

        .fila-datos{
            height: 12mm;
        }

        .fila-datos td{
            height: 8mm;
            vertical-align: top;
        }

        .h-large{
            height: 50mm;
        }

        /* =====================================================
           COLUMNAS PRINCIPALES
        ====================================================== */

        .col-identificacion,
        .col-codigo,
        .col-matriz-principal{
            font-size: 10px;
            font-weight: bold;
            padding: 3px;
            line-height: 1.2;
        }

        /* =====================================================
           MATRIZ
        ====================================================== */

        .matriz-sub{
            width: 7mm;
            min-width: 7mm;
            max-width: 7mm;
            padding: 0;
            height: 45mm;
        }

        /* =====================================================
           COLUMNAS ANALISIS
        ====================================================== */

        .w-small{
            width: 5mm;
            min-width: 5mm;
            max-width: 5mm;
            padding: 0;
        }

        /* =====================================================
           TEXTO VERTICAL
        ====================================================== */

        .vertical-text{
            display: inline-block;
            transform: rotate(-90deg);
            white-space: nowrap;
            font-size: 10px;
        }

        /* =====================================================
           DOMPDF
        ====================================================== */

        tr,
        td,
        th{
            page-break-inside: avoid;
        }

    </style>

</head>

<body>

    <!-- =====================================================
         ENCABEZADO
    ====================================================== -->

    <table>

        <tr>

            <!-- LOGO -->
            <td rowspan="3" style="width:35mm;">

                <div class="logo-container">

                    @if(file_exists(public_path('images/logo-cima.jpg')))
                        <img src="{{ public_path('images/logo-cima.jpg') }}" alt="Logo">
                    @elseif(file_exists(public_path('images/logo-cima.png')))
                        <img src="{{ public_path('images/logo-cima.png') }}" alt="Logo">
                    @endif

                </div>

            </td>

            <!-- TITULO -->
            <td rowspan="3">

                <div class="titulo-principal">
                    CADENA DE CUSTODIA
                </div>

            </td>

            <!-- CODIGO -->
            <td class="codigo-cell" style="width:35mm;">
                <strong>PO04-FR01</strong>
            </td>

        </tr>

        <tr>

            <td class="codigo-cell">
                VERSIÓN: 05
            </td>

        </tr>

        <tr>

            <td class="codigo-cell">
                FECHA: 2024-08-15
            </td>

        </tr>

    </table>

    <!-- =====================================================
         CHECKBOX
    ====================================================== -->

    <div class="checkbox-row">

        <span class="checkbox-option">

            <strong>CONTRATO</strong>

            <span class="checkbox-square">
                {{ $proforma->tipo == 'CONTRATO' ? '✔' : '' }}
            </span>

        </span>

        <span class="checkbox-option">

            <strong>CONTRATO MODIFICADO</strong>

            <span class="checkbox-square">
                {{ $proforma->tipo == 'CONTRATO_MODIFICADO' ? '✔' : '' }}
            </span>

        </span>

    </div>

    <!-- =====================================================
         TABLA PRINCIPAL
    ====================================================== -->

    <table>

        <!-- =================================================
             FILA 1
        ================================================== -->

        <tr class="fila-superior">

            <!-- TIPO MUESTRA -->
            <td colspan="4">

                TIPO DE MUESTRA

            </td>

            <!-- ESPACIO GRANDE -->
            <td colspan="8">{{ $proforma->tipo_muestra }}</td>

            <!-- REQUERIMIENTO -->
            <td colspan="28" rowspan="3" class="text-center">

                <strong>REQUERIMIENTO DE ANALISIS</strong>

            </td>

        </tr>

        <!-- =================================================
             FILA 2
        ================================================== -->

        <tr class="fila-superior">

            <!-- FECHA MUESTREO -->
            <td colspan="4">

                FECHA DE MUESTREO

            </td>

            <!-- ESPACIO GRANDE -->
            <td colspan="8">{{ $proforma->fecha_emision->format('d/m/Y') }}</td>

        </tr>

        <!-- =================================================
             FILA 3
        ================================================== -->

        <tr class="fila-superior">

            <!-- FECHA RECEPCION -->
            <td colspan="4">

                FECHA DE RECEPCION

            </td>

            <!-- ESPACIO GRANDE -->
            <td colspan="8">{{ $proforma->fecha_recepcion->format('d/m/Y') }}</td>

        </tr>

        <!-- =================================================
             ENCABEZADOS PRINCIPALES
        ================================================== -->

        <tr>

            <!-- IDENTIFICACION -->
            <td rowspan="2" colspan="4" class="h-large col-identificacion">

                IDENTIFICACION
                <br>
                DE CAMPO O
                <br>
                CLIENTE

            </td>

            <!-- CODIGO -->
            <td rowspan="2" colspan="4" class="h-large col-codigo">

                CODIGO
                <br>
                LABORATORIO

            </td>

            <!-- MATRIZ -->
            <td colspan="4" class="h-large col-matriz-principal">

                MATRIZ

            </td>

            <!-- =================================================
                 28 COLUMNAS ANALISIS
            ================================================== -->

            @for ($i = 0; $i < 28; $i++)

                <td class="w-small vertical">

                    <span class="vertical-text">

                        {{ $proforma->parametros[$i]->nombre ?? '' }}

                    </span>

                </td>

            @endfor

        </tr>

        <!-- =================================================
             SUBDIVISION MATRIZ
        ================================================== -->

        <tr class="fila-sub">

            <td class="matriz-sub">

                <div class="vertical-text">

                    AGUA SUBTERRANEA

                </div>

            </td>

            <td class="matriz-sub">

                <div class="vertical-text">

                    AGUA SUPERFICIAL

                </div>

            </td>

            <td class="matriz-sub">

                <div class="vertical-text">

                    SUELO

                </div>

            </td>

            <td class="matriz-sub">

                <div class="vertical-text">

                    OTROS

                </div>

            </td>

            <!-- =================================================
                 28 COLUMNAS METODOS
            ================================================== -->

            @for ($i = 0; $i < 28; $i++)

                <td class="w-small vertical">

                    <span class="vertical-text">

                        {{ $proforma->parametros[$i]->metodo ?? '' }}

                    </span>

                </td>

            @endfor

        </tr>

        <!-- =================================================
             FILAS DE MUESTRAS POR PARAMETRO
        ================================================== -->
        
        @php
            // Encontrar el número máximo de muestras entre todos los parámetros
            $maxMuestras = 0;
            foreach($proforma->parametros as $parametro) {
                $cantidad = $parametro->pivot->cantidad_muestras ?? 1;
                if($cantidad > $maxMuestras) {
                    $maxMuestras = $cantidad;
                }
            }
            
            // Si no hay parámetros, al menos mostrar 1 fila
            if($maxMuestras == 0) $maxMuestras = 1;
        @endphp

        @for ($fila = 0; $fila < $maxMuestras; $fila++)
        
        @php
            // Número de muestra actual (1-indexed)
            $numMuestra = $fila + 1;
        @endphp

        <tr class="fila-datos">

            <!-- IDENTIFICACION -->
            <td colspan="4">
                {{ $fila == 0 ? $proforma->procedencia : '' }}
            </td>

            <!-- CODIGO -->
            <td colspan="4">
                {{ $proforma->generarCodigoLaboratorio($numMuestra) }}
            </td>

            <!-- MATRIZ -->
             @php
                $tipo = strtoupper($proforma->tipo_muestra);
            @endphp

            <!-- AGUA SUBTERRANEA -->
            <td>
                @if(str_contains($tipo, 'SUBTERRANEA'))
                    X
                @endif
            </td>

            <!-- AGUA SUPERFICIAL -->
            <td>
                @if(str_contains($tipo, 'SUPERFICIAL'))
                    X
                @endif
            </td>

            <!-- SUELO -->
            <td>
                @if(str_contains($tipo, 'SUELO'))
                    X
                @endif
            </td>

            <!-- OTROS -->
            <td>
                @if(
                    !str_contains($tipo, 'SUBTERRANEA') &&
                    !str_contains($tipo, 'SUPERFICIAL') &&
                    !str_contains($tipo, 'SUELO')
                )
                    X
                @endif
            </td>

            <!-- =================================================
                 DINAMICA DE COLOR DE CANTIDAD DE MUESTRAS POR PARAMETRO
            ================================================== -->
            @for ($i = 0; $i < 28; $i++)
                
                @php
                    $parametro = $proforma->parametros[$i] ?? null;
                @endphp
                
                @if(!$parametro)
                    <!-- Si NO existe el parámetro: fondo AZUL -->
                    <td class="w-small" style="background-color: #aed0ef;">
                    </td>
                @else
                    @php
                        $cantidadMuestrasParametro = $parametro->pivot->cantidad_muestras ?? 1;
                        $debePintarse = $numMuestra <= $cantidadMuestrasParametro;
                    @endphp
                    
                    @if($debePintarse)
                        <!-- Si APPLICA para esta muestra: fondo blanco -->
                        <td class="w-small" style="background-color: #ffff; text-align: center; vertical-align: middle;">
                        </td>
                    @else
                        <!-- Si NO APPLICA para esta muestra: fondo AZUL -->
                        <td class="w-small" style="background-color: #aed0ef;">
                        </td>
                    @endif
                @endif
                
            @endfor

        </tr>

        @endfor

    </table>

</body>

</html>