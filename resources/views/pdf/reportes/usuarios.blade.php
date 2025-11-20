<h2>Reporte de Usuarios</h2>
<p>Periodo: {{ $fechaInicio }} - {{ $fechaFin }}</p>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Fecha Registro</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reporteData as $u)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $u->nombre }}</td>
                <td>{{ $u->rol->nombre ?? '-' }}</td>
                <td>{{ $u->estado ? 'Activo' : 'Inactivo' }}</td>
                <td>{{ $u->fecha_creacion }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
