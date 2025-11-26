<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Reservaciones - EL RINCON SABROSITO</title>
    <style>
    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 12px;
        margin: 20px;
    }

    .header {
        background: #8B4513;
        color: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .table th,
    .table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .table th {
        background: #f8f9fa;
        font-weight: bold;
    }

    .badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 10px;
        color: white;
    }

    .bg-primary {
        background: #007bff;
    }

    .bg-success {
        background: #28a745;
    }

    .bg-warning {
        background: #ffc107;
        color: black;
    }

    .bg-danger {
        background: #dc3545;
    }

    .bg-info {
        background: #17a2b8;
    }

    .estadisticas {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    </style>
</head>

<body>
    <div class="header">
        <h1>EL RINCON SABROSITO</h1>
        <h2>Reporte de Reservaciones</h2>
        <p>Período: {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} -
            {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</p>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="estadisticas">
        <h3>Estadísticas Generales</h3>
        <p><strong>Total Reservaciones:</strong> {{ $estadisticas['total'] ?? 0 }}</p>
        <p><strong>Pendientes:</strong> {{ $estadisticas['pendientes'] ?? 0 }}</p>
        <p><strong>Confirmadas:</strong> {{ $estadisticas['confirmadas'] ?? 0 }}</p>
        <p><strong>Completadas:</strong> {{ $estadisticas['completadas'] ?? 0 }}</p>
        <p><strong>Canceladas:</strong> {{ $estadisticas['canceladas'] ?? 0 }}</p>
        <p><strong>No Asistió:</strong> {{ $estadisticas['no_asistio'] ?? 0 }}</p>
        <p><strong>Total Personas:</strong> {{ $estadisticas['personas_total'] ?? 0 }}</p>
        <p><strong>Promedio por Reserva:</strong> {{ $estadisticas['promedio_personas'] ?? 0 }} personas</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Mesa</th>
                <th>Fecha</th>
                <th>Personas</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reporteData as $res)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $res->usuario->nombre_completo ?? 'No registrado' }}</td>
                <td>{{ $res->mesa->numero_mesa ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($res->fecha_reservacion)->format('d/m/Y H:i') }}</td>
                <td>{{ $res->numero_personas }}</td>
                <td>
                    @php
                    $estadoClase = [
                    'pendiente' => 'bg-warning',
                    'confirmada' => 'bg-success',
                    'completada' => 'bg-primary',
                    'cancelada' => 'bg-danger',
                    'no_asistio' => 'bg-info'
                    ][$res->estado] ?? 'bg-secondary';
                    @endphp
                    <span class="badge {{ $estadoClase }}">
                        {{ ucfirst($res->estado) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="text-align: center; color: #666; font-size: 10px; margin-top: 30px;">
        <p>Sistema de Gestión - EL RINCON SABROSITO</p>
    </div>
</body>

</html>