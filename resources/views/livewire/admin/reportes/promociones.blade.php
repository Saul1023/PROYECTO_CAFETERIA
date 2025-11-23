<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Promociones</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        font-size: 12px;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #333;
        padding-bottom: 10px;
    }

    .header h1 {
        margin: 0;
        color: #333;
        font-size: 24px;
    }

    .info-periodo {
        text-align: center;
        margin-bottom: 15px;
        color: #666;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .table th,
    .table td {
        border: 1px solid #ddd;
        padding: 6px;
        text-align: left;
    }

    .table th {
        background-color: #f5f5f5;
        font-weight: bold;
    }

    .estadisticas {
        margin-bottom: 20px;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 5px;
    }

    .footer {
        text-align: center;
        margin-top: 30px;
        font-size: 10px;
        color: #666;
    }

    .text-center {
        text-align: center;
    }

    .badge {
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 10px;
    }

    .bg-success {
        background-color: #28a745;
        color: white;
    }

    .bg-danger {
        background-color: #dc3545;
        color: white;
    }

    .bg-warning {
        background-color: #ffc107;
        color: black;
    }

    .bg-info {
        background-color: #17a2b8;
        color: white;
    }

    .bg-secondary {
        background-color: #6c757d;
        color: white;
    }

    .bg-primary {
        background-color: #007bff;
        color: white;
    }
    </style>
</head>

<body>
    <div class="header">
        <h1>Reporte de Promociones</h1>
        <div class="info-periodo">
            <strong>Periodo:</strong> {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} -
            {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}
        </div>
    </div>

    @if(isset($estadisticas) && !empty($estadisticas))
    <div class="estadisticas">
        <h3>Estadísticas</h3>
        <table style="width: 100%;">
            <tr>
                <td><strong>Total Promociones:</strong> {{ $estadisticas['total'] ?? 0 }}</td>
                <td><strong>Activas:</strong> {{ $estadisticas['activas'] ?? 0 }}</td>
                <td><strong>Vigentes:</strong> {{ $estadisticas['vigentes'] ?? 0 }}</td>
                <td><strong>Expiradas:</strong> {{ $estadisticas['expiradas'] ?? 0 }}</td>
            </tr>
            @if(isset($estadisticas['futuras']))
            <tr>
                <td colspan="4"><strong>Futuras:</strong> {{ $estadisticas['futuras'] ?? 0 }}</td>
            </tr>
            @endif
        </table>
    </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Tipo</th>
                <th>Valor</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Productos</th>
                <th>Estado</th>
                <th>Vigencia</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reporteData as $i => $promocion)
            @php
            $hoy = \Carbon\Carbon::today();
            $fechaInicio = \Carbon\Carbon::parse($promocion->fecha_inicio);
            $fechaFin = \Carbon\Carbon::parse($promocion->fecha_fin);

            $estadoVigencia = 'vigente';
            if (!$promocion->estado) {
            $estadoVigencia = 'inactiva';
            } elseif ($fechaFin->lt($hoy)) {
            $estadoVigencia = 'expirada';
            } elseif ($fechaInicio->gt($hoy)) {
            $estadoVigencia = 'futura';
            }
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $promocion->nombre }}</td>
                <td>{{ $promocion->descripcion ? Str::limit($promocion->descripcion, 50) : 'Sin descripción' }}</td>
                <td class="text-center">{{ $promocion->tipo_descuento == 'porcentaje' ? 'Porcentaje' : 'Monto Fijo' }}
                </td>
                <td class="text-center">
                    @if($promocion->tipo_descuento == 'porcentaje')
                    {{ $promocion->valor_descuento }}%
                    @else
                    Bs. {{ number_format($promocion->valor_descuento, 2) }}
                    @endif
                </td>
                <td class="text-center">{{ $fechaInicio->format('d/m/Y') }}</td>
                <td class="text-center">
                    {{ $promocion->productos_count ?? ($promocion->productos ? count($promocion->productos) : 0) }}</td>
                <td class="text-center">
                    {{ $promocion->productos_count ?? ($promocion->productos ? count($promocion->productos) : 0) }}</td>
                <td class="text-center">
                    <span class="badge bg-{{ $promocion->estado ? 'success' : 'danger' }}">
                        {{ $promocion->estado ? 'Activa' : 'Inactiva' }}
                    </span>
                </td>
                <td class="text-center">
                    @switch($estadoVigencia)
                    @case('vigente')<span class="badge bg-success">Vigente</span>@break
                    @case('expirada')<span class="badge bg-danger">Expirada</span>@break
                    @case('futura')<span class="badge bg-info">Futura</span>@break
                    @case('inactiva')<span class="badge bg-secondary">Inactiva</span>@break
                    @endswitch
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">No hay promociones registradas en este periodo</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Generado el: {{ now()->format('d/m/Y H:i') }} | Sistema de Gestión</p>
    </div>
</body>

</html>
