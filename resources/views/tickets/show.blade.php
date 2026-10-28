@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <!-- Botón Volver -->
            <div class="mb-3">
                <a href="{{ route('home') }}" class="text-decoration-none text-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Volver al Panel
                </a>
            </div>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-bottom p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="fw-bold mb-0 text-dark">Ticket #{{ $ticket->id }}</h4>
                        
                        <!-- Mostramos el estado actual con colores -->
                        @if($ticket->status == 'Abierto')
                            <span class="badge bg-danger fs-6 px-3 py-2">Abierto</span>
                        @elseif($ticket->status == 'En progreso')
                            <span class="badge bg-warning text-dark fs-6 px-3 py-2">En progreso</span>
                        @else
                            <span class="badge bg-success fs-6 px-3 py-2">Resuelto</span>
                        @endif
                    </div>
                </div>

                <div class="card-body p-4 text-start">
                    <!-- Título -->
                    <h5 class="text-muted mb-1 text-uppercase" style="font-size: 0.85rem;">Descripción del Problema</h5>
                    <p class="fs-5 fw-medium mb-4">{{ $ticket->title }}</p>
                    
                    <div class="row bg-light rounded-3 p-3 mb-4">
                        <!-- Ubicación -->
                        <div class="col-6">
                            <h5 class="text-muted mb-1 text-uppercase" style="font-size: 0.85rem;">Ubicación</h5>
                            <p class="mb-0 fw-bold"><i class="bi bi-geo-alt me-1"></i> {{ $ticket->location ?? 'No especificada' }}</p>
                        </div>
                        <!-- Urgencia -->
                        <div class="col-6">
                            <h5 class="text-muted mb-1 text-uppercase" style="font-size: 0.85rem;">Nivel de Urgencia</h5>
                            <p class="mb-0 fw-bold"><i class="bi bi-exclamation-triangle me-1"></i> {{ $ticket->urgency }}</p>
                        </div>
                    </div>

                    <div class="text-muted small">
                        <i class="bi bi-calendar-check me-1"></i> Creado el {{ $ticket->created_at->format('d/m/Y') }} a las {{ $ticket->created_at->format('H:i') }}
                    </div>
                </div>
                
                <!-- Panel de Acciones -->
                <div class="card-footer bg-white border-top p-4">
                    <h6 class="fw-bold mb-3">Actualizar estado del ticket:</h6>
                    <div class="d-flex gap-2">
                        <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="En progreso">
                            <button type="submit" class="btn btn-outline-warning text-dark fw-medium" {{ $ticket->status == 'En progreso' ? 'disabled' : '' }}>
                                <i class="bi bi-tools me-1"></i> Pasar a En Progreso
                            </button>
                        </form>

                        <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="Resuelto">
                            <button type="submit" class="btn btn-outline-success fw-medium" {{ $ticket->status == 'Resuelto' ? 'disabled' : '' }}>
                                <i class="bi bi-check-circle-fill me-1"></i> Marcar como Resuelto
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection