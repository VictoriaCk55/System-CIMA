<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROFORMA {{ $proforma->codigo }} - CIMA</title>
    <style>
        /* CONFIGURACIÓN BASE */
        @page {
            margin: 15mm 10mm 15mm 10mm;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 0;
            background-color: white;
        }
        
        /* ENCABEZADO */
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
        
        /* Unidad de la proforma */
        .unidad-badge {
            background-color: #2c5282;
            color: white;
            padding: 3px 8px;
            border-radius: 15px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
            margin-top: 5px;
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
        
        /* LÍNEA SEPARADORA */
        .separator {
            border-top: 2px solid #1c3d6e;
            margin: 8px 0 15px 0;
            width: 100%;
        }
        
        /* SECCIONES */
        .section-title {
            background-color: #2c5282;
            color: white;
            font-weight: bold;
            padding: 5px 10px;
            margin-bottom: 8px;
            font-size: 11px;
            border-radius: 3px;
        }
        
        /* TABLAS DE DATOS */
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
        
        /* TABLA DE PARÁMETROS */
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
        
        .align-right {
            text-align: right;
        }
        
        .align-center {
            text-align: center;
        }
        
        .align-left {
            text-align: left;
        }
        
        .bold {
            font-weight: bold;
        }
        
        /* ALERTA DE MODIFICACIÓN DE PARÁMETROS */
        .alert-modification {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-left: 5px solid #ffc107;
            padding: 12px;
            margin: 15px 0;
            border-radius: 5px;
            font-size: 10px;
        }
        
        .alert-modification-title {
            font-weight: bold;
            color: #856404;
            margin-bottom: 8px;
            font-size: 11px;
        }
        
        .alert-modification-text {
            color: #856404;
            margin-bottom: 5px;
        }
        
        .alert-modification-detail {
            font-style: italic;
            margin-top: 5px;
            padding-top: 5px;
            border-top: 1px dashed #ffc107;
            color: #6c757d;
        }
        
        /* RESUMEN FINANCIERO */
        .financial-summary {
            border: 2px solid #2c5282;
            padding: 12px;
            margin: 20px 0;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
        
        .summary-title {
            font-size: 12px;
            font-weight: bold;
            color: #2c5282;
            margin-bottom: 10px;
            text-align: center;
            text-transform: uppercase;
        }
        
        .summary-line {
            margin: 6px 0;
            font-size: 11px;
            display: flex;
            justify-content: space-between;
        }
        
        .summary-line.total {
            font-size: 13px;
            font-weight: bold;
            margin-top: 10px;
            padding-top: 8px;
            border-top: 2px solid #2c5282;
        }
        
        .summary-label {
            font-weight: bold;
        }
        
        .summary-value {
            font-weight: bold;
        }
        
        .summary-value.text-success {
            color: #28a745;
        }
        
        .summary-value.text-danger {
            color: #dc3545;
        }
        
        .total-in-words {
            font-style: italic;
            margin: 15px 0;
            text-align: center;
            font-size: 11px;
            padding: 10px;
            background-color: #f0f7ff;
            border: 1px solid #cce5ff;
            border-radius: 4px;
            color: #004085;
        }
        
        /* FIRMAS */
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
        
        /* FOOTER */
        .footer {
            margin-top: 30px;
            font-size: 9px;
            color: #666;
            text-align: center;
            padding-top: 8px;
        }
        
        /* ESPACIOS */
        .mb-10 {
            margin-bottom: 10px;
        }
        
        .mt-10 {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- ENCABEZADO -->
    <table class="header-table">
         <tr>
            <td class="logo-cell">
                <div class="logo-container">
                    @if(file_exists(public_path('images/logo-cima.jpg')))
                        <img src="{{ public_path('images/logo-cima.jpg') }}" alt="Logo CIMA">
                    @elseif(file_exists(public_path('images/logo-cima.png')))
                        <img src="{{ public_path('images/logo-cima.png') }}" alt="Logo CIMA">
                    @endif
                </div>
             </td>
            
            <td class="center-cell">
                <h1>PROFORMA DE SERVICIOS</h1>
                <h2>CENTRO DE INVESTIGACIÓN MINERO AMBIENTAL</h2>
                <h3>
                    @if($proforma->unidad == 'UIA')
                        Unidad de Investigación Ambiental "UIA"
                    @elseif($proforma->unidad == 'UAQ')
                        Unidad de Análisis Químico "UAQ"
                    @else
                        Unidad de Análisis Químico "UAQ"
                    @endif
                </h3>
                
                <div class="document-subtitle">
                    ANÁLISIS QUÍMICO - BACTERIOLÓGICO: AGUAS, SUELOS, SEDIMENTOS Y MINERALES
                </div>
                
                <div class="document-options">
                    <span class="doc-option {{ $proforma->tipo == 'AMBIENTAL' ? 'selected' : '' }}">PROFORMA</span>
                    <span class="doc-option {{ $proforma->tipo == 'INVESTIGACION' ? 'selected' : '' }}">COTIZACIÓN</span>
                    <span class="doc-option">CONTRATO</span>
                    <span class="doc-option {{ $proforma->parametros_modificados ? 'selected' : '' }}">CONTRATO MODIFICADO</span>
                </div>
                
                @if($proforma->unidad)
                <div class="unidad-badge">
                    <i class="fas fa-building"></i> {{ $proforma->unidad == 'UIA' ? 'Unidad de Investigación Ambiental' : 'Unidad de Análisis Químico' }}
                </div>
                @endif
             </td>
            
            <td class="codigo-cell">
                <div class="codigo-box">
                    <div><strong>PO01-FR02</strong></div>
                    <div>VERSIÓN: 05</div>
                    <div>FECHA: {{ $proforma->fecha_emision->format('Y-m-d') }}</div>
                    <div style="margin-top: 5px; border-top: 1px solid #ccc; padding-top: 3px;">
                        <strong>CÓDIGO:</strong> {{ $proforma->codigo }}
                    </div>
                </div>
             </td>
         </tr>
     </table>
    
    <div class="separator"></div>

    <!-- SECCIÓN 1: DATOS DEL CLIENTE -->
    <div class="mb-10">
        <div class="section-title">1.- DATOS DEL CLIENTE</div>
        
        <table class="data-table">
             <tr>
                <td style="width: 25%;"><strong>Nombre/Razón Social:</strong></td>
                <td style="width: 75%;">{{ $proforma->cliente->razon_social }}</td>
             </tr>
             <tr>
                <td><strong>Persona en contacto:</strong></td>
                <td>{{ $proforma->persona_contacto ?? $proforma->cliente->persona_contacto }}</td>
             </tr>
             <tr>
                <td><strong>NIT:</strong></td>
                <td>{{ $proforma->cliente->nit ?? 'N/A' }}</td>
             </tr>
             <tr>
                <td><strong>Teléfono/Celular:</strong></td>
                <td>{{ $proforma->telefono_contacto ?? $proforma->cliente->telefono ?? 'N/A' }}</td>
             </tr>
             <tr>
                <td><strong>Dirección:</strong></td>
                <td>{{ $proforma->cliente->direccion ?? 'N/A' }}</td>
             </tr>
         </table>
    </div>

    <!-- SECCIÓN 2: DATOS DE LA MUESTRA -->
    <div class="mb-10">
        <div class="section-title">2.- DATOS DE LA MUESTRA</div>
        
        <table class="data-table">
             <tr>
                <td style="width: 25%;"><strong>Tipo de muestra:</strong></td>
                <td style="width: 25%;">{{ $proforma->tipo_muestra }}</td>
                <td style="width: 25%;"><strong>Muestreado por:</strong></td>
                <td style="width: 25%;">{{ $proforma->muestreado_por ?? 'N/A' }}</td>
             </tr>
             <tr>
                <td><strong>Fecha de muestreo:</strong></td>
                <td>{{ $proforma->fecha_emision->format('d/m/Y') }}</td>
                <td><strong>Fecha recepción:</strong></td>
                <td>{{ $proforma->fecha_recepcion->format('d/m/Y') }}</td>
             </tr>
             <tr>
                <td><strong>Procedencia:</strong></td>
                <td colspan="3">{{ $proforma->procedencia ?? 'N/A' }}</td>
             </tr>
             <tr>
                <td><strong>Coordenadas:</strong></td>
                <td colspan="3">{{ $proforma->coordenadas ?? 'N/A' }}</td>
             </tr>
         </table>
    </div>

    <!-- SECCIÓN 3: PARÁMETROS A ANALIZAR -->
    <div class="mb-10">
        <div class="section-title">3.- PARÁMETROS A ANALIZAR - MUESTRAS DE {{ strtoupper($proforma->tipo_muestra) }}</div>
        
        <table class="params-table">
            <thead>
                 <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 30%;">Parámetro</th>
                    <th style="width: 25%;">Método</th>
                    <th style="width: 10%;">N° Muestras</th>
                    <th style="width: 15%;">Precio Unit. (Bs)</th>
                    <th style="width: 15%;">Total (Bs)</th>
                 </tr>
            </thead>
            <tbody>
                @if($proforma->parametros->count() > 0)
                    @foreach($proforma->parametros as $index => $parametro)
                    <tr>
                        <td class="align-center">{{ $index + 1 }}</td>
                        <td>{{ $parametro->nombre }}</td>
                        <td>{{ $parametro->metodo }}</td>
                        <td class="align-center">{{ $parametro->pivot->cantidad_muestras }}</td>
                        <td class="align-right">Bs. {{ number_format($parametro->pivot->precio_unitario, 2) }}</td>
                        <td class="align-right">Bs. {{ number_format($parametro->pivot->precio_unitario * $parametro->pivot->cantidad_muestras, 2) }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="align-center">No hay parámetros asignados</td>
                    </tr>
                @endif
            </tbody>
         </table>
        
        <!-- ALERTA DE MODIFICACIÓN DE PARÁMETROS -->
        @if($proforma->parametros_modificados && $proforma->justificacion_modificacion)
        <div class="alert-modification">
            <div class="alert-modification-title">
                <strong>⚠️ MODIFICACIÓN DE PARÁMETROS BAJO CONTRATO</strong>
            </div>
            <div class="alert-modification-text">
                Esta proforma ha sido modificada en sus parámetros de análisis.
            </div>
            <div class="alert-modification-text">
                <strong>Justificación:</strong> {{ $proforma->justificacion_modificacion }}
            </div>
            @if($proforma->usuarioModificacion)
            <div class="alert-modification-detail">
                Modificado por: {{ $proforma->usuarioModificacion->name }} | 
                Fecha: {{ $proforma->updated_at->format('d/m/Y H:i:s') }}
            </div>
            @endif
        </div>
        @endif
        
        <!-- TOTAL EN LETRAS -->
        <div class="total-in-words">
            <strong>{{ $totalEnLetras }}</strong>
        </div>
    </div>

    <!-- RESUMEN FINANCIERO -->
    <div class="financial-summary">
        <div class="summary-title">RESUMEN FINANCIERO</div>
        
        <div class="summary-line">
            <span class="summary-label">Subtotal:</span>
            <span class="summary-value">Bs. {{ number_format($proforma->subtotal, 2) }}</span>
        </div>
        
        @if($proforma->descuento > 0)
        <div class="summary-line">
            <span class="summary-label">Descuento Institucional (20%):</span>
            <span class="summary-value text-danger">- Bs. {{ number_format($proforma->descuento, 2) }}</span>
        </div>
        @endif
        
        <div class="summary-line total">
            <span class="summary-label">TOTAL:</span>
            <span class="summary-value text-success">Bs. {{ number_format($proforma->total, 2) }}</span>
        </div>
        
        @if($proforma->adelanto > 0)
        <div class="summary-line" style="margin-top: 8px;">
            <span class="summary-label">Adelanto recibido:</span>
            <span class="summary-value">Bs. {{ number_format($proforma->adelanto, 2) }}</span>
        </div>
        
        <div class="summary-line" style="border-top: 1px dashed #999; padding-top: 5px;">
            <span class="summary-label">Saldo pendiente:</span>
            <span class="summary-value {{ $proforma->saldo > 0 ? 'text-danger' : 'text-success' }}">
                Bs. {{ number_format($proforma->saldo, 2) }}
            </span>
        </div>
        @endif
    </div>

    <!-- OBSERVACIONES -->
    @if($proforma->observaciones)
    <div class="mb-10">
        <div class="section-title">OBSERVACIONES</div>
        <div style="padding: 8px; border: 1px solid #e2e8f0; border-radius: 3px; font-size: 10px; background-color: #fffde7; line-height: 1.4;">
            {!! nl2br(e($proforma->observaciones)) !!}
        </div>
    </div>
    @endif

    <!-- NOTAS -->
    <div style="font-size: 10px; margin-top: 15px; padding: 8px; background-color: #f8f9fa; border-radius: 3px; border-left: 3px solid #2c5282;">
        <p><strong>Nota 1:</strong> Para realizar el análisis se debe dejar cancelado el 100% del monto total.</p>
        <p><strong>Nota 2:</strong> El laboratorio no realiza declaraciones de conformidad sobre los resultados que se reportan.</p>
        <p><strong>Nota 3:</strong> Los resultados estarán disponibles dentro de los plazos establecidos según el tipo de análisis.</p>
    </div>

    <!-- FIRMAS -->
    <table class="signatures-table">
         <tr>
             <td>
                <div class="signature-line"></div>
                <div class="signature-text">
                    <strong>Ing. ___________________________</strong><br>
                    Responsable Técnico<br>
                    Centro de Investigación Minero Ambiental
                </div>
             </td>
             <td>
                <div class="signature-line"></div>
                <div class="signature-text">
                    <strong>___________________________</strong><br>
                    Representante Legal / Cliente<br>
                    {{ $proforma->cliente->razon_social }}
                </div>
             </td>
         </tr>
     </table>

    <!-- FOOTER -->
    <div class="footer">
        <p><strong>Centro de Investigación Minero Ambiental (CIMA)</strong></p>
        <p>Av. Arce esq. Villazón s/n; Edificio Facultad de Ingeniería Minera Subsuelo, Universidad Autónoma "Tomás Frías"</p>
        <p>Teléfono/Fax: 6229711 | Email: cima@cima.edu.bo</p>
        <p><em>* Por favor llame al CIMA antes de venir a recoger su informe, gracias.</em></p>
    </div>
</body>
</html>