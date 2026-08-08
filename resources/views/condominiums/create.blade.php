@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fw-bold">Registrar Nueva Comunidad</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('condominiums.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre del Condominio</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="rut" class="form-label">RUT de la Comunidad</label>
                            <input type="text" class="form-control" id="rut" name="rut" placeholder="Ej: 70.123.456-7">
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>

                        <div class="mb-3">
                            <label for="city" class="form-label">Ciudad</label>
                            <input type="text" class="form-control" id="city" name="city">
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('condominiums.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-success">Guardar Comunidad</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection