<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Resultados de Ensayo - Proforma {{ $proforma->id }}</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 30px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .main-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .modern-header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            padding: 25px 30px;
            border-bottom: 3px solid #ffc107;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .header-title h1 {
            color: white;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .header-title p {
            color: #a0a0a0;
            font-size: 14px;
        }
        
        .header-badge {
            background: rgba(255,193,7,0.2);
            padding: 8px 16px;
            border-radius: 50px;
            color: #ffc107;
            font-weight: 600;
            font-size: 14px;
        }
        
        .nav-bar {
            background: white;
            padding: 15px 30px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .nav-links {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        
        .btn-modern {
            padding: 10px 20px;
            border: none;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
        }
        
        .btn-modern i {
            font-size: 14px;
        }
        
        .btn-modern:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        
        .btn-modern:active:not(:disabled) {
            transform: translateY(0);
        }
        
        .btn-modern:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .btn-gray {
            background: #f0f0f0;
            color: #333;
        }
        
        .btn-gray:hover:not(:disabled) {
            background: #e0e0e0;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #ffc107, #ffb300);
            color: #1a1a2e;
        }
        
        .btn-primary:hover:not(:disabled) {
            background: linear-gradient(135deg, #ffb300, #ffa000);
        }
        
        .btn-clean {
            background: linear-gradient(135deg, #ff9800, #f57c00);
            color: white;
        }
        
        .btn-clean:hover:not(:disabled) {
            background: linear-gradient(135deg, #f57c00, #e65100);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #28a745, #218838);
            color: white;
        }
        
        .btn-success:hover:not(:disabled) {
            background: linear-gradient(135deg, #218838, #1e7e34);
        }
        
        .btn-info {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
        }
        
        .btn-info:hover:not(:disabled) {
            background: linear-gradient(135deg, #138496, #0f6674);
        }
        
        .btn-purple {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .btn-purple:hover:not(:disabled) {
            background: linear-gradient(135deg, #5a67d8, #6b46a0);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #ffc107, #ffb300);
            color: #1a1a2e;
        }
        
        .info-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            margin: 20px 30px;
            padding: 20px;
            border-radius: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .info-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #ffc107, #ffb300);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1a1a2e;
            font-size: 20px;
        }
        
        .info-text h4 {
            font-size: 12px;
            color: #666;
            margin-bottom: 4px;
        }
        
        .info-text p {
            font-size: 15px;
            font-weight: 700;
            color: #1a1a2e;
        }

        .info-item-stacked {
            align-items: flex-start;
        }

        .info-item-stacked .stacked-fields {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .estado-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .table-wrapper {
            padding: 0 30px 30px 30px;
            overflow-x: auto;
        }
        
        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .modern-table thead tr {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
        }
        
        .modern-table th {
            padding: 15px 12px;
            text-align: center;
            color: white;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .modern-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .modern-table tbody tr:hover {
            background: #f8f9ff;
        }
        
        .modern-table td:first-child {
            background: #f8f9fa;
            font-weight: 600;
            text-align: left;
        }
        
        .modern-input {
            width: 85px;
            padding: 8px 10px;
            text-align: center;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 12px;
            font-family: 'Inter', monospace;
            transition: all 0.3s ease;
            background: white;
        }
        
        .modern-input:focus {
            outline: none;
            border-color: #ffc107;
            box-shadow: 0 0 0 3px rgba(255,193,7,0.2);
        }
        
        .modern-input:hover:not(:disabled) {
            border-color: #ffc107;
        }
        
        .modern-input:disabled {
            background: #f5f5f5;
            color: #999;
            cursor: not-allowed;
            border-color: #e0e0e0;
        }
        
        input[type="date"].modern-input {
            width: 130px;
        }
        
        .action-buttons {
            padding: 0 30px 30px 30px;
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .modal-content {
            background: white;
            margin: 10% auto;
            padding: 30px;
            border-radius: 24px;
            width: 400px;
            max-width: 90%;
            text-align: center;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .modal-icon-clean {
            width: 70px;
            height: 70px;
            background: #fff3e0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 35px;
            color: #ff9800;
        }
        
        .modal-buttons {
            margin-top: 25px;
            display: flex;
            justify-content: center;
            gap: 12px;
        }
        
        .page-number {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #e0e0e0;
        }
        
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            .modern-header {
                padding: 20px;
            }
            .header-title h1 {
                font-size: 18px;
            }
            .nav-bar {
                padding: 15px 20px;
                flex-direction: column;
            }
            .table-wrapper {
                padding: 0 20px 20px 20px;
            }
            .modern-input {
                width: 60px;
                font-size: 10px;
                padding: 6px;
            }
            input[type="date"].modern-input {
                width: 100px;
            }
            .info-card {
                margin: 20px;
                grid-template-columns: 1fr;
            }
            .action-buttons {
                padding: 0 20px 20px 20px;
            }
            .btn-modern {
                padding: 8px 14px;
                font-size: 11px;
            }
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .nav-bar, .action-buttons, .modal {
                display: none;
            }
            .modern-input {
                border: none;
                background: transparent;
            }
            .main-card {
                box-shadow: none;
            }
        }
        
        .toast-notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #28a745, #218838);
            color: white;
            padding: 14px 24px;
            border-radius: 50px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            z-index: 1100;
            animation: slideInRight 0.3s ease;
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .estado-actual {
            background: rgba(0,0,0,0.05);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 500;
        }
        
        .estado-actual i {
            margin-right: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-card">
            <div class="modern-header">
                <div class="header-content">
                    <div class="header-title">
                        <h1><i class="fas fa-flask"></i> Resultados de Ensayo</h1>
                        <p>Ingrese los resultados de laboratorio para cada parámetro</p>
                    </div>
                    <div class="header-badge">
                        @php
                            $partes = (explode('-', $proforma->codigo));
                            $numero = end($partes);
                        @endphp
                        <td width="100" class="center " style="color: #ef1111;">
                            <strong>Nº:</strong> {{ $numero }}
                        </td>
                        <!-- <i class="fas fa-file-invoice"></i> Proforma #{{ $proforma->id }} -->
                    </div>
                </div>
            </div>
            
            <div class="nav-bar">
                <div class="nav-links">
                    <a href="{{ route('proformas.index') }}" class="btn-modern btn-gray">
                        <i class="fas fa-arrow-left"></i> Volver al Listado
                    </a>
                    <a href="{{ route('proformas.cadena-custodia', $proforma->id) }}" class="btn-modern btn-purple">
                        <i class="fas fa-link"></i> Cadena Custodia
                    </a>
                </div>
                <div class="estado-actual" id="estadoActual">
                    <i class="fas fa-info-circle"></i> Estado: <span id="estadoTexto">Sin datos</span>
                </div>
            </div>
            
            <div class="info-card">
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-hashtag"></i></div>
                    <div class="info-text">
                        <h4>CÓDIGO</h4>
                        <p>{{ $proforma->codigo }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-sort-numeric-down"></i></div>
                    <div class="info-text">
                        <h4>N° RECEPCIÓN</h4>
                        <input type="text" id="numeroRecepcion" name="numero_recepcion"
                            class="modern-input" value="{{ $proforma->numero_recepcion ?? '' }}"
                            placeholder="N°" style="width: 120px;">
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-building"></i></div>
                    <div class="info-text">
                        <h4>CLIENTE</h4>
                        <p>{{ $proforma->cliente->razon_social ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-tint"></i></div>
                    <div class="info-text">
                        <h4>TIPO DE MUESTRA</h4>
                        <p>{{ $proforma->tipo_muestra }}</p>
                    </div>
                </div>
                <!-- <div class="info-item">
                    <div class="info-icon"><i class="fas fa-calendar"></i></div>
                    <div class="info-text">
                        <h4>FECHA</h4>
                        <p>{{ now()->format('d/m/Y') }}</p>
                    </div>
                </div> -->
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-chart-line"></i></div>
                    <div class="info-text">
                        <h4>TOTAL</h4>
                        <p>Bs. {{ number_format($proforma->total, 2) }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-flag-checkered"></i></div>
                    <div class="info-text">
                        <h4>ESTADO</h4>
                        <p><span class="estado-badge" style="background: {{ $proforma->estado_color }}20; color: {{ $proforma->estado_color }};">{{ $proforma->estado_texto }}</span></p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon"> 
                        <i class="fas fa-calendar-plus"></i> 
                    </div>
                    <div class="info-text"> 
                        <h4>INICIO ENSAYO</h4> 
                        <input type="date" 
                        id="fecha_inicio_ensayo" 
                        class="modern-input fecha-inicio-ensayo" 
                        value="{{ $fecha_inicio_ensayo }}"> </div>
                </div>

                <div class="info-item">
                    <div class="info-icon"> 
                        <i class="fas fa-calendar-check"></i> 
                    </div>
                    <div class="info-text">
                        <h4>CONCLUSIÓN ENSAYO</h4>
                        <input type="date" 
                        id="fecha_conclusion_ensayo" 
                        class="modern-input fecha-conclusion-ensayo" 
                        value="{{ $fecha_conclusion_ensayo }}">
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="info-text">
                        <h4>TIPO LÍMITE PERMISIBLE</h4>
                        <select id="tipoPermisible" class="modern-input" style="width: auto; min-width: 160px;">
                            <option value="NB-512">NB-512</option>
                            <option value="ANEXO_A-2">ANEXO A-2</option>
                        </select>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-globe-americas"></i>
                    </div>
                    <div class="info-text">
                        <h4>ZONA UTM</h4>
                        <select id="zonaUtm" name="zona_utm" class="modern-input" style="width: auto; min-width: 130px;">
                            <option value="ZONA_19K">ZONA 19K</option>
                            <option value="ZONA_20K">ZONA 20K</option>
                            <option value="ZONA_21K">ZONA 21K</option>
                        </select>
                    </div>
                </div>

                <div class="info-item info-item-stacked">
                    <div class="info-icon">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <div class="stacked-fields">
                        <div class="info-text">
                            <h4>PUNTO CARDINAL 1</h4>
                            <select id="puntoCardinal1" name="punto_cardinal_1" class="modern-input" style="width: auto; min-width: 80px;">
                                <option value="">--</option>
                                <option value="E">Este (E)</option>
                                <option value="N">Norte (N)</option>
                                <option value="O">Oeste (O)</option>
                                <option value="S">Sur (S)</option>
                            </select>
                        </div>
                        <div class="info-text">
                            <h4>VALOR 1</h4>
                            <input type="text" id="valorCardinal1" name="valor_cardinal_1"
                                class="modern-input" placeholder="Coord. 1" style="width: 100px;">
                        </div>
                    </div>
                </div>

                <div class="info-item info-item-stacked">
                    <div class="info-icon">
                        <i class="fas fa-arrow-left"></i>
                    </div>
                    <div class="stacked-fields">
                        <div class="info-text">
                            <h4>PUNTO CARDINAL 2</h4>
                            <select id="puntoCardinal2" name="punto_cardinal_2" class="modern-input" style="width: auto; min-width: 80px;">
                                <option value="">--</option>
                                <option value="E">Este (E)</option>
                                <option value="N">Norte (N)</option>
                                <option value="O">Oeste (O)</option>
                                <option value="S">Sur (S)</option>
                            </select>
                        </div>
                        <div class="info-text">
                            <h4>VALOR 2</h4>
                            <input type="text" id="valorCardinal2" name="valor_cardinal_2"
                                class="modern-input" placeholder="Coord. 2" style="width: 100px;">
                        </div>
                    </div>
                </div>
            </div>
            
            @php
                $parametros = $proforma->parametros;
                $maxMuestras = 0;
                foreach($parametros as $p) {
                    $cant = $p->pivot->cantidad_muestras ?? 1;
                    if($cant > $maxMuestras) $maxMuestras = $cant;
                }
                if($maxMuestras == 0) $maxMuestras = 1;
            @endphp
            
            <div class="table-wrapper">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th style="min-width: 180px;">Parámetro</th>
                            @foreach($parametros as $p)
                                <th style="min-width: 100px;">{{ $p->nombre }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Límites de cuantificación</td>
                            @foreach($parametros as $p)
                                <td>{{ $p->limite_cuantificacion ?? '---' }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Unidad</td>
                            @foreach($parametros as $p)
                                <td>{{ $p->unidad ?? '---' }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Método ó técnica de ensayo</td>
                            @foreach($parametros as $p)
                                <td>{{ $p->codigo_poe ?? '---' }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Responsable de ensayo</td>
                            @foreach($parametros as $p)
                                <td>
                                    <input type="text" class="modern-input responsable" 
                                           data-id="{{ $p->id }}" value="{{ $responsables[$p->id] ?? '' }}"
                                           placeholder="Responsable" style="width: 100px;">
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Fecha de ensayo</td>
                            @foreach($parametros as $p)
                                <td>
                                    <input type="date" class="modern-input fecha" 
                                           data-id="{{ $p->id }}" value="{{ $fechas[$p->id] ?? date('Y-m-d') }}"
                                           style="width: 120px;">
                                </td>
                            @endforeach
                        </tr>
                        
                        @for($m = 1; $m <= $maxMuestras; $m++)
                            <tr>
                                <td style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); font-weight: 700;">
                                    <i class="fas fa-vial"></i> {{ $proforma->generarCodigoLaboratorio($m) }}
                                    <br><small style="font-size: 10px; color: #666;">Muestra {{ $m }}</small>
                                </td>
                                @foreach($parametros as $p)
                                    @php $aplica = $m <= ($p->pivot->cantidad_muestras ?? 1); @endphp
                                    <td>
                                        @if($aplica)
                                            <input type="text"
                                            class="modern-input resultado"
                                            data-muestra="{{ $m }}"
                                            data-parametro="{{ $p->id }}"
                                            value="{{ $resultados[$m][$p->id] ?? '' }}"
                                            placeholder="_____"
                                            style="width: 80px;">
                                        @else
                                            <span style="color: #999;">---</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endfor

                        <tr>
                            <td>V°B°</td>
                            @foreach($parametros as $p)
                                <td>
                                    <input type="text" class="modern-input vb" 
                                           data-id="{{ $p->id }}" value="{{ $vbs[$p->id] ?? '' }}"
                                           placeholder="V°B°" style="width: 100px;">
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="action-buttons">
                <button id="btnGuardar" class="btn-modern btn-success" onclick="guardarTodo()">
                    <i class="fas fa-save"></i> Guardar Resultados
                </button>
                <button id="btnEditar" class="btn-modern btn-warning" onclick="editarDatos()" style="display: none;">
                    <i class="fas fa-edit"></i> Editar
                </button>
                <button class="btn-modern btn-clean" onclick="confirmarLimpiar()">
                    <i class="fas fa-eraser"></i> Limpiar
                </button>
                <!-- <button class="btn-modern btn-primary" onclick="cargarTodo()">
                    <i class="fas fa-download"></i> Cargar Guardados
                </button> -->
                <a href="{{ route('proformas.resultados.pdf', $proforma->id) }}" 
                class="btn-modern btn-info" target="_blank"> 
                <i class="fas fa-file-pdf"></i> Exportar PDF </a>
                <a href="{{ route('proformas.informe-resultados-pdf', $proforma->id) }}"
                target="_blank"
                class="btn-modern btn-purple">
                    <i class="fas fa-print"></i> Informe de Resultados
                </a>
                <a href="#" id="btnInformePermisible" class="btn-modern btn-primary">
                    <i class="fas fa-file-pdf"></i> TIPO LÍMITE PERMISIBLE
                </a>
                <a href="{{ route('proformas.show', $proforma->id) }}" class="btn-modern btn-gray">
                    <i class="fas fa-times"></i> Salir
                </a>
            </div>
        </div>
    </div>
    
    <div id="modalLimpiar" class="modal">
        <div class="modal-content">
            <div class="modal-icon-clean">
                <i class="fas fa-eraser"></i>
            </div>
            <h3>🧹 Limpiar Resultados</h3>
            <p style="margin: 15px 0;">¿Estás seguro de limpiar <strong>TODOS los resultados</strong> ingresados en esta pantalla?</p>
            <p style="color: #ff9800; font-size: 12px;">⚠️ Esta acción solo borrará los datos de los campos.<br>NO eliminará la proforma ni los parámetros.</p>
            <div class="modal-buttons">
                <button onclick="cerrarModalLimpiar()" class="btn-modern btn-gray">Cancelar</button>
                <button onclick="limpiarResultados()" class="btn-modern btn-clean">Sí, Limpiar</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <script>

        let estadoActual = 'sin_datos';

        const proformaId = {{ $proforma->id }};

        const btnGuardar =
            document.getElementById('btnGuardar');

        const btnEditar =
            document.getElementById('btnEditar');

        const inputsEditables = document.querySelectorAll(
            '.resultado, .responsable, .fecha, .vb, .fecha-inicio-ensayo, .fecha-conclusion-ensayo, #zonaUtm, #puntoCardinal1, #valorCardinal1, #puntoCardinal2, #valorCardinal2, #numeroRecepcion, #tipoPermisible'
        );

        function csrfToken() {
            return document.querySelector('meta[name="csrf-token"]').content;
        }

        function actualizarEstadoUI() {

            const estadoTexto =
                document.getElementById('estadoTexto');

            switch (estadoActual) {

                case 'sin_datos':

                    btnGuardar.style.display = 'inline-flex';
                    btnEditar.style.display = 'none';

                    estadoTexto.innerHTML =
                        'Sin datos <i class="fas fa-database"></i>';

                    habilitarInputs(true);

                    break;

                case 'guardado':

                    btnGuardar.style.display = 'none';
                    btnEditar.style.display = 'inline-flex';

                    estadoTexto.innerHTML =
                        'Datos guardados <i class="fas fa-lock"></i>';

                    habilitarInputs(false);

                    break;

                case 'editando':

                    btnGuardar.style.display = 'inline-flex';
                    btnEditar.style.display = 'none';

                    estadoTexto.innerHTML =
                        'Editando <i class="fas fa-edit"></i>';

                    habilitarInputs(true);

                    break;
            }
        }

        function habilitarInputs(habilitado) {

            inputsEditables.forEach(input => {

                input.disabled = !habilitado;
            });
        }

        function obtenerDatosFormulario() {

            let datos = {

                resultados: {},
                responsables: {},
                fechas: {},
                vbs: {},
                fecha_inicio_ensayo: '',
                fecha_conclusion_ensayo: '',
                zona_utm: '',
                punto_cardinal_1: '',
                valor_cardinal_1: '',
                punto_cardinal_2: '',
                valor_cardinal_2: '',
                numero_recepcion: ''
            };

            // RESULTADOS
            document.querySelectorAll('.resultado')
                .forEach(inp => {

                    let muestra =
                        inp.dataset.muestra;

                    let parametro =
                        inp.dataset.parametro;

                    if (!datos.resultados[muestra]) {

                        datos.resultados[muestra] = {};
                    }

                    datos.resultados[muestra][parametro] =
                        inp.value;
                });

            // RESPONSABLES
            document.querySelectorAll('.responsable')
                .forEach(inp => {

                    datos.responsables[inp.dataset.id] =
                        inp.value;
                });

            // FECHAS
            document.querySelectorAll('.fecha')
                .forEach(inp => {

                    datos.fechas[inp.dataset.id] =
                        inp.value;
                });

            // VB
            document.querySelectorAll('.vb')
                .forEach(inp => {

                    datos.vbs[inp.dataset.id] =
                        inp.value;
                });

            // FECHAS GENERALES
            datos.fecha_inicio_ensayo =
                document.getElementById('fecha_inicio_ensayo').value;

            datos.fecha_conclusion_ensayo =
                document.getElementById('fecha_conclusion_ensayo').value;

            // COORDENADAS
            datos.zona_utm = document.getElementById('zonaUtm').value;
            datos.punto_cardinal_1 = document.getElementById('puntoCardinal1').value;
            datos.valor_cardinal_1 = document.getElementById('valorCardinal1').value;
            datos.punto_cardinal_2 = document.getElementById('puntoCardinal2').value;
            datos.valor_cardinal_2 = document.getElementById('valorCardinal2').value;
            datos.numero_recepcion = document.getElementById('numeroRecepcion').value;

            return datos;
        }

        function guardarTodo() {
            const datos = obtenerDatosFormulario();
            const formData = new FormData();
            formData.append('resultados', JSON.stringify(datos.resultados));
            formData.append('responsables', JSON.stringify(datos.responsables));
            formData.append('fechas', JSON.stringify(datos.fechas));
            formData.append('vbs', JSON.stringify(datos.vbs));
            formData.append('fecha_inicio_ensayo', datos.fecha_inicio_ensayo);
            formData.append('fecha_conclusion_ensayo', datos.fecha_conclusion_ensayo);
            formData.append('zona_utm', datos.zona_utm);
            formData.append('punto_cardinal_1', datos.punto_cardinal_1);
            formData.append('valor_cardinal_1', datos.valor_cardinal_1);
            formData.append('punto_cardinal_2', datos.punto_cardinal_2);
            formData.append('valor_cardinal_2', datos.valor_cardinal_2);
            formData.append('numero_recepcion', datos.numero_recepcion);

            fetch('{{ route("proformas.resultados.guardar", $proforma->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken(),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    estadoActual = 'guardado';
                    actualizarEstadoUI();
                    mostrarToast('✅ Resultados guardados correctamente');
                } else {
                    mostrarToast('❌ Error al guardar: ' + (data.message || 'Error desconocido'));
                }
            })
            .catch(error => {
                mostrarToast('❌ Error de conexión al guardar');
                console.error('Error:', error);
            });
        }

        function cargarTodo() {
            fetch('{{ route("proformas.resultados.cargar", $proforma->id) }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    mostrarToast('⚠️ No hay datos guardados en el servidor');
                    actualizarEstadoUI();
                    return;
                }

                // RESULTADOS
                if (data.resultados) {
                    Object.keys(data.resultados)
                        .forEach(muestra => {
                            Object.keys(
                                data.resultados[muestra]
                            ).forEach(parametro => {
                                const inp = document.querySelector(
                                    `.resultado[data-muestra="${muestra}"][data-parametro="${parametro}"]`
                                );
                                if (inp) {
                                    inp.value = data.resultados[muestra][parametro];
                                }
                            });
                        });
                }

                // RESPONSABLES
                if (data.responsables) {
                    Object.keys(data.responsables)
                        .forEach(id => {
                            const inp = document.querySelector(
                                `.responsable[data-id="${id}"]`
                            );
                            if (inp) {
                                inp.value = data.responsables[id];
                            }
                        });
                }

                // FECHAS
                if (data.fechas) {
                    Object.keys(data.fechas)
                        .forEach(id => {
                            const inp = document.querySelector(
                                `.fecha[data-id="${id}"]`
                            );
                            if (inp) {
                                inp.value = data.fechas[id];
                            }
                        });
                }

                // VB
                if (data.vbs) {
                    Object.keys(data.vbs)
                        .forEach(id => {
                            const inp = document.querySelector(
                                `.vb[data-id="${id}"]`
                            );
                            if (inp) {
                                inp.value = data.vbs[id];
                            }
                        });
                }

                // FECHA INICIO ENSAYO
                if (data.fecha_inicio_ensayo) {
                    document.getElementById('fecha_inicio_ensayo').value = data.fecha_inicio_ensayo;
                }

                // FECHA CONCLUSION ENSAYO
                if (data.fecha_conclusion_ensayo) {
                    document.getElementById('fecha_conclusion_ensayo').value = data.fecha_conclusion_ensayo;
                }

                // COORDENADAS
                if (data.zona_utm) document.getElementById('zonaUtm').value = data.zona_utm;
                if (data.punto_cardinal_1) document.getElementById('puntoCardinal1').value = data.punto_cardinal_1;
                if (data.valor_cardinal_1) document.getElementById('valorCardinal1').value = data.valor_cardinal_1;
                if (data.punto_cardinal_2) document.getElementById('puntoCardinal2').value = data.punto_cardinal_2;
                if (data.valor_cardinal_2) document.getElementById('valorCardinal2').value = data.valor_cardinal_2;
                if (data.numero_recepcion) document.getElementById('numeroRecepcion').value = data.numero_recepcion;

                estadoActual = 'guardado';
                actualizarEstadoUI();
                mostrarToast('✅ Datos cargados correctamente');
            })
            .catch(error => {
                mostrarToast('⚠️ No hay datos guardados en el servidor');
                actualizarEstadoUI();
                console.error('Error:', error);
            });
        }

        function editarDatos() {

            estadoActual = 'editando';

            actualizarEstadoUI();

            mostrarToast(
                '✏️ Modo edición activado'
            );
        }

        function limpiarResultados() {
            const formData = new FormData();

            fetch('{{ route("proformas.limpiar-resultados", $proforma->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken(),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    inputsEditables.forEach(inp => {
                        inp.value = '';
                    });
                    estadoActual = 'sin_datos';
                    actualizarEstadoUI();
                    cerrarModalLimpiar();
                    mostrarToast('🧹 Resultados eliminados');
                } else {
                    mostrarToast('❌ Error al limpiar: ' + (data.message || 'Error desconocido'));
                }
            })
            .catch(error => {
                mostrarToast('❌ Error de conexión al limpiar');
                console.error('Error:', error);
            });
        }

        function confirmarLimpiar() {

            document.getElementById(
                'modalLimpiar'
            ).style.display = 'block';
        }

        function cerrarModalLimpiar() {

            document.getElementById(
                'modalLimpiar'
            ).style.display = 'none';
        }

        window.onclick = function(event) {

            let modal = document.getElementById(
                'modalLimpiar'
            );

            if (event.target == modal) {

                modal.style.display = 'none';
            }
        }

        function mostrarToast(mensaje) {

            let toast = document.createElement('div');

            toast.className = 'toast-notification';

            toast.innerHTML =
                '<i class="fas fa-check-circle"></i> ' + mensaje;

            document.body.appendChild(toast);

            setTimeout(() => {

                toast.style.opacity = '0';

                setTimeout(() => toast.remove(), 300);

            }, 2500);
        }

        // BOTON INFORME CON DATOS PERMISIBLES
        document.getElementById('btnInformePermisible')?.addEventListener('click', function(e) {
            e.preventDefault();
            const tipo = document.getElementById('tipoPermisible').value;
            const url = '{{ route("proformas.informe-permisibles-pdf", ["id" => $proforma->id, "tipo" => "TIPO_PLACEHOLDER"]) }}'.replace('TIPO_PLACEHOLDER', tipo);
            window.open(url, '_blank');
        });

        // AUTO CARGAR
        window.addEventListener('DOMContentLoaded', () => {
            cargarTodo();
        });

    </script>


</body>
</html>