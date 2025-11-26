<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Mesas - EL RINCON SABROSITO</title>
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
    }

    .bg-success {
        background: #28a745;
        color: white;
    }

    .bg-danger {
        background: #dc3545;
        color: white;
    }

    .bg-warning {
        background: #ffc107;
        color: black;
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
        <h2>Reporte de Mesas</h2>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="estadisticas">
        <h3>Estadísticas Generales</h3>
        <p><strong>Total Mesas:</strong> {{ $estadisticas['total'] ?? 0 }}</p>
        <p><strong>Mesas Activas:</strong> {{ $estadisticas['activas'] ?? 0 }}</p>
        <p><strong>Disponibles:</strong> {{ $estadisticas['disponibles'] ?? 0 }}</p>
        <p><strong>Ocupadas:</strong> {{ $estadisticas['ocupadas'] ?? 0 }}</p>
        <p><strong>Reservadas:</strong> {{ $estadisticas['reservadas'] ?? 0 }}</p>
        <p><strong>Reservaciones en Período:</strong> {{ $estadisticas['reservaciones_periodo'] ?? 0 }}</p>
        <p><strong>Capacidad Total:</strong> {{ $estadisticas['capacidad_total'] ?? 0 }} personas</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Mesa</th>
                <th>Ubicación</th>
                <th>Capacidad</th>
                <th>Estado</th>
                <th>Reservaciones (Periodo)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reporteData as $mesa)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $mesa->nombre ?? 'N/A' }}</td>
                <td>{{ $mesa->ubicacion ?? 'N/A' }}</td>
                <td>{{ $mesa->capacidad ?? 0 }}</td>
                <td>
                    <span
                        class="badge bg-{{ $mesa->estado == 'ocupada' ? 'danger' : ($mesa->estado == 'reservada' ? 'warning' : 'success') }}">
                        {{ ucfirst($mesa->estado) }}
                    </span>
                </td>
                <td>{{ $mesa->reservaciones_count ?? 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="text-align: center; color: #666; font-size: 10px; margin-top: 30px;">
        <p>Sistema de Gestión - EL RINCON SABROSITO</p>
    </div>
</body>

</html>