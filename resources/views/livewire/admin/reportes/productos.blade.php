<table class="table table-hover mb-0">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Producto</th>
            <th>Categoría</th>
            <th>Stock</th>
            <th>Mínimo</th>
            <th>Precio (Bs.)</th>
            <th>Estado</th>
            <th>Fecha Alta</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reporteData as $i => $prod)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $prod->nombre }}</td>
                <td>{{ $prod->categoria->nombre ?? '-' }}</td>
                <td>{{ $prod->stock }}</td>
                <td>{{ $prod->stock_minimo }}</td>
                <td>{{ number_format($prod->precio, 2) }}</td>
                <td>
                    <span class="badge bg-{{ $prod->estado ? 'success' : 'danger' }}">
                        {{ $prod->estado ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td>{{ $prod->fecha_creacion }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
