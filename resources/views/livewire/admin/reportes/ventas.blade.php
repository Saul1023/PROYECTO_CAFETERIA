<table class="table table-hover mb-0">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Usuario</th>
            <th>Método Pago</th>
            <th>Subtotal (Bs.)</th>
            <th>Descuento</th>
            <th>Total (Bs.)</th>
        </tr>
    </thead>
    <tbody>
        @php
        $ventasValidas = [];
        if (is_iterable($reporteData)) {
        foreach ($reporteData as $item) {
        if (is_object($item) && property_exists($item, 'fecha_venta')) {
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
                N/A
                @endif
            </td>
            <td>
                @if(isset($venta->usuario) && is_object($venta->usuario))
                {{ $venta->usuario->nombre ?? 'No asignado' }}
                @else
                No asignado
                @endif
            </td>
            <td>{{ $venta->metodo_pago ?? 'N/A' }}</td>
            <td>{{ number_format($venta->subtotal ?? 0, 2) }}</td>
            <td>{{ number_format($venta->descuento ?? 0, 2) }}</td>
            <td>{{ number_format($venta->total ?? 0, 2) }}</td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="7" class="text-center text-muted">
                @if(!is_iterable($reporteData))
                Error: Los datos del reporte no son válidos
                @else
                No hay ventas registradas en el período seleccionado
                @endif
            </td>
        </tr>
        @endif
    </tbody>
</table>
