<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Pagos y Morosidad</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        .subtitle {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
        }

        .kpi-container {
            width: 100%;
            margin-bottom: 20px;
        }

        .kpi-box {
            width: 48%;
            float: left;
            padding: 10px;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .kpi-danger {
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        .kpi-success {
            background-color: #dcfce7;
            border: 1px solid #86efac;
            color: #166534;
            margin-left: 4%;
        }

        .clear {
            clear: both;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            padding: 5px;
        }

        .title-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .title-success {
            background-color: #f0fdf4;
            color: #166534;
            border-left: 4px solid #22c55e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th {
            background-color: #f3f4f6;
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }

        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-danger {
            background-color: #ef4444;
            color: white;
        }

        .badge-success {
            background-color: #22c55e;
            color: white;
        }

        .text-right {
            text-align: right;
        }

        .empty-row {
            text-align: center;
            color: #888;
            font-style: italic;
            padding: 15px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1 class="title">Reporte de Pagos y Morosidad</h1>
        <div class="subtitle">Periodo: {{ $selectedMonth }} {{ $selectedYear }} | Generado: {{ date('d/m/Y H:i') }}</div>
    </div>

    <!-- RESUMEN FINANCIAL -->
    <div class="kpi-container">
        <div class="kpi-box kpi-danger">
            <strong>Pendiente por Cobrar:</strong>
            <div style="font-size: 16px; font-weight: bold;">$ {{ number_format($totalPendingAmount, 0, ',', '.') }}
            </div>
            <small>{{ $pendingExpenses->count() }} cobros no pagados</small>
        </div>
        <div class="kpi-box kpi-success">
            <strong>Total Recaudado:</strong>
            <div style="font-size: 16px; font-weight: bold;">$ {{ number_format($totalPaidAmount, 0, ',', '.') }}</div>
            <small>{{ $paidExpenses->count() }} cobros al día</small>
        </div>
        <div class="clear"></div>
    </div>

    <!-- TABLA 1: PENDIENTES -->
    <div class="section-title title-danger">Cobros No Pagados / Pendientes</div>
    <table>
        <thead>
            <tr>
                <th>Residente / Unidad</th>
                <th>Concepto</th>
                <th>Vencimiento</th>
                <th class="text-right">Monto</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendingExpenses as $expense)
                <tr>
                    <td>
                        <strong>{{ $expense->unit->resident->name ?? 'Sin residente' }}</strong><br>
                        <small>Depto {{ $expense->unit->number ?? 'N/A' }}
                            {{ optional($expense->unit)->tower ? '- Torre ' . $expense->unit->tower : '' }}</small>
                    </td>
                    <td>{{ $expense->concept ?? 'Gasto Común Ordinario' }}</td>
                    <td>{{ \Carbon\Carbon::parse($expense->due_date)->format('d-m-Y') }}</td>
                    <td class="text-right"><strong>$ {{ number_format($expense->amount, 0, ',', '.') }}</strong></td>
                    <td><span class="badge badge-danger">Por Pagar</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty-row">No existen saldos pendientes para este periodo.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TABLA 2: PAGADOS -->
    <div class="section-title title-success">Cobros Pagados</div>
    <table>
        <thead>
            <tr>
                <th>Residente / Unidad</th>
                <th>Concepto</th>
                <th>Vencimiento</th>
                <th class="text-right">Monto</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($paidExpenses as $expense)
                <tr>
                    <td>
                        <strong>{{ $expense->unit->resident->name ?? 'Sin residente' }}</strong><br>
                        <small>Depto {{ $expense->unit->number ?? 'N/A' }}
                            {{ optional($expense->unit)->tower ? '- Torre ' . $expense->unit->tower : '' }}</small>
                    </td>
                    <td>{{ $expense->concept ?? 'Gasto Común Ordinario' }}</td>
                    <td>{{ \Carbon\Carbon::parse($expense->due_date)->format('d-m-Y') }}</td>
                    <td class="text-right"><strong>$ {{ number_format($expense->amount, 0, ',', '.') }}</strong></td>
                    <td><span class="badge badge-success">Pagado</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty-row">No hay registros de pagos para este periodo.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
