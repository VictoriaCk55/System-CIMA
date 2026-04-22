@extends('layouts.app')

@section('title', 'Resumen Financiero')

@section('content')
<div class="container-main">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>
                    <i class="fas fa-chart-line" style="color: #2798F5;"></i>
                    Resumen Financiero General
                </h1>
                <p class="page-subtitle">
                    Movimientos financieros del sistema
                </p>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('financiero.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                           value="{{ request('fecha_inicio') }}">
                </div>
                <div class="col-md-3">
                    <label for="fecha_fin" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                           value="{{ request('fecha_fin') }}">
                </div>
                <div class="col-md-3">
                    <label for="cliente_id" class="form-label">Cliente</label>
                    <select class="form-select" id="cliente_id" name="cliente_id">
                        <option value="">Todos los clientes</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ request('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->razon_social }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select class="form-select" id="tipo" name="tipo">
                        <option value="">Todos</option>
                        <option value="DEUDA" {{ request('tipo') == 'DEUDA' ? 'selected' : '' }}>Deudas</option>
                        <option value="PAGO" {{ request('tipo') == 'PAGO' ? 'selected' : '' }}>Pagos</option>
                        <option value="AJUSTE" {{ request('tipo') == 'AJUSTE' ? 'selected' : '' }}>Ajustes</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn" style="background-color: #2798F5; color: white; border: none; border-radius: 30px; padding: 8px 20px;">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Deudas</h5>
                    <h3>Bs. {{ number_format($resumen['total_deudas'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Pagos</h5>
                    <h3>Bs. {{ number_format($resumen['total_pagos'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Saldo Pendiente</h5>
                    <h3>Bs. {{ number_format($resumen['saldo_total'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Clientes con Deuda</h5>
                    <h3>{{ $resumen['clientes_con_deuda'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de movimientos -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Movimientos Financieros</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Concepto</th>
                            <th>Tipo</th>
                            <th class="text-end">Monto</th>
                            <th class="text-end">Saldo Cliente</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movimientos as $mov)
                        <tr>
                            <td>{{ $mov->fecha->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('clientes.show', $mov->cliente) }}">
                                    {{ $mov->cliente->razon_social }}
                                </a>
                            </td>
                            <td>{{ $mov->concepto }}</td>
                            <td>
                                @if($mov->tipo == 'DEUDA')
                                    <span class="badge bg-danger">DEUDA</span>
                                @elseif($mov->tipo == 'PAGO')
                                    <span class="badge bg-success">PAGO</span>
                                @else
                                    <span class="badge bg-warning">AJUSTE</span>
                                @endif
                            </td>
                            <td class="text-end {{ $mov->tipo == 'DEUDA' ? 'text-danger' : 'text-success' }}">
                                {{ $mov->tipo == 'DEUDA' ? '-' : '+' }} Bs. {{ number_format($mov->monto, 2) }}
                            </td>
                            <td class="text-end fw-bold {{ $mov->saldo_cliente > 0 ? 'text-danger' : 'text-success' }}">
                                Bs. {{ number_format($mov->saldo_cliente, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-info-circle fa-2x mb-2 text-muted"></i>
                                <p class="text-muted">No hay movimientos para mostrar</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($movimientos->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $movimientos->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection