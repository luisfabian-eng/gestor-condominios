@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark fs-5">
                            <i class="bi bi-pencil-square me-2 text-primary"></i>Editar Usuario: {{ $user->name }}
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

                        <form action="{{ route('users.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nombre Completo:</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Correo Electrónico:</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nueva Contraseña (Opcional):</label>
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Dejar en blanco para conservar la actual">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Rol de Sistema:</label>
                                    <select name="role" class="form-select" required>
                                        <option value="residente"
                                            {{ old('role', $user->role) == 'residente' ? 'selected' : '' }}>Residente
                                        </option>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                            Administrador</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Vincular a Perfil de Residente (Opcional):</label>
                                <select name="resident_id" class="form-select">
                                    <option value="">-- Sin vincular --</option>
                                    @foreach ($residents as $resident)
                                        <option value="{{ $resident->id }}"
                                            {{ old('resident_id', $user->resident_id) == $resident->id ? 'selected' : '' }}>
                                            {{ $resident->name }}
                                            (Depto {{ $resident->unit->number ?? 'N/A' }}
                                            {{ optional($resident->unit)->tower ? '- Torre ' . $resident->unit->tower : '' }}
                                            -
                                            {{ optional(optional($resident->unit)->condominium)->name ?? 'Sin comunidad' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-check-lg me-1"></i> Actualizar Usuario
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
