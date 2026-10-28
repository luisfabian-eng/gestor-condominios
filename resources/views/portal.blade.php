@extends('layouts.app')

@section('content')
    <style>
        /* Estilo inactivo: texto gris oscuro sobrio */
        .custom-portal-pills .nav-link {
            color: #475569 !important;
            background-color: transparent !important;
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }

        .custom-portal-pills .nav-link i {
            color: #64748b !important;
        }

        /* Hover cuando no está seleccionado */
        .custom-portal-pills .nav-link:hover:not(.active) {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
        }

        /* ÚNICO BOTÓN ACTIVO: Fondo índigo oscuro y texto blanco puro */
        .custom-portal-pills .nav-link.active {
            background-color: #4338ca !important;
            /* Índigo oscuro elegante */
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(67, 56, 202, 0.25);
        }

        .custom-portal-pills .nav-link.active i {
            color: #ffffff !important;
        }
    </style>
    <div class="container pb-5">

        <!-- BANNER HEROE / BIENVENIDA -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 text-white overflow-hidden"
            style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
            <div class="card-body p-4 p-md-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7 text-center text-lg-start">
                        <span class="badge mb-3 px-3 py-2 fw-normal rounded-pill text-white"
                            style="background-color: rgba(255, 255, 255, 0.2); backdrop-filter: blur(5px);">
                            <i class="bi bi-person-badge me-1"></i> Portal del Residente
                        </span>
                        <h1 class="fw-bold display-6 text-white mb-2">¡Hola, {{ Auth::user()->name }}!</h1>
                        <p class="text-white-50 mb-0 fs-6">
                            Bienvenido a tu panel de gestión. Revisa avisos oficiales, el estado de tus gastos comunes y
                            genera requerimientos.
                        </p>
                    </div>

                    <!-- Ficha del departamento -->
                    <div class="col-lg-5">
                        @if ($resident && $resident->unit)
                            <div class="rounded-4 p-3 border"
                                style="background-color: rgba(255, 255, 255, 0.15); border-color: rgba(255, 255, 255, 0.25) !important; backdrop-filter: blur(8px);">
                                <div class="d-flex align-items-center justify-content-center justify-content-lg-start mb-2">
                                    <i class="bi bi-building fs-3 text-white me-3"></i>
                                    <div class="text-start">
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

        <!-- Alertas de sesión -->
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- CONTENEDOR CON TRES PESTAÑAS -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <!-- Barra de navegación con tabs -->
            <div class="card-header bg-white border-bottom pt-3 px-4">
                <!-- Barra de pestañas con estilo sobrio / índigo -->
                <!-- Barra de pestañas -->
                <ul class="nav nav-pills custom-portal-pills gap-2" id="residentTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill px-4 py-2 fw-semibold" id="tab-notices"
                            data-bs-toggle="tab" data-bs-target="#content-notices" type="button" role="tab">
                            <i class="bi bi-megaphone me-1"></i> Avisos y Circulares
                            @if (isset($announcements) && $announcements->count() > 0)
                                <span class="badge bg-danger rounded-pill ms-1">{{ $announcements->count() }}</span>
                            @endif
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4 py-2 fw-semibold" id="tab-expenses" data-bs-toggle="tab"
                            data-bs-target="#content-expenses" type="button" role="tab">
                            <i class="bi bi-receipt-cutoff me-1"></i> Mis Gastos Comunes
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4 py-2 fw-semibold" id="tab-tickets" data-bs-toggle="tab"
                            data-bs-target="#content-tickets" type="button" role="tab">
                            <i class="bi bi-tools me-1"></i> Mis Incidencias
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body p-4">
                <div class="tab-content" id="residentTabsContent">

                    <!-- PESTAÑA 1: MURO DE AVISOS Y CIRCULARES -->
                    <div class="tab-pane fade show active" id="content-notices" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">Muro de Comunicados Oficiales</h5>
                                <span class="text-muted small">Noticias, avisos de corte, asambleas y circulares de la
                                    administración</span>
                            </div>
                        </div>

                        <div class="row g-3">
                            @forelse ($announcements ?? [] as $notice)
                                <div class="col-12">
                                    <div class="card border border-light-subtle rounded-4 p-3 shadow-sm hover-shadow">
                                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                @if ($notice->priority == 'Urgente')
                                                    <span class="badge bg-danger px-3 py-1 rounded-pill">
                                                        <i class="bi bi-exclamation-octagon-fill me-1"></i> Urgente
                                                    </span>
                                                @elseif ($notice->priority == 'Alta')
                                                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill">
                                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Importante
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-3 py-1 rounded-pill">
                                                        Informativo
                                                    </span>
                                                @endif
                                                <h6 class="fw-bold text-dark mb-0">{{ $notice->title }}</h6>
                                            </div>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>{{ $notice->created_at->format('d/m/Y') }}
                                            </small>
                                        </div>

                                        <p class="text-secondary small mb-2 text-break">
                                            {{ $notice->content }}
                                        </p>

                                        @if ($notice->attachment_path)
                                            <div class="pt-2 border-top">
                                                <a href="{{ asset($notice->attachment_path) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    <i class="bi bi-file-earmark-pdf me-1"></i> Descargar Documento Adjunto
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 py-5 text-center text-muted">
                                    <i class="bi bi-chat-square-text text-secondary opacity-50 display-4 d-block mb-2"></i>
                                    <h6 class="fw-bold text-dark">No hay comunicados publicados</h6>
                                    <p class="small mb-0">La administración aún no ha publicado ningún aviso reciente para
                                        tu comunidad.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- PESTAÑA 2: MIS GASTOS COMUNES -->
                    <div class="tab-pane fade" id="content-expenses" role="tabpanel">
                        <div
                            class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">Cobros y Estado de Cuenta</h5>
                                <span class="text-muted small">Detalle mensual de gastos comunes e historial de
                                    recaudación</span>
                            </div>
                            <a href="{{ route('resident.payments.index') }}"
                                class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                <i class="bi bi-clock-history me-1"></i> Ver Historial de Pagos
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle w-100 mb-0 text-nowrap datatable">
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
                                                {{ \Carbon\Carbon::parse($expense->due_date)->format('d-m-Y') }}
                                            </td>
                                            <td class="fw-bold fs-6">$ {{ number_format($expense->amount, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                @if ($expense->status == 'Pendiente')
                                                    @if ($expense->rejection_note)
                                                        <span
                                                            class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill">
                                                            <i class="bi bi-exclamation-circle me-1"></i> Rechazado
                                                            (Reenviar)
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill">Por
                                                            Pagar</span>
                                                    @endif
                                                @elseif ($expense->status == 'En revisión')
                                                    <span
                                                        class="badge bg-warning-subtle text-warning-emphasis px-3 py-2 rounded-pill border border-warning-subtle">
                                                        <i class="bi bi-hourglass-split me-1"></i> En Revisión
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">Pagado</span>
                                                @endif

                                                @if ($expense->rejection_note && $expense->status != 'Pagado')
                                                    <div class="alert alert-danger p-2 small mt-2 mb-0 rounded-3 text-wrap"
                                                        style="max-width: 280px;">
                                                        <i class="bi bi-exclamation-octagon-fill me-1"></i>
                                                        <strong>Motivo:</strong> {{ $expense->rejection_note }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="pe-3 text-end">
                                                @if ($expense->status == 'Pendiente')
                                                    <button type="button"
                                                        class="btn btn-sm btn-primary rounded-pill px-3"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#payModal{{ $expense->id }}">
                                                        <i class="bi bi-credit-card me-1"></i> Pagar
                                                    </button>
                                                @elseif ($expense->status == 'En revisión')
                                                    <div class="d-inline-flex align-items-center gap-2">
                                                        @if ($expense->receipt_path)
                                                            <a href="{{ asset('uploads/receipts/' . $expense->receipt_path) }}"
                                                                target="_blank"
                                                                class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-1"
                                                                title="Ver comprobante adjunto">
                                                                <i class="bi bi-file-earmark-check"></i>
                                                            </a>
                                                        @endif
                                                        <span class="text-warning small fw-bold">
                                                            <i class="bi bi-hourglass-split me-1"></i> Esperando revisión
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="d-inline-flex align-items-center gap-2">
                                                        @if ($expense->receipt_path)
                                                            <a href="{{ asset('uploads/receipts/' . $expense->receipt_path) }}"
                                                                target="_blank"
                                                                class="btn btn-sm btn-outline-success rounded-pill px-2 py-1"
                                                                title="Ver comprobante pagado">
                                                                <i class="bi bi-file-earmark-check"></i>
                                                            </a>
                                                        @endif
                                                        <span class="text-success small fw-semibold">
                                                            <i class="bi bi-check-circle-fill me-1"></i> Al día
                                                        </span>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="ps-3 py-4 text-center text-muted">¡Excelente! Estás al día con tus
                                                cobros.</td>
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

                    <!-- PESTAÑA 3: MIS REQUERIMIENTOS E INCIDENCIAS -->
                    <div class="tab-pane fade" id="content-tickets" role="tabpanel">
                        <div
                            class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">Mis Requerimientos e Incidencias</h5>
                                <span class="text-muted small">Estado de las solicitudes de mantención creadas por
                                    ti</span>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('resident.tickets.index') }}"
                                    class="btn btn-outline-secondary rounded-pill px-3 py-2 btn-sm">
                                    <i class="bi bi-list-task me-1"></i> Ver Todas
                                </a>
                                <button type="button" class="btn btn-primary rounded-pill px-3 py-2 shadow-sm btn-sm"
                                    data-bs-toggle="modal" data-bs-target="#newTicketModal">
                                    <i class="bi bi-plus-lg me-1"></i> Crear Requerimiento
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle w-100 mb-0 text-nowrap datatable">
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
                                    @forelse ($tickets as $ticket)
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
                                    @empty
                                        <tr>
                                            <td class="ps-3 py-4 text-center text-muted">No has generado ningún
                                                requerimiento aún.</td>
                                            <td></td>
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

    <!-- MODALES DE PAGO -->
    @foreach ($expenses as $expense)
        @if ($expense->status == 'Pendiente')
            <div class="modal fade text-start" id="payModal{{ $expense->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content rounded-4 border-0 shadow">
                        <form action="{{ route('portal.expenses.pay_manual', $expense->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header border-bottom py-3 px-4">
                                <h5 class="modal-title fw-bold text-dark">
                                    <i class="bi bi-bank2 text-primary me-2"></i>Pago de Gasto Común
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4 text-start">
                                @if ($expense->rejection_note)
                                    <div class="alert alert-danger rounded-3 mb-4">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> <strong>Tu comprobante
                                            anterior fue rechazado:</strong><br>
                                        {{ $expense->rejection_note }}
                                    </div>
                                @endif

                                <div class="alert alert-secondary rounded-3 mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="fw-bold mb-0">Datos para Transferencia:</h6>
                                        <button type="button" class="btn btn-sm btn-light border shadow-sm rounded-3"
                                            onclick="copyAllData('{{ number_format($expense->amount, 0, '', '') }}', this)"
                                            title="Copiar todos los datos">
                                            <i class="bi bi-clipboard me-1"></i> Copiar
                                        </button>
                                    </div>
                                    <ul class="mb-0 small list-unstyled text-break">
                                        <li class="mb-1"><strong>Banco:</strong> BancoEstado</li>
                                        <li class="mb-1"><strong>Cuenta Corriente:</strong> 123456789</li>
                                        <li class="mb-1"><strong>RUT:</strong> 70.123.456-7</li>
                                        <li><strong>Correo:</strong> pagos@santamaria.cl</li>
                                    </ul>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark">Monto a Transferir:</label>
                                    <input type="text" class="form-control fw-bold text-success"
                                        value="$ {{ number_format($expense->amount, 0, ',', '.') }}" readonly>
                                </div>

                                <div class="mb-3 mt-4">
                                    <label class="form-label fw-bold text-dark">Adjuntar Comprobante:</label>
                                    <input type="file" class="form-control" name="receipt"
                                        accept=".jpg,.jpeg,.png,.pdf" required>
                                    <div class="form-text">Formatos aceptados: JPG, PNG o PDF. Max: 20MB.</div>
                                </div>
                            </div>
                            <div class="modal-footer border-top py-3 px-4 flex-column flex-sm-row">
                                <button type="button"
                                    class="btn btn-outline-secondary rounded-3 w-100 w-sm-auto mb-2 mb-sm-0"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary rounded-3 w-100 w-sm-auto">
                                    <i class="bi bi-send me-1"></i> Enviar Comprobante
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <!-- MODAL DE NUEVO REQUERIMIENTO -->
    <div class="modal fade" id="newTicketModal" tabindex="-1" aria-labelledby="newTicketModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header py-3 border-bottom px-4" style="background: transparent;">
                    <h5 class="modal-title fw-bold text-dark" id="newTicketModalLabel">
                        <i class="bi bi-tools me-2 text-warning"></i>Nuevo Requerimiento
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('resident.tickets.store') }}" method="POST">
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
                    <div class="modal-footer py-3 border-top px-4 flex-column flex-sm-row"
                        style="background: transparent;">
                        <button type="button" class="btn btn-outline-secondary rounded-3 w-100 w-sm-auto mb-2 mb-sm-0"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary rounded-3 w-100 w-sm-auto">
                            <i class="bi bi-send me-1"></i> Enviar Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SCRIPT COPIAR DATOS BANCARIOS Y RECALCULAR DATATABLES EN TABS -->
    <script>
        function copyAllData(monto, buttonElement) {
            const textToCopy =
                `Datos de Transferencia:\nBanco: BancoEstado\nCuenta Corriente: 123456789\nRUT: 70.123.456-7\nCorreo: pagos@santamaria.cl\nMonto: $${monto}`;

            navigator.clipboard.writeText(textToCopy).then(function() {
                let originalHTML = buttonElement.innerHTML;
                buttonElement.innerHTML = '<i class="bi bi-check2 text-success me-1"></i> ¡Copiado!';
                buttonElement.classList.replace('btn-light', 'btn-success');
                buttonElement.classList.replace('text-dark', 'text-white');

                setTimeout(function() {
                    buttonElement.innerHTML = originalHTML;
                    buttonElement.classList.replace('btn-success', 'btn-light');
                    buttonElement.classList.replace('text-white', 'text-dark');
                }, 2000);
            }).catch(function(err) {
                console.error('Error al intentar copiar: ', err);
            });
        }

        // Ajusta las tablas de DataTables cuando el usuario cambia entre pestañas
        document.addEventListener('DOMContentLoaded', function() {
            const tabElements = document.querySelectorAll('button[data-bs-toggle="tab"]');
            tabElements.forEach(function(tabEl) {
                tabEl.addEventListener('shown.bs.tab', function() {
                    if (window.jQuery && $.fn.dataTable) {
                        $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive
                            .recalc();
                    }
                });
            });
        });
    </script>
@endsection
