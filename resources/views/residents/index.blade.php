@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Directorio de Residentes</span>
                    <a href="{{ route('residents.create') }}" class="btn btn-primary btn-sm">Registrar Residente</a>
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
                                <th>Nombre</th>
                                <th>RUT</th>
                                <th>Contacto</th>
                                <th>Propiedad (Unidad)</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($residents as $resident)
                            <tr>
                                <td class="fw-bold">{{ $resident->name }}</td>
                                <td>{{ $resident->rut ?? 'No registrado' }}</td>
                                <td>
                                    <div><i class="bi bi-envelope text-muted me-1"></i> {{ $resident->email ?? '-' }}</div>
                                    <div><i class="bi bi-telephone text-muted me-1"></i> {{ $resident->phone ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $resident->unit->number }} 
                                        {{ $resident->unit->tower ? '- Torre ' . $resident->unit->tower : '' }}
                                    </span>
                                    <div class="small text-muted mt-1">{{ $resident->unit->condominium->name }}</div>
                                </td>
                                <td>
                                    <a href="{{ route('residents.edit', $resident->id) }}" class="btn btn-sm btn-primary mb-1">Editar</a>
                                    
                                    <form action="{{ route('residents.destroy', $resident->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar a este residente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger mb-1">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-people" style="font-size: 2rem;"></i><br>
                                    Aún no hay residentes registrados.
                                </td>
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