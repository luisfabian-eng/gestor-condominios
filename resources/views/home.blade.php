@extends('layouts.app')

@section('content')
    <div class="container pb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-dark">Panel de Control</h2>
                <p class="text-muted mt-1 mb-0">Resumen general de tu gestión, {{ Auth::user()->name }}</p>
            </div>
        </div>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- TARJETAS SUPERIORES -->
        <div class="row">
            <!-- Tarjeta Comunidades -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm"
                    style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                    <div class="card-body text-white position-relative overflow-hidden p-4">
                        <i class="bi bi-building position-absolute"
                            style="font-size: 6rem; opacity: 0.1; right: -10px; bottom: -20px;"></i>
                        <h5 class="card-title fw-light mb-1 text-nowrap text-white">Comunidades</h5>
                        <h2 class="fs-2 fw-bold mb-0 text-nowrap text-white">{{ $totalCondominiums ?? 0 }}</h2>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 pb-3 px-4">
                        <a href="{{ route('condominiums.index') }}" class="text-white text-decoration-none fw-medium"><i
                                class="bi bi-arrow-right-circle me-1"></i> Ver detalles</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Unidades -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm"
                    style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="card-body text-white position-relative overflow-hidden p-4">
                        <i class="bi bi-door-open position-absolute"
                            style="font-size: 6rem; opacity: 0.1; right: -10px; bottom: -20px;"></i>
                        <h5 class="card-title fw-light mb-1 text-nowrap text-white">Unidades</h5>
                        <h2 class="fs-2 fw-bold mb-0 text-nowrap text-white">{{ $totalUnits ?? 0 }}</h2>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 pb-3 px-4">
                        <a href="{{ route('units.index') }}" class="text-white text-decoration-none fw-medium"><i
                                class="bi bi-arrow-right-circle me-1"></i> Ver detalles</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Dinero Pendiente -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm"
                    style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                    <div class="card-body text-white position-relative overflow-hidden p-4">
                        <i class="bi bi-exclamation-circle position-absolute"
                            style="font-size: 6rem; opacity: 0.1; right: -10px; bottom: -20px;"></i>
                        <h5 class="card-title fw-light mb-1 text-nowrap text-white">Por Cobrar</h5>
                        <h2 class="fs-2 fw-bold mb-0 text-nowrap text-white">$
                            {{ isset($pendingAmount) ? number_format($pendingAmount, 0, ',', '.') : '0' }}</h2>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 pb-3 px-4">
                        <a href="{{ route('common_expenses.index') }}"
                            class="text-white text-decoration-none fw-medium text-nowrap"><i
                                class="bi bi-arrow-right-circle me-1"></i> Ir a Finanzas</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Dinero Recaudado -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm"
                    style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <div class="card-body text-white position-relative overflow-hidden p-4">
                        <i class="bi bi-check2-circle position-absolute"
                            style="font-size: 6rem; opacity: 0.1; right: -10px; bottom: -20px;"></i>
                        <h5 class="card-title fw-light mb-1 text-nowrap text-white">Recaudado</h5>
                        <h2 class="fs-2 fw-bold mb-0 text-nowrap text-white">$
                            {{ isset($paidAmount) ? number_format($paidAmount, 0, ',', '.') : '0' }}</h2>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 pb-3 px-4">
                        <a href="{{ route('common_expenses.index') }}"
                            class="text-white text-decoration-none fw-medium text-nowrap"><i
                                class="bi bi-arrow-right-circle me-1"></i> Ir a Finanzas</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- MÓDULO 1: GASTOS COMUNES Y COMPROBANTES RECIENTES -->
        <div class="row mt-3 mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div
                        class="card-header bg-white border-bottom pb-3 pt-4 px-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <div>
                            <span class="fw-bold fs-5 text-dark">
                                <i class="bi bi-receipt-cutoff me-2 text-primary"></i>Gastos Comunes y Pagos Recientes
                            </span>
                            <small class="text-muted d-block mt-1">Cobros pendientes y comprobantes enviados para
                                aprobación</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('common_expenses.index') }}"
                                class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-3">
                                <i class="bi bi-list-ul me-1"></i> Ver Todos los Cobros
                            </a>
                            <a href="{{ route('common_expenses.create') }}"
                                class="btn btn-primary btn-sm px-3 py-2 rounded-3 shadow-sm">
                                <i class="bi bi-plus-lg me-1"></i> Emitir Cobro
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle w-100 mb-0 text-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3 py-3">Unidad / Residente</th>
                                        <th>Periodo</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th class="pe-3 text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentExpenses as $expense)
                                        <tr>
                                            <td class="ps-3">
                                                <div class="fw-bold text-dark">
                                                    Depto {{ $expense->unit->number ?? 'S/N' }}
                                                    @if (optional($expense->unit)->tower)
                                                        <span class="text-muted fw-normal small">· Torre
                                                            {{ $expense->unit->tower }}</span>
                                                    @endif
                                                </div>
                                                <div class="small text-muted">
                                                    {{ $expense->unit->resident->name ?? 'Sin residente asignado' }}
                                                </div>
                                            </td>

                                            <td class="small text-muted">
                                                {{ $expense->month }} {{ $expense->year }}
                                            </td>

                                            <td class="fw-bold text-dark">
                                                $ {{ number_format($expense->amount, 0, ',', '.') }}
                                            </td>

                                            <td>
                                                @if ($expense->status == 'Pendiente')
                                                    @if ($expense->rejection_note)
                                                        <span
                                                            class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill"
                                                            title="Motivo: {{ $expense->rejection_note }}">
                                                            Rechazado
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge bg-secondary-subtle text-secondary border px-2 py-1 rounded-pill">
                                                            Pendiente
                                                        </span>
                                                    @endif
                                                @elseif ($expense->status == 'En revisión')
                                                    <span
                                                        class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 rounded-pill">
                                                        <i class="bi bi-hourglass-split me-1"></i> En Revisión
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">
                                                        Pagado
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="pe-3 text-end text-nowrap">
                                                <div class="d-inline-flex gap-1 align-items-center justify-content-end">
                                                    <!-- Comprobante -->
                                                    @if ($expense->receipt_path)
                                                        <a href="{{ asset('uploads/receipts/' . $expense->receipt_path) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-primary"
                                                            title="Ver comprobante adjunto">
                                                            <i class="bi bi-file-earmark-arrow-down"></i>
                                                        </a>
                                                    @endif

                                                    <!-- Acciones si no está Pagado -->
                                                    @if ($expense->status != 'Pagado')
                                                        <!-- Aprobar / Pagar -->
                                                        <form action="{{ route('common_expenses.update', $expense->id) }}"
                                                            method="POST" class="m-0 d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="payment_action" value="1">
                                                            <button type="submit"
                                                                class="btn btn-sm btn-success px-2 py-1"
                                                                title="{{ $expense->status == 'En revisión' ? 'Aprobar comprobante' : 'Marcar como Pagado' }}">
                                                                <i
                                                                    class="bi bi-check-lg me-1"></i>{{ $expense->status == 'En revisión' ? 'Aprobar' : 'Pagar' }}
                                                            </button>
                                                        </form>

                                                        <!-- Rechazar comprobante -->
                                                        @if ($expense->status == 'En revisión' || $expense->receipt_path)
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-danger px-2 py-1"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#rejectModalHome{{ $expense->id }}"
                                                                title="Rechazar comprobante">
                                                                <i class="bi bi-x-lg me-1"></i>Rechazar
                                                            </button>
                                                        @endif
                                                    @endif

                                                    <!-- Editar -->
                                                    <a href="{{ route('common_expenses.edit', $expense->id) }}"
                                                        class="btn btn-sm btn-outline-primary" title="Editar cobro">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="ps-3 py-4 text-center text-muted">No existen registros de gastos
                                                comunes disponibles.</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MÓDULO 2: INCIDENCIAS Y MANTENCIONES RECIENTES -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div
                        class="card-header bg-white border-bottom pb-3 pt-4 px-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <div>
                            <span class="fw-bold fs-5 text-dark">
                                <i class="bi bi-tools me-2 text-secondary"></i>Mantenciones e Incidencias Recientes
                            </span>
                            <small class="text-muted d-block mt-1">Últimas solicitudes ingresadas en el condominio</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('tickets.index') }}"
                                class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-3">
                                <i class="bi bi-list-ul me-1"></i> Ver Todos
                            </a>
                            <a href="{{ route('tickets.create') }}"
                                class="btn btn-primary btn-sm px-3 py-2 rounded-3 shadow-sm">
                                <i class="bi bi-plus-lg me-1"></i> Nuevo Ticket
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle w-100 mb-0 text-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3 py-3">Descripción del Problema</th>
                                        <th>Reportado Por</th>
                                        <th>Urgencia</th>
                                        <th>Estado</th>
                                        <th class="pe-3 text-end">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tickets as $ticket)
                                        <tr>
                                            <td class="ps-3 py-3">
                                                <div class="fw-bold text-dark">{{ $ticket->title }}</div>
                                                <div class="small text-muted mt-1">
                                                    <i class="bi bi-geo-alt me-1"></i>
                                                    {{ $ticket->location ?? 'Sin ubicación específica' }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-dark">
                                                    <i class="bi bi-person-circle me-1 text-primary"></i>
                                                    {{ $ticket->user->name ?? 'Anónimo' }}
                                                </span>
                                            </td>
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
                                                <div class="d-inline-flex gap-1 align-items-center">
                                                    <!-- Ver detalle -->
                                                    <a href="{{ route('tickets.show', $ticket->id) }}"
                                                        class="btn btn-sm btn-outline-primary" title="Ver detalle">
                                                        <i class="bi bi-eye"></i>
                                                    </a>

                                                    <!-- En progreso -->
                                                    @if ($ticket->status != 'En progreso')
                                                        <form action="{{ route('tickets.update', $ticket->id) }}"
                                                            method="POST" class="m-0 d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="En progreso">
                                                            <button type="submit"
                                                                class="btn btn-sm btn-outline-warning text-dark"
                                                                title="Marcar En Progreso">
                                                                <i class="bi bi-tools"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <!-- Resuelto -->
                                                    @if ($ticket->status != 'Resuelto')
                                                        <form action="{{ route('tickets.update', $ticket->id) }}"
                                                            method="POST" class="m-0 d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="Resuelto">
                                                            <button type="submit" class="btn btn-sm btn-outline-success"
                                                                title="Marcar Resuelto">
                                                                <i class="bi bi-check-lg"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="ps-3 py-4 text-center text-muted">No existen tickets registrados
                                                recientemente.</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
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

    <!-- MODALES DE RECHAZO PARA EL HOME -->
    @foreach ($recentExpenses as $expense)
        @if ($expense->status != 'Pagado')
            <div class="modal fade" id="rejectModalHome{{ $expense->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4 border-0 shadow">
                        <form action="{{ route('common_expenses.reject', $expense->id) }}" method="POST">
                            @csrf
                            <div class="modal-header border-bottom py-3 px-4">
                                <h6 class="modal-title fw-bold text-dark">
                                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>Rechazar Comprobante
                                </h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <p class="text-muted small mb-3">
                                    Depto {{ $expense->unit->number ?? '' }} · {{ $expense->month }} {{ $expense->year }}
                                </p>
                                <div class="mb-2">
                                    <label class="form-label fw-bold text-dark small">Motivo del rechazo</label>
                                    <textarea name="rejection_note" class="form-control rounded-3" rows="3"
                                        placeholder="Ej: Monto transferido incompleto o comprobante ilegible." required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer border-top py-2 px-4">
                                <button type="button" class="btn btn-light btn-sm px-3 rounded-3"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger btn-sm px-3 rounded-3">Enviar
                                    Rechazo</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection
