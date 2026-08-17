<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte Anual - {{ $resident->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #334155;
            margin: 0;
            padding: 20px;
        }

        .header {
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            color: #4f46e5;
            font-size: 16px;
            text-transform: uppercase;
        }

        .info-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 20px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            border: none;
            padding: 4px 6px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #cbd5e1;
            padding: 7px 10px;
            text-align: left;
        }

        .data-table th {
            background-color: #f1f5f9;
            color: #1e293b;
            text-transform: uppercase;
            font-size: 10px;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .status-paid {
            color: #16a34a;
            font-weight: bold;
        }

        .status-pending {
            color: #dc2626;
            font-weight: bold;
        }

        .status-none {
            color: #94a3b8;
        }

        .summary-box {
            margin-top: 20px;
            width: 45%;
            float: right;
            border-collapse: collapse;
        }

        .summary-box td {
            border: 1px solid #cbd5e1;
            padding: 6px 10px;
        }

        .footer {
            clear: both;
            padding-top: 30px;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Cartola Anual de Gastos Comunes - Año {{ $year }}</h2>
        <p style="margin: 3px 0 0; color: #64748b; font-size: 10px;">Fecha de emisión: {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="info-box">
        <table class="info-table">
            <tr>
                <td><strong>Residente:</strong> {{ $resident->name }}</td>
                <td><strong>RUT:</strong> {{ $resident->rut ?? 'No registrado' }}</td>
            </tr>
            <tr>
                <td><strong>Comunidad:</strong>
                    {{ optional(optional($resident->unit)->condominium)->name ?? 'Sin asignar' }}</td>
                <td><strong>Unidad:</strong> Depto {{ $resident->unit->number ?? 'N/A' }}
                    {{ optional($resident->unit)->tower ? '(Torre ' . $resident->unit->tower . ')' : '' }}</td>
            </tr>
            <tr>
                <td><strong>Correo:</strong> {{ $resident->email ?? '-' }}</td>
                <td><strong>Teléfono:</strong> {{ $resident->phone ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Mes</th>
                <th class="text-end">Monto Cobrado</th>
                <th class="text-center">Estado</th>
                <th class="text-center">Fecha Pago</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($monthlyData as $row)
                <tr>
                    <td><strong>{{ $row['month'] }}</strong></td>
                    <td class="text-end">
                        @if ($row['amount'] > 0)
                            $ {{ number_format($row['amount'], 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($row['status'] === 'Pagado')
                            <span class="status-paid">Pagado</span>
                        @elseif($row['status'] === 'Pendiente')
                            <span class="status-pending">Pendiente</span>
                        @else
                            <span class="status-none">No emitido</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $row['paid_date'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="summary-box">
        <tr style="background-color: #f8fafc;">
            <td><strong>Total Pagado:</strong></td>
            <td class="text-end status-paid">$ {{ number_format($totalPaid, 0, ',', '.') }}</td>
        </tr>
        <tr style="background-color: #f8fafc;">
            <td><strong>Total Pendiente:</strong></td>
            <td class="text-end status-pending">$ {{ number_format($totalPending, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer">
        Documento informativo de gastos comunes generado automáticamente por el sistema de administración.
    </div>

</body>

</html>
