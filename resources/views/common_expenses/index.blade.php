@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-dark">Gastos Comunes</h2>
                <p class="text-muted mt-1">Gestión y control de cobros por periodo</p>
            </div>
            <a href="{{ route('common_expenses.create') }}" class="btn btn-primary px-4">
                <i class="bi bi-plus-lg me-1"></i> Emitir Gasto Común
            </a>
        </div>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                <i class="bi bi-check-circle me-1"></i> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- FILTROS SUTILES -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3 p-md-4">
                <form method="GET" action="{{ route('common_expenses.index') }}" class="row g-3 align-items-center">

                    <div class="col-md-5">
                        <label for="year" class="form-label fw-bold small text-muted mb-1">AÑO</label>
                        <select name="year" id="year" class="form-select" onchange="this.form.submit()">
                            @forelse($availableYears as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @empty
                                <option value="2026" selected>2026</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label for="month" class="form-label fw-bold small text-muted mb-1">MES DE EMISIÓN</label>
                        <select name="month" id="month" class="form-select" onchange="this.form.submit()">
                            @php
                                $months = [
                                    'Enero',
                                    'Febrero',
                                    'Marzo',
                                    'Abril',
                                    'Mayo',
                                    'Junio',
                                    'Julio',
                                    'Agosto',
                                    'Septiembre',
                                    'Octubre',
                                    'Noviembre',
                                    'Diciembre',
                                ];
                            @endphp
                            @foreach ($months as $m)
                                <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                    {{ $m }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end mt-3 mt-md-0">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="bi bi-funnel me-1"></i> Filtrar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TABLA DE RESULTADOS CON RESUMEN MINIMALISTA AL COSTADO -->
        <div class="card border-0 shadow-sm rounded-4">
            <!-- HEADER CON RESUMEN DISCRETO (CONTEOS Y TOTALES YUXTAPUESTOS) -->
            <div
                class="card-header bg-white py-3 px-4 border-bottom-0 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h5 class="fw-bold text-dark mb-0">Detalle de Cobros</h5>
                    <span class="text-muted small">Periodo seleccionado: {{ $selectedMonth }} {{ $selectedYear }}</span>
                </div>

                <!-- RESUMEN DISCRETO A UN COSTADO -->
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <!-- Pendientes -->
                    <div
                        class="d-flex align-items-center bg-danger bg-opacity-10 border border-danger-subtle text-danger px-3 py-1-5 rounded-3">
                        <i class="bi bi-clock-history me-2"></i>
                        <div class="lh-1">
                            <span class="d-block fw-bold small">{{ $pendingCount }} Pendientes</span>
                            <span class="small opacity-75" style="font-size: 0.75rem;">$
                                {{ number_format($pendingAmount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Pagados -->
                    <div
                        class="d-flex align-items-center bg-success bg-opacity-10 border border-success-subtle text-success px-3 py-1-5 rounded-3">
                        <i class="bi bi-check-circle me-2"></i>
                        <div class="lh-1">
                            <span class="d-block fw-bold small">{{ $paidCount }} Pagados</span>
                            <span class="small opacity-75" style="font-size: 0.75rem;">$
                                {{ number_format($paidAmount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-4 pt-0">
                <div class="table-responsive">
                    <table class="table table-hover datatable align-middle w-100 mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 py-3">Residente y Unidad</th>
                                <th>Periodo</th>
                                <th>Monto</th>
                                <th>Vencimiento</th>
                                <th>Estado</th>
                                <th class="pe-3 text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expenses as $expense)
                                <tr>
                                    <td class="ps-3">
                                        @if ($expense->unit && $expense->unit->resident)
                                            <div class="fw-bold text-dark">
                                                <i class="bi bi-person me-1 text-primary"></i>
                                                {{ $expense->unit->resident->name }}
                                            </div>
                                        @else
                                            <div class="fw-bold text-muted">
                                                <i class="bi bi-person-x me-1"></i> Sin residente asignado
                                            </div>
                                        @endif
                                        <div class="small text-muted">
                                            Depto {{ $expense->unit->number ?? 'N/A' }}
                                            @if (optional($expense->unit)->tower)
                                                <span class="badge bg-light text-dark border ms-1">
                                                    Torre {{ $expense->unit->tower }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $expense->month }} {{ $expense->year }}</td>
                                    <td class="fw-bold text-dark">$ {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($expense->due_date)->format('d-m-Y') }}</td>
                                    <td>
                                        @if ($expense->status == 'Pendiente')
                                            <span class="badge bg-danger px-3 py-1">Pendiente</span>
                                        @else
                                            <span class="badge bg-success px-3 py-1">Pagado</span>
                                        @endif
                                    </td>
                                    <td class="pe-3 text-end">
                                        <div class="d-inline-flex gap-1">
                                            @if ($expense->status == 'Pendiente')
                                                <form action="{{ route('common_expenses.update', $expense->id) }}"
                                                    method="POST" class="m-0">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="Pagado">
                                                    <button type="submit" class="btn btn-sm btn-success"
                                                        title="Marcar como Pagado">
                                                        <i class="bi bi-check-lg me-1"></i> Pagar
                                                    </button>
                                                </form>
                                            @endif

                                            <a href="{{ route('common_expenses.edit', $expense->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Editar">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <form action="{{ route('common_expenses.destroy', $expense->id) }}"
                                                method="POST" class="m-0"
                                                onsubmit="return confirm('¿Estás seguro de eliminar este cobro?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Borrar">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
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
