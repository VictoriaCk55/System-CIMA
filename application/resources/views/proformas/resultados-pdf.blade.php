<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">

<style>

    @page {
        margin: 15px;
    }

    body{
        font-family: "Times New Roman", Times, serif;
        font-size: 11px;
        color:#000;
    }

    table{
        width: auto;
        border-collapse: collapse;
    }

    .wrapper{
        text-align: center;
    }

    .table-content{
        display: inline-block;
        text-align: left;
    }

    .table-content table{
        width: 100%;
    }

    td, th{
        border:1px solid #000;
        padding:4px;
        text-align:center;
        vertical-align: middle;
    }

    .logo{
        width:90px;
    }

    .titulo{
        font-size:22px;
        font-weight:bold;
    }

    .encabezado td{
        height:40px;
    }

    .left{
        text-align:left;
    }

    .small{
        font-size:9px;
    }

    .codigo-lab{
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        width:20px;
        font-weight:bold;
    }

</style>

</head>
<body>
@php $parametrosReversed = $proforma->parametros->reverse(); @endphp
<div class="wrapper">
<div class="table-content">

<table class="encabezado">

    <tr>

        <td rowspan="3" width="120">

            @php
                $cfg = \App\Models\Documento::whereSlug('resultados-ensayo')->first() ?? new \App\Models\Documento;
                $logo = $cfg->config('logo_path');
            @endphp
            @if($logo && file_exists(storage_path('app/public/' . $logo)))
                <img src="{{ storage_path('app/public/' . $logo) }}" class="logo">
            @else
                <img src="{{ public_path('images/logo-cima.jpg') }}" class="logo">
            @endif

        </td>

        <td rowspan="3" class="titulo">

            RESULTADOS DE ENSAYO

        </td>

        <td class="left" width="180">
            {{ $cfg->codigo_documento }}
        </td>

    </tr>

    <tr>

        <td class="left">
            VERSION: {{ $cfg->version }}
        </td>

    </tr>

    <tr>

        <td class="left">
            FECHA: {{ $cfg->fecha_documento }}
        </td>

    </tr>

</table>

<br>

<table>

    <tr>

        <th colspan="2">
            Parámetros
        </th>

        @foreach($parametrosReversed as $p)

            <th>
                {{ $p->nombre }}
            </th>

        @endforeach

    </tr>

    <tr>

        <td colspan="2">
            <strong>
                Límites de cuantificación
            </strong>
        </td>

        @foreach($parametrosReversed as $p)

            <td>
                {{ ($limites[$p->id] ?? $p->limite_cuantificacion) ?: '---' }}
            </td>

        @endforeach

    </tr>

    <tr>

        <td colspan="2">
            <strong>
                Unidad
            </strong>
        </td>

        @foreach($parametrosReversed as $p)

            <td>
                {{ ($unidades[$p->id] ?? $p->unidad) ?: '---' }}
            </td>

        @endforeach

    </tr>

    <tr>

        <td colspan="2">
            <strong>
                Método ó técnica de ensayo
            </strong>
        </td>

        @foreach($parametrosReversed as $p)

            <td class="small">
                {{ $p->codigo_poe ?? '---' }}
            </td>

        @endforeach

    </tr>

    <tr>

        <td colspan="2">
            <strong>
                Responsable de ensayo
            </strong>
        </td>

        @foreach($parametrosReversed as $p)

            <td>
                {{ $responsables[$p->id] ?? '---' }}
            </td>

        @endforeach

    </tr>

    <tr>

        <td colspan="2">
            <strong>
                Fecha de ensayo
            </strong>
        </td>

        @foreach($parametrosReversed as $p)

            <td>
                {{ $fechas[$p->id] ?? '---' }}
            </td>

        @endforeach

    </tr>

    @php $firstCodLab = true; $totalCodLab = count($resultados); @endphp
    @foreach($resultados as $muestra => $datos)

    <tr>

        @if($firstCodLab)
        <td rowspan="{{ $totalCodLab }}">
            <strong>Cod. Lab.</strong>
        </td>
        @php $firstCodLab = false; @endphp
        @endif

        <td>

            {{ $proforma->generarCodigoLaboratorio($muestra) }}

        </td>

        @foreach($parametrosReversed as $p)

            <td>

                {{ $datos[$p->id] ?? '---' }}

            </td>

        @endforeach

    </tr>

    @endforeach

    <tr>

        <td>
            <strong>V°B°</strong>
        </td>

        <td colspan="{{ count($parametrosReversed) + 1 }}">

            @php
                $vbsList = array_unique(array_filter(array_values($vbs ?? [])));
            @endphp
            {{ !empty($vbsList) ? implode(' / ', $vbsList) : '---' }}

        </td>

    </tr>

</table>

<br>

<table>

    <tr>

        <td style="border:none; text-align:right;">

            Pag 1 de 1

        </td>

    </tr>

</table>

</div>
</div>
</body>
</html>