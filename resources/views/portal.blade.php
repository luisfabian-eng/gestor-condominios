@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            
            <div class="text-center mb-4">
                <i class="bi bi-house-check-fill text-primary" style="font-size: 4rem;"></i>
                <h2 class="fw-bold mt-2">Bienvenido, {{ Auth::user()->name }}</h2>
                <p class="text-muted">
                    @if($resident && $resident->unit)
                        Residente del <strong>Depto {{ $resident->unit->number }}</strong> 
                        {{ $resident->unit->tower ? '(Torre '.$resident->unit->tower.')' : '' }} 
                        en {{ $resident->unit->condominium->name }}
                    @else
                        Tu cuenta aún no tiene un departamento asignado.
                    @endif
                </p>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <h4 class="fw-bold border-bottom pb-3 mb-4">
                        <i class="bi bi-receipt me-2"></i> Mis Gastos Comunes
                    </h4>
                    
                    <div class="table-responsive">
                        <table class="table table-hover text-start align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Periodo</th>
                                    <th>Vencimiento</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenses as $expense)
                                <tr>
                                    <td>{{ $expense->month }} {{ $expense->year }}</td>
                                    <td>{{ \Carbon\Carbon::parse($expense->due_date)->format('d-m-Y') }}</td>
                                    <td class="fw-bold">$ {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                    <td>
                                        @if($expense->status == 'Pendiente')
                                            <span class="badge bg-danger">Por Pagar</span>
                                        @else
                                            <span class="badge bg-success">Pagado</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($expense->status == 'Pendiente')
                                            <!-- Aquí a futuro pondremos el botón de Webpay -->
                                            <button class="btn btn-sm btn-primary disabled"><i class="bi bi-credit-card me-1"></i> Pagar en línea</button>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary disabled"><i class="bi bi-check2-all me-1"></i> Listo</button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="bi bi-emoji-smile fs-1 d-block mb-2 text-success"></i>
                                        Estás al día. No tienes cobros registrados.
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