@extends('layouts.app')

@section('content')
    <div class="container pb-5">

        <!-- BANNER TIPO HEROE / BIENVENIDA -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 text-white overflow-hidden"
            style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
            <div class="card-body p-4 p-md-5">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <span class="badge mb-2 px-3 py-2 fw-normal rounded-pill text-white"
                            style="background-color: rgba(255, 255, 255, 0.2); backdrop-filter: blur(5px);">
                            <i class="bi bi-person-badge me-1"></i> Portal del Residente
                        </span>
                        <h1 class="fw-bold display-6 text-white mb-2">¡Hola, {{ Auth::user()->name }}!</h1>
                        <p class="text-white-50 mb-0 fs-6">
                            Bienvenido a tu panel de gestión. Desde aquí puedes revisar el estado de tu cuenta y reportar
                            cualquier requerimiento.
                        </p>
                    </div>

                    <!-- Ficha de la propiedad con soporte universal para modo claro/oscuro -->
                    <div class="col-lg-5 mt-4 mt-lg-0">
                        @if ($resident && $resident->unit)
                            <div class="rounded-4 p-3 border"
                                style="background-color: rgba(255, 255, 255, 0.15); border-color: rgba(255, 255, 255, 0.25) !important; backdrop-filter: blur(8px);">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-building fs-3 text-white me-3"></i>
                                    <div>
                                        <div class="small text-white-50 text-uppercase fw-semibold">Comunidad</div>
                                        <div class="fw-bold text-white fs-5">{{ $resident->unit->condominium->name }}</div>
                                    </div>
                                </div>
                                <hr class="my-2" style="border-color: rgba(255, 255, 255, 0.25);">
                                <div class="d-flex justify-content-between pt-1 text-center">
                                    <div class="col">
                                        <span class="d-block text-white-50 small">Departamento</span>
                                        <span class="fw-bold text-white fs-5">{{ $resident->unit->number }}</span>
                                    </div>
                                    @if ($resident->unit->tower)
                                        <div class="col border-start"
                                            style="border-color: rgba(255, 255, 255, 0.25) !important;">
                                            <span class="d-block text-white-50 small">Torre</span>
                                            <span class="fw-bold text-white fs-5">{{ $resident->unit->tower }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="rounded-4 p-3 border text-center"
                                style="background-color: rgba(255, 255, 255, 0.15); border-color: rgba(255, 255, 255, 0.25) !important;">
                                <i class="bi bi-exclamation-triangle fs-2 text-warning d-block mb-1"></i>
                                <span class="small text-white">Sin departamento asignado actualmente.</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas de estado -->
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">

            <!-- TARJETA 1: MIS GASTOS COMUNES -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header py-3 px-4 border-0 d-flex align-items-center justify-content-between"
                        style="background: transparent;">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3 text-primary">
                                <i class="bi bi-receipt-cutoff fs-4"></i>
                            </div>
                            <h5 class="fw-bold mb-0 text-dark">Mis Gastos Comunes</h5>
                        </div>
                    </div>

                    <div class="card-body px-4 pb-4 pt-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle w-100 mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3 border-0 py-3">Periodo</th>
                                        <th class="border-0">Vencimiento</th>
                                        <th class="border-0">Monto</th>
                                        <th class="border-0">Estado</th>
                                        <th class="pe-3 border-0 text-end">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($expenses as $expense)
                                        <tr>
                                            <td class="ps-3 fw-bold text-dark">{{ $expense->month }} {{ $expense->year }}
                                            </td>
                                            <td class="text-muted">
                                                {{ \Carbon\Carbon::parse($expense->due_date)->format('d-m-Y') }}</td>
                                            <td class="fw-bold fs-6">$ {{ number_format($expense->amount, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                @if ($expense->status == 'Pendiente')
                                                    <span
                                                        class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill">Por
                                                        Pagar</span>
                                                @else
                                                    <span
                                                        class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">Pagado</span>
                                                @endif
                                            </td>
                                            <td class="pe-3 text-end">
                                                @if ($expense->status == 'Pendiente')
                                                    <button class="btn btn-sm btn-primary rounded-pill px-3 disabled">
                                                        <i class="bi bi-credit-card me-1"></i> Pagar
                                                    </button>
                                                @else
                                                    <span class="text-success small fw-semibold"><i
                                                            class="bi bi-check-circle-fill me-1"></i> Al día</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <i
                                                    class="bi bi-check2-circle text-success fs-1 d-block mb-2 opacity-75"></i>
                                                <h6 class="fw-bold text-dark mb-1">¡Estás al día con tus pagos!</h6>
                                                <p class="text-muted small mb-0">No registras cobros pendientes en este
                                                    momento.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TARJETA 2: MIS REQUERIMIENTOS E INCIDENCIAS -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header py-3 px-4 border-0 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3"
                        style="background: transparent;">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-warning bg-opacity-10 p-2 me-3 text-warning">
                                <i class="bi bi-tools fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">Mis Requerimientos e Incidencias</h5>
                                <span class="text-muted small">Historial de solicitudes de mantenimiento</span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm"
                            data-bs-toggle="modal" data-bs-target="#newTicketModal">
                            <i class="bi bi-plus-lg me-1"></i> Crear Requerimiento
                        </button>
                    </div>

                    <div class="card-body p-4 pt-2">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle w-100 mb-0 datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3 border-0 py-3"># ID</th>
                                        <th class="border-0">Asunto / Problema</th>
                                        <th class="border-0">Ubicación</th>
                                        <th class="border-0">Fecha</th>
                                        <th class="border-0">Urgencia</th>
                                        <th class="pe-3 border-0">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tickets as $ticket)
                                        <tr>
                                            <td class="ps-3 fw-bold text-muted">#{{ $ticket->id }}</td>
                                            <td class="fw-bold text-dark">{{ $ticket->title }}</td>
                                            <td class="small text-muted">
                                                <i class="bi bi-geo-alt me-1 text-primary"></i>
                                                {{ $ticket->location ?? 'En departamento' }}
                                            </td>
                                            <td class="small text-muted">{{ $ticket->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                @if ($ticket->urgency == 'Alta')
                                                    <span class="badge bg-danger px-3 py-1 rounded-pill">Alta</span>
                                                @elseif($ticket->urgency == 'Media')
                                                    <span
                                                        class="badge bg-warning text-dark px-3 py-1 rounded-pill">Media</span>
                                                @else
                                                    <span
                                                        class="badge bg-info text-dark px-3 py-1 rounded-pill">Baja</span>
                                                @endif
                                            </td>
                                            <td class="pe-3">
                                                @if ($ticket->status == 'Abierto')
                                                    <span
                                                        class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill">Abierto</span>
                                                @elseif($ticket->status == 'En progreso')
                                                    <span
                                                        class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1 rounded-pill">En
                                                        progreso</span>
                                                @else
                                                    <span
                                                        class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill">Resuelto</span>
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
    <div class="modal fade" id="newTicketModal" tabindex="-1" aria-labelledby="newTicketModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header py-3 border-bottom px-4" style="background: transparent;">
                    <h5 class="modal-title fw-bold text-dark" id="newTicketModalLabel">
                        <i class="bi bi-tools me-2 text-warning"></i>Nuevo Requerimiento
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('portal.tickets.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold text-dark">Descripción del Problema /
                                Asunto:</label>
                            <input type="text" name="title" id="title" class="form-control rounded-3"
                                placeholder="Ej: Fuga de agua en el baño principal" required>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label fw-bold text-dark">Ubicación de la Incidencia:</label>
                            <input type="text" name="location" id="location" class="form-control rounded-3"
                                value="Depto {{ $resident->unit->number ?? '' }} {{ optional($resident->unit)->tower ? '- Torre ' . $resident->unit->tower : '' }}"
                                placeholder="Ej: Balcón, Pasillo, Estacionamiento">
                        </div>

                        <div class="mb-3">
                            <label for="urgency" class="form-label fw-bold text-dark">Nivel de Urgencia:</label>
                            <select name="urgency" id="urgency" class="form-select rounded-3" required>
                                <option value="Baja">Baja (Requerimiento general)</option>
                                <option value="Media" selected>Media (Atención regular)</option>
                                <option value="Alta">Alta (Urgencia inmediata)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer py-3 border-top px-4" style="background: transparent;">
                        <button type="button" class="btn btn-outline-secondary rounded-3"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4">
                            <i class="bi bi-send me-1"></i> Enviar Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
