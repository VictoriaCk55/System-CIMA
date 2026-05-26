<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">

<style>

    @page {
        margin: 15px;
    }

    body{
        font-family: DejaVu Sans, sans-serif;
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
<div class="wrapper">
<div class="table-content">

<table class="encabezado">

    <tr>

        <td rowspan="3" width="120">

            <img src="{{ public_path('images/logo-cima.jpg') }}" class="logo">

        </td>

        <td rowspan="3" class="titulo">

            RESULTADOS DE ENSAYO

        </td>

        <td class="left" width="180">
            MTD1-FR06
        </td>

    </tr>

    <tr>

        <td class="left">
            VERSION: 05
        </td>

    </tr>

    <tr>

        <td class="left">
            FECHA: 2024-08-15
        </td>

    </tr>

</table>

<br>

<table>

    <tr>

        <th>
            Parámetros
        </th>

        @foreach($proforma->parametros as $p)

            <th>
                {{ $p->nombre }}
            </th>

        @endforeach

    </tr>

    <tr>

        <td>
            <strong>
                Límites de cuantificación
            </strong>
        </td>

        @foreach($proforma->parametros as $p)

            <td>
                {{ ($limites[$p->id] ?? '') ?: '---' }}
            </td>

        @endforeach

    </tr>

    <tr>

        <td>
            <strong>
                Unidad
            </strong>
        </td>

        @foreach($proforma->parametros as $p)

            <td>
                {{ ($unidades[$p->id] ?? '') ?: '---' }}
            </td>

        @endforeach

    </tr>

    <tr>

        <td>
            <strong>
                Método ó técnica de ensayo
            </strong>
        </td>

        @foreach($proforma->parametros as $p)

            <td class="small">
                {{ $p->metodo ?? '---' }}
            </td>

        @endforeach

    </tr>

    <tr>

        <td>
            <strong>
                Responsable de ensayo
            </strong>
        </td>

        @foreach($proforma->parametros as $p)

            <td>
                {{ $responsables[$p->id] ?? '---' }}
            </td>

        @endforeach

    </tr>

    <tr>

        <td>
            <strong>
                Fecha de ensayo
            </strong>
        </td>

        @foreach($proforma->parametros as $p)

            <td>
                {{ $fechas[$p->id] ?? '---' }}
            </td>

        @endforeach

    </tr>

    @foreach($resultados as $muestra => $datos)

    <tr>

        <td>

            {{ $proforma->generarCodigoLaboratorio($muestra) }}

        </td>

        @foreach($proforma->parametros as $p)

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

        <td colspan="{{ count($proforma->parametros) }}">

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