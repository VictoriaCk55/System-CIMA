@extends('layouts.app')

@section('title', 'Papelera de Informes')

@section('content')
<div class="container-main">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>
                    <i class="fas fa-trash-alt" style="color: #C2F527;"></i>
                    Papelera de Informes
                </h1>
                <p class="page-subtitle">
                    Informes eliminados que pueden ser restaurados (Solo Administrador)
                </p>
            </div>
            <a href="{{ route('informes.index') }}" class="btn btn-outline-secondary btn-volver" style="border-radius: 30px; padding: 8px 20px;">
                <i class="fas fa-arrow-left me-2"></i>
                Volver a informes activos
            </a>
        </div>
    </div>

    <!-- ===== MENSAJE ESTÁTICO DE ADVERTENCIA (NO ES UNA ALERTA) ===== -->
    <div class="mensaje-advertencia mb-4" style="border-left: 5px solid #dc3545; background-color: #fff3cd; padding: 1rem; border-radius: 0.5rem;">
        <div class="d-flex">
            <div class="me-3">
                <i class="fas fa-exclamation-triangle fa-2x" style="color: #dc3545;"></i>
            </div>
            <div>
                <h5 style="color: #856404; font-weight: 700; margin-bottom: 0.25rem;">
                    ⚠️ ¡ATENCIÓN! ELIMINACIÓN PERMANENTE
                </h5>
                <p style="color: #856404; margin-bottom: 0; line-height: 1.5;">
                    Al hacer clic en el botón <span style="background-color: #dc3545; color: white; padding: 0.2rem 0.5rem; border-radius: 0.25rem; font-weight: 600;">Eliminar</span> el informe será <strong>BORRADO DEFINITIVAMENTE</strong> del sistema.<br>
                    Esta acción es <strong>IRREVERSIBLE</strong> y no se podrá recuperar la información bajo ninguna circunstancia.
                </p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="background-color: #C2F527; border-bottom: none;">
            <h5 class="mb-0" style="color: #000000;">
                <i class="fas fa-trash-alt me-2"></i>
                Informes Eliminados
            </h5>
        </div>
        <div class="card-body">
            @if($informes->count() > 0)
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Área exclusiva para administradores.</strong> Aquí puede restaurar informes eliminados o eliminarlos permanentemente.
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr style="background-color: #C2F527; color: #000;">
                                <th>Código</th>
                                <th>Proforma</th>
                                <th>Cliente</th>
                                <th>Fecha Emisión</th>
                                <th>Prioridad</th>
                                <th>Estado</th>
                                <th>Eliminado el</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($informes as $informe)
                                <tr>
                                    <td><span class="badge bg-secondary">{{ $informe->codigo }}</span></td>
                                    <td>{{ $informe->proforma->codigo ?? 'N/A' }}</td>
                                    <td>{{ $informe->proforma->cliente->razon_social ?? 'N/A' }}</td>
                                    <td>{{ $informe->fecha_emision->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $informe->prioridad_color }}">{{ $informe->prioridad_texto }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $informe->estado_color }}">{{ $informe->estado_texto }}</span>
                                    </td>
                                    <td>
                                        <span class="text-danger">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            {{ $informe->deleted_at->format('d/m/Y H:i') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <form action="{{ route('informes.restore', $informe->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" 
                                                        data-bs-toggle="tooltip" title="Restaurar informe"
                                                        onclick="return confirm('¿Está seguro de restaurar este informe?')">
                                                    <i class="fas fa-trash-restore me-1"></i> Restaurar
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('informes.force-delete', $informe->id) }}" method="POST" class="d-inline" id="delete-form-{{ $informe->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        data-bs-toggle="tooltip" 
                                                        title="Eliminar permanentemente"
                                                        onclick="confirmarEliminacionPermanente({{ $informe->id }}, '{{ $informe->codigo }}')">
                                                    <i class="fas fa-times-circle me-1"></i> Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($informes->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $informes->links() }}
                    </div>
                @endif
                
                <div class="text-center mt-3 text-muted">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        Total de informes en papelera: {{ $informes->total() }}
                    </small>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-trash-alt fa-4x mb-4 text-muted"></i>
                    <h4 class="mb-3">La papelera está vacía</h4>
                    <p class="text-muted mb-4">No hay informes eliminados para mostrar.</p>
                    <a href="{{ route('informes.index') }}" class="btn" style="background-color: #C2F527; color: #000; border-radius: 30px; padding: 10px 25px;">
                        <i class="fas fa-arrow-left me-2"></i>
                        Volver a informes activos
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.btn-volver {
    color: #000000 !important;
    border: 2px solid #ffffff !important;
    background-color: #ffffff !important;
    transition: all 0.3s ease !important;
    font-weight: 500 !important;
}

.btn-volver:hover {
    background-color: #ffffff !important;
    color: #000000 !important;
    border-color: #ffffff !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 20px rgba(128, 128, 128, 0.3) !important;
}

.btn-success, .btn-danger {
    transition: all 0.2s ease;
    margin: 0 3px;
    border-radius: 20px;
    padding: 0.375rem 1rem;
}

.btn-success:hover, .btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* Estilo para el mensaje de advertencia estático */
.mensaje-advertencia {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Reforzar color del icono de informes */
.fa-file-alt {
    color: #C2F527 !important;
}

@media (max-width: 768px) {
    .page-header .d-flex {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 15px !important;
    }
    
    .page-header .btn-volver {
        width: 100% !important;
        justify-content: center !important;
    }
    
    .btn-group {
        flex-direction: column !important;
        gap: 5px !important;
    }
    
    .btn-group .btn {
        width: 100% !important;
        margin: 0 !important;
    }
}
</style>

@push('scripts')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Función específica para confirmar eliminación permanente
    function confirmarEliminacionPermanente(id, codigo) {
        Swal.fire({
            title: '¿Eliminar permanentemente?',
            html: `
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <p class="fw-bold">Estás a punto de eliminar PERMANENTEMENTE el informe:</p>
                    <p class="fs-5 text-danger fw-bold">"${codigo}"</p>
                    <p class="text-danger mt-2">⚠️ Esta acción NO SE PUEDE DESHACER</p>
                    <p class="small text-muted">El informe será borrado definitivamente de la base de datos y no podrá ser recuperado.</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar permanentemente',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
</script>
@endpush
@endsection