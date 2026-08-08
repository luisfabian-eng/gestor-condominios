@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fw-bold">Registrar Nuevo Departamento</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('units.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="condominium_id" class="form-label">Comunidad</label>
                            <select class="form-select" id="condominium_id" name="condominium_id" required>
                                <option value="" selected disabled>Seleccione una comunidad...</option>
                                @foreach($condominiums as $condominium)
                                    <option value="{{ $condominium->id }}">{{ $condominium->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="number" class="form-label">Número de Unidad (Ej: Depto 402)</label>
                                <input type="text" class="form-control" id="number" name="number" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tower" class="form-label">Torre / Sector (Opcional)</label>
                                <input type="text" class="form-control" id="tower" name="tower" placeholder="Ej: Torre A, Torre B...">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Tipo de Propiedad</label>
                            <!-- Campo automático de solo lectura -->
                            <input type="text" class="form-control bg-light text-muted" id="type" name="type" value="Departamento" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="prorata" class="form-label">Prorrateo (%)</label>
                            <input type="number" step="0.01" class="form-control" id="prorata" name="prorata" placeholder="Ej: 1.25" required>
                            <div class="form-text">Porcentaje con el que este departamento participa en los gastos comunes.</div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('units.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-success">Guardar Departamento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection