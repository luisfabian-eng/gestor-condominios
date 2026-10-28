@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark">
                            <i class="bi bi-tools me-2 text-warning"></i>Reporte de Incidencias y Mantenimiento
                        </span>
                        <a href="{{ route('reports.menu') }}" class="btn btn-sm btn-outline-secondary">Volver al Menú</a>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('reports.tickets.filtered.pdf') }}" method="POST" target="_blank">
                            @csrf

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Estado del Ticket:</label>
                                    <select name="status" class="form-select">
                                        <option value="">-- Todos los estados --</option>
                                        <option value="Abierto">Abierto</option>
                                        <option value="En progreso">En progreso</option>
                                        <option value="Resuelto">Resuelto</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nivel de Urgencia:</label>
                                    <select name="urgency" class="form-select">
                                        <option value="">-- Todas las urgencias --</option>
                                        <option value="Baja">Baja</option>
                                        <option value="Media">Media</option>
                                        <option value="Alta">Alta</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Fecha Desde:</label>
                                    <input type="date" name="date_from" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Fecha Hasta:</label>
                                    <input type="date" name="date_to" class="form-control">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-file-earmark-pdf-fill me-2"></i>Generar y Descargar PDF
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
