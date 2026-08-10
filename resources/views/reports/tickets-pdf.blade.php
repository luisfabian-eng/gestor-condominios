<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #334155;
            margin: 0;
            padding: 15px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            color: #4f46e5;
            font-size: 16px;
            text-transform: uppercase;
        }

        .header p {
            margin: 4px 0 0 0;
            font-size: 10px;
            color: #64748b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-size: 10px;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-info {
            background-color: #e0f2fe;
            color: #075985;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }

        .footer {
            margin-top: 25px;
            text-align: right;
            font-size: 9px;
            color: #94a3b8;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>{{ $title }}</h2>
        <p>Fecha de emisión: {{ date('d/m/Y H:i:s') }} | Total de registros: {{ $tickets->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 35px;">ID</th>
                <th>Descripción del Problema</th>
                <th>Reportado Por</th>
                <th>Ubicación</th>
                <th>Fecha Creación</th>
                <th>Urgencia</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
                <tr>
                    <td><strong>#{{ $ticket->id }}</strong></td>
                    <td>{{ $ticket->title }}</td>
                    <td>{{ $ticket->user->name ?? 'Anónimo' }}</td>
                    <td>{{ $ticket->location ?? '-' }}</td>
                    <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if ($ticket->urgency == 'Alta')
                            <span class="badge badge-danger">Alta</span>
                        @elseif($ticket->urgency == 'Media')
                            <span class="badge badge-warning">Media</span>
                        @else
                            <span class="badge badge-info">Baja</span>
                        @endif
                    </td>
                    <td>
                        @if ($ticket->status == 'Abierto')
                            <span class="badge badge-danger">Abierto</span>
                        @elseif($ticket->status == 'En progreso')
                            <span class="badge badge-warning">En progreso</span>
                        @else
                            <span class="badge badge-success">Resuelto</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #94a3b8;">No se encontraron incidencias
                        registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Documento generado automáticamente por el sistema de administración.
    </div>

</body>

</html>
