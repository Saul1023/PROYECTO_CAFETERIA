<div>
<div class="container-fluid p-4">
    <div class="row">
        <div class="col-12">
            <!-- Mensajes Flash -->
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session()->has('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Header -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
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
                                <input type="text" wire:model.live="search" placeholder="Buscar por mesa, cliente, código..."
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
                                    <th>Monto</th>
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
                                            @if($reservacion->codigo_qr)
                                                <small class="badge bg-secondary">{{ $reservacion->codigo_qr }}</small>
                                            @endif
                                            @if($reservacion->observaciones)
                                            <small class="text-muted d-block">{{ Str::limit($reservacion->observaciones, 30) }}</small>
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
                                            <small class="text-muted">
                                                {{ $reservacion->hora_reservacion->format('H:i') }} hrs
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $reservacion->numero_personas }}</span>
                                    </td>
                                    <td>
                                        <strong>Bs. {{ number_format($reservacion->monto_pago, 2) }}</strong>
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
                                        <span class="badge bg-{{ $estadoColors[$reservacion->estado] ?? 'secondary' }}">
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
                                                            wire:click.prevent="cambiarEstado({{ $reservacion->id_reservacion }}, 'pendiente')">Pendiente</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#"
                                                            wire:click.prevent="cambiarEstado({{ $reservacion->id_reservacion }}, 'confirmada')">Confirmada</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#"
                                                            wire:click.prevent="cambiarEstado({{ $reservacion->id_reservacion }}, 'completada')">Completada</a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#"
                                                            wire:click.prevent="cambiarEstado({{ $reservacion->id_reservacion }}, 'cancelada')">Cancelar</a>
                                                    </li>
                                                    <li><a class="dropdown-item text-dark" href="#"
                                                            wire:click.prevent="cambiarEstado({{ $reservacion->id_reservacion }}, 'no_asistio')">No Asistió</a></li>
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
                                    <td colspan="8" class="text-center py-5">
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
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-calendar-plus me-2"></i>
                        {{ $isEditing ? 'Editar Reservación' : 'Nueva Reservación' }}
                    </h5>
                    <button type="button" wire:click="closeModal" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="saveReservacion">
                        <div class="row g-3">
                            <!-- Fecha y Número de Personas (para verificar disponibilidad) -->
                            <div class="col-md-6">
                                <label class="form-label">Fecha <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('fecha_reservacion') is-invalid @enderror"
                                    wire:model.live="fecha_reservacion" min="{{ date('Y-m-d') }}">
                                @error('fecha_reservacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Número de Personas <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('numero_personas') is-invalid @enderror"
                                    wire:model.live="numero_personas" min="1" max="20" placeholder="Ej: 4">
                                @error('numero_personas')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Mostrar disponibilidad -->
                            @if($mostrarDisponibilidad && !$isEditing)
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Mesas Disponibles</strong> - Haz clic en un horario para seleccionar
                                </div>

                                @if(count($mesasDisponibles) > 0)
                                    <div class="row g-3">
                                        @foreach($mesasDisponibles as $disponible)
                                        <div class="col-md-6">
                                            <div class="card border-primary">
                                                <div class="card-header bg-primary text-white">
                                                    <h6 class="mb-0">
                                                        <i class="bi bi-table me-2"></i>
                                                        Mesa {{ $disponible['mesa']->numero_mesa }}
                                                        <small>(Capacidad: {{ $disponible['mesa']->capacidad }} personas - {{ $disponible['mesa']->ubicacion }})</small>
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <p class="text-muted small mb-2">
                                                        <i class="bi bi-clock me-1"></i>Horarios disponibles:
                                                    </p>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach($disponible['horarios'] as $horario)
                                                        <button type="button"
                                                            wire:click="seleccionarMesaHorario({{ $disponible['mesa']->id_mesa }}, '{{ $horario }}')"
                                                            class="btn btn-sm btn-outline-success">
                                                            <i class="bi bi-check-circle me-1"></i>{{ $horario }}
                                                        </button>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        No hay mesas disponibles para la fecha y número de personas seleccionados.
                                        @if(Carbon\Carbon::parse($fecha_reservacion)->isToday())
                                            <br><strong>Nota:</strong> Para reservas hoy, solo se muestran horarios con al menos 1 hora de anticipación.
                                        @endif
                                    </div>
                                @endif
                            </div>
                            @endif

                            <!-- Mesa y Hora (se llenan automáticamente al seleccionar) -->
                            <div class="col-md-6">
                                <label class="form-label">Mesa <span class="text-danger">*</span></label>
                                <select class="form-select @error('id_mesa') is-invalid @enderror"
                                    wire:model="id_mesa"
                                    {{ !$isEditing ? 'disabled' : '' }}>
                                    <option value="">{{ !$isEditing ? 'Selecciona un horario disponible arriba' : 'Seleccionar mesa...' }}</option>
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

                            <div class="col-md-6">
                                <label class="form-label">Hora <span class="text-danger">*</span></label>
                                @if($isEditing)
                                    <select class="form-select @error('hora_reservacion') is-invalid @enderror"
                                        wire:model="hora_reservacion">
                                        <option value="">Seleccionar horario...</option>
                                        <option value="08:00">8:00 AM</option>
                                        <option value="10:00">10:00 AM</option>
                                        <option value="12:00">12:00 PM</option>
                                        <option value="14:00">2:00 PM</option>
                                        <option value="16:00">4:00 PM</option>
                                        <option value="18:00">6:00 PM</option>
                                        <option value="20:00">8:00 PM</option>
                                    </select>
                                @else
                                    <input type="text" class="form-control @error('hora_reservacion') is-invalid @enderror"
                                        wire:model="hora_reservacion"
                                        readonly
                                        placeholder="Selecciona un horario disponible arriba">
                                @endif
                                <small class="text-muted">Horarios disponibles: 8:00 AM - 8:00 PM (cada 2 horas). Mínimo 1 hora de anticipación.</small>
                                @error('hora_reservacion')
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
                                        {{ $usuario->nombre_completo }} - {{ $usuario->email }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('id_usuario')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Monto y Estado -->
                            <div class="col-md-3">
                                <label class="form-label">Monto (Bs.) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('monto_pago') is-invalid @enderror"
                                    wire:model="monto_pago" min="0" placeholder="30.00">
                                @error('monto_pago')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
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
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                                {{ !$id_mesa || !$hora_reservacion ? 'disabled' : '' }}>
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
                    <button wire:click="$set('reservacionToDelete', null)" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-2"></i>
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.modal-xl {
    max-width: 1140px;
}
</style>
</div>
