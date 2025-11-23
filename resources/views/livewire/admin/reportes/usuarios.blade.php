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
        @foreach($reporteData as $usuario)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $usuario->nombre_completo ?? 'N/A' }}</td> {{-- ← CORREGIDO --}}
            <td>{{ $usuario->email ?? $usuario->nombre_usuario ?? 'N/A' }}</td> {{-- ← CORREGIDO --}}
            <td>{{ $usuario->rol->nombre ?? 'Sin rol' }}</td>
            <td>
                <span class="badge bg-{{ $usuario->estado ? 'success' : 'danger' }}">
                    {{ $usuario->estado ? 'Activo' : 'Inactivo' }}
                </span>
            </td>
            <td>
                @if(isset($usuario->fecha_creacion))
                {{ \Carbon\Carbon::parse($usuario->fecha_creacion)->format('d/m/Y H:i') }}
                @else
                N/A
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
