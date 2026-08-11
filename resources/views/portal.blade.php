@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">

                <!-- Encabezado de Bienvenida -->
                <div class="text-center mb-4">
                    <i class="bi bi-house-check-fill text-primary" style="font-size: 4rem;"></i>
                    <h2 class="fw-bold mt-2">Bienvenido, {{ Auth::user()->name }}</h2>
                    <p class="text-muted">
                        @if ($resident && $resident->unit)
                            Residente del <strong>Depto {{ $resident->unit->number }}</strong>
                            {{ $resident->unit->tower ? '(Torre ' . $resident->unit->tower . ')' : '' }}
                            en {{ $resident->unit->condominium->name }}
                        @else
                            Tu cuenta aún no tiene un departamento asignado.
                        @endif
                    </p>
                </div>

                <!-- Alertas de estado -->
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                        <i class="bi bi-check-circle me-1"></i> {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- TARJETA 1: MIS GASTOS COMUNES -->
                <div class="card border-0 shadow-sm rounded-4 mb-5">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="fw-bold border-bottom pb-3 mb-4">
                            <i class="bi bi-receipt me-2 text-primary"></i> Mis Gastos Comunes
                        </h4>

                        <div class="table-responsive">
                            <table class="table table-hover datatable text-start align-middle w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th>Periodo</th>
                                        <th>Vencimiento</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expenses as $expense)
                                        <tr>
                                            <td>{{ $expense->month }} {{ $expense->year }}</td>
                                            <td>{{ \Carbon\Carbon::parse($expense->due_date)->format('d-m-Y') }}</td>
                                            <td class="fw-bold">$ {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($expense->status == 'Pendiente')
                                                    <span class="badge bg-danger">Por Pagar</span>
                                                @else
                                                    <span class="badge bg-success">Pagado</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($expense->status == 'Pendiente')
                                                    <button class="btn btn-sm btn-primary disabled">
                                                        <i class="bi bi-credit-card me-1"></i> Pagar en línea
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline-secondary disabled">
                                                        <i class="bi bi-check2-all me-1"></i> Listo
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TARJETA 2: MIS REQUERIMIENTOS E INCIDENCIAS -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-md-center border-bottom pb-3 mb-4 gap-3">
                            <h4 class="fw-bold mb-0">
                                <i class="bi bi-tools me-2 text-warning"></i> Mis Requerimientos / Incidencias
                            </h4>
                            <!-- Botón para abrir el modal de nuevo ticket -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#newTicketModal">
                                <i class="bi bi-plus-circle me-1"></i> Crear Requerimiento
                            </button>
                        </div>

                        <!-- Tabla de solo lectura para el residente -->
                        <div class="table-responsive">
                            <table class="table table-hover datatable text-start align-middle w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th># ID</th>
                                        <th>Descripción del Problema</th>
                                        <th>Ubicación</th>
                                        <th>Fecha</th>
                                        <th>Urgencia</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tickets as $ticket)
                                        <tr>
                                            <td class="fw-bold text-muted">#{{ $ticket->id }}</td>
                                            <td class="fw-medium">{{ $ticket->title }}</td>
                                            <td>
                                                <i class="bi bi-geo-alt me-1 text-muted"></i>
                                                {{ $ticket->location ?? 'En departamento' }}
                                            </td>
                                            <td class="small text-muted">{{ $ticket->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                @if ($ticket->urgency == 'Alta')
                                                    <span class="badge bg-danger">Alta</span>
                                                @elseif($ticket->urgency == 'Media')
                                                    <span class="badge bg-warning text-dark">Media</span>
                                                @else
                                                    <span class="badge bg-info text-dark">Baja</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($ticket->status == 'Abierto')
                                                    <span class="badge bg-danger">Abierto</span>
                                                @elseif($ticket->status == 'En progreso')
                                                    <span class="badge bg-warning text-dark">En progreso</span>
                                                @else
                                                    <span class="badge bg-success">Resuelto</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL DE NUEVO REQUERIMIENTO -->
    <div class="modal fade" id="newTicketModal" tabindex="-1" aria-labelledby="newTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header bg-white py-3 border-bottom">
                    <h5 class="modal-title fw-bold text-dark" id="newTicketModalLabel">
                        <i class="bi bi-tools me-2 text-warning"></i>Nuevo Requerimiento
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('portal.tickets.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">Descripción del Problema / Asunto:</label>
                            <input type="text" name="title" id="title" class="form-control"
                                placeholder="Ej: Fuga de agua en el baño principal" required>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label fw-bold">Ubicación de la Incidencia:</label>
                            <input type="text" name="location" id="location" class="form-control"
                                value="Depto {{ $resident->unit->number ?? '' }} {{ optional($resident->unit)->tower ? '- Torre ' . $resident->unit->tower : '' }}"
                                placeholder="Ej: Balcón, Pasillo, Estacionamiento">
                        </div>

                        <div class="mb-3">
                            <label for="urgency" class="form-label fw-bold">Nivel de Urgencia:</label>
                            <select name="urgency" id="urgency" class="form-select" required>
                                <option value="Baja">Baja (Requerimiento general)</option>
                                <option value="Media" selected>Media (Atención regular)</option>
                                <option value="Alta">Alta (Urgencia inmediata)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-3 border-top">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-send me-1"></i> Enviar Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
