@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 rounded-4">
                <!-- Se agregó flex-column flex-md-row y gap-3 para que en celular se vea bien el título y el botón -->
                <div class="card-header bg-white border-bottom pb-3 pt-4 px-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <span class="fw-bold fs-5">Gestión de Finanzas (Gastos Comunes)</span>
                    <a href="{{ route('common_expenses.create') }}" class="btn btn-primary btn-sm px-3 py-2">Registrar Nuevo Cobro</a>
                </div>

                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- EL SECRETO ESTÁ AQUÍ: class="table-responsive" envuelve la tabla -->
                    <div class="table-responsive">
                        <!-- Se agregó text-nowrap para que las columnas no se aplasten y align-middle para centrar -->
                        <table class="table table-hover mt-3 align-middle text-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Residente y Unidad</th>
                                    <th>Periodo</th>
                                    <th>Monto</th>
                                    <th>Vencimiento</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenses as $expense)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-primary">
                                            <i class="bi bi-person-fill me-1"></i>
                                            {{ $expense->unit->resident ? $expense->unit->resident->name : 'Sin residente asignado' }}
                                        </div>
                                        <div class="small text-muted mt-1">
                                            Depto {{ $expense->unit->number }} 
                                            @if($expense->unit->tower)
                                                <span class="badge bg-light text-dark border">Torre {{ $expense->unit->tower }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $expense->month }} {{ $expense->year }}</td>
                                    <td class="fw-bold text-dark">$ {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($expense->due_date)->format('d-m-Y') }}</td>
                                    <td>
                                        @if($expense->status == 'Pendiente')
                                            <span class="badge bg-danger px-2 py-1">Pendiente</span>
                                        @else
                                            <span class="badge bg-success px-2 py-1">Pagado</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            @if($expense->status == 'Pendiente')
                                                <form action="{{ route('common_expenses.pay', $expense->id) }}" method="POST" class="m-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success">Pagar</button>
                                                </form>
                                            @endif
                                            
                                            <a href="{{ route('common_expenses.edit', $expense->id) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                                            
                                            <form action="{{ route('common_expenses.destroy', $expense->id) }}" method="POST" class="m-0" onsubmit="return confirm('¿Estás seguro de eliminar este cobro?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Borrar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                        No hay cobros registrados en el sistema.
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
</div>
@endsection