<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    @php $cfg = \App\Models\Documento::whereSlug('informe-final')->first() ?? new \App\Models\Documento; @endphp
    <title>INFORME {{ $informe->codigo }} - {{ $cfg->config('institucion_sigla', 'CIMA') }}</title>
    <style>
        /* ========== CONFIGURACIÓN BASE ========== */
        @page {
            margin: 15mm 10mm 15mm 10mm;
        }
        
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11px;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 0;
            background-color: white;
        }
        
        /* ========== ENCABEZADO CON TABLA TRADICIONAL ========== */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .header-table td {
            vertical-align: top;
            padding: 0;
        }
        
        .logo-cell {
            width: 100px;
        }
        
        .logo-container {
            width: 90px;
            height: 90px;
            border: 1px solid #ccc;
            display: block;
            background-color: #f9f9f9;
            overflow: hidden;
            text-align: center;
        }
        
        .logo-container img {
            max-width: 85px;
            max-height: 85px;
            margin-top: 2px;
        }
        
        .center-cell {
            text-align: center;
            padding: 0 10px;
        }
        
        .center-cell h1 {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 3px 0;
            text-transform: uppercase;
            color: #1c3d6e;
        }
        
        .center-cell h2 {
            font-size: 13px;
            margin: 0 0 2px 0;
            color: #2c5282;
            font-weight: 600;
        }
        
        .center-cell h3 {
            font-size: 11px;
            margin: 0 0 4px 0;
            color: #4a5568;
            font-style: italic;
        }
        
        .document-subtitle {
            font-size: 10px;
            font-weight: bold;
            color: #333;
            margin: 5px 0;
        }
        
        .document-options {
            margin: 8px 0 0 0;
            font-size: 10px;
        }
        
        .doc-option {
            display: inline-block;
            margin: 0 4px;
            padding: 2px 6px;
            border: 1px solid #666;
            border-radius: 2px;
            background-color: #f8f9fa;
        }
        
        .doc-option.selected {
            background-color: #2c5282;
            color: white;
            border-color: #2c5282;
            font-weight: bold;
        }
        
        .codigo-cell {
            width: 120px;
            text-align: right;
        }
        
        .codigo-box {
            border: 1px solid #000;
            padding: 5px;
            font-size: 9px;
            background-color: #f8f9fa;
            text-align: center;
            display: inline-block;
        }
        
        /* ========== LÍNEA SEPARADORA ========== */
        .separator {
            border-top: 2px solid #1c3d6e;
            margin: 8px 0 15px 0;
            width: 100%;
        }
        
        /* ========== SECCIONES ========== */
        .section-title {
            background-color: #2c5282;
            color: white;
            font-weight: bold;
            padding: 5px 10px;
            margin-bottom: 8px;
            font-size: 11px;
            border-radius: 3px;
        }
        
        /* ========== TABLAS DE DATOS ========== */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 10px;
        }
        
        .data-table th, .data-table td {
            border: 1px solid #999;
            padding: 5px 8px;
            vertical-align: top;
        }
        
        .data-table th {
            background-color: #e0e0e0;
            text-align: left;
            font-weight: bold;
        }
        
        /* ========== TABLA DE PARÁMETROS ========== */
        .params-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 15px;
        }
        
        .params-table thead th {
            background-color: #2c5282;
            color: white;
            text-align: center;
            padding: 6px;
            border: 1px solid #999;
            font-weight: bold;
        }
        
        .params-table tbody td {
            border: 1px solid #999;
            padding: 5px 6px;
        }
        
        /* ========== TABLA DE CRONOGRAMA ========== */
        .cronograma-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 10px;
        }
        
        .cronograma-table td {
            border: 1px solid #999;
            padding: 6px 8px;
            vertical-align: top;
        }
        
        .cronograma-table td:first-child {
            width: 40%;
            background-color: #f0f4f8;
            font-weight: bold;
        }
        
        /* ========== CONTENIDO TÉCNICO ========== */
        .contenido-box {
            border: 1px solid #999;
            padding: 12px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
            border-radius: 3px;
            font-size: 10px;
            line-height: 1.5;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        
        .conclusion-box {
            border: 1px solid #2c5282;
            padding: 12px;
            margin-bottom: 10px;
            background-color: #e8f0fe;
            border-radius: 3px;
            font-size: 10px;
            line-height: 1.5;
            border-left: 4px solid #2c5282;
        }
        
        .recomendacion-box {
            border: 1px solid #856404;
            padding: 12px;
            margin-bottom: 10px;
            background-color: #fff3cd;
            border-radius: 3px;
            font-size: 10px;
            line-height: 1.5;
            border-left: 4px solid #856404;
        }
        
        /* ========== BADGES DE ESTADO Y PRIORIDAD ========== */
        .estado-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: white;
            background-color: #6c757d;
        }
        
        .estado-APROBADO { background-color: #28a745; }
        .estado-BORRADOR { background-color: #6c757d; }
        .estado-EN_PROCESO { background-color: #ffc107; color: #212529; }
        .estado-REVISADO { background-color: #17a2b8; }
        .estado-ENTREGADO { background-color: #007bff; }
        
        .prioridad-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: white;
        }
        
        .prioridad-URGENTE { background-color: #dc3545; }
        .prioridad-ALTA { background-color: #fd7e14; }
        .prioridad-MEDIA { background-color: #ffc107; color: #212529; }
        .prioridad-BAJA { background-color: #28a745; }
        
        /* ========== RESPONSABLES - SIN EMOTICONS ========== */
        .responsables-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 10px;
        }
        
        .responsables-table td {
            border: 1px solid #999;
            padding: 6px 8px;
        }
        
        .responsables-table td:first-child {
            width: 30%;
            background-color: #f0f4f8;
            font-weight: bold;
        }
        
        /* ========== FIRMAS CON TABLA TRADICIONAL ========== */
        .signatures-table {
            width: 100%;
            margin-top: 60px;
            border-collapse: collapse;
        }
        
        .signatures-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            width: 80%;
            margin: 50px auto 0 auto;
            display: block;
        }
        
        .signature-text {
            margin-top: 5px;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
        }
        
        /* ========== UTILIDADES ========== */
        .align-right { text-align: right; }
        .align-center { text-align: center; }
        .align-left { text-align: left; }
        .bold { font-weight: bold; }
        .mb-10 { margin-bottom: 10px; }
        .mt-10 { margin-top: 10px; }
        .mt-30 { margin-top: 30px; }
        
        /* ========== FOOTER ========== */
        .footer {
            margin-top: 40px;
            font-size: 9px;
            color: #666;
            text-align: center;
            padding-top: 8px;
            border-top: 1px solid #ccc;
        }
        
        /* ========== ESPACIO EN BLANCO PARA FIRMAS ========== */
        .firma-espacio {
            height: 25px;
            width: 100%;
        }
    </style>
</head>
<body>
    <!-- ========== ENCABEZADO CON TABLA TRADICIONAL ========== -->
    <table class="header-table">
        <tr>
            <!-- Logo a la izquierda -->
            <td class="logo-cell">
                <div class="logo-container">
                    @php
                        $logo = $cfg->config('logo_path');
                    @endphp
                    @if($logo && file_exists(storage_path('app/public/' . $logo)))
                        <img src="{{ storage_path('app/public/' . $logo) }}" alt="Logo">
                    @elseif(file_exists(public_path('images/logo-cima.jpg')))
                        <img src="{{ public_path('images/logo-cima.jpg') }}" alt="Logo CIMA">
                    @elseif(file_exists(public_path('images/logo-cima.png')))
                        <img src="{{ public_path('images/logo-cima.png') }}" alt="Logo CIMA">
                    @else
                        <div style="width: 85px; height: 85px; background: #2c5282; color: white; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: bold;">{{ $cfg->config('institucion_sigla', 'CIMA') }}</div>
                    @endif
                </div>
            </td>
            
            <!-- Título centrado -->
            <td class="center-cell">
                <h1>INFORME TÉCNICO</h1>
                <h2>{{ strtoupper($cfg->config('laboratorio_nombre')) }}</h2>
                <h3>{{ $cfg->config('footer_texto') }}</h3>
                
                <div class="document-subtitle">
                    {{ $cfg->config('footer_texto') }}
                </div>
                
                <div class="document-options">
                    <span class="doc-option selected">INFORME FINAL</span>
                    <span class="doc-option">INFORME PRELIMINAR</span>
                    <span class="doc-option">INFORME COMPLEMENTARIO</span>
                </div>
            </td>
            
            <!-- Código a la derecha -->
            <td class="codigo-cell">
                <div class="codigo-box">
                    <div><strong>{{ $cfg->codigo_documento }}</strong></div>
                    <div>VERSIÓN: {{ $cfg->version }}</div>
                    <div>FECHA: {{ $cfg->fecha_documento ?? $informe->fecha_emision->format('Y-m-d') }}</div>
                </div>
            </td>
        </tr>
    </table>
    
    <!-- Línea separadora -->
    <div class="separator"></div>

    <!-- ========== INFORMACIÓN DEL ESTADO Y PRIORIDAD ========== -->
    <div style="margin-bottom: 15px; padding: 8px 12px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; display: flex; justify-content: space-between;">
        <div>
            <span style="font-weight: bold; margin-right: 10px;">ESTADO:</span>
            <span class="estado-badge estado-{{ $informe->estado }}">
                {{ $informe->estado_texto }}
            </span>
        </div>
        <div>
            <span style="font-weight: bold; margin-right: 10px;">PRIORIDAD:</span>
            <span class="prioridad-badge prioridad-{{ $informe->prioridad }}">
                {{ $informe->prioridad_texto }}
            </span>
        </div>
    </div>

    <!-- ========== SECCIÓN 1: DATOS DE LA PROFORMA ASOCIADA ========== -->
    @if($informe->proforma)
    <div class="mb-10">
        <div class="section-title">1.- DATOS DE LA PROFORMA ASOCIADA</div>
        
        <table class="data-table">
            <tr>
                <td style="width: 25%;"><strong>Código Proforma:</strong></td>
                <td style="width: 75%;">{{ $informe->proforma->codigo }}</td>
            </tr>
            <tr>
                <td><strong>Cliente:</strong></td>
                <td>{{ $informe->proforma->cliente->razon_social ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Persona de contacto:</strong></td>
                <td>{{ $informe->proforma->persona_contacto ?? $informe->proforma->cliente->persona_contacto ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Teléfono:</strong></td>
                <td>{{ $informe->proforma->telefono_contacto ?? $informe->proforma->cliente->telefono ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>NIT:</strong></td>
                <td>{{ $informe->proforma->cliente->nit ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Tipo de muestra:</strong></td>
                <td>{{ $informe->proforma->tipo_muestra }}</td>
            </tr>
            @if($informe->proforma->procedencia)
            <tr>
                <td><strong>Procedencia:</strong></td>
                <td>{{ $informe->proforma->procedencia }}</td>
            </tr>
            @endif
            @if($informe->proforma->coordenadas)
            <tr>
                <td><strong>Coordenadas:</strong></td>
                <td>{{ $informe->proforma->coordenadas }}</td>
            </tr>
            @endif
            <tr>
                <td><strong>Fecha recepción:</strong></td>
                <td>{{ $informe->proforma->fecha_recepcion->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td><strong>Muestreado por:</strong></td>
                <td>{{ $informe->proforma->muestreado_por ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- ========== SECCIÓN 2: PARÁMETROS SOLICITADOS - SIN EMOTICONS ========== -->
    @if($informe->proforma->parametros && $informe->proforma->parametros->count() > 0)
    <div class="mb-10">
        <div class="section-title">2.- PARÁMETROS SOLICITADOS</div>
        
        <table class="params-table">
            <thead>
                <tr>
                    <th style="width: 5%;" class="align-center">#</th>
                    <th style="width: 55%;" class="align-left">Parámetro</th>
                    <th style="width: 15%;" class="align-center">Método</th>
                    <th style="width: 10%;" class="align-center">N° Muestras</th>
                    <th style="width: 15%;" class="align-right">Precio Unit. (Bs)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($informe->proforma->parametros as $index => $parametro)
                <tr>
                    <td class="align-center">{{ $index + 1 }}</td>
                    <td class="align-left">{{ $parametro->nombre }}</td>
                    <td class="align-center">{{ $parametro->metodo ?? 'N/A' }}</td>
                    <td class="align-center">{{ $parametro->pivot->cantidad_muestras }}</td>
                    <td class="align-right">Bs. {{ number_format($parametro->pivot->precio_unitario, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    @endif

    <!-- ========== SECCIÓN 3: CRONOGRAMA DEL INFORME - SIN EMOTICONS ========== -->
    <div class="mb-10">
        <div class="section-title">3.- CRONOGRAMA DEL INFORME</div>
        
        <table class="cronograma-table">
            @if($informe->fecha_emision)
            <tr>
                <td>Fecha de Emisión:</td>
                <td>{{ $informe->fecha_emision->format('d/m/Y') }}</td>
            </tr>
            @endif
            @if($informe->fecha_analisis)
            <tr>
                <td>Fecha de Análisis:</td>
                <td>{{ \Carbon\Carbon::parse($informe->fecha_analisis)->format('d/m/Y') }}</td>
            </tr>
            @endif
            @if($informe->fecha_revision)
            <tr>
                <td>Fecha de Revisión:</td>
                <td>{{ \Carbon\Carbon::parse($informe->fecha_revision)->format('d/m/Y') }}</td>
            </tr>
            @endif
            @if($informe->fecha_entrega)
            <tr>
                <td style="background-color: #d4edda; font-weight: bold;">Fecha de Entrega:</td>
                <td style="background-color: #d4edda; font-weight: bold;">{{ \Carbon\Carbon::parse($informe->fecha_entrega)->format('d/m/Y') }}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- ========== SECCIÓN 4: RESULTADOS DEL ANÁLISIS ========== -->
    @if($informe->resultado)
    <div class="mb-10">
        <div class="section-title">4.- RESULTADOS DEL ANÁLISIS</div>
        <div class="contenido-box">
            {!! nl2br(e($informe->resultado)) !!}
        </div>
    </div>
    @endif

    <!-- ========== SECCIÓN 5: CONCLUSIONES ========== -->
    @if($informe->conclusiones)
    <div class="mb-10">
        <div class="section-title">5.- CONCLUSIONES</div>
        <div class="conclusion-box">
            {!! nl2br(e($informe->conclusiones)) !!}
        </div>
    </div>
    @endif

    <!-- ========== SECCIÓN 6: RECOMENDACIONES ========== -->
    @if($informe->recomendaciones)
    <div class="mb-10">
        <div class="section-title">6.- RECOMENDACIONES</div>
        <div class="recomendacion-box">
            {!! nl2br(e($informe->recomendaciones)) !!}
        </div>
    </div>
    @endif

    <!-- ========== SECCIÓN 7: OBSERVACIONES ========== -->
    @if($informe->observaciones)
    <div class="mb-10">
        <div class="section-title">7.- OBSERVACIONES</div>
        <div style="padding: 10px; border: 1px solid #999; background-color: #fff9c4; border-radius: 3px; font-size: 10px;">
            {!! nl2br(e($informe->observaciones)) !!}
        </div>
    </div>
    @endif

    <!-- ========== SECCIÓN 8: RESPONSABLES - SIN EMOTICONS, SIN LÍNEAS ========== -->
    <div class="mb-10">
        <div class="section-title">8.- RESPONSABLES</div>
        
        <table class="responsables-table">
            <tr>
                <td>Creado por:</td>
                <td style="padding: 6px 8px;">&nbsp;</td>
            </tr>
            @if($informe->revisor)
            <tr>
                <td>Revisado por:</td>
                <td style="padding: 6px 8px;">&nbsp;</td>
            </tr>
            @endif
            @if($informe->aprobador)
            <tr>
                <td>Aprobado por:</td>
                <td style="padding: 6px 8px;">&nbsp;</td>
            </tr>
            @endif
            @if($informe->entregador)
            <tr>
                <td>Entregado por:</td>
                <td style="padding: 6px 8px;">&nbsp;</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- ========== FIRMAS - SIN EMOTICONS, ESPACIOS EN BLANCO ========== -->
    <table class="signatures-table">
        <tr>
            <td>
                <div class="signature-line"></div>
                <div class="signature-text">
                    <div style="height: 25px;">&nbsp;</div>
                    {{ $cfg->config('responsable_nombre') }}<br>
                    {{ $cfg->config('responsable_cargo') }}
                </div>
            </td>
            <td>
                <div class="signature-line"></div>
                <div class="signature-text">
                    <div style="height: 25px;">&nbsp;</div>
                    {{ $cfg->config('director_nombre') }}<br>
                    {{ $cfg->config('director_cargo') }}
                </div>
            </td>
        </tr>
    </table>

    <!-- ========== NOTAS INSTITUCIONALES ========== -->
    <div style="font-size: 10px; margin-top: 20px; padding: 8px; background-color: #f8f9fa; border-radius: 3px; border-left: 3px solid #2c5282;">
        <p><strong>Nota 1:</strong> {{ $cfg->config('nota1', 'Este informe es válido únicamente con las firmas correspondientes.') }}</p>
        <p><strong>Nota 2:</strong> {{ $cfg->config('nota2', 'Los resultados reportados corresponden exclusivamente a las muestras analizadas.') }}</p>
        <p><strong>Nota 3:</strong> {{ $cfg->config('nota3', 'Prohibida la reproducción parcial de este informe sin autorización del CIMA.') }}</p>
    </div>

    <!-- ========== FOOTER ========== -->
    <div class="footer">
        <p><strong>{{ $cfg->config('institucion_nombre') }}</strong></p>
        <p>{{ $cfg->config('footer_direccion') }}</p>
        <p>{{ $cfg->config('footer_telefono') }} | {{ $cfg->config('footer_email') }}</p>
        <p class="mt-10" style="font-size: 8px; color: #999;">
            Informe generado el {{ now()->format('d/m/Y H:i:s') }} - Código: {{ $informe->codigo }} | Proforma: {{ $informe->proforma->codigo ?? 'N/A' }}
        </p>
    </div>
</body>
</html>