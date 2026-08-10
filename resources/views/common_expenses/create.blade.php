@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fw-bold">Emitir Nuevo Gasto Común</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('common_expenses.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="unit_id" class="form-label">Unidad a cobrar</label>
                            <select class="form-select" id="unit_id" name="unit_id" required>
                                <option value="" selected disabled>Seleccione una unidad...</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">
                                        {{ $unit->number }} - {{ $unit->condominium->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="month" class="form-label">Mes</label>
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
                                <label for="year" class="form-label">Año</label>
                                <input type="number" class="form-control" id="year" name="year" value="2026" required>
                            </div>
                        </div>

                        <!-- SECCIÓN DEL MONTO ACTUALIZADA CON EL VALOR POR DEFECTO -->
                        <div class="mb-4">
                            <label for="amount" class="form-label fw-bold">Monto a Cobrar ($)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">$</span>
                                <input type="number" class="form-control form-control-lg text-end" id="amount" name="amount" value="{{ old('amount', 60000) }}" required>
                            </div>
                            <div class="form-text text-primary mt-2">
                                <i class="bi bi-info-circle-fill me-1"></i>
                                El monto base es de $60.000. Puedes borrarlo y escribir otro valor si el residente tiene multas o arrastra deudas de meses anteriores.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="due_date" class="form-label">Fecha de Vencimiento</label>
                            <input type="date" class="form-control" id="due_date" name="due_date" required>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('common_expenses.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-success">Emitir Cobro</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection