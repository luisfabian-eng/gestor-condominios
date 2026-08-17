@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Directorio de Residentes</span>
                        <a href="{{ route('residents.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i>Registrar Residente
                        </a>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover datatable w-100 mt-2 align-middle">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>RUT</th>
                                        <th>Contacto</th>
                                        <th>Propiedad (Unidad)</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($residents as $resident)
                                        <tr>
                                            <td class="fw-bold">{{ $resident->name }}</td>
                                            <td>{{ $resident->rut ?? 'No registrado' }}</td>
                                            <td>
                                                <div><i class="bi bi-envelope text-muted me-1"></i>
                                                    {{ $resident->email ?? '-' }}</div>
                                                <div><i class="bi bi-telephone text-muted me-1"></i>
                                                    {{ $resident->phone ?? '-' }}</div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $resident->unit->number ?? 'N/A' }}
                                                    {{ optional($resident->unit)->tower ? '- Torre ' . $resident->unit->tower : '' }}
                                                </span>
                                                <div class="small text-muted mt-1">
                                                    {{ optional(optional($resident->unit)->condominium)->name ?? 'Sin comunidad' }}
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <!-- Botón Editar -->
                                                <a href="{{ route('residents.edit', $resident->id) }}"
                                                    class="btn btn-sm btn-outline-primary me-1">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>

                                                <!-- Botón Eliminar -->
                                                <form action="{{ route('residents.destroy', $resident->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('¿Eliminar a este residente?');">
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
