@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Comunidades Administradas</span>
                        <a href="{{ route('condominiums.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i>Nueva Comunidad
                        </a>
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
                                        <th>Nombre</th>
                                        <th>RUT</th>
                                        <th>Dirección</th>
                                        <th>Ciudad</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($condominiums as $condominium)
                                        <tr>
                                            <td><strong>{{ $condominium->name }}</strong></td>
                                            <td>{{ $condominium->rut ?? '-' }}</td>
                                            <td>{{ $condominium->address }}</td>
                                            <td>{{ $condominium->city ?? '-' }}</td>
                                            <td class="text-end">
                                                <!-- Botón Editar -->
                                                <a href="{{ route('condominiums.edit', $condominium->id) }}"
                                                    class="btn btn-sm btn-outline-primary me-1">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>

                                                <!-- Botón Eliminar -->
                                                <form action="{{ route('condominiums.destroy', $condominium->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('¿Estás seguro de eliminar esta comunidad? Esta acción no se puede deshacer.');">
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
