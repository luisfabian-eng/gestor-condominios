@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white py-3 fw-bold fs-5 text-dark">
                        <i class="bi bi-person-badge me-2 text-primary"></i>Registro de Nuevo Residente
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Nombre -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Nombre Completo</label>
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name') }}" placeholder="Ej: Juan Pérez" required autofocus>
                                @error('name')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Correo Electrónico</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" placeholder="usuario@correo.com" required>
                                @error('email')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <!-- Selección de Departamento/Unidad -->
                            <div class="mb-3">
                                <label for="unit_id" class="form-label fw-bold">Selecciona tu Departamento / Unidad</label>
                                <select id="unit_id" name="unit_id"
                                    class="form-select @error('unit_id') is-invalid @enderror" required>
                                    <option value="">-- Selecciona una unidad disponible --</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                            Depto {{ $unit->number }}
                                            {{ $unit->tower ? '- Torre ' . $unit->tower : '' }}
                                            ({{ $unit->condominium->name ?? 'Comunidad' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="rut" class="form-label fw-bold">RUT (Opcional)</label>
                                    <input id="rut" type="text"
                                        class="form-control @error('rut') is-invalid @enderror" name="rut"
                                        value="{{ old('rut') }}" placeholder="12.345.678-K" maxlength="12">
                                    @error('rut')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-bold">Teléfono (Opcional)</label>
                                    <input id="phone" type="text"
                                        class="form-control @error('phone') is-invalid @enderror" name="phone"
                                        value="{{ old('phone') }}" placeholder="+56 9 1234 5678" maxlength="15">
                                    @error('phone')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-bold">Contraseña</label>
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        placeholder="Mínimo 8 caracteres" required>
                                    @error('password')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password-confirm" class="form-label fw-bold">Confirmar Contraseña</label>
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" placeholder="Repite tu contraseña" required>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-check-circle me-1"></i> Completar Registro
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
            // Formateador de RUT en tiempo real
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

            // Formateador de Teléfono en tiempo real
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
