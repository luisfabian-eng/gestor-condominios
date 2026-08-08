@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fw-bold">Editar Cobro</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('common_expenses.update', $expense->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="unit_id" class="form-label">Unidad a cobrar</label>
                            <select class="form-select" id="unit_id" name="unit_id" required>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ $unit->id == $expense->unit_id ? 'selected' : '' }}>
                                        {{ $unit->number }} - {{ $unit->condominium->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="month" class="form-label">Mes</label>
                                <input type="text" class="form-control" id="month" name="month" value="{{ $expense->month }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="year" class="form-label">Año</label>
                                <input type="number" class="form-control" id="year" name="year" value="{{ $expense->year }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Monto a Cobrar ($)</label>
                            <input type="number" class="form-control" id="amount" name="amount" value="{{ $expense->amount }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="due_date" class="form-label">Fecha de Vencimiento</label>
                            <!-- Formateamos la fecha para que el input tipo date la pueda leer -->
                            <input type="date" class="form-control" id="due_date" name="due_date" value="{{ \Carbon\Carbon::parse($expense->due_date)->format('Y-m-d') }}" required>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('common_expenses.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-success">Actualizar Cobro</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection