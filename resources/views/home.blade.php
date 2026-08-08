@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Panel de Control</h2>
            <p class="text-muted mt-1">Resumen general de tu gestión, {{ Auth::user()->name }}</p>
        </div>
    </div>

    <div class="row">
        <!-- Tarjeta Comunidades -->
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            <div class="card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                <div class="card-body text-white position-relative overflow-hidden p-4">
                    <i class="bi bi-building position-absolute" style="font-size: 6rem; opacity: 0.1; right: -10px; bottom: -20px;"></i>
                    <h5 class="card-title fw-light mb-1 text-nowrap">Comunidades</h5>
                    <!-- Se cambió display-5 por fs-2 para evitar que el texto se rompa -->
                    <h2 class="fs-2 fw-bold mb-0 text-nowrap">{{ $totalCondominiums ?? 0 }}</h2>
                </div>
                <div class="card-footer bg-transparent border-top-0 pb-3 px-4">
                    <a href="{{ route('condominiums.index') }}" class="text-white text-decoration-none fw-medium"><i class="bi bi-arrow-right-circle me-1"></i> Ver detalles</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta Unidades -->
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            <div class="card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <div class="card-body text-white position-relative overflow-hidden p-4">
                    <i class="bi bi-door-open position-absolute" style="font-size: 6rem; opacity: 0.1; right: -10px; bottom: -20px;"></i>
                    <h5 class="card-title fw-light mb-1 text-nowrap">Unidades</h5>
                    <h2 class="fs-2 fw-bold mb-0 text-nowrap">{{ $totalUnits ?? 0 }}</h2>
                </div>
                <div class="card-footer bg-transparent border-top-0 pb-3 px-4">
                    <a href="{{ route('units.index') }}" class="text-white text-decoration-none fw-medium"><i class="bi bi-arrow-right-circle me-1"></i> Ver detalles</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta Dinero Pendiente -->
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            <div class="card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <div class="card-body text-white position-relative overflow-hidden p-4">
                    <i class="bi bi-exclamation-circle position-absolute" style="font-size: 6rem; opacity: 0.1; right: -10px; bottom: -20px;"></i>
                    <h5 class="card-title fw-light mb-1 text-nowrap">Por Cobrar</h5>
                    <h2 class="fs-2 fw-bold mb-0 text-nowrap">$ {{ isset($pendingAmount) ? number_format($pendingAmount, 0, ',', '.') : '0' }}</h2>
                </div>
                <div class="card-footer bg-transparent border-top-0 pb-3 px-4">
                    <a href="{{ route('common_expenses.index') }}" class="text-white text-decoration-none fw-medium text-nowrap"><i class="bi bi-arrow-right-circle me-1"></i> Ir a Finanzas</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta Dinero Recaudado -->
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            <div class="card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <div class="card-body text-white position-relative overflow-hidden p-4">
                    <i class="bi bi-check2-circle position-absolute" style="font-size: 6rem; opacity: 0.1; right: -10px; bottom: -20px;"></i>
                    <h5 class="card-title fw-light mb-1 text-nowrap">Recaudado</h5>
                    <h2 class="fs-2 fw-bold mb-0 text-nowrap">$ {{ isset($paidAmount) ? number_format($paidAmount, 0, ',', '.') : '0' }}</h2>
                </div>
                <div class="card-footer bg-transparent border-top-0 pb-3 px-4">
                    <a href="{{ route('common_expenses.index') }}" class="text-white text-decoration-none fw-medium text-nowrap"><i class="bi bi-arrow-right-circle me-1"></i> Ir a Finanzas</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection