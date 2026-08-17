@extends('layouts.app')

@section('content')
    <div class="container pb-5">

        <!-- ENCABEZADO CON BOTÓN DE DESCARGA PDF -->
        <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
            <div>
                <h2 class="fw-bold mb-0 text-dark">Reporte de Pagos y Morosidad</h2>
                <p class="text-muted mt-1">Estado detallado de recaudación y saldos pendientes</p>
            </div>
            <a href="{{ route('reports.payments.pdf', ['year' => $selectedYear, 'month' => $selectedMonth]) }}"
                class="btn btn-danger px-4 shadow-sm">
                <i class="bi bi-file-earmark-pdf me-1"></i> Descargar PDF
            </a>
        </div>

        <!-- CONTENEDOR EXPORTABLE A PDF -->
        <div id="pdfContent">

            <!-- ENCABEZADO EXCLUSIVO PARA EL PDF (Solo visible en el archivo generado) -->
            <div class="d-none d-print-block mb-4 text-center border-bottom pb-3">
                <h2 class="fw-bold mb-1">Reporte de Pagos y Morosidad</h2>
                <p class="mb-0 text-muted">Periodo: {{ $selectedMonth }} {{ $selectedYear }}</p>
                <small class="text-muted">Generado el {{ date('d/m/Y H:i') }}</small>
            </div>

            <!-- FILTROS SUTILES (MES Y AÑO) -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 d-print-none">
                <div class="card-body p-3 p-md-4">
                    <form method="GET" action="{{ route('reports.payments') }}" class="row g-3 align-items-center">

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
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-funnel me-1"></i> Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- RESUMEN DISCRETO (KPIs) -->
            <div class="row g-3 mb-4">
                <!-- Resumen Pagados -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 border-start border-success border-4">
                        <div class="card-body p-3 p-md-4 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small fw-bold text-uppercase d-block">Total Recaudado
                                    ({{ $selectedMonth }} {{ $selectedYear }})</span>
                                <h3 class="fw-bold text-success mb-0">$ {{ number_format($totalPaidAmount, 0, ',', '.') }}
                                </h3>
                                <span class="small text-muted">{{ $paidExpenses->count() }} cobros al día</span>
                            </div>
                            <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success">
                                <i class="bi bi-check-circle-fill fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resumen Pendientes -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 border-start border-danger border-4">
                        <div class="card-body p-3 p-md-4 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small fw-bold text-uppercase d-block">Total Pendiente por
                                    Cobrar</span>
                                <h3 class="fw-bold text-danger mb-0">$
                                    {{ number_format($totalPendingAmount, 0, ',', '.') }}
                                </h3>
                                <span class="small text-muted">{{ $pendingExpenses->count() }} cobros no pagados</span>
                            </div>
                            <div class="rounded-circle bg-danger bg-opacity-10 p-3 text-danger">
                                <i class="bi bi-exclamation-triangle-fill fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 1: NO PAGADOS / PENDIENTES -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-danger mb-0">
                        <i class="bi bi-x-circle me-2"></i> Cobros No Pagados ({{ $pendingExpenses->count() }})
                    </h5>
                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-1 rounded-pill fw-bold">
                        $ {{ number_format($totalPendingAmount, 0, ',', '.') }}
                    </span>
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="table-responsive">
                        <table class="table table-hover datatable align-middle w-100 mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 py-3">Residente y Unidad</th>
                                    <th>Motivo / Concepto</th>
                                    <th>Vencimiento</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingExpenses as $expense)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold text-dark">
                                                {{ $expense->unit->resident->name ?? 'Sin residente asignado' }}
                                            </div>
                                            <div class="small text-muted">
                                                Depto {{ $expense->unit->number ?? 'N/A' }}
                                                {{ optional($expense->unit)->tower ? '- Torre ' . $expense->unit->tower : '' }}
                                            </div>
                                        </td>
                                        <td class="fw-semibold text-dark">
                                            {{ $expense->concept ?? 'Gasto Común Ordinario' }}
                                        </td>
                                        <td class="text-danger fw-semibold">
                                            {{ \Carbon\Carbon::parse($expense->due_date)->format('d-m-Y') }}</td>
                                        <td class="fw-bold text-dark">$ {{ number_format($expense->amount, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <span class="badge bg-danger px-3 py-1">Por Pagar</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="ps-3 py-4 text-center text-muted">
                                            <i class="bi bi-emoji-smile fs-4 d-block text-success mb-1"></i>
                                            ¡Excelente! No existen saldos no pagados en este periodo.
                                        </td>
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

            <!-- SECCIÓN 2: PAGADOS -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-success mb-0">
                        <i class="bi bi-check-circle me-2"></i> Cobros Pagados ({{ $paidExpenses->count() }})
                    </h5>
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill fw-bold">
                        $ {{ number_format($totalPaidAmount, 0, ',', '.') }}
                    </span>
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="table-responsive">
                        <table class="table table-hover datatable align-middle w-100 mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 py-3">Residente y Unidad</th>
                                    <th>Motivo / Concepto</th>
                                    <th>Vencimiento</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paidExpenses as $expense)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold text-dark">
                                                {{ $expense->unit->resident->name ?? 'Sin residente asignado' }}
                                            </div>
                                            <div class="small text-muted">
                                                Depto {{ $expense->unit->number ?? 'N/A' }}
                                                {{ optional($expense->unit)->tower ? '- Torre ' . $expense->unit->tower : '' }}
                                            </div>
                                        </td>
                                        <td class="fw-semibold text-dark">
                                            {{ $expense->concept ?? 'Gasto Común Ordinario' }}
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($expense->due_date)->format('d-m-Y') }}</td>
                                        <td class="fw-bold text-dark">$ {{ number_format($expense->amount, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <span class="badge bg-success px-3 py-1">Pagado</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="ps-3 py-4 text-center text-muted">
                                            No hay registros de pagos confirmados para este periodo.
                                        </td>
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
@endsection

@push('scripts')
    <!-- Librería html2pdf para generación limpia de archivos PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnPdf = document.getElementById('downloadPdfBtn');

            btnPdf.addEventListener('click', function() {
                const element = document.getElementById('pdfContent');

                // Configuración del PDF
                const opt = {
                    margin: [10, 10, 10, 10],
                    filename: `Reporte_Pagos_{{ $selectedMonth }}_{{ $selectedYear }}.pdf`,
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2,
                        useCORS: true
                    },
                    jsPDF: {
                        unit: 'mm',
                        format: 'a4',
                        orientation: 'portrait'
                    }
                };

                // Generar y descargar automáticamente
                html2pdf().set(opt).from(element).save();
            });
        });
    </script>
@endpush
