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
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover datatable align-middle w-100 mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 py-3">ID</th>
                                <th>Descripción del Problema</th>
                                <th>Reportado Por</th>
                                <th>Ubicación</th>
                                <th>Fecha</th>
                                <th>Urgencia</th>
                                <th>Estado</th>
                                <th class="pe-3 text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tickets as $ticket)
                                <tr>
                                    <td class="ps-3 text-muted fw-bold">#{{ $ticket->id }}</td>
                                    <td class="fw-medium">{{ $ticket->title }}</td>
                                    <td>
                                        <span class="fw-bold text-dark">
                                            <i class="bi bi-person-circle me-1 text-primary"></i>
                                            {{ $ticket->user->name ?? 'Anónimo' }}
                                        </span>
                                    </td>
                                    <td><i class="bi bi-geo-alt me-1 text-muted"></i>{{ $ticket->location ?? 'N/A' }}</td>
                                    <td class="text-muted small">{{ $ticket->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if ($ticket->urgency == 'Alta')
                                            <span class="badge bg-danger px-2 py-1">Alta</span>
                                        @elseif($ticket->urgency == 'Media')
                                            <span class="badge bg-warning text-dark px-2 py-1">Media</span>
                                        @else
                                            <span class="badge bg-info text-dark px-2 py-1">Baja</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($ticket->status == 'Abierto')
                                            <span class="badge bg-danger px-2 py-1">Abierto</span>
                                        @elseif($ticket->status == 'En progreso')
                                            <span class="badge bg-warning text-dark px-2 py-1">En progreso</span>
                                        @else
                                            <span class="badge bg-success px-2 py-1">Resuelto</span>
                                        @endif
                                    </td>
                                    <td class="pe-3 text-end">
                                        <div class="d-inline-flex gap-1">
                                            <!-- Ver detalle -->
                                            <a href="{{ route('tickets.show', $ticket->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Ver detalle">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <!-- Cambiar a "En progreso" -->
                                            @if ($ticket->status != 'En progreso')
                                                <form action="{{ route('tickets.update', $ticket->id) }}" method="POST"
                                                    class="m-0">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="En progreso">
                                                    <button type="submit" class="btn btn-sm btn-outline-warning text-dark"
                                                        title="Marcar como En Progreso">
                                                        <i class="bi bi-tools"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Cambiar a "Resuelto" -->
                                            @if ($ticket->status != 'Resuelto')
                                                <form action="{{ route('tickets.update', $ticket->id) }}" method="POST"
                                                    class="m-0">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="Resuelto">
                                                    <button type="submit" class="btn btn-sm btn-outline-success"
                                                        title="Marcar como Resuelto">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
