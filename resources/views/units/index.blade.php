@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Unidades / Departamentos</span>
                    <a href="{{ route('units.create') }}" class="btn btn-primary btn-sm">Nueva Unidad</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-hover mt-3">
                        <thead>
                            <tr>
                                <th>Comunidad</th>
                                <th>Unidad (Torre)</th>
                                <th>Tipo</th>
                                <th>Prorrateo (%)</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($units as $unit)
                            <tr>
                                <td>{{ $unit->condominium->name }}</td>
                                <td>
                                    <strong>{{ $unit->number }}</strong>
                                    @if($unit->tower)
                                        <span class="badge bg-info text-dark ms-1">Torre {{ $unit->tower }}</span>
                                    @endif
                                </td>
                                <td>{{ $unit->type }}</td>
                                <td>{{ $unit->prorata }}%</td>
                                <td>
                                    <!-- Botón Editar -->
                                    <a href="{{ route('units.edit', $unit->id) }}" class="btn btn-sm btn-primary">Editar</a>
                                    
                                    <!-- Botón Eliminar -->
                                    <form action="{{ route('units.destroy', $unit->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta unidad? Sus gastos comunes asociados también se perderán.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Aún no hay unidades registradas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection