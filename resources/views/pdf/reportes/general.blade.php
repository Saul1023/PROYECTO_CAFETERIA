<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Ejecutivo - EL RINCON SABROSITO</title>
    <style>
    /* Reset y configuración base */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 20px;
        padding: 0;
        color: #2d3748;
        font-size: 12px;
        line-height: 1.4;
        background: #ffffff;
    }

    .container {
        max-width: 1000px;
        margin: 0 auto;
        background: white;
    }

    /* Header Rediseñado */
    .header {
        background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
    }

    .header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255, 215, 0, 0.1) 0%, transparent 70%);
    }

    .header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        z-index: 2;
    }

    .brand-section {
        display: flex;
        align-items: center;
        flex: 1;
        gap: 25px;
    }

    .logo-container {
        width: 100px;
        height: 100px;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        padding: 8px;
        flex-shrink: 0;
        border: 3px solid #FFD700;
        position: relative;
    }

    .logo-container::after {
        content: '';
        position: absolute;
        top: -3px;
        left: -3px;
        right: -3px;
        bottom: -3px;
        border: 1px solid rgba(255, 215, 0, 0.5);
        border-radius: 14px;
        pointer-events: none;
    }

    .logo-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #D2691E 0%, #8B4513 100%);
        color: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        font-weight: bold;
    }

    .brand-text {
        flex: 1;
        text-align: left;
    }

    .company-name {
        color: #FFD700;
        font-size: 32px;
        font-weight: 800;
        margin: 0 0 8px 0;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .report-title {
        color: white;
        font-size: 20px;
        font-weight: 600;
        margin: 0 0 6px 0;
        opacity: 0.95;
    }

    .report-subtitle {
        color: rgba(255, 255, 255, 0.85);
        font-size: 14px;
        margin: 0;
        font-weight: 400;
    }

    .header-info {
        text-align: right;
        flex-shrink: 0;
    }

    .period-info {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 15px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .period-label {
        color: rgba(255, 255, 255, 0.9);
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .period-dates {
        font-size: 14px;
        color: white;
        font-weight: 700;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 8px 18px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
    }

    .status-badge::before {
        content: '●';
        margin-right: 8px;
        font-size: 8px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }

        100% {
            opacity: 1;
        }
    }

    /* Estilos de tablas (mantenidos igual) */
    .table-container {
        margin-bottom: 25px;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .table-title {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 15px 20px;
        font-size: 16px;
        font-weight: 700;
        color: #8B4513;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
    }

    .table-title::before {
        content: '📊';
        margin-right: 10px;
        font-size: 16px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background: #f8fafc;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        color: #4a5568;
        border-bottom: 1px solid #e2e8f0;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .data-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 12px;
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .data-table tr:hover {
        background: #f8fafc;
    }

    /* Estilos específicos para KPIs */
    .kpi-value {
        font-weight: 700;
        font-size: 14px;
    }

    .kpi-value.ingresos {
        color: #10b981;
    }

    .kpi-value.reservas {
        color: #3b82f6;
    }

    .kpi-value.inventario {
        color: #f59e0b;
    }

    .kpi-value.clientes {
        color: #8b5cf6;
    }

    .trend-badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 600;
        margin-left: 8px;
    }

    .trend-positive {
        background: #dcfce7;
        color: #166534;
    }

    .trend-negative {
        background: #fef2f2;
        color: #dc2626;
    }

    /* Tabla de métricas */
    .metrics-table .metric-value {
        font-size: 16px;
        font-weight: 800;
        color: #8B4513;
        text-align: center;
    }

    .metrics-table .metric-label {
        text-align: center;
        color: #64748b;
        font-weight: 600;
    }

    /* Análisis ejecutivo */
    .analysis-section {
        background: linear-gradient(135deg, #fef9e7 0%, #fef3c7 100%);
        border: 1px solid #fcd34d;
        border-radius: 8px;
        padding: 20px;
        margin: 20px 0;
    }

    .analysis-title {
        color: #92400e;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
    }

    .analysis-title::before {
        content: '💡';
        margin-right: 8px;
    }

    .analysis-content {
        color: #78350f;
        font-size: 12px;
        line-height: 1.5;
        background: white;
        padding: 15px;
        border-radius: 6px;
        border-left: 3px solid #f59e0b;
    }

    /* Alertas */
    .alerts-section {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 8px;
        padding: 20px;
        margin: 20px 0;
    }

    .alerts-title {
        color: #dc2626;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }

    .alerts-title::before {
        content: '⚠️';
        margin-right: 8px;
    }

    .alert-item {
        background: white;
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 8px;
        border-left: 2px solid #dc2626;
        font-size: 11px;
        color: #7f1d1d;
    }

    /* Footer */
    .footer {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #e5e7eb;
        text-align: center;
        color: #6b7280;
        font-size: 11px;
    }

    .footer-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .footer-notes {
        font-size: 10px;
        color: #9ca3af;
        margin-top: 5px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            text-align: center;
        }

        .brand-section {
            flex-direction: column;
            margin-bottom: 20px;
            gap: 20px;
        }

        .brand-text {
            text-align: center;
        }

        .header-info {
            text-align: center;
        }

        .data-table {
            display: block;
            overflow-x: auto;
        }

        .footer-content {
            flex-direction: column;
            gap: 10px;
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Rediseñado -->
        <div class="header">
            <div class="header-content">
                <div class="brand-section">
                    <div class="logo-container">
                        @php
                        $logoPath = public_path('img/logo.png');
                        if (file_exists($logoPath)) {
                        $imageData = base64_encode(file_get_contents($logoPath));
                        $imageSrc = 'data:image/png;base64,' . $imageData;
                        echo '<img src="' . $imageSrc . '" alt="EL RINCON SABROSITO"
                            style="width: 100%; height: 100%; object-fit: contain;">';
                        } else {
                        echo '<div class="logo-placeholder">☕</div>';
                        }
                        @endphp
                    </div>
                    <div class="brand-text">
                        <div class="company-name">EL RINCON SABROSITO</div>
                        <div class="report-title">REPORTE EJECUTIVO</div>
                        <div class="report-subtitle">Análisis Integral de Gestión y Rendimiento</div>
                    </div>
                </div>
                <div class="header-info">
                    <div class="period-info">
                        <div class="period-label">PERÍODO ANALIZADO</div>
                        <div class="period-dates">{{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</div>
                    </div>
                    <div class="status-badge">SISTEMA OPERATIVO</div>
                </div>
            </div>
        </div>

        <!-- Tabla de KPIs Principales -->
        <div class="table-container">
            <div class="table-title">MÉTRICAS PRINCIPALES</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 40%">INDICADOR</th>
                        <th style="width: 25%">VALOR</th>
                        <th style="width: 35%">DETALLES</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>💰 Ingresos Totales</strong>
                        </td>
                        <td>
                            <span class="kpi-value ingresos">Bs
                                {{ number_format($estadisticas['ventas']['ingresos'] ?? 0, 2) }}</span>
                        </td>
                        <td>
                            {{ $estadisticas['ventas']['total'] ?? 0 }} transacciones procesadas
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>📅 Reservaciones</strong>
                        </td>
                        <td>
                            <span class="kpi-value reservas">{{ $estadisticas['reservaciones']['total'] ?? 0 }}</span>
                        </td>
                        <td>
                            {{ $estadisticas['reservaciones']['confirmadas'] ?? 0 }} confirmadas
                            <span class="trend-badge trend-positive">
                                {{ ($estadisticas['reservaciones']['total'] ?? 0) > 0 ? round((($estadisticas['reservaciones']['confirmadas'] ?? 0) / ($estadisticas['reservaciones']['total'] ?? 1)) * 100, 1) : 0 }}%
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>📦 Inventario</strong>
                        </td>
                        <td>
                            <span class="kpi-value inventario">{{ $estadisticas['productos']['total'] ?? 0 }}</span>
                        </td>
                        <td>
                            {{ $estadisticas['productos']['stock_bajo'] ?? 0 }} alertas de stock
                            <span
                                class="trend-badge {{ ($estadisticas['productos']['stock_bajo'] ?? 0) > 0 ? 'trend-negative' : 'trend-positive' }}">
                                {{ ($estadisticas['productos']['total'] ?? 0) > 0 ? round((($estadisticas['productos']['total'] ?? 0) - ($estadisticas['productos']['stock_bajo'] ?? 0)) / ($estadisticas['productos']['total'] ?? 1) * 100, 1) : 100 }}%
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>👥 Clientes</strong>
                        </td>
                        <td>
                            <span class="kpi-value clientes">{{ $estadisticas['usuarios']['total'] ?? 0 }}</span>
                        </td>
                        <td>
                            {{ $estadisticas['usuarios']['nuevos'] ?? 0 }} nuevos clientes
                            <span class="trend-badge trend-positive">
                                {{ ($estadisticas['usuarios']['total'] ?? 0) > 0 ? round((($estadisticas['usuarios']['nuevos'] ?? 0) / ($estadisticas['usuarios']['total'] ?? 1)) * 100, 1) : 0 }}%
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Tabla de Indicadores de Eficiencia -->
        <div class="table-container">
            <div class="table-title">INDICADORES DE EFICIENCIA</div>
            <table class="data-table metrics-table">
                <thead>
                    <tr>
                        <th style="width: 33%">VOLUMEN DE VENTAS</th>
                        <th style="width: 34%">TASA DE CONVERSIÓN</th>
                        <th style="width: 33%">SALUD INVENTARIO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="metric-value">{{ $estadisticas['ventas']['total'] ?? 0 }}</div>
                            <div class="metric-label">Transacciones</div>
                        </td>
                        <td>
                            <div class="metric-value">
                                {{ ($estadisticas['reservaciones']['total'] ?? 0) > 0 ? round((($estadisticas['reservaciones']['confirmadas'] ?? 0) / ($estadisticas['reservaciones']['total'] ?? 1)) * 100, 1) : 0 }}%
                            </div>
                            <div class="metric-label">Reservas Confirmadas</div>
                        </td>
                        <td>
                            <div class="metric-value">
                                {{ ($estadisticas['productos']['total'] ?? 0) > 0 ? round((($estadisticas['productos']['total'] ?? 0) - ($estadisticas['productos']['stock_bajo'] ?? 0)) / ($estadisticas['productos']['total'] ?? 1) * 100, 1) : 100 }}%
                            </div>
                            <div class="metric-label">Productos Estables</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Análisis Ejecutivo -->
        <div class="analysis-section">
            <div class="analysis-title">ANÁLISIS EJECUTIVO</div>
            <div class="analysis-content">
                @php
                $ingresos = $estadisticas['ventas']['ingresos'] ?? 0;
                $ventasTotal = $estadisticas['ventas']['total'] ?? 0;
                $reservasConfirmadas = $estadisticas['reservaciones']['confirmadas'] ?? 0;
                $stockBajo = $estadisticas['productos']['stock_bajo'] ?? 0;

                if ($ingresos > 1000) {
                echo "✅ <strong>Excelente desempeño:</strong> Bs " . number_format($ingresos, 2) . " en " . $ventasTotal
                . " ventas.";
                } elseif ($ingresos > 0) {
                echo "📈 <strong>Actividad positiva:</strong> Bs " . number_format($ingresos, 2) . " en ingresos.";
                } elseif ($ventasTotal > 0) {
                echo "🛒 <strong>Movimiento comercial:</strong> " . $ventasTotal . " transacciones.";
                } elseif ($reservasConfirmadas > 0) {
                echo "📋 <strong>Demanda:</strong> " . $reservasConfirmadas . " reservas confirmadas.";
                } else {
                echo "⏳ <strong>Evaluación:</strong> Sin actividad comercial registrada.";
                }

                if ($stockBajo > 0) {
                echo " ⚠️ <strong>Alerta:</strong> " . $stockBajo . " productos con stock bajo.";
                } else {
                echo " ✅ <strong>Inventario estable.</strong>";
                }
                @endphp
            </div>
        </div>

        <!-- Alertas -->
        @if(($estadisticas['productos']['stock_bajo'] ?? 0) > 0)
        <div class="alerts-section">
            <div class="alerts-title">ACCIONES REQUERIDAS</div>
            <div class="alert-item">
                <strong>Gestión de Inventario:</strong> Revisar {{ $estadisticas['productos']['stock_bajo'] ?? 0 }}
                productos con stock bajo.
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="footer-content">
                <div><strong>Generado:</strong> {{ now()->format('d/m/Y H:i') }}</div>
                <div><strong>Período:</strong> {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} -
                    {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</div>
            </div>
            <div class="footer-notes">
                Sistema de Gestión - EL RINCON SABROSITO | Reporte generado automáticamente
            </div>
        </div>
    </div>
</body>

</html>
