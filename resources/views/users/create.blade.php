@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark fs-5">
                            <i class="bi bi-person-plus me-2 text-primary"></i>Crear Nuevo Usuario
                        </span>
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Volver
                        </a>
                    </div>

                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nombre Completo:</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                    placeholder="Ej: Juan Pérez" required>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">RUT / Identificación:</label>
                                    <input type="text" id="rut" name="rut" class="form-control"
                                        value="{{ old('rut') }}" placeholder="12.345.678-K" maxlength="12">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Teléfono de Contacto:</label>
                                    <input type="text" id="phone" name="phone" class="form-control"
                                        value="{{ old('phone') }}" placeholder="+56 9 1234 5678" maxlength="15">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Correo Electrónico:</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                    placeholder="usuario@correo.com" required>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Contraseña:</label>
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Mínimo 6 caracteres" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Rol de Sistema:</label>
                                    <select name="role" class="form-select" required>
                                        <option value="residente" {{ old('role') == 'residente' ? 'selected' : '' }}>
                                            Residente</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Vincular a Perfil de Residente (Opcional):</label>
                                <select name="resident_id" class="form-select">
                                    <option value="">-- Sin vincular --</option>
                                    @foreach ($residents as $resident)
                                        <option value="{{ $resident->id }}"
                                            {{ old('resident_id') == $resident->id ? 'selected' : '' }}>
                                            {{ $resident->name }}
                                            (Depto {{ $resident->unit->number ?? 'N/A' }}
                                            {{ optional($resident->unit)->tower ? '- Torre ' . $resident->unit->tower : '' }}
                                            -
                                            {{ optional(optional($resident->unit)->condominium)->name ?? 'Sin comunidad' }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Si selecciona un residente, este usuario podrá ver los cobros e
                                    incidencias de su departamento.</div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-save me-1"></i> Guardar Usuario
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
            // Formateador de RUT
            const rutInput = document.getElementById('rut');
            if (rutInput) {
                rutInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/[^0-9kK]/g, '').toUpperCase();
                    if (value.length > 1) {
                        let body = value.slice(0, -1);
                        let dv = value.slice(-1);
                        body = body.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        e.target.value = body + '-' + dv;
                    } else {
                        e.target.value = value;
                    }
                });
            }

            // Formateador de Teléfono
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    let digits = e.target.value.replace(/\D/g, '');
                    if (digits.startsWith('56')) {
                        digits = digits.substring(2);
                    }
                    if (digits.length > 0) {
                        if (digits.length <= 1) {
                            e.target.value = '+56 ' + digits;
                        } else if (digits.length <= 5) {
                            e.target.value = '+56 ' + digits.substring(0, 1) + ' ' + digits.substring(1);
                        } else {
                            e.target.value = '+56 ' + digits.substring(0, 1) + ' ' + digits.substring(1,
                                5) + ' ' + digits.substring(5, 9);
                        }
                    } else {
                        e.target.value = '';
                    }
                });
            }
        });
    </script>
@endpush
