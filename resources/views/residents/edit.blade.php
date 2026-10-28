@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <!-- Header -->
                    <div
                        class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold mb-0 text-dark">
                                <i class="bi bi-pencil-square text-primary me-2"></i>Editar Residente
                            </h4>
                            <small class="text-muted">Modifica los datos personales y de contacto del residente</small>
                        </div>
                        <a href="{{ route('residents.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
                            <i class="bi bi-arrow-left me-1"></i> Volver al listado
                        </a>
                    </div>

                    <div class="card-body p-4">

                        <!-- Errores de validación -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>Por favor corrige los siguientes errores:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('residents.update', $resident->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Selección de Departamento / Unidad -->
                            <div class="mb-3">
                                <label for="unit_id" class="form-label fw-bold text-dark">Unidad / Departamento
                                    asignado</label>
                                <select class="form-select rounded-3 py-2" id="unit_id" name="unit_id" required>
                                    <option value="" disabled>Selecciona una unidad...</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ old('unit_id', $resident->unit_id) == $unit->id ? 'selected' : '' }}>
                                            Depto {{ $unit->number }}
                                            {{ $unit->tower ? ' - Torre ' . $unit->tower : '' }}
                                            ({{ $unit->condominium->name ?? 'Condominio' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Nombre Completo -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold text-dark">Nombre y Apellido</label>
                                <input type="text" class="form-control rounded-3 py-2" id="name" name="name"
                                    value="{{ old('name', $resident->name) }}" placeholder="Ej: Juan Pérez" required>
                            </div>

                            <div class="row mb-3">
                                <!-- RUT -->
                                <div class="col-md-6">
                                    <label for="rut" class="form-label fw-bold text-dark">RUT / Documento</label>
                                    <input type="text" class="form-control rounded-3 py-2" id="rut" name="rut"
                                        value="{{ old('rut', $resident->rut) }}" placeholder="Ej: 12.345.678-9">
                                </div>

                                <!-- Teléfono -->
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-bold text-dark">Teléfono de Contacto</label>
                                    <input type="text" class="form-control rounded-3 py-2" id="phone" name="phone"
                                        value="{{ old('phone', $resident->phone) }}" placeholder="Ej: +56 9 1234 5678">
                                </div>
                            </div>

                            <!-- Correo Electrónico -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-bold text-dark">Correo Electrónico (Acceso al
                                    Portal)</label>
                                <input type="email" class="form-control rounded-3 py-2" id="email" name="email"
                                    value="{{ old('email', $resident->email) }}" placeholder="correo@ejemplo.com" required>
                                <div class="form-text text-muted">
                                    <i class="bi bi-info-circle me-1"></i> Si modificas el correo electrónico, se
                                    actualizará también el usuario para iniciar sesión.
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                                <a href="{{ route('residents.index') }}" class="btn btn-secondary px-4 rounded-3">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary px-4 rounded-3 shadow-sm">
                                    <i class="bi bi-check-lg me-1"></i> Guardar Cambios
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
