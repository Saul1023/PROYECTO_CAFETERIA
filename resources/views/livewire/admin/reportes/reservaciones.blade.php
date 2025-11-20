<table class="table table-hover mb-0">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Mesa</th>
            <th>Fecha</th>
            <th>Personas</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reporteData as $i => $res)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $res->usuario->nombre ?? 'No registrado' }}</td>
                <td>{{ $res->mesa->nombre ?? '-' }}</td>
                <td>{{ $res->fecha_reservacion }}</td>
                <td>{{ $res->numero_personas }}</td>
                <td>
                    <span class="badge bg-primary">
                        {{ ucfirst($res->estado) }}
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
