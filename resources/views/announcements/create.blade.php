@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div
                        class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold mb-0 text-dark">
                                <i class="bi bi-megaphone text-primary me-2"></i>Publicar Aviso
                            </h4>
                            <small class="text-muted">Ingresa los datos para emitir un comunicado</small>
                        </div>
                        <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
                            <i class="bi bi-arrow-left me-1"></i> Volver
                        </a>
                    </div>

                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger rounded-3 mb-4">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('announcements.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold text-dark">Título del Aviso</label>
                                <input type="text" name="title" id="title" class="form-control rounded-3 py-2"
                                    value="{{ old('title') }}"
                                    placeholder="Ej: Corte programado de agua / Mantención ascensor" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="condominium_id" class="form-label fw-bold text-dark">Comunidad
                                        Destino</label>
                                    <select name="condominium_id" id="condominium_id" class="form-select rounded-3 py-2">
                                        <option value="">Todas las comunidades (Global)</option>
                                        @foreach ($condominiums as $condo)
                                            <option value="{{ $condo->id }}"
                                                {{ old('condominium_id') == $condo->id ? 'selected' : '' }}>
                                                {{ $condo->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="priority" class="form-label fw-bold text-dark">Nivel de Prioridad</label>
                                    <select name="priority" id="priority" class="form-select rounded-3 py-2" required>
                                        <option value="Baja" {{ old('priority') == 'Baja' ? 'selected' : '' }}>Baja
                                            (Informativo simple)</option>
                                        <option value="Normal"
                                            {{ old('priority', 'Normal') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="Alta" {{ old('priority') == 'Alta' ? 'selected' : '' }}>Alta
                                            (Importante)</option>
                                        <option value="Urgente" {{ old('priority') == 'Urgente' ? 'selected' : '' }}>
                                            Urgente (Emergencia / Cortes)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label fw-bold text-dark">Mensaje o Detalle</label>
                                <textarea name="content" id="content" class="form-control rounded-3" rows="5"
                                    placeholder="Redacta el comunicado para los vecinos..." required>{{ old('content') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label for="attachment" class="form-label fw-bold text-dark">Archivo Adjunto / Circular
                                    (Opcional)</label>
                                <input type="file" name="attachment" id="attachment" class="form-control rounded-3 py-2"
                                    accept=".pdf,.jpg,.jpeg,.png">
                                <div class="form-text">PDF o imagen explicativa. Máx: 20MB.</div>
                            </div>

                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active"
                                    name="is_active" value="1" checked>
                                <label class="form-check-label fw-semibold text-dark" for="is_active">
                                    Publicar inmediatamente (Visible para los residentes)
                                </label>
                            </div>

                            <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                                <a href="{{ route('announcements.index') }}"
                                    class="btn btn-secondary px-4 rounded-3">Cancelar</a>
                                <button type="submit" class="btn btn-primary px-4 rounded-3 shadow-sm">
                                    <i class="bi bi-send me-1"></i> Publicar Aviso
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
