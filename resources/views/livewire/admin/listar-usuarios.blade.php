<div class="container-fluid p-4 bg-light min-vh-100">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="h2 text-dark mb-1">Gestión de Usuarios</h1>
            <p class="text-muted mb-0">Administra los usuarios del sistema</p>
        </div>
        <a href="{{ route('admin.usuarios.crear') }}"
            class="btn btn-primary d-flex align-items-center">
            <i class="bi bi-plus-circle me-2"></i>
            Nuevo Usuario
        </a>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <!-- Búsqueda -->
                <div class="col-md-4">
                    <label class="form-label">Buscar</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text"
                            wire:model.live="search"
                            placeholder="Buscar por nombre, usuario o email..."
                            class="form-control">
                    </div>
                </div>

                <!-- Filtro por Rol -->
                <div class="col-md-4">
                    <label class="form-label">Filtrar por Rol</label>
                    <select wire:model.live="filterRol" class="form-select">
                        <option value="">Todos los roles</option>
                        @foreach($roles as $rol)
                        <option value="{{ $rol->id_rol }}">{{ $rol->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por Estado -->
                <div class="col-md-4">
                    <label class="form-label">Filtrar por Estado</label>
                    <select wire:model.live="filterEstado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="1">Activos</option>
                        <option value="0">Inactivos</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Usuario</th>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                        <tr>
                            <td class="ps-4">
                                <strong>{{ $usuario->nombre_usuario }}</strong>
                            </td>
                            <td>{{ $usuario->nombre_completo }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $usuario->rol->nombre }}
                                </span>
                            </td>
                            <td>
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        role="switch"
                                        {{ $usuario->estado ? 'checked' : '' }}
                                        wire:click="cambiarEstado({{ $usuario->id_usuario }})">
                                </div>
                                <small class="text-muted ms-2">
                                    {{ $usuario->estado ? 'Activo' : 'Inactivo' }}
                                </small>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button wire:click="editar({{ $usuario->id_usuario }})"
                                        class="btn btn-sm btn-outline-primary d-flex align-items-center">
                                        <i class="bi bi-pencil me-1"></i>
                                        Editar
                                    </button>
                                    <button wire:click="confirmarEliminacion({{ $usuario->id_usuario }})"
                                        class="btn btn-sm btn-outline-danger d-flex align-items-center">
                                        <i class="bi bi-trash me-1"></i>
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-people display-4 d-block mb-3"></i>
                                    <h5>No se encontraron usuarios</h5>
                                    <p class="mb-0">Intenta ajustar los filtros de búsqueda</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($usuarios->hasPages())
            <div class="card-footer bg-transparent">
                {{ $usuarios->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    @if($usuarioEliminar)
    <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5)" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Confirmar Eliminación
                    </h5>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-person-x display-4 text-danger mb-3"></i>
                    <h6>¿Estás seguro de que deseas eliminar este usuario?</h6>
                    <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button wire:click="eliminar"
                        class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>
                        Eliminar
                    </button>
                    <button wire:click="$set('usuarioEliminar', null)"
                        class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-2"></i>
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Mensajes Flash -->
    @if (session()->has('success'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show" role="alert">
            <div class="toast-header bg-success text-white">
                <i class="bi bi-check-circle me-2"></i>
                <strong class="me-auto">Éxito</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>
    @endif
</div>