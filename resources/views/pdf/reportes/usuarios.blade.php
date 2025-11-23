<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Usuarios - EL RINCON SABROSITO</title>
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

    .bg-success {
        background: #28a745;
    }

    .bg-danger {
        background: #dc3545;
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
        <h2>Reporte de Usuarios</h2>
        <p>Período: {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} -
            {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</p>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="estadisticas">
        <h3>Estadísticas Generales</h3>
        <p><strong>Total Usuarios:</strong> {{ $estadisticas['total'] ?? 0 }}</p>
        <p><strong>Activos:</strong> {{ $estadisticas['activos'] ?? 0 }}</p>
        <p><strong>Inactivos:</strong> {{ $estadisticas['inactivos'] ?? 0 }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Email / Usuario</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Fecha Registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reporteData as $usuario)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $usuario->nombre_completo ?? 'N/A' }}</td> {{-- ← CORREGIDO --}}
                <td>{{ $usuario->email ?? $usuario->nombre_usuario ?? 'N/A' }}</td> {{-- ← CORREGIDO --}}
                <td>{{ $usuario->rol->nombre ?? 'Sin rol' }}</td>
                <td>
                    <span class="badge bg-{{ $usuario->estado ? 'success' : 'danger' }}">
                        {{ $usuario->estado ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td>
                    @if(isset($usuario->fecha_creacion))
                    {{ \Carbon\Carbon::parse($usuario->fecha_creacion)->format('d/m/Y H:i') }}
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
