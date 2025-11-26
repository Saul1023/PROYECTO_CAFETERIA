<table class="table table-hover mb-0">
    <thead class="table-light">
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
        @foreach($reporteData as $i => $mesa)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $mesa->nombre }}</td>
            <td>{{ $mesa->ubicacion }}</td>
            <td>{{ $mesa->capacidad }}</td>
            <td>
                <span
                    class="badge bg-{{ $mesa->estado == 'ocupada' ? 'danger' : ($mesa->estado == 'reservada' ? 'warning' : 'success') }}">
                    {{ ucfirst($mesa->estado) }}
                </span>
            </td>
            <td>{{ $mesa->reservaciones_count }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
