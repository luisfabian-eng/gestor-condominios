@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-bottom pb-3 pt-4 px-4">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-tools me-2 text-primary"></i>Registrar Nueva Incidencia
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('tickets.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold">Descripción del Problema</label>
                            <input type="text" class="form-control form-control-lg" id="title" name="title" placeholder="Ej: Fuga de agua, Foco quemado, Revisión de gas..." required>
                        </div>

                        <div class="mb-4">
                            <label for="location" class="form-label fw-bold">Ubicación (Opcional)</label>
                            <input type="text" class="form-control" id="location" name="location" placeholder="Ej: Pasillo piso 3, Estacionamiento, Quincho...">
                        </div>

                        <div class="mb-5">
                            <label for="urgency" class="form-label fw-bold">Nivel de Urgencia</label>
                            <select class="form-select" id="urgency" name="urgency" required>
                                <option value="Baja" selected>Baja (Puede esperar)</option>
                                <option value="Media">Media (Requiere atención pronto)</option>
                                <option value="Alta">Alta (Emergencia inmediata)</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('home') }}" class="btn btn-light border px-4">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4">Guardar Ticket</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection