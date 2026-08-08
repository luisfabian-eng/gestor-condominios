@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Comunidades Administradas</span>
                    <a href="{{ route('condominiums.create') }}" class="btn btn-primary btn-sm">Nueva Comunidad</a>
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
                                <th>Dirección</th>
                                <th>Ciudad</th>
                                <th>Acciones</th> <!-- Nueva columna -->
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($condominiums as $condominium)
                            <tr>
                                <td>{{ $condominium->name }}</td>
                                <td>{{ $condominium->rut }}</td>
                                <td>{{ $condominium->address }}</td>
                                <td>{{ $condominium->city }}</td>
                                <td>
                                    <!-- Botón Editar -->
                                    <a href="{{ route('condominiums.edit', $condominium->id) }}" class="btn btn-sm btn-primary">Editar</a>
                                    
                                    <!-- Botón Eliminar -->
                                    <form action="{{ route('condominiums.destroy', $condominium->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta comunidad? Esta acción no se puede deshacer.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Aún no hay comunidades registradas.</td>
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