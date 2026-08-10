@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Historial de Incidencias</h2>
            <p class="text-muted mt-1">Gestión completa de mantenciones y requerimientos</p>
        </div>
        <a href="{{ route('tickets.create') }}" class="btn btn-primary px-4">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Ticket
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3">ID</th>
                            <th>Descripción del Problema</th>
                            <th>Ubicación</th>
                            <th>Fecha</th>
                            <th>Urgencia</th>
                            <th>Estado</th>
                            <th class="pe-4 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                        <tr>
                            <td class="ps-4 text-muted fw-bold">#{{ $ticket->id }}</td>
                            <td class="fw-medium">{{ $ticket->title }}</td>
                            <td><i class="bi bi-geo-alt me-1 text-muted"></i>{{ $ticket->location ?? 'N/A' }}</td>
                            <td class="text-muted small">{{ $ticket->created_at->format('d/m/Y') }}</td>
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
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Gestionar
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                        <li><a class="dropdown-item fw-bold text-primary" href="{{ route('tickets.show', $ticket->id) }}"><i class="bi bi-eye me-2"></i> Ver detalle</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                                                @csrf @method('PUT') <input type="hidden" name="status" value="En progreso">
                                                <button type="submit" class="dropdown-item"><i class="bi bi-tools me-2 text-warning"></i> "En progreso"</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                                                @csrf @method('PUT') <input type="hidden" name="status" value="Resuelto">
                                                <button type="submit" class="dropdown-item"><i class="bi bi-check-circle-fill me-2 text-success"></i> "Resuelto"</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                No hay incidencias en el historial.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Paginación -->
        @if($tickets->hasPages())
        <div class="card-footer bg-white border-top p-3 d-flex justify-content-center">
            {{ $tickets->links() }}
        </div>
        @endif
    </div>
</div>
@endsection