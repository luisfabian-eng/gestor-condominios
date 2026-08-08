@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Unidades / Departamentos</span>
                        <div class="d-flex gap-2">
                            <a href="{{ route('reports.units.pdf') }}" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-file-earmark-pdf me-1"></i> Descargar PDF
                            </a>
                            <a href="{{ route('units.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-lg me-1"></i> Nueva Unidad
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover datatable w-100 mt-2">
                                <thead>
                                    <tr>
                                        <th>Comunidad</th>
                                        <th>Unidad (Torre)</th>
                                        <th>Tipo</th>
                                        <th>Prorrateo (%)</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($units as $unit)
                                        <tr>
                                            <td>{{ $unit->condominium->name ?? 'Sin asignar' }}</td>
                                            <td>
                                                <strong>{{ $unit->number }}</strong>
                                                @if ($unit->tower)
                                                    <span class="badge bg-info text-dark ms-1">Torre
                                                        {{ $unit->tower }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $unit->type }}</td>
                                            <td>{{ $unit->prorata }}%</td>
                                            <td class="text-end">
                                                <!-- Botón Editar -->
                                                <a href="{{ route('units.edit', $unit->id) }}"
                                                    class="btn btn-sm btn-outline-primary me-1">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>

                                                <!-- Botón Eliminar -->
                                                <form action="{{ route('units.destroy', $unit->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('¿Estás seguro de eliminar esta unidad? Sus gastos comunes asociados también se perderán.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
