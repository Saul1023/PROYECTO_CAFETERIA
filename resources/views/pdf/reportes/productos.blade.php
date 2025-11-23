<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Productos</title>
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
        padding: 8px;
        text-align: left;
    }

    .table th {
        background-color: #f5f5f5;
        font-weight: bold;
    }

    .estadisticas {
        margin-bottom: 20px;
        padding: 15px;
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

    .text-right {
        text-align: right;
    }
    </style>
</head>

<body>
    <div class="header">
        <h1>Reporte de Productos</h1>
        <div class="info-periodo">
            <strong>Periodo:</strong> {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} -
            {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}
        </div>
    </div>

    @if(isset($estadisticas) && !empty($estadisticas))
    <div class="estadisticas">
        <h3>Estadísticas de Productos</h3>
        <table style="width: 100%;">
            <tr>
                <td><strong>Total Productos:</strong> {{ $estadisticas['total'] ?? 0 }}</td>
                <td><strong>Activos:</strong> {{ $estadisticas['activos'] ?? 0 }}</td>
                <td><strong>Sin Stock:</strong> {{ $estadisticas['sin_stock'] ?? 0 }}</td>
                <td><strong>Stock Bajo:</strong> {{ $estadisticas['stock_bajo'] ?? 0 }}</td>
                <td><strong>Valor Inventario:</strong> Bs {{ number_format($estadisticas['valor_inventario'] ?? 0, 2) }}
                </td>
            </tr>
        </table>
    </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Stock Mínimo</th>
                <th>Estado</th>
                <th>Valor Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reporteData as $i => $producto)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $producto->nombre }}</td>
                <td>{{ $producto->categoria->nombre ?? 'Sin categoría' }}</td>
                <td class="text-right">Bs {{ number_format($producto->precio, 2) }}</td>
                <td class="text-center {{ $producto->stock <= $producto->stock_minimo ? 'text-danger' : '' }}">
                    {{ $producto->stock }}
                </td>
                <td class="text-center">{{ $producto->stock_minimo }}</td>
                <td class="text-center">
                    {{ $producto->estado ? 'Activo' : 'Inactivo' }}
                </td>
                <td class="text-right">Bs {{ number_format($producto->precio * $producto->stock, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">No hay productos registrados en este periodo</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Generado el: {{ now()->format('d/m/Y H:i') }} | Sistema de Gestión</p>
    </div>
</body>

</html>
