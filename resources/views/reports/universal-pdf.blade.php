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
        <p>Fecha de emisión: {{ date('d/m/Y H:i:s') }} | Total de registros: {{ $records->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                @foreach ($selectedColumns as $colKey => $colLabel)
                    <th>{{ $colLabel }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($records as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>

                    @foreach ($selectedColumns as $colKey => $colLabel)
                        <td>
                            @if ($module === 'units')
                                @if ($colKey === 'condominium')
                                    {{ $row->condominium->name ?? 'N/A' }}
                                @elseif($colKey === 'number')
                                    {{ $row->number }}
                                @elseif($colKey === 'tower')
                                    {{ $row->tower ?? '-' }}
                                @elseif($colKey === 'type')
                                    {{ $row->type }}
                                @elseif($colKey === 'prorata')
                                    {{ $row->prorata }}%
                                @else
                                    {{ $row->$colKey ?? '-' }}
                                @endif
                            @elseif($module === 'residents')
                                @if ($colKey === 'name')
                                    {{ $row->name }}
                                @elseif($colKey === 'email')
                                    {{ $row->email }}
                                @elseif($colKey === 'phone')
                                    {{ $row->phone ?? '-' }}
                                @elseif($colKey === 'unit')
                                    {{ $row->unit->number ?? 'N/A' }}
                                @elseif($colKey === 'condominium')
                                    {{ $row->unit->condominium->name ?? 'N/A' }}
                                @else
                                    {{ $row->$colKey ?? '-' }}
                                @endif
                            @elseif($module === 'condominiums')
                                @if ($colKey === 'name')
                                    {{ $row->name }}
                                @elseif($colKey === 'address')
                                    {{ $row->address ?? '-' }}
                                @elseif($colKey === 'rut')
                                    {{ $row->rut ?? '-' }}
                                @else
                                    {{ $row->$colKey ?? '-' }}
                                @endif
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($selectedColumns) + 1 }}" style="text-align: center; color: #94a3b8;">
                        No se encontraron registros con los filtros seleccionados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Documento generado automáticamente por el sistema de administración.
    </div>

</body>

</html>
