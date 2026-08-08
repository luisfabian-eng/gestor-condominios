@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-bottom pb-3 pt-4 px-4 fw-bold fs-5">
                    Registrar Nuevo Residente
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('residents.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="unit_id" class="form-label">Departamento / Unidad</label>
                            <select class="form-select" id="unit_id" name="unit_id" required>
                                <option value="" selected disabled>Seleccione un departamento...</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">Depto {{ $unit->number }} {{ $unit->tower ? '(Torre '.$unit->tower.')' : '' }} - {{ $unit->condominium->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="rut" class="form-label">RUT (Opcional)</label>
                                <input type="text" class="form-control" id="rut" name="rut" placeholder="Ej: 12.345.678-9">
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Teléfono (Opcional)</label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Ej: +569...">
                            </div>
                        </div>

                        <!-- AQUÍ ESTÁ EL CAMBIO PRINCIPAL -->
                        <div class="mb-4">
                            <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="ejemplo@correo.com" required>
                            <div class="form-text text-primary mt-2">
                                <i class="bi bi-info-circle-fill me-1"></i>
                                El sistema creará una cuenta automáticamente usando este correo y generará una contraseña segura al guardar.
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4 border-top pt-4">
                            <a href="{{ route('residents.index') }}" class="btn btn-light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar y Generar Cuenta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection