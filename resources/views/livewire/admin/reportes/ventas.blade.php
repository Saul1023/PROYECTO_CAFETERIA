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
        @foreach($reporteData as $i => $venta)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $venta->fecha_venta }}</td>
                <td>{{ $venta->usuario->nombre ?? 'No asignado' }}</td>
                <td>{{ $venta->metodo_pago }}</td>
                <td>{{ number_format($venta->subtotal, 2) }}</td>
                <td>{{ number_format($venta->descuento, 2) }}</td>
                <td>{{ number_format($venta->total, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
