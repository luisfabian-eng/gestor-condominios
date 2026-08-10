@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold text-dark mb-1">Centro de Reportes</h3>
                        <p class="text-muted mb-0">Selecciona el tipo de informe que deseas exportar en formato PDF</p>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Reporte Anual de Pagos -->
                    <div class="col-md-6">
                        <div class="card h-100 p-3">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <div class="badge bg-primary-subtle text-primary p-3 rounded-circle mb-3">
                                        <i class="bi bi-person-lines-fill fs-3"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark">Estado Anual por Residente</h5>
                                    <p class="text-muted small">
                                        Genera un estado de cuenta anual para un residente en particular, con el detalle de
                                        cobros mes a mes, montos pagados y saldos pendientes.
                                    </p>
                                </div>
                                <a href="{{ route('reports.resident.annual.form') }}"
                                    class="btn btn-outline-primary w-100 mt-3">
                                    <i class="bi bi-file-earmark-person me-1"></i> Configurar Reporte
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Generador Universal -->
                    <div class="col-md-6">
                        <div class="card h-100 p-3">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <div class="badge bg-indigo-subtle text-indigo p-3 rounded-circle mb-3">
                                        <i class="bi bi-sliders2-vertical fs-3"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark">Generador Dinámico / Universal</h5>
                                    <p class="text-muted small">
                                        Selecciona cualquier módulo (Unidades, Residentes o Comunidades), filtra por
                                        edificio y marca exactamente qué columnas quieres exportar.
                                    </p>
                                </div>
                                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary w-100 mt-3">
                                    <i class="bi bi-gear-wide-connected me-1"></i> Generador Dinámico
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
