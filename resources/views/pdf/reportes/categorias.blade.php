<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Categorías - EL RINCON SABROSITO</title>
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
        <h2>Reporte de Categorías</h2>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="estadisticas">
        <h3>Estadísticas Generales</h3>
        <p><strong>Total Categorías:</strong> {{ $estadisticas['total'] ?? 0 }}</p>
        <p><strong>Categorías Activas:</strong> {{ $estadisticas['activas'] ?? 0 }}</p>
        <p><strong>Con Productos:</strong> {{ $estadisticas['con_productos'] ?? 0 }}</p>
        <p><strong>Sin Productos:</strong> {{ $estadisticas['sin_productos'] ?? 0 }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Total Productos</th>
                <th>Fecha Creación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reporteData as $categoria)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $categoria->nombre ?? 'N/A' }}</td>
                <td>{{ $categoria->descripcion ?? 'Sin descripción' }}</td>
                <td>
                    <span class="badge bg-{{ ($categoria->estado ?? false) ? 'success' : 'danger' }}">
                        {{ ($categoria->estado ?? false) ? 'Activa' : 'Inactiva' }}
                    </span>
                </td>
                <td>{{ $categoria->productos_count ?? 0 }}</td>
                <td>
                    @if(isset($categoria->fecha_creacion))
                    {{ \Carbon\Carbon::parse($categoria->fecha_creacion)->format('d/m/Y H:i') }}
                    @else
                    N/A
                    @endif
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
