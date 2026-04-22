@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="container-main">
    <!-- Encabezado de página con botón en la esquina superior derecha -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>
                    <i class="fas fa-user-circle" style="color: rgb(21, 88, 185);"></i>
                    Mi Perfil
                </h1>
                <p class="page-subtitle">
                    Gestiona tu información personal y contraseña
                </p>
            </div>
            
            <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-volver" style="border-radius: 30px; padding: 8px 20px;">
                <i class="fas fa-arrow-left me-2"></i>
                Volver al Dashboard
            </a>
        </div>
    </div>

    <!-- Alertas -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tarjetas de perfil -->
    <div class="row">
        <!-- COLUMNA IZQUIERDA: DATOS PERSONALES (AZUL #2798F5) -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header" style="background-color: #2798F5; border-bottom: none;">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-id-card me-2 text-white"></i>
                        Datos Personales
                    </h5>
                </div>
                <div class="card-body" style="background-color: white;">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre completo</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required
                                   style="border-radius: 8px;">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   required
                                   style="border-radius: 8px;">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Este será tu usuario para iniciar sesión</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rol</label>
                            <input type="text" 
                                   class="form-control" 
                                   value="{{ $user->role === 'admin' ? 'Administrador' : 'Técnico' }}" 
                                   disabled
                                   style="border-radius: 8px; background-color: #f8f9fa;">
                            <small class="text-muted">El rol es asignado por el administrador</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Miembro desde</label>
                            <input type="text" 
                                   class="form-control" 
                                   value="{{ $user->created_at->format('d/m/Y H:i') }}" 
                                   disabled
                                   style="border-radius: 8px; background-color: #f8f9fa;">
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="submit" class="btn" 
                                    style="background-color: #2798F5; border-radius: 30px; padding: 10px 25px; color: white; border: none; transition: all 0.3s ease; width: 100%;">
                                <i class="fas fa-save me-2 text-white"></i>
                                Actualizar Datos
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: CAMBIO DE CONTRASEÑA (ROJO #A31800) -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header" style="background-color: #A31800; border-bottom: none;">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-key me-2 text-white"></i>
                        Cambiar Contraseña
                    </h5>
                </div>
                <div class="card-body" style="background-color: white;">
                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Contraseña Actual</label>
                            <input type="password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password" 
                                   required
                                   style="border-radius: 8px;">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nueva Contraseña</label>
                            <input type="password" 
                                   class="form-control @error('new_password') is-invalid @enderror" 
                                   id="new_password" 
                                   name="new_password" 
                                   required
                                   style="border-radius: 8px;">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Mínimo 8 caracteres</small>
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="new_password_confirmation" 
                                   name="new_password_confirmation" 
                                   required
                                   style="border-radius: 8px;">
                        </div>

                        <div class="alert alert-info" style="border-radius: 8px;">
                            <i class="fas fa-info-circle me-2" style="color: #A31800;"></i>
                            <small>La contraseña debe tener al menos 8 caracteres para mayor seguridad.</small>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button type="submit" class="btn" 
                                    style="background-color: #A31800; border-radius: 30px; padding: 10px 25px; color: white; border: none; transition: all 0.3s ease; width: 100%;">
                                <i class="fas fa-key me-2 text-white"></i>
                                Cambiar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estilos adicionales específicos para la página de perfil -->
<style>
/* Estilo para el botón Volver */
.btn-volver {
    color: #000000 !important;
    border: 2px solid #ffffff !important;
    background-color: #ffffff !important;
    transition: all 0.3s ease !important;
    font-weight: 500 !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
}

.btn-volver:hover {
    background-color: #ffffff !important;
    color: #000000 !important;
    border-color: #ffffff !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 20px rgba(128, 128, 128, 0.3) !important;
}

/* Estilo para los headers de los cards */
.card-header[style*="background-color: #2798F5"] {
    background-color: #2798F5 !important;
    border-radius: 12px 12px 0 0 !important;
    padding: 1.25rem 1.5rem;
}

.card-header[style*="background-color: #A31800"] {
    background-color: #A31800 !important;
    border-radius: 12px 12px 0 0 !important;
    padding: 1.25rem 1.5rem;
}

/* Estilo para el texto blanco en los headers */
.card-header h5.text-white,
.card-header i.text-white {
    color: white !important;
}

/* Estilo para el botón de Actualizar Datos (azul) */
button[style*="background-color: #2798F5"]:hover {
    background-color: #1a7ac9 !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(39, 152, 245, 0.3);
}

/* Estilo para el botón de Cambiar Contraseña (rojo) */
button[style*="background-color: #A31800"]:hover {
    background-color: #7a1200 !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(163, 24, 0, 0.3);
}

/* Enfocar inputs con el color correspondiente */
.form-control:focus, .form-select:focus {
    border-color: #2798F5 !important;
    box-shadow: 0 0 0 3px rgba(39, 152, 245, 0.15) !important;
}

/* Específico para inputs del formulario de contraseña */
#current_password:focus, #new_password:focus, #new_password_confirmation:focus {
    border-color: #A31800 !important;
    box-shadow: 0 0 0 3px rgba(163, 24, 0, 0.15) !important;
}

/* Estilo para el icono en el encabezado principal */
.fa-user-circle {
    color: rgb(21, 88, 185) !important;
}

/* Estilo para el icono de información */
.fa-info-circle {
    color: #A31800 !important;
}

/* Responsividad */
@media (max-width: 768px) {
    /* Reorganizar el encabezado en móvil */
    .page-header .d-flex {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 15px !important;
    }
    
    /* El botón volver ocupa todo el ancho en móvil */
    .page-header .btn-volver {
        width: 100% !important;
        justify-content: center !important;
        margin-top: 10px !important;
    }
    
    /* Ajustar columnas */
    .col-md-6 {
        width: 100% !important;
    }
    
    /* Ajustar espaciado del formulario */
    .card-body {
        padding: 1rem !important;
    }
    
    /* Ajustar inputs para mejor visualización */
    .form-control, .form-select {
        font-size: 16px !important;
        padding: 12px !important;
    }
    
    /* Botones ocupan todo el ancho */
    .d-flex.justify-content-between.pt-3.border-top {
        flex-direction: column !important;
    }
    
    .btn[style*="width: 100%"] {
        width: 100% !important;
    }
}

/* Ajuste para tablets */
@media (min-width: 769px) and (max-width: 991px) {
    .col-md-6 {
        width: 100% !important;
    }
    
    .card {
        margin-bottom: 20px !important;
    }
}
</style>
@endsection