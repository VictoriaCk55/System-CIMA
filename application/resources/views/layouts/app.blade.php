<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema CIMA - @yield('title', 'Dashboard')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tipografía consistente -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
    /* ========== TIPOGRAFÍA CONSISTENTE ========== */
    * {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        background-color: #f8fafc;
        color: #334155;
        line-height: 1.6;
        padding-top: 64px;
    }
    
    /* ========== NAVBAR - VERSIÓN SIMPLIFICADA ========== */
    .navbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1030;
        width: 100%;
        height: 64px;
    }
    
    /* Navbar con el azul más oscuro (21, 88, 185) - SIN LÍNEA */
    .navbar.bg-primary {
        background-color: rgb(21, 88, 185) !important;
        border: 0 !important;
        border-bottom: 0 !important;
        outline: 0 !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15) !important;
    }
    
    /* Eliminar cualquier borde de Bootstrap */
    .navbar,
    .navbar *,
    .navbar .container,
    .navbar .navbar-collapse,
    .navbar .navbar-nav,
    .navbar .nav-link {
        border: 0 !important;
        border-bottom: 0 !important;
        outline: 0 !important;
    }
    
    /* Eliminar pseudo-elementos */
    .navbar::before,
    .navbar::after {
        display: none !important;
    }
    
    /* Links del navbar - color blanco */
    .navbar .nav-link {
        color: rgba(255, 255, 255, 0.85) !important;
        transition: color 0.2s;
    }
    
    .navbar .nav-link:hover,
    .navbar .nav-link.active {
        color: white !important;
    }
    
    .navbar .nav-link.active {
        background-color: rgba(255, 255, 255, 0.15);
        border-radius: 6px;
    }
    
    /* Para el navbar colapsado en móvil */
    .navbar-collapse {
        border-radius: 2rem;
        background-color: rgb(21, 88, 185) !important;
    }
    
    /* Botón toggler */
    .navbar-toggler {
        border-color: rgba(255, 255, 255, 0.3) !important;
    }
    
    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.9%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
    }
    
    /* ========== CONTENIDO PRINCIPAL ========== */
    main {
        flex: 1;
        padding-top: 20px;
        padding-bottom: 40px;
    }
    
    .container-main {
        max-width: 1200px;
        margin: 0 auto;
        padding-left: 20px;
        padding-right: 20px;
    }
    
    /* ========== TÍTULOS ========== */
    h1, h2, h3, h4, h5, h6 {
        font-weight: 600;
        color: #1e293b;
        line-height: 1.3;
        margin-bottom: 1rem;
    }
    
    h1 { font-size: 2rem; }
    h2 { font-size: 1.75rem; }
    h3 { font-size: 1.5rem; }
    h4 { font-size: 1.25rem; }
    h5 { font-size: 1.125rem; }
    h6 { font-size: 1rem; }
    
    .page-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .page-header h1 {
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .page-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin-bottom: 0;
    }
    
    /* ========== CARDS ========== */
    .card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        background: white;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    
    .card-header {
        border-radius: 12px 12px 0 0 !important;
        padding: 1.25rem 1.5rem;
        font-weight: 600;
        font-size: 1.125rem;
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    /* Hover effects para tarjetas */
    .card.h-100 {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card.h-100:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
    }
    
    /* ========== FORMULARIOS ========== */
    .form-label {
        font-weight: 500;
        color: #475569;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 0.625rem 0.875rem;
        font-size: 0.95rem;
        transition: all 0.2s;
        background-color: white;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: rgb(21, 88, 185);
        box-shadow: 0 0 0 3px rgba(21, 88, 185, 0.15);
    }
    
    /* ========== BOTONES ========== */
    .btn {
        border-radius: 8px;
        font-weight: 500;
        padding: 0.625rem 1.25rem;
        font-size: 0.95rem;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn i {
        font-size: 0.9em;
    }
    
    .btn-warning {
        background-color: #f8b803;
        border: none;
        color: #000000;
    }
    
    .btn-warning:hover {
        background-color: #e5aa03;
    }
    
    .btn.w-100.py-3 {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }
    
    .btn.w-100.py-3 .d-block {
        text-align: left;
    }
    
    .btn.w-100.py-3 small {
        opacity: 0.9;
        font-size: 0.8rem;
    }
    
    /* ========== TABLAS ========== */
    .table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }
    
    .table th {
        font-weight: 600;
        background-color: #f1f5f9;
        color: #334155;
        padding: 1rem;
        border-bottom: 2px solid #e2e8f0;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }
    
    .table td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .table tr:hover {
        background-color: #f8fafc;
    }
    
    /* ========== BADGES ========== */
    .badge {
        border-radius: 20px;
        padding: 0.35em 0.85em;
        font-weight: 500;
        font-size: 0.85em;
    }
    
    /* ========== FOOTER ========== */
    footer {
        margin-top: auto;
        background-color: #1e293b;
        color: #cbd5e1;
        padding: 1.5rem 0;
        font-size: 0.9rem;
    }
    
    /* ========== TOOLTIPS ========== */
    .tooltip {
        position: absolute !important;
        z-index: 999999 !important;
        pointer-events: none !important;
    }

    .tooltip-inner {
        background-color: #1e293b !important;
        color: white !important;
        font-size: 12px !important;
        padding: 6px 12px !important;
        border-radius: 6px !important;
        max-width: 250px !important;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important;
        border: 1px solid #334155 !important;
    }
    
    /* ========== USUARIO AUTENTICADO ========== */
    .user-badge {
        background-color: rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.85rem;
    }
    
    .user-badge.admin {
        background-color: rgba(40, 167, 69, 0.2);
        color: #28a745;
    }
    
    .user-badge.user {
        background-color: rgba(108, 117, 125, 0.2);
        color: #6c757d;
    }
    
    /* ========== ESTILOS PARA MÓVIL - CORREGIDOS ========== */
    @media (max-width: 991.98px) {
        .navbar-collapse {
            max-height: 80vh;
            overflow-y: auto;
            text-align: center;
            padding: 1rem;
            background-color: rgb(21, 88, 185) !important;
            border-radius: 0 0 2rem 2rem;
        }
        
        .navbar-nav {
            align-items: center;
            width: 100%;
        }
        
        .nav-item {
            width: 100%;
            text-align: center;
        }
        
        .nav-link {
            justify-content: center !important;
            padding: 0.75rem !important;
        }
        
        .nav-link i {
            margin-right: 8px !important;
        }
        
        .dropdown-menu {
            position: static !important;
            float: none;
            width: 100%;
            margin-top: 0.5rem;
            background-color: rgba(255, 255, 255, 0.95) !important;
            border: none !important;
            text-align: center;
            transform: none !important;
            max-height: 300px;
            overflow-y: auto;
        }
        
        .dropdown-item {
            text-align: center;
            padding: 0.75rem;
            justify-content: center;
            color: #212529 !important;
        }
        
        .dropdown-item i {
            margin-right: 8px;
            color: #212529 !important;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        
        .d-flex.flex-column {
            align-items: center;
        }
        
        .nav-item.dropdown {
            width: 100%;
        }
        
        .nav-link.dropdown-toggle {
            width: 100%;
            justify-content: center;
        }
        
        .nav-link.dropdown-toggle::after {
            display: inline-block;
            margin-left: 0.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .container-main {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    }
    
    /* ========== BARRA DE DESPLAZAMIENTO ========== */
    html, body {
        overflow-x: hidden !important;
    }

    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- ========== NAVBAR ========== -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="fas fa-flask me-2"></i>
                Sistema CIMA
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="fas fa-home me-1"></i> Inicio
                        </a>
                    </li>
                    
                    @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('clientes*') ? 'active' : '' }}" href="{{ route('clientes.index') }}">
                            <i class="fas fa-users me-1"></i> Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('parametros*') ? 'active' : '' }}" href="{{ route('parametros.index') }}">
                            <i class="fas fa-microscope me-2"></i> Parámetros
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('proformas*') ? 'active' : '' }}" href="{{ route('proformas.index') }}">
                            <i class="fas fa-file-invoice-dollar me-1"></i> Proformas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('informes*') ? 'active' : '' }}" 
                           href="{{ route('informes.index') }}">
                            <i class="fas fa-file-alt me-1"></i> Informes
                        </a>
                    </li>
                    <!-- ===== ENLACE FINANCIERO ===== -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('financiero*') ? 'active' : '' }}" href="{{ route('financiero.index') }}">
                            <i class="fas fa-chart-line me-1"></i> Financiero
                        </a>
                    </li>
                    <!-- ===== ENLACES DE ADMINISTRACIÓN (SOLO ADMIN) ===== -->
                    @role('admin')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('users*') || request()->is('roles*') || request()->is('permissions*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-cog me-1"></i> Admin
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item {{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                    <i class="fas fa-users-cog me-2"></i> Usuarios
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->is('roles*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                                    <i class="fas fa-user-tag me-2"></i> Roles
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->is('permissions*') ? 'active' : '' }}" href="{{ route('permissions.index') }}">
                                    <i class="fas fa-shield-alt me-2"></i> Permisos
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item {{ request()->is('configuraciones*') ? 'active' : '' }}" href="{{ route('configuraciones.index') }}">
                                    <i class="fas fa-sliders-h me-2"></i> Configuración
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endrole
                    @endauth
                </ul>   
                
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-2"></i>
                                <div class="d-flex flex-column">
                                    <span>{{ Auth::user()->name }}</span>
                                    <small class="user-badge {{ Auth::user()->role === 'admin' ? 'admin' : 'user' }}">
                                        @if(Auth::user()->role === 'admin')
                                            <i class="fas fa-shield-alt me-1"></i> Administrador
                                        @elseif(Auth::user()->role === 'tecnico')
                                            <i class="fas fa-user-cog me-1"></i> Técnico
                                        @elseif(Auth::user()->role === 'analista')
                                            <i class="fas fa-flask me-1"></i> Analista
                                        @else
                                            <i class="fas fa-user me-1"></i> {{ ucfirst(Auth::user()->role) }}
                                        @endif
                                    </small>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <span class="dropdown-item-text">
                                        <small>Conectado como:</small><br>
                                        <strong>{{ Auth::user()->email }}</strong>
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                
                                {{-- ENLACE AL PERFIL (PARA TODOS LOS USUARIOS) --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user-circle me-2"></i> Mi Perfil
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                
                                @if(Auth::user()->role === 'admin')
                                <li>
                                    <a class="dropdown-item" href="{{ route('clientes.create') }}">
                                        <i class="fas fa-user-plus me-2"></i> Nuevo Cliente
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('parametros.create') }}">
                                        <i class="fas fa-plus-circle me-2"></i> Nuevo Parámetro
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('proformas.create') }}">
                                        <i class="fas fa-file-invoice me-2"></i> Nueva Proforma
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('informes.create') }}">
                                        <i class="fas fa-file-medical me-2"></i> Nuevo Informe
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                @endif
                                
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                        @csrf
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Iniciar Sesión
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="container-fluid">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @yield('content')
        </div>
    </main>

    <footer class="py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">
                        <i class="fas fa-copyright me-1"></i> {{ date('Y') }} - Centro de Investigación Minero Ambiental (CIMA)
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        <i class="fas fa-code me-1"></i> Sistema de Gestión v1.0
                        <span class="mx-2">|</span>
                        <i class="fas fa-calendar me-1"></i> {{ date('d/m/Y') }}
                        @auth
                        <span class="mx-2">|</span>
                        <i class="fas fa-user me-1"></i> {{ Auth::user()->name }}
                        @endauth
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    function confirmarEliminacion(id, nombre, tipo = 'registro') {
        const mensaje = `¿Está seguro de eliminar el ${tipo} "${nombre}"?\n\n⚠️ Esta acción no se puede deshacer.`;
        if (confirm(mensaje)) {
            const formulario = document.getElementById(`delete-form-${id}`);
            if (formulario) formulario.submit();
        }
    };
    
    window.crearCliente = function() {
        const formData = {
            razon_social: document.getElementById('nueva_razon_social')?.value.trim() || '',
            persona_contacto: document.getElementById('nueva_persona_contacto')?.value.trim() || '',
            telefono: document.getElementById('nueva_telefono')?.value.trim() || '',
            nit: document.getElementById('nueva_nit')?.value.trim() || '',
            direccion: document.getElementById('nueva_direccion')?.value.trim() || '',
            _token: document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
        };

        if (!formData.razon_social || !formData.persona_contacto) {
            alert('Razón Social y Persona de Contacto son obligatorios');
            return;
        }

        const submitBtn = document.querySelector('#crearClienteModal .btn-primary');
        if (submitBtn) {
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Guardando...';
            submitBtn.disabled = true;

            fetch('/clientes/api', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': formData._token
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const select = document.getElementById('cliente_id');
                    if (select) {
                        const option = document.createElement('option');
                        option.value = data.cliente.id;
                        option.text = `${data.cliente.razon_social} - ${data.cliente.persona_contacto}`;
                        select.add(option);
                        select.value = data.cliente.id;
                    }
                    
                    const modalElement = document.getElementById('crearClienteModal');
                    if (modalElement) {
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) modal.hide();
                    }
                    
                    ['nueva_razon_social', 'nueva_persona_contacto', 'nueva_telefono', 'nueva_nit', 'nueva_direccion']
                        .forEach(id => {
                            const field = document.getElementById(id);
                            if (field) field.value = '';
                        });
                    
                    alert('✅ Cliente creado exitosamente');
                } else {
                    alert('❌ Error: ' + (data.message || 'Error desconocido'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('❌ Error al crear cliente');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }
    };
    
    window.calcularTotalesEstimados = function() {
        let subtotal = 0;
        document.querySelectorAll('.parametro-row').forEach(row => {
            const precioUnitario = parseFloat(row.querySelector('.precio-unitario')?.value) || 0;
            const muestras = parseInt(row.querySelector('.muestra-input')?.value) || 0;
            subtotal += precioUnitario * muestras;
        });
        
        const tipoSelect = document.getElementById('tipo');
        const aplicaDescuento = tipoSelect && tipoSelect.value === 'INVESTIGACION';
        const descuento = aplicaDescuento ? subtotal * 0.20 : 0;
        const total = subtotal - descuento;
        const adelanto = parseFloat(document.getElementById('adelanto')?.value) || 0;
        const saldo = total - adelanto;
        
        document.getElementById('subtotal-estimado') && (document.getElementById('subtotal-estimado').textContent = subtotal.toFixed(2));
        document.getElementById('descuento-estimado') && (document.getElementById('descuento-estimado').textContent = descuento.toFixed(2));
        document.getElementById('total-estimado') && (document.getElementById('total-estimado').textContent = total.toFixed(2));
        document.getElementById('saldo-estimado') && (document.getElementById('saldo-estimado').textContent = saldo.toFixed(2));
        
        const descuentoNota = document.getElementById('descuento-nota');
        if (descuentoNota) {
            descuentoNota.textContent = aplicaDescuento ? '(20% descuento aplicado)' : '(No aplica)';
        }
    };
    
    window.abrirModalCliente = function() {
        const modalElement = document.getElementById('crearClienteModal');
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    };
    
    document.addEventListener('DOMContentLoaded', function() {
        console.log('✅ Sistema CIMA inicializado');
        
        const rutaActual = window.location.pathname;
        document.querySelectorAll('.nav-link').forEach(link => {
            const href = link.getAttribute('href');
            if (href === rutaActual || (href !== '/' && rutaActual.startsWith(href))) {
                link.classList.add('active');
            }
        });
    });
    </script>
    @stack('scripts')
</body>
</html>