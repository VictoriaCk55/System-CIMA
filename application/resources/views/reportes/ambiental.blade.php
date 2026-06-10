@extends('layouts.app')

@section('title', 'Reporte Ambiental')

@push('styles')
<style>
    .hub-card {
        border-radius: 15px;
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .hub-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    .hub-card .card-body {
        padding: 30px 20px;
        text-align: center;
    }
    .hub-card .icon-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 28px;
        color: #fff;
    }
    .hub-card.aire .icon-circle { background: #0d6efd; }
    .hub-card.ruido .icon-circle { background: #fd7e14; }
    .hub-card.gases .icon-circle { background: #198754; }
    .hub-card.aire { border: 2px solid #0d6efd; }
    .hub-card.ruido { border: 2px solid #fd7e14; }
    .hub-card.gases { border: 2px solid #198754; }
    .section-card { border-radius: 10px; margin-bottom: 1.5rem; }
    .section-card .card-header { font-weight: 600; border-radius: 10px 10px 0 0; }
    .section-card.aire .card-header { background-color: #0d6efd; color: #fff; }
    .section-card.ruido .card-header { background-color: #fd7e14; color: #fff; }
    .section-card.gases .card-header { background-color: #198754; color: #fff; }
</style>
@endpush

@section('content')
<div class="container-main">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-file-signature" style="color: #ffc107;"></i> Reporte Ambiental</h1>
                <p class="page-subtitle">
                    Proforma: <strong>{{ $proforma->codigo }}</strong> — {{ $proforma->cliente->razon_social }}
                </p>
            </div>
            <a href="{{ route('proformas.show', $proforma) }}" class="btn btn-outline-secondary" style="border-radius: 30px; padding: 8px 20px;">
                <i class="fas fa-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4 mt-2">
        @if(in_array('AIRE', $categorias))
        <div class="col-md-4">
            <a href="{{ route('reportes.ambiental.aire', $proforma) }}" class="hub-card aire card">
                <div class="card-body">
                    <div class="icon-circle"><i class="fas fa-wind"></i></div>
                    <h5 class="fw-bold">Aire</h5>
                    <p class="text-muted small mb-0">Muestreo de Partículas Suspendidas</p>
                    <span class="badge bg-primary mt-2">{{ $conteo['AIRE'] ?? 0 }} parámetro(s)</span>
                    <span class="badge bg-primary mt-3 d-block">Ingresar datos <i class="fas fa-arrow-right ms-1"></i></span>
                </div>
            </a>
        </div>
        @endif
        @if(in_array('RUIDO', $categorias))
        <div class="col-md-4">
            <a href="{{ route('reportes.ambiental.ruido', $proforma) }}" class="hub-card ruido card">
                <div class="card-body">
                    <div class="icon-circle"><i class="fas fa-volume-up"></i></div>
                    <h5 class="fw-bold">Ruido</h5>
                    <p class="text-muted small mb-0">Nivel de Presión Sonora (NPS)</p>
                    <span class="badge bg-warning text-dark mt-2">{{ $conteo['RUIDO'] ?? 0 }} parámetro(s)</span>
                    <span class="badge bg-warning text-dark mt-3 d-block">Ingresar datos <i class="fas fa-arrow-right ms-1"></i></span>
                </div>
            </a>
        </div>
        @endif
        @if(in_array('GASES', $categorias))
        <div class="col-md-4">
            <a href="{{ route('reportes.ambiental.gases', $proforma) }}" class="hub-card gases card">
                <div class="card-body">
                    <div class="icon-circle"><i class="fas fa-flask"></i></div>
                    <h5 class="fw-bold">Gases</h5>
                    <p class="text-muted small mb-0">Medición de Gases</p>
                    <span class="badge bg-success mt-2">{{ $conteo['GASES'] ?? 0 }} parámetro(s)</span>
                    <span class="badge bg-success mt-3 d-block">Ingresar datos <i class="fas fa-arrow-right ms-1"></i></span>
                </div>
            </a>
        </div>
        @endif
    </div>

    @if($reporte && $reporte->estado === 'PUBLICADO')
    <div class="mt-4 d-flex gap-2">
        <a href="{{ route('reportes.ambiental.pdf', $reporte) }}" target="_blank" class="btn btn-info" style="border-radius: 30px; padding: 10px 25px;">
            <i class="fas fa-file-pdf me-2"></i> Ver PDF completo
        </a>
        <a href="{{ route('reportes.ambiental.download', $reporte) }}" class="btn btn-success" style="border-radius: 30px; padding: 10px 25px;">
            <i class="fas fa-download me-2"></i> Descargar PDF completo
        </a>
    </div>
    @endif
</div>
@endsection
