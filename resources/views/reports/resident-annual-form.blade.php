@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary">
                            <i class="bi bi-person-badge me-2"></i>Reporte Anual de Pagos por Residente
                        </span>
                        <a href="{{ route('reports.menu') }}" class="btn btn-sm btn-outline-secondary">Volver al Menú</a>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('reports.resident.annual.pdf') }}" method="POST" target="_blank">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-bold">Selecciona el Residente:</label>
                                <select name="resident_id" class="form-select" required>
                                    <option value="">-- Seleccionar residente --</option>
                                    @foreach ($residents as $res)
                                        <option value="{{ $res->id }}">
                                            {{ $res->name }} (Unidad: {{ $res->unit->number ?? 'N/A' }} -
                                            {{ optional(optional($res->unit)->condominium)->name ?? 'Sin comunidad' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Año a Consultar:</label>
                                <select name="year" class="form-select" required>
                                    @foreach ($years as $y)
                                        <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>
                                            {{ $y }}</option>
                                    @endforeach
                                </select>
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
