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

    <!-- MÓDULO DE INCIDENCIAS Y MANTENCIONES -->
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-bottom pb-3 pt-4 px-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <span class="fw-bold fs-5" style="color: #1B2A47;">
                        <i class="bi bi-tools me-2 text-secondary"></i>Mantenciones e Incidencias
                    </span>
                    <a href="{{ route('tickets.create') }}" class="btn btn-primary btn-sm px-3 py-2">
                        <i class="bi bi-plus-lg me-1"></i> Nuevo Ticket
                    </a>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 py-3">Descripción del Problema</th>
                                    <th>Urgencia</th>
                                    <th>Estado</th>
                                    <th class="pe-4 text-end">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark">{{ $ticket->title }}</div>
                                        <div class="small text-muted mt-1">
                                            <i class="bi bi-geo-alt me-1"></i> {{ $ticket->location ?? 'Sin ubicación específica' }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($ticket->urgency == 'Alta')
                                            <span class="badge bg-danger px-2 py-1">Alta</span>
                                        @elseif($ticket->urgency == 'Media')
                                            <span class="badge bg-warning text-dark px-2 py-1">Media</span>
                                        @else
                                            <span class="badge bg-info text-dark px-2 py-1">Baja</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ticket->status == 'Abierto')
                                            <span class="badge bg-danger px-2 py-1">Abierto</span>
                                        @elseif($ticket->status == 'En progreso')
                                            <span class="badge bg-warning text-dark px-2 py-1">En progreso</span>
                                        @else
                                            <span class="badge bg-success px-2 py-1">Resuelto</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end">
                                        <button class="btn btn-sm btn-outline-secondary">Ver detalle</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
                                        <i class="bi bi-check2-circle fs-1 d-block mb-2 text-success"></i>
                                        No hay incidencias registradas. ¡Todo está en orden!
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection