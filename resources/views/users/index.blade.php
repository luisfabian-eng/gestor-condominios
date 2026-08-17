@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-dark">Gestión de Usuarios</h2>
                <p class="text-muted mt-1">Cuentas de acceso al sistema y vinculación con residentes</p>
            </div>
            <a href="{{ route('users.create') }}" class="btn btn-primary px-4">
                <i class="bi bi-person-plus me-1"></i> Nuevo Usuario
            </a>
        </div>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover datatable align-middle w-100 mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 py-3">Usuario</th>
                                <th>RUT</th>
                                <th>Contacto</th>
                                <th>Rol</th>
                                <th>Propiedad Asignada</th>
                                <th class="pe-3 text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold text-dark">
                                            <i class="bi bi-person-circle me-1 text-primary"></i> {{ $user->name }}
                                        </div>
                                    </td>
                                    <td>
                                        {{ $user->resident->rut ?? 'No registrado' }}
                                    </td>
                                    <td>
                                        <div>
                                            <i class="bi bi-envelope text-muted me-1"></i> {{ $user->email }}
                                        </div>
                                        <div>
                                            <i class="bi bi-telephone text-muted me-1"></i>
                                            {{ $user->resident->phone ?? '-' }}
                                        </div>
                                    </td>
                                    <td>
                                        @if ($user->role === 'admin')
                                            <span class="badge bg-danger px-2 py-1">Administrador</span>
                                        @else
                                            <span class="badge bg-primary px-2 py-1">Residente</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->resident && $user->resident->unit)
                                            <div>
                                                <strong>Depto {{ $user->resident->unit->number }}</strong>
                                                @if ($user->resident->unit->tower)
                                                    <span class="badge bg-light text-dark border ms-1">Torre
                                                        {{ $user->resident->unit->tower }}</span>
                                                @endif
                                            </div>
                                            <div class="small text-muted">
                                                {{ $user->resident->unit->condominium->name ?? 'Sin comunidad' }}
                                            </div>
                                        @else
                                            <span class="text-muted small"><em>Sin vínculo</em></span>
                                        @endif
                                    </td>
                                    <td class="pe-3 text-end">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('users.edit', $user->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Editar usuario">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                class="m-0"
                                                onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    title="Eliminar usuario">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
