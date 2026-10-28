<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Unidades</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #334155;
            margin: 0;
            padding: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            color: #4f46e5;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header p {
            margin: 4px 0 0 0;
            color: #64748b;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            text-align: left;
        }

        th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-size: 11px;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .badge {
            background-color: #e0e7ff;
            color: #4338ca;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #94a3b8;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Reporte General de Unidades</h2>
        <p>Generado el: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Comunidad</th>
                <th>Unidad</th>
                <th>Tipo</th>
                <th>Prorrateo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($units as $index => $unit)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $unit->condominium->name ?? 'Sin asignar' }}</td>
                    <td>
                        <strong>{{ $unit->number }}</strong>
                        @if ($unit->tower)
                            <span class="badge">Torre {{ $unit->tower }}</span>
                        @endif
                    </td>
                    <td>{{ $unit->type }}</td>
                    <td>{{ $unit->prorata }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #94a3b8;">No hay registros disponibles.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Documento generado automáticamente por el sistema de gestión.
    </div>

</body>

</html>
