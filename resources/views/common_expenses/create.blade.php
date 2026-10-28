@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark fs-5">
                            <i class="bi bi-receipt me-2 text-primary"></i>Emitir Nuevo Gasto Común
                        </span>

                        <!-- Botón para cobro masivo a todas las unidades -->
                        <form action="{{ route('common_expenses.store_bulk') }}" method="POST" id="bulkForm"
                            onsubmit="return confirm('¿Estás seguro de emitir este gasto común a TODAS las unidades registradas?');">
                            @csrf
                            <!-- Se envían dinámicamente los valores seleccionados -->
                            <input type="hidden" name="month" id="bulk_month" value="Agosto">
                            <input type="hidden" name="year" id="bulk_year" value="2026">
                            <input type="hidden" name="amount" id="bulk_amount" value="60000">
                            <input type="hidden" name="concept" id="bulk_concept" value="Gasto Común Ordinario">
                            <input type="hidden" name="due_date" id="bulk_due_date" value="">

                            <button type="submit" class="btn btn-primary btn-sm px-3">
                                <i class="bi bi-people-fill me-1"></i> Emitir a Todos
                            </button>
                        </form>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('common_expenses.store') }}">
                            @csrf

                            <!-- Selección de Unidad con datos del Residente -->
                            <div class="mb-3">
                                <label for="unit_id" class="form-label fw-bold">Unidad a cobrar</label>
                                <select class="form-select" id="unit_id" name="unit_id" required>
                                    <option value="" selected disabled>Seleccione una unidad...</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">
                                            Depto {{ $unit->number }}
                                            {{ $unit->tower ? ' - Torre ' . $unit->tower : '' }}
                                            ({{ $unit->condominium->name }})
                                            — Residente: {{ $unit->resident->name ?? 'Sin Asignar' }}
                                            {{ optional($unit->resident)->rut ? '(' . $unit->resident->rut . ')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Motivo / Concepto del Cobro -->
                            <div class="mb-3">
                                <label for="concept" class="form-label fw-bold">Motivo / Concepto del Cobro</label>
                                <input type="text" class="form-control" id="concept" name="concept"
                                    value="{{ old('concept', 'Gasto Común Ordinario') }}"
                                    placeholder="Ej: Gasto Común Ordinario, Fondo de Reserva, Multa por ruidos molestos"
                                    required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="month" class="form-label fw-bold">Mes</label>
                                    <select class="form-select" id="month" name="month" required>
                                        <option value="Enero">Enero</option>
                                        <option value="Febrero">Febrero</option>
                                        <option value="Marzo">Marzo</option>
                                        <option value="Abril">Abril</option>
                                        <option value="Mayo">Mayo</option>
                                        <option value="Junio">Junio</option>
                                        <option value="Julio">Julio</option>
                                        <option value="Agosto" selected>Agosto</option>
                                        <option value="Septiembre">Septiembre</option>
                                        <option value="Octubre">Octubre</option>
                                        <option value="Noviembre">Noviembre</option>
                                        <option value="Diciembre">Diciembre</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="year" class="form-label fw-bold">Año</label>
                                    <input type="number" class="form-control" id="year" name="year" value="2026"
                                        required>
                                </div>
                            </div>

                            <!-- Sección del Monto -->
                            <div class="mb-4">
                                <label for="amount" class="form-label fw-bold">Monto a Cobrar ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">$</span>
                                    <input type="number" class="form-control form-control-lg text-end" id="amount"
                                        name="amount" value="{{ old('amount', 60000) }}" required>
                                </div>
                                <div class="form-text text-primary mt-2">
                                    <i class="bi bi-info-circle-fill me-1"></i>
                                    El monto base es de $60.000. Puedes borrarlo y escribir otro valor si el residente tiene
                                    multas o arrastra deudas de meses anteriores.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="due_date" class="form-label fw-bold">Fecha de Vencimiento</label>
                                <input type="date" class="form-control" id="due_date" name="due_date" required>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('common_expenses.index') }}"
                                    class="btn btn-secondary px-4">Cancelar</a>
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="bi bi-check-lg me-1"></i> Emitir Cobro
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const monthSelect = document.getElementById('month');
            const yearInput = document.getElementById('year');
            const amountInput = document.getElementById('amount');
            const conceptInput = document.getElementById('concept');
            const dueDateInput = document.getElementById('due_date');

            const bulkMonth = document.getElementById('bulk_month');
            const bulkYear = document.getElementById('bulk_year');
            const bulkAmount = document.getElementById('bulk_amount');
            const bulkConcept = document.getElementById('bulk_concept');
            const bulkDueDate = document.getElementById('bulk_due_date');

            // Sincronizar los campos del formulario individual con el formulario masivo
            function syncBulkInputs() {
                bulkMonth.value = monthSelect.value;
                bulkYear.value = yearInput.value;
                bulkAmount.value = amountInput.value;
                bulkConcept.value = conceptInput.value;
                bulkDueDate.value = dueDateInput.value;
            }

            monthSelect.addEventListener('change', syncBulkInputs);
            yearInput.addEventListener('input', syncBulkInputs);
            amountInput.addEventListener('input', syncBulkInputs);
            conceptInput.addEventListener('input', syncBulkInputs);
            dueDateInput.addEventListener('change', syncBulkInputs);

            // Inicializar sincronización
            syncBulkInputs();
        });
    </script>
@endpush
