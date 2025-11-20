<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Comprobante de Venta - {{ $numero_venta }}</title>
    <style>
    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 12px;
        line-height: 1.4;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #333;
        padding-bottom: 10px;
    }

    .empresa-nombre {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .empresa-info {
        font-size: 10px;
        color: #666;
    }

    .comprobante-info {
        margin-bottom: 15px;
    }

    .comprobante-info table {
        width: 100%;
        border-collapse: collapse;
    }

    .comprobante-info td {
        padding: 3px 0;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }

    .items-table th {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
        font-weight: bold;
    }

    .items-table td {
        border: 1px solid #ddd;
        padding: 8px;
    }

    .totales {
        margin-top: 20px;
        border-top: 2px solid #333;
        padding-top: 10px;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }

    .total-final {
        font-weight: bold;
        font-size: 14px;
        border-top: 1px solid #333;
        padding-top: 5px;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .promocion {
        color: #28a745;
        font-size: 10px;
    }

    .observaciones {
        margin-top: 20px;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 5px;
    }

    .footer {
        margin-top: 30px;
        text-align: center;
        font-size: 10px;
        color: #666;
        border-top: 1px solid #ddd;
        padding-top: 10px;
    }
    </style>
</head>

<body>
    <div class="header">
        <div class="empresa-nombre">{{ $empresa['nombre'] }}</div>
        <div class="empresa-info">
            {{ $empresa['direccion'] }} | Tel: {{ $empresa['telefono'] }} | NIT: {{ $empresa['nit'] }}
        </div>
        <div style="font-size: 14px; font-weight: bold; margin-top: 10px;">COMPROBANTE DE VENTA</div>
    </div>

    <div class="comprobante-info">
        <table>
            <tr>
                <td><strong>N° Venta:</strong> {{ $numero_venta }}</td>
                <td><strong>N° Pedido:</strong> {{ $numero_pedido }}</td>
            </tr>
            <tr>
                <td><strong>Fecha:</strong> {{ $fecha }}</td>
                <td><strong>Método Pago:</strong> {{ ucfirst($metodo_pago) }}</td>
            </tr>
            <tr>
                <td><strong>Cliente:</strong> {{ $cliente }}</td>
                <td><strong>Vendedor:</strong> {{ $vendedor }}</td>
            </tr>
            <tr>
                <td><strong>Tipo Consumo:</strong> {{ $tipo_consumo === 'mesa' ? 'En Mesa' : 'Para Llevar' }}</td>
                <td><strong>Mesa:</strong> {{ $mesa }}</td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">Cant</th>
                <th width="45%">Producto</th>
                <th width="20%">P. Unitario</th>
                <th width="15%">Desc.</th>
                <th width="15%" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item['cantidad'] }}</td>
                <td>
                    {{ $item['nombre'] }}
                    @if($item['tiene_promocion'])
                    <div class="promocion">(PROMOCIÓN APLICADA)</div>
                    @endif
                </td>
                <td>Bs. {{ number_format($item['precio_unitario'], 2) }}</td>
                <td>
                    @if($item['tiene_promocion'])
                    Bs. {{ number_format($item['descuento_aplicado'], 2) }}
                    @else
                    Bs. 0.00
                    @endif
                </td>
                <td class="text-right">Bs. {{ number_format($item['subtotal'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totales">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>Bs. {{ number_format($subtotal, 2) }}</span>
        </div>
        @if($descuento_promociones > 0)
        <div class="total-row">
            <span>Descuento Promociones:</span>
            <span>-Bs. {{ number_format($descuento_promociones, 2) }}</span>
        </div>
        @endif
        @if($descuento_manual > 0)
        <div class="total-row">
            <span>Descuento Manual:</span>
            <span>-Bs. {{ number_format($descuento_manual, 2) }}</span>
        </div>
        @endif
        <div class="total-row total-final">
            <span><strong>TOTAL:</strong></span>
            <span><strong>Bs. {{ number_format($total, 2) }}</strong></span>
        </div>
    </div>

    @if($observaciones)
    <div class="observaciones">
        <strong>Observaciones:</strong> {{ $observaciones }}
    </div>
    @endif

    <div class="footer">
        <div class="footer-message">
            ¡Gracias por su preferencia! Esperamos verle pronto.
        </div>
        <div>Comprobante generado automáticamente el {{ $fecha_generacion ?? date('d/m/Y \a \l\a\s H:i:s') }}</div>
        <div class="legal-text">
            Este documento es un comprobante de venta generado electrónicamente.<br>
            {{ $empresa['nombre'] }} - {{ $empresa['direccion'] }} - {{ $empresa['telefono'] }}
        </div>
    </div>
</body>

</html>