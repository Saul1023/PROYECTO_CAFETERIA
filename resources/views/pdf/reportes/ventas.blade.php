<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas - EL RINCON SABROSITO</title>
    <style>
    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 12px;
        margin: 20px;
        color: #333;
    }

    .header {
        background: #8B4513;
        color: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
    }

    .header h1 {
        margin: 0 0 10px 0;
        font-size: 24px;
    }

    .header h2 {
        margin: 0 0 15px 0;
        font-size: 18px;
    }

    .header p {
        margin: 5px 0;
        font-size: 14px;
    }

    .estadisticas {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #8B4513;
    }

    .estadisticas h3 {
        margin-top: 0;
        color: #8B4513;
        border-bottom: 1px solid #ddd;
        padding-bottom: 8px;
    }

    .estadisticas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 10px;
    }

    .estadistica-item {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
    }

    .estadistica-valor {
        font-weight: bold;
        color: #8B4513;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    .table th {
        background: #8B4513;
        color: white;
        padding: 12px 10px;
        text-align: left;
        font-weight: bold;
    }

    .table td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    .table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .text-muted {
        color: #6c757d;
    }

    .badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: bold;
    }

    .badge-cash {
        background: #28a745;
        color: white;
    }

    .badge-card {
        background: #007bff;
        color: white;
    }

    .badge-transfer {
        background: #6f42c1;
        color: white;
    }

    .badge-other {
        background: #6c757d;
        color: white;
    }

    .footer {
        text-align: center;
        color: #666;
        font-size: 10px;
        margin-top: 30px;
        padding-top: 15px;
        border-top: 1px solid #ddd;
    }

    .no-data {
        text-align: center;
        padding: 20px;
        color: #6c757d;
        font-style: italic;
    }

    .error-message {
        text-align: center;
        padding: 20px;
        color: #dc3545;
        background-color: #f8d7da;
        border-radius: 4px;
        margin: 10px 0;
    }

    .section-title {
        color: #8B4513;
        border-bottom: 2px solid #8B4513;
        padding-bottom: 5px;
        margin: 25px 0 15px 0;
        font-size: 16px;
    }
    </style>
</head>

<body>
    <div class="header">
        <h1>EL RINCON SABROSITO</h1>
        <h2>Reporte de Ventas</h2>
        <p>Período: {{ \Carbon\Carbon::parse($fechaInicio ?? now()->startOfMonth())->format('d/m/Y') }} -
            {{ \Carbon\Carbon::parse($fechaFin ?? now())->format('d/m/Y') }}</p>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Sección de Estadísticas -->
    <div class="estadisticas">
        <h3>Estadísticas de Ventas</h3>
        <div class="estadisticas-grid">
            <div class="estadistica-item">
                <span>Total Ventas:</span>
                <span class="estadistica-valor">{{ $estadisticas['total_ventas'] ?? 0 }}</span>
            </div>
            <div class="estadistica-item">
                <span>Ingresos Totales:</span>
                <span class="estadistica-valor">Bs.
                    {{ number_format($estadisticas['ingresos_totales'] ?? 0, 2) }}</span>
            </div>
            <div class="estadistica-item">
                <span>Subtotal:</span>
                <span class="estadistica-valor">Bs. {{ number_format($estadisticas['subtotal'] ?? 0, 2) }}</span>
            </div>
            <div class="estadistica-item">
                <span>Descuentos:</span>
                <span class="estadistica-valor">Bs. {{ number_format($estadisticas['descuentos'] ?? 0, 2) }}</span>
            </div>
            <div class="estadistica-item">
                <span>Ticket Promedio:</span>
                <span class="estadistica-valor">Bs. {{ number_format($estadisticas['ticket_promedio'] ?? 0, 2) }}</span>
            </div>
            <div class="estadistica-item">
                <span>Productos Vendidos:</span>
                <span class="estadistica-valor">{{ $estadisticas['productos_vendidos'] ?? 0 }}</span>
            </div>
        </div>
    </div>

    <!-- Sección de Métodos de Pago -->
    @if(isset($estadisticas['por_metodo_pago']) && count($estadisticas['por_metodo_pago']) > 0)
    <div class="estadisticas">
        <h3>Ventas por Método de Pago</h3>
        <div class="estadisticas-grid">
            @foreach($estadisticas['por_metodo_pago'] as $metodo)
            <div class="estadistica-item">
                <span>{{ $metodo->metodo_pago }}:</span>
                <span class="estadistica-valor">
                    {{ $metodo->total }} ventas (Bs. {{ number_format($metodo->monto, 2) }})
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Sección de Datos Detallados -->
    <h3 class="section-title">Datos Detallados de Ventas</h3>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Método Pago</th>
                <th class="text-right">Subtotal (Bs.)</th>
                <th class="text-right">Descuento</th>
                <th class="text-right">Total (Bs.)</th>
            </tr>
        </thead>
        <tbody>
            @php
            $ventasValidas = [];
            if (isset($reporteData) && is_iterable($reporteData)) {
            foreach ($reporteData as $item) {
            if (is_object($item)) {
            $ventasValidas[] = $item;
            }
            }
            }
            @endphp

            @if(count($ventasValidas) > 0)
            @foreach($ventasValidas as $venta)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if(!empty($venta->fecha_venta))
                    {{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y H:i') }}
                    @else
                    <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>
                    @php
                    $nombreUsuario = 'No asignado';
                    if (isset($venta->usuario) && is_object($venta->usuario)) {
                    $nombreUsuario = $venta->usuario->nombre_completo ??
                    $venta->usuario->nombre ??
                    $venta->usuario->name ??
                    'Usuario #' . ($venta->usuario->id_usuario ?? $venta->id_usuario ?? 'N/A');
                    } elseif (isset($venta->id_usuario)) {
                    $nombreUsuario = 'Usuario #' . $venta->id_usuario;
                    }
                    @endphp
                    {{ $nombreUsuario }}
                </td>
                <td>
                    @php
                    $metodoPago = strtolower($venta->metodo_pago ?? '');
                    $badgeClass = 'badge-other';
                    if (str_contains($metodoPago, 'efectivo')) $badgeClass = 'badge-cash';
                    elseif (str_contains($metodoPago, 'tarjeta')) $badgeClass = 'badge-card';
                    elseif (str_contains($metodoPago, 'transferencia')) $badgeClass = 'badge-transfer';
                    @endphp
                    <span class="badge {{ $badgeClass }}">
                        {{ $venta->metodo_pago ?? 'N/A' }}
                    </span>
                </td>
                <td class="text-right">{{ number_format($venta->subtotal ?? 0, 2) }}</td>
                <td class="text-right">{{ number_format($venta->descuento ?? 0, 2) }}</td>
                <td class="text-right"><strong>{{ number_format($venta->total ?? 0, 2) }}</strong></td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="7" class="no-data">
                    @if(!isset($reporteData) || !is_iterable($reporteData))
                    <div class="error-message">
                        Error: Los datos del reporte no son válidos
                    </div>
                    @else
                    No hay ventas registradas en el período seleccionado
                    @endif
                </td>
            </tr>
            @endif
        </tbody>
        @if(count($ventasValidas) > 0)
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <td colspan="4" class="text-right">TOTALES:</td>
                <td class="text-right">Bs. {{ number_format(collect($ventasValidas)->sum('subtotal'), 2) }}</td>
                <td class="text-right">Bs. {{ number_format(collect($ventasValidas)->sum('descuento'), 2) }}</td>
                <td class="text-right">Bs. {{ number_format(collect($ventasValidas)->sum('total'), 2) }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        <p>Sistema de Gestión - EL RINCON SABROSITO</p>
        <p>Página 1 de 1</p>
    </div>
</body>

</html>
