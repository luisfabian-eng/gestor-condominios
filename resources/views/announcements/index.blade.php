@extends('layouts.app')

@section('content')
    <div class="container pb-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold mb-1 text-dark">Muro de Avisos y Circulares</h3>
                <p class="text-muted small mb-0">Gestión de comunicados y noticias para los residentes</p>
            </div>
            <a href="{{ route('announcements.create') }}" class="btn btn-primary px-3 py-2 rounded-3 shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Publicar Nuevo Aviso
            </a>
        </div>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-3 p-md-4">
                <div class="table-responsive">
                    <table class="table table-hover datatable align-middle w-100 mb-0 text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 py-3">Título / Contenido</th>
                                <th>Destino</th>
                                <th>Prioridad</th>
                                <th>Adjunto</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th class="pe-3 text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($announcements as $item)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold text-dark">{{ $item->title }}</div>
                                        <div class="small text-muted text-truncate" style="max-width: 320px;">
                                            {{ Str::limit($item->content, 70) }}
                                        </div>
                                    </td>
                                    <td>
                                        @if ($item->condominium)
                                            <span class="badge bg-light text-dark border">
                                                <i
                                                    class="bi bi-building me-1 text-primary"></i>{{ $item->condominium->name }}
                                            </span>
                                        @else
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                                Todas las comunidades
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->priority == 'Urgente')
                                            <span class="badge bg-danger px-2 py-1 rounded-pill">Urgente</span>
                                        @elseif ($item->priority == 'Alta')
                                            <span class="badge bg-warning text-dark px-2 py-1 rounded-pill">Alta</span>
                                        @elseif ($item->priority == 'Normal')
                                            <span
                                                class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1 rounded-pill">Normal</span>
                                        @else
                                            <span
                                                class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill">Baja</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->attachment_path)
                                            <a href="{{ asset($item->attachment_path) }}" target="_blank"
                                                class="btn btn-sm btn-outline-secondary px-2 py-1 rounded-2"
                                                title="Ver documento">
                                                <i class="bi bi-paperclip"></i>
                                            </a>
                                        @else
                                            <span class="text-muted opacity-50">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->is_active)
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">
                                                <i class="bi bi-eye me-1"></i> Visible
                                            </span>
                                        @else
                                            <span
                                                class="badge bg-secondary-subtle text-secondary border px-2 py-1 rounded-pill">
                                                <i class="bi bi-eye-slash me-1"></i> Oculto
                                            </span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">
                                        {{ $item->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="pe-3 text-end text-nowrap">
                                        <div class="d-inline-flex gap-1 align-items-center">
                                            <a href="{{ route('announcements.edit', $item->id) }}"
                                                class="btn btn-sm btn-outline-primary px-2 py-1 rounded-2" title="Editar">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <form action="{{ route('announcements.destroy', $item->id) }}" method="POST"
                                                class="m-0 d-inline"
                                                onsubmit="return confirm('¿Seguro de eliminar este aviso?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger px-2 py-1 rounded-2"
                                                    title="Eliminar">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="ps-3 py-4 text-center text-muted">No existen avisos publicados aún.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
