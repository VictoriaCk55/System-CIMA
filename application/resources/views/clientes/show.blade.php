@extends('layouts.app')

@section('content')
<div class="container-main">
    <!-- Encabezado de página con más espacio -->
    <div class="page-header mt-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>
                    <i class="fas fa-user-circle" style="color: #2798F5;"></i>
                    Detalles del Cliente
                </h1>
                <p class="page-subtitle mt-2">
                    Información completa del cliente registrado
                </p>
            </div>
            <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary btn-volver" style="border-radius: 30px; padding: 8px 20px;">
                <i class="fas fa-arrow-left me-2"></i>
                Volver al listado
            </a>
        </div>
    </div>

    <!-- Mensajes de éxito/error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Información principal -->
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header" style="background-color: #2798F5; border-bottom: none;">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-id-card me-2"></i>
                        Información del Cliente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Razón Social</label>
                            <p class="fs-5 fw-semibold">{{ $cliente->razon_social }}</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Persona de Contacto</label>
                            <p class="fs-5">
                                <i class="fas fa-user me-2" style="color: #2798F5;"></i>
                                {{ $cliente->persona_contacto }}
                            </p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Teléfono</label>
                            <p class="fs-5">
                                @if($cliente->telefono)
                                    <i class="fas fa-phone me-2" style="color: #2798F5;"></i>
                                    {{ $cliente->telefono }}
                                @else
                                    <span class="text-muted">No registrado</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">NIT</label>
                            <p class="fs-5">
                                @if($cliente->nit)
                                    <i class="fas fa-id-card me-2" style="color: #2798F5;"></i>
                                    <code>{{ $cliente->nit }}</code>
                                @else
                                    <span class="text-muted">No registrado</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted small">Dirección</label>
                            <p class="fs-5">
                                @if($cliente->direccion)
                                    <i class="fas fa-map-marker-alt me-2" style="color: #2798F5;"></i>
                                    {{ $cliente->direccion }}
                                @else
                                    <span class="text-muted">No registrada</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted small">ID del Cliente</label>
                            <p>
                                <span class="badge bg-secondary fs-6">#{{ $cliente->id }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Información adicional -->
            <div class="card mb-4">
                <div class="card-header" style="background-color: #2798F5; border-bottom: none;">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-info-circle me-2"></i>
                        Información Adicional
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Fecha de Registro</label>
                        <p>
                            <i class="far fa-calendar-plus me-2" style="color: #2798F5;"></i>
                            {{ $cliente->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small">Última Actualización</label>
                        <p>
                            <i class="far fa-calendar-check me-2" style="color: #2798F5;"></i>
                            {{ $cliente->updated_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small">Estado en el Sistema</label>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span>Activo y disponible en el sistema</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Resumen Financiero -->
            @php
                use App\Models\MovimientoFinanciero;
                
                $totalProformas = $cliente->proformas->count();
                $saldoTotal = $cliente->proformas->sum('saldo');
                $totalPagado = $cliente->proformas->sum('adelanto');
                $totalFacturado = $cliente->proformas->sum('total');
                
                // Obtener el último movimiento financiero para el saldo actual real
                $ultimoMovimiento = MovimientoFinanciero::where('cliente_id', $cliente->id)
                    ->latest()
                    ->first();
                $saldoReal = $ultimoMovimiento ? $ultimoMovimiento->saldo_cliente : 0;
            @endphp
            
            <div class="card mb-4">
                <div class="card-header" style="background-color: #2798F5; border-bottom: none;">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-chart-pie me-2"></i>
                        Resumen Financiero
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Total Proformas</label>
                        <p class="fs-5">
                            <i class="fas fa-file-invoice me-2" style="color: #2798F5;"></i>
                            {{ $totalProformas }} proforma(s)
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small">Total Facturado</label>
                        <p class="fs-5">
                            <i class="fas fa-dollar-sign me-2" style="color: #2798F5;"></i>
                            Bs. {{ number_format($totalFacturado, 2) }}
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small">Total Pagado (Adelantos)</label>
                        <p class="fs-5">
                            <i class="fas fa-check-circle me-2" style="color: #28a745;"></i>
                            Bs. {{ number_format($totalPagado, 2) }}
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small">Saldo Pendiente</label>
                        <p class="fs-4 fw-bold {{ $saldoReal > 0 ? 'text-danger' : 'text-success' }}">
                            <i class="fas {{ $saldoReal > 0 ? 'fa-exclamation-triangle' : 'fa-check-circle' }} me-2"></i>
                            Bs. {{ number_format($saldoReal, 2) }}
                        </p>
                        @if($saldoReal > 0)
                            <div class="progress" style="height: 10px;">
                                @php
                                    $porcentajePagado = $totalFacturado > 0 ? ($totalPagado / $totalFacturado) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $porcentajePagado }}%;" 
                                     aria-valuenow="{{ $porcentajePagado }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ number_format($porcentajePagado, 1) }}%
                                </div>
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ 100 - $porcentajePagado }}%;" 
                                     aria-valuenow="{{ 100 - $porcentajePagado }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ number_format(100 - $porcentajePagado, 1) }}%
                                </div>
                            </div>
                            <small class="text-muted mt-1 d-block">
                                Pagado: {{ number_format($porcentajePagado, 1) }}% | Pendiente: {{ number_format(100 - $porcentajePagado, 1) }}%
                            </small>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Acciones -->
            @auth
                @if(Auth::user()->hasAnyRole(['admin', 'tecnico', 'analista']))
                    <div class="card">
                        <div class="card-header" style="background-color: #2798F5; border-bottom: none;">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-cogs me-2"></i>
                                Acciones
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @can('editar clientes')
                                <a href="{{ route('clientes.edit', $cliente) }}" 
                                   class="btn"
                                   style="color: #000000; border: 2px solid #ffc107; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500;"
                                   onmouseover="this.style.backgroundColor='#ffc107'; this.style.color='#000000'; this.style.borderColor='#ffc107';"
                                   onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000'; this.style.borderColor='#ffc107';">
                                    <i class="fas fa-edit me-2"></i>
                                    Editar Cliente
                                </a>
                                @endcan
                                
                                <!-- ===== NUEVO BOTÓN PARA REGISTRAR PAGO MANUAL ===== -->
                                
                                @can('registrar pago clientes')
                                <button type="button" 
                                        class="btn"
                                        style="color: #000000; border: 2px solid #198754; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500;"
                                        onmouseover="this.style.backgroundColor='#198754'; this.style.color='#ffffff';"
                                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000';"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#registrarPagoModal">
                                    <i class="fas fa-money-bill-wave me-2"></i>
                                    Registrar Pago Manual
                                </button>
                                @endcan
                                
                                <!-- ===== BOTÓN PARA ACTUALIZAR SALDO (RECALCULAR) ===== -->
                                @can('actualizar saldo clientes')
                                <form action="{{ route('clientes.actualizar-saldo', $cliente->id) }}" method="POST" class="d-grid">
                                    @csrf
                                    <button type="submit" class="btn" style="color: #000000; border: 2px solid #0dcaf0; background-color: transparent; border-radius: 30px; padding: 10px 25px; transition: all 0.3s ease; font-weight: 500;"
                                            onmouseover="this.style.backgroundColor='#0dcaf0'; this.style.color='#ffffff';"
                                            onmouseout="this.style.backgroundColor='transparent'; this.style.color='#000000';">
                                        <i class="fas fa-sync-alt me-2"></i>
                                        Recalcular Saldo
                                    </button>
                                </form>
                                @endcan
                                @can('eliminar clientes')
                                <button type="button" 
                                        class="btn btn-outline-danger" 
                                        style="border-radius: 30px; padding: 10px 25px; border-width: 2px;"
                                        onclick="confirmarEliminacion({{ $cliente->id }}, '{{ $cliente->razon_social }}', 'cliente')">
                                    <i class="fas fa-trash me-2"></i>
                                    Eliminar Cliente
                                </button>
                                
                                <form id="delete-form-{{ $cliente->id }}" 
                                      action="{{ route('clientes.destroy', $cliente) }}" 
                                      method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    <!-- Proformas del Cliente -->
    @if($cliente->proformas->count() > 0)
    <div class="card mt-4">
        <div class="card-header" style="background-color: #2798F5; border-bottom: none;">
            <h5 class="mb-0 text-white">
                <i class="fas fa-file-invoice-dollar me-2"></i>
                Proformas del Cliente - Resumen General
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr style="background-color: #2798F5; color: white;">
                            <th style="background-color: #2798F5; color: white;">Código</th>
                            <th style="background-color: #2798F5; color: white;">Fecha Emisión</th>
                            <th style="background-color: #2798F5; color: white;">Estado</th>
                            <th style="background-color: #2798F5; color: white;" class="text-end">Total (Bs.)</th>
                            <th style="background-color: #2798F5; color: white;" class="text-end">Adelanto (Bs.)</th>
                            <th style="background-color: #2798F5; color: white;" class="text-end">Saldo (Bs.)</th>
                            <th style="background-color: #2798F5; color: white;" class="text-center">Estado Deuda</th>
                            <th style="background-color: #2798F5; color: white;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cliente->proformas as $proforma)
                        <tr>
                            <td>
                                <span class="badge bg-secondary">{{ $proforma->codigo }}</span>
                            </td>
                            <td>{{ $proforma->fecha_emision->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $proforma->estado_color }}">{{ $proforma->estado_texto }}</span>
                            </td>
                            <td class="text-end fw-bold">Bs. {{ number_format($proforma->total, 2) }}</td>
                            <td class="text-end">Bs. {{ number_format($proforma->adelanto, 2) }}</td>
                            <td class="text-end fw-bold {{ $proforma->saldo > 0 ? 'text-danger' : 'text-success' }}">
                                Bs. {{ number_format($proforma->saldo, 2) }}
                            </td>
                            <td class="text-center">
                                @if($proforma->saldo > 0)
                                    <span class="badge bg-danger" style="padding: 6px 10px;">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Debe Bs. {{ number_format($proforma->saldo, 2) }}
                                    </span>
                                @elseif($proforma->saldo < 0)
                                    <span class="badge bg-warning text-dark" style="padding: 6px 10px;">
                                        <i class="fas fa-arrow-up me-1"></i>
                                        Favor Bs. {{ number_format(abs($proforma->saldo), 2) }}
                                    </span>
                                @else
                                    <span class="badge bg-success" style="padding: 6px 10px;">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Pagada
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('proformas.show', $proforma) }}" 
                                   class="btn btn-sm"
                                   style="color: #0dcaf0; border: 1px solid #0dcaf0; background: transparent; border-radius: 6px; padding: 0.3rem; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                   data-bs-toggle="tooltip" 
                                   title="Ver proforma">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #f8f9fa;">
                            <th colspan="3" class="text-end">Totales:</th>
                            <th class="text-end">Bs. {{ number_format($cliente->proformas->sum('total'), 2) }}</th>
                            <th class="text-end">Bs. {{ number_format($cliente->proformas->sum('adelanto'), 2) }}</th>
                            <th class="text-end {{ $cliente->proformas->sum('saldo') > 0 ? 'text-danger' : 'text-success' }}">
                                Bs. {{ number_format($cliente->proformas->sum('saldo'), 2) }}
                            </th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <!-- Mini resumen de deudas -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="alert alert-success py-2 mb-0">
                        <small>
                            <i class="fas fa-check-circle me-1"></i>
                            <strong>Pagadas:</strong> 
                            {{ $cliente->proformas->filter(function($p) { return $p->saldo <= 0; })->count() }} proformas
                        </small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-danger py-2 mb-0">
                        <small>
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <strong>Con deuda:</strong> 
                            {{ $cliente->proformas->filter(function($p) { return $p->saldo > 0; })->count() }} proformas
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="card mt-4">
        <div class="card-body text-center py-4">
            <i class="fas fa-file-invoice-dollar fa-3x mb-3 text-muted"></i>
            <h5>Este cliente no tiene proformas asociadas</h5>
            <p class="text-muted">Las proformas creadas para este cliente aparecerán aquí.</p>
        </div>
    </div>
    @endif

    <!-- ===== ÚLTIMOS MOVIMIENTOS FINANCIEROS ===== -->
    @php
        $movimientosRecientes = App\Models\MovimientoFinanciero::with(['usuario'])
            ->where('cliente_id', $cliente->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    @endphp

    @if($movimientosRecientes->count() > 0)
    <div class="card mt-4">
        <div class="card-header" style="background-color: #2798F5; border-bottom: none;">
            <h5 class="mb-0 text-white">
                <i class="fas fa-history me-2"></i>
                Últimos Movimientos Financieros
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Concepto</th>
                            <th class="text-end">Monto</th>
                            <th class="text-end">Saldo después</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movimientosRecientes as $mov)
                        <tr>
                            <td>{{ $mov->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($mov->tipo == 'DEUDA')
                                    <span class="badge bg-danger">DEUDA</span>
                                @elseif($mov->tipo == 'PAGO')
                                    <span class="badge bg-success">PAGO</span>
                                @else
                                    <span class="badge bg-warning">AJUSTE</span>
                                @endif
                            </td>
                            <td>{{ $mov->concepto }}</td>
                            <td class="text-end {{ $mov->tipo == 'DEUDA' ? 'text-danger' : 'text-success' }}">
                                {{ $mov->tipo == 'DEUDA' ? '-' : '+' }} Bs. {{ number_format($mov->monto, 2) }}
                            </td>
                            <td class="text-end fw-bold {{ $mov->saldo_cliente > 0 ? 'text-danger' : 'text-success' }}">
                                Bs. {{ number_format($mov->saldo_cliente, 2) }}
                            </td>
                            <td>{{ $mov->usuario->name ?? 'Sistema' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-2">
                <a href="{{ route('financiero.index', ['cliente_id' => $cliente->id]) }}" class="btn btn-sm" style="color: #2798F5;">
                    Ver todos los movimientos <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- ===== MODAL PARA REGISTRAR PAGO MANUAL ===== -->
 @can('registrar pago clientes')
<div class="modal fade" id="registrarPagoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #2798F5; border-bottom: none;">
                <h5 class="modal-title text-white">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Registrar Pago Manual
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: invert(1);"></button>
            </div>
            <form action="{{ route('clientes.registrar-pago', $cliente->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Cliente:</strong> {{ $cliente->razon_social }}<br>
                        <strong>Saldo actual:</strong> 
                        <span class="fw-bold {{ $saldoReal > 0 ? 'text-danger' : 'text-success' }}">
                            Bs. {{ number_format($saldoReal, 2) }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <label for="monto" class="form-label">
                            <i class="fas fa-dollar-sign me-1 text-danger"></i>
                            Monto del Pago <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">Bs.</span>
                            <input type="number" 
                                   class="form-control" 
                                   id="monto" 
                                   name="monto" 
                                   step="0.01" 
                                   min="0.01" 
                                   max="{{ abs($saldoReal) }}"
                                   placeholder="0.00"
                                   required>
                        </div>
                        <small class="text-muted">
                            Monto máximo a pagar: Bs. {{ number_format(abs($saldoReal), 2) }}
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="concepto" class="form-label">
                            <i class="fas fa-tag me-1"></i>
                            Concepto
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="concepto" 
                               name="concepto" 
                               value="Pago manual"
                               placeholder="Ej: Pago en efectivo">
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">
                            <i class="fas fa-sticky-note me-1"></i>
                            Observaciones
                        </label>
                        <textarea class="form-control" 
                                  id="observaciones" 
                                  name="observaciones" 
                                  rows="2"
                                  placeholder="Observaciones adicionales..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 30px; padding: 8px 20px;">
                        <i class="fas fa-times me-2"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn" style="background-color: #2798F5; border-radius: 30px; padding: 8px 20px; color: white; border: none;">
                        <i class="fas fa-save me-2"></i>
                        Registrar Pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
<!-- Estilos adicionales -->
<style>
.btn[style*="background-color: #2798F5"]:hover {
    background-color: #1a7ac9 !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(39, 152, 245, 0.3);
}

.btn-outline-danger {
    border-radius: 30px !important;
    padding: 10px 25px !important;
    transition: all 0.3s ease !important;
}

.fa-users {
    color: #2798F5 !important;
}

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

.progress {
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    transition: width 0.6s ease;
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
        margin-top: 10px !important;
    }
    
    .col-md-8, .col-md-4 {
        width: 100% !important;
    }
    
    .table-responsive {
        overflow-x: auto !important;
    }
}
</style>

@push('scripts')
<script>
    function confirmarEliminacion(id, nombre, tipo) {
        if (confirm(`¿Está seguro de eliminar el ${tipo} "${nombre}"?\n\n⚠️ Esta acción moverá el registro a la papelera.`)) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    }
    
    // Validar que el monto no exceda el saldo
    document.addEventListener('DOMContentLoaded', function() {
        const montoInput = document.getElementById('monto');
        if (montoInput) {
            const maxMonto = {{ abs($saldoReal) }};
            montoInput.addEventListener('change', function() {
                if (parseFloat(this.value) > maxMonto) {
                    alert('El monto no puede ser mayor al saldo pendiente');
                    this.value = maxMonto;
                }
            });
        }
    });
</script>
@endpush
@endsection