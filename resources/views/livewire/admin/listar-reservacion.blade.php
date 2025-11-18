<div class="container-fluid p-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                <div class="mb-3 mb-md-0">
                    <h1 class="h2 text-brown mb-1">Gestión de Reservaciones</h1>
                    <p class="text-muted mb-0">Administra las reservaciones de mesas</p>
                </div>
                <button wire:click="openModal" class="btn btn-primary d-flex align-items-center">
                    <i class="bi bi-plus-circle me-2"></i>
                    Nueva Reservación
                </button>
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
                                <input type="text" wire:model.live="search" placeholder="Buscar por mesa, cliente..."
                                    class="form-control">
                            </div>
                        </div>

                        <!-- Filtro por Estado -->
                        <div class="col-md-4">
                            <label class="form-label">Filtrar por Estado</label>
                            <select wire:model.live="filterEstado" class="form-select">
                                <option value="">Todos los estados</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="confirmada">Confirmada</option>
                                <option value="completada">Completada</option>
                                <option value="cancelada">Cancelada</option>
                                <option value="no_asistio">No Asistió</option>
                            </select>
                        </div>

                        <!-- Filtro por Fecha -->
                        <div class="col-md-4">
                            <label class="form-label">Filtrar por Fecha</label>
                            <select wire:model.live="filterFecha" class="form-select">
                                <option value="">Todas las fechas</option>
                                <option value="hoy">Hoy</option>
                                <option value="futuras">Futuras</option>
                                <option value="pasadas">Pasadas</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Reservaciones -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Reservación</th>
                                    <th>Mesa</th>
                                    <th>Cliente</th>
                                    <th>Fecha y Hora</th>
                                    <th>Personas</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reservaciones as $reservacion)
                                <tr>
                                    <td class="ps-4">
                                        <div>
                                            <strong class="d-block">#{{ $reservacion->id_reservacion }}</strong>
                                            @if($reservacion->observaciones)
                                            <small
                                                class="text-muted">{{ Str::limit($reservacion->observaciones, 30) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            Mesa {{ $reservacion->mesa->numero_mesa }}
                                        </span>
                                        <small class="d-block text-muted">
                                            {{ $reservacion->mesa->capacidad }} personas
                                        </small>
                                    </td>
                                    <td>
                                        @if($reservacion->usuario)
                                        <div>
                                            <strong>{{ $reservacion->usuario->nombre_completo }}</strong>
                                            <small class="d-block text-muted">{{ $reservacion->usuario->email }}</small>
                                        </div>
                                        @else
                                        <span class="text-muted">Sin cliente</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <strong>{{ $reservacion->fecha_reservacion->format('d/m/Y') }}</strong>
                                            <small
                                                class="text-muted">{{ $reservacion->hora_reservacion->format('H:i') }}
                                                hrs</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $reservacion->numero_personas }}</span>
                                    </td>
                                    <td>
                                        @php
                                        $estadoColors = [
                                        'pendiente' => 'warning',
                                        'confirmada' => 'success',
                                        'completada' => 'info',
                                        'cancelada' => 'danger',
                                        'no_asistio' => 'dark'
                                        ];
                                        @endphp
                                        <span class="badge bg-{{ $estadoColors[$reservacion->estado] }}">
                                            {{ ucfirst($reservacion->estado) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- Botones de Estado Rápido -->
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                    data-bs-toggle="dropdown">
                                                    Estado
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#"
                                                            wire:click="cambiarEstado({{ $reservacion->id_reservacion }}, 'pendiente')">Pendiente</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#"
                                                            wire:click="cambiarEstado({{ $reservacion->id_reservacion }}, 'confirmada')">Confirmada</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#"
                                                            wire:click="cambiarEstado({{ $reservacion->id_reservacion }}, 'completada')">Completada</a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#"
                                                            wire:click="cambiarEstado({{ $reservacion->id_reservacion }}, 'cancelada')">Cancelar</a>
                                                    </li>
                                                    <li><a class="dropdown-item text-dark" href="#"
                                                            wire:click="cambiarEstado({{ $reservacion->id_reservacion }}, 'no_asistio')">No
                                                            Asistió</a></li>
                                                </ul>
                                            </div>

                                            <button wire:click="editReservacion({{ $reservacion->id_reservacion }})"
                                                class="btn btn-sm btn-outline-primary d-flex align-items-center">
                                                <i class="bi bi-pencil me-1"></i>
                                                Editar
                                            </button>
                                            <button wire:click="confirmDelete({{ $reservacion->id_reservacion }})"
                                                class="btn btn-sm btn-outline-danger d-flex align-items-center">
                                                <i class="bi bi-trash me-1"></i>
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-calendar-check display-4 d-block mb-3"></i>
                                            <h5>No se encontraron reservaciones</h5>
                                            <p class="mb-0">Crea tu primera reservación para comenzar</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($reservaciones->hasPages())
                    <div class="card-footer bg-transparent">
                        {{ $reservaciones->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Crear/Editar Reservación -->
    @if($showModal)
    <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5)" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-calendar-plus me-2"></i>
                        {{ $isEditing ? 'Editar Reservación' : 'Nueva Reservación' }}
                    </h5>
                    <button type="button" wire:click="closeModal" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit="saveReservacion">
                        <div class="row g-3">
                            <!-- Mesa -->
                            <div class="col-md-6">
                                <label class="form-label">Mesa <span class="text-danger">*</span></label>
                                <select class="form-select @error('id_mesa') is-invalid @enderror" wire:model="id_mesa">
                                    <option value="">Seleccionar mesa...</option>
                                    @foreach($mesas as $mesa)
                                    <option value="{{ $mesa->id_mesa }}">
                                        Mesa {{ $mesa->numero_mesa }} ({{ $mesa->capacidad }} personas) -
                                        {{ $mesa->ubicacion }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('id_mesa')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Cliente -->
                            <div class="col-md-6">
                                <label class="form-label">Cliente</label>
                                <select class="form-select @error('id_usuario') is-invalid @enderror"
                                    wire:model="id_usuario">
                                    <option value="">Seleccionar cliente...</option>
                                    @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id_usuario }}">
                                        <!-- CORREGIDO: id_usuario en lugar de id -->
                                        {{ $usuario->nombre_completo }} - {{ $usuario->email }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('id_usuario')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fecha y Hora -->
                            <div class="col-md-6">
                                <label class="form-label">Fecha <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('fecha_reservacion') is-invalid @enderror"
                                    wire:model="fecha_reservacion" min="{{ date('Y-m-d') }}">
                                @error('fecha_reservacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Hora <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('hora_reservacion') is-invalid @enderror"
                                    wire:model="hora_reservacion" min="08:00" max="22:00">
                                @error('hora_reservacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Número de Personas y Estado -->
                            <div class="col-md-6">
                                <label class="form-label">Número de Personas <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('numero_personas') is-invalid @enderror"
                                    wire:model="numero_personas" min="1" max="20" placeholder="Ej: 4">
                                @error('numero_personas')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-select @error('estado') is-invalid @enderror" wire:model="estado">
                                    <option value="pendiente">Pendiente</option>
                                    <option value="confirmada">Confirmada</option>
                                    <option value="completada">Completada</option>
                                    <option value="cancelada">Cancelada</option>
                                    <option value="no_asistio">No Asistió</option>
                                </select>
                                @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Observaciones -->
                            <div class="col-12">
                                <label class="form-label">Observaciones</label>
                                <textarea class="form-control @error('observaciones') is-invalid @enderror"
                                    wire:model="observaciones" rows="3"
                                    placeholder="Notas especiales, requerimientos del cliente..."></textarea>
                                @error('observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="modal-footer mt-4">
                            <button type="button" wire:click="closeModal" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <i class="bi bi-check-circle me-1"></i>
                                <span wire:loading.remove>{{ $isEditing ? 'Actualizar' : 'Crear' }} Reservación</span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Guardando...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal de Confirmación de Eliminación -->
    @if($reservacionToDelete)
    <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5)" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Confirmar Eliminación
                    </h5>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-trash display-4 text-danger mb-3"></i>
                    <h6>¿Estás seguro de que deseas eliminar esta reservación?</h6>
                    <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button wire:click="deleteReservacion" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>
                        Eliminar
                    </button>
                    <button wire:click="reservacionToDelete = null" class="btn btn-outline-secondary">
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

    @if (session()->has('error'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show" role="alert">
            <div class="toast-header bg-danger text-white">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
    </div>
    @endif
</div>
