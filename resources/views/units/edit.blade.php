@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fw-bold">Editar Unidad: {{ $unit->number }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('units.update', $unit->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="condominium_id" class="form-label">Comunidad</label>
                            <select class="form-select" id="condominium_id" name="condominium_id" required>
                                @foreach($condominiums as $condominium)
                                    <option value="{{ $condominium->id }}" {{ $condominium->id == $unit->condominium_id ? 'selected' : '' }}>
                                        {{ $condominium->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="number" class="form-label">Número de Unidad</label>
                                <input type="text" class="form-control" id="number" name="number" value="{{ $unit->number }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tower" class="form-label">Torre / Sector (Opcional)</label>
                                <input type="text" class="form-control" id="tower" name="tower" value="{{ $unit->tower }}" placeholder="Ej: Torre A, Torre B...">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Tipo de Propiedad</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="Departamento" {{ $unit->type == 'Departamento' ? 'selected' : '' }}>Departamento</option>
                                <option value="Casa" {{ $unit->type == 'Casa' ? 'selected' : '' }}>Casa</option>
                                <option value="Estacionamiento" {{ $unit->type == 'Estacionamiento' ? 'selected' : '' }}>Estacionamiento</option>
                                <option value="Bodega" {{ $unit->type == 'Bodega' ? 'selected' : '' }}>Bodega</option>
                                <option value="Local Comercial" {{ $unit->type == 'Local Comercial' ? 'selected' : '' }}>Local Comercial</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="prorata" class="form-label">Prorrateo (%)</label>
                            <input type="number" step="0.01" class="form-control" id="prorata" name="prorata" value="{{ $unit->prorata }}" required>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('units.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Actualizar Unidad</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection