<table class="table table-hover mb-0">
    <thead class="table-light">
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
        @foreach($reporteData as $i => $usuario)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $usuario->nombre }}</td>
                <td>{{ $usuario->email ?? $usuario->usuario }}</td>
                <td>{{ $usuario->rol->nombre ?? 'Sin rol' }}</td>
                <td>
                    <span class="badge bg-{{ $usuario->estado ? 'success' : 'danger' }}">
                        {{ $usuario->estado ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td>{{ $usuario->fecha_creacion }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
