<div class="container-fluid p-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                <div class="mb-3 mb-md-0">
                    <h1 class="h2 text-brown mb-1">Gestión de Mesas</h1>
                    <p class="text-muted mb-0">Administra las mesas del restaurante</p>
                </div>
                <button wire:click="openModal" class="btn btn-primary d-flex align-items-center">
                    <i class="bi bi-plus-circle me-2"></i>
                    Nueva Mesa
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
                                <input type="text" wire:model.live="search"
                                    placeholder="Buscar por número o ubicación..." class="form-control">
                            </div>
                        </div>

                        <!-- Filtro por Estado -->
                        <div class="col-md-4">
                            <label class="form-label">Filtrar por Estado</label>
                            <select wire:model.live="filterEstado" class="form-select">
                                <option value="">Todos los estados</option>
                                <option value="disponible">Disponible</option>
                                <option value="ocupada">Ocupada</option>
                                <option value="reservada">Reservada</option>
                            </select>
                        </div>

                        <!-- Filtro por Ubicación -->
                        <div class="col-md-4">
                            <label class="form-label">Filtrar por Ubicación</label>
                            <select wire:model.live="filterUbicacion" class="form-select">
                                <option value="">Todas las ubicaciones</option>
                                @foreach($ubicaciones as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Mesas -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Mesa</th>
                                    <th>Capacidad</th>
                                    <th>Ubicación</th>
                                    <th>Estado</th>
                                    <th>Reservaciones Activas</th>
                                    <th>Activa</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mesas as $mesa)
                                <tr class="{{ $mesa->activa ? '' : 'table-secondary' }}">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="bi bi-tablet fs-4 text-brown"></i>
                                            </div>
                                            <div>
                                                <strong class="d-block">Mesa {{ $mesa->numero_mesa }}</strong>
                                                <small class="text-muted">ID: {{ $mesa->id_mesa }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info fs-6">
                                            {{ $mesa->capacidad }} <i class="bi bi-person"></i>
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                        $ubicacionColors = [
                                        'interior' => 'primary',
                                        'terraza' => 'success',
                                        'vip' => 'warning',
                                        'jardin' => 'info'
                                        ];
                                        @endphp
                                        <span class="badge bg-{{ $ubicacionColors[$mesa->ubicacion] ?? 'secondary' }}">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            {{ $ubicaciones[$mesa->ubicacion] ?? $mesa->ubicacion }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                        $estadoColors = [
                                        'disponible' => 'success',
                                        'ocupada' => 'danger',
                                        'reservada' => 'warning'
                                        ];
                                        @endphp
                                        <span class="badge bg-{{ $estadoColors[$mesa->estado] }}">
                                            {{ ucfirst($mesa->estado) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($mesa->reservaciones_activas > 0)
                                        <span class="badge bg-warning">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $mesa->reservaciones_activas }}
                                        </span>
                                        @else
                                        <span class="text-muted">Sin reservaciones</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                {{ $mesa->activa ? 'checked' : '' }}
                                                wire:click="toggleActiva({{ $mesa->id_mesa }})">
                                        </div>
                                        <small class="text-muted ms-2">
                                            {{ $mesa->activa ? 'Activa' : 'Inactiva' }}
                                        </small>
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
                                                    <li><a class="dropdown-item text-success" href="#"
                                                            wire:click="cambiarEstado({{ $mesa->id_mesa }}, 'disponible')">Disponible</a>
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#"
                                                            wire:click="cambiarEstado({{ $mesa->id_mesa }}, 'ocupada')">Ocupada</a>
                                                    </li>
                                                    <li><a class="dropdown-item text-warning" href="#"
                                                            wire:click="cambiarEstado({{ $mesa->id_mesa }}, 'reservada')">Reservada</a>
                                                    </li>
                                                </ul>
                                            </div>

                                            <button wire:click="editMesa({{ $mesa->id_mesa }})"
                                                class="btn btn-sm btn-outline-primary d-flex align-items-center">
                                                <i class="bi bi-pencil me-1"></i>
                                                Editar
                                            </button>
                                            <button wire:click="confirmDelete({{ $mesa->id_mesa }})"
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
                                            <i class="bi bi-tablet display-4 d-block mb-3"></i>
                                            <h5>No se encontraron mesas</h5>
                                            <p class="mb-0">Crea tu primera mesa para comenzar</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($mesas->hasPages())
                    <div class="card-footer bg-transparent">
                        {{ $mesas->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Crear/Editar Mesa -->
    @if($showModal)
    <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5)" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-tablet me-2"></i>
                        {{ $isEditing ? 'Editar Mesa' : 'Nueva Mesa' }}
                    </h5>
                    <button type="button" wire:click="closeModal" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit="saveMesa">
                        <div class="row g-3">
                            <!-- Número de Mesa -->
                            <div class="col-md-6">
                                <label class="form-label">Número de Mesa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('numero_mesa') is-invalid @enderror"
                                    wire:model="numero_mesa" placeholder="Ej: M01, T02, V03..." maxlength="10">
                                @error('numero_mesa')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Identificador único de la mesa</small>
                            </div>

                            <!-- Capacidad -->
                            <div class="col-md-6">
                                <label class="form-label">Capacidad <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('capacidad') is-invalid @enderror"
                                        wire:model="capacidad" min="1" max="20" placeholder="Ej: 4">
                                    <span class="input-group-text">personas</span>
                                    @error('capacidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Entre 1 y 20 personas</small>
                            </div>

                            <!-- Ubicación -->
                            <div class="col-md-6">
                                <label class="form-label">Ubicación <span class="text-danger">*</span></label>
                                <select class="form-select @error('ubicacion') is-invalid @enderror"
                                    wire:model="ubicacion">
                                    @foreach($ubicaciones as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('ubicacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Estado -->
                            <div class="col-md-6">
                                <label class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-select @error('estado') is-invalid @enderror" wire:model="estado">
                                    <option value="disponible">Disponible</option>
                                    <option value="ocupada">Ocupada</option>
                                    <option value="reservada">Reservada</option>
                                </select>
                                @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Estado Activa -->
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" wire:model="activa"
                                        id="activaMesa">
                                    <label class="form-check-label" for="activaMesa">
                                        Mesa Activa
                                    </label>
                                </div>
                                <small class="text-muted">
                                    Las mesas inactivas no estarán disponibles para reservaciones
                                </small>
                            </div>

                            <!-- Vista Previa -->
                            @if($numero_mesa && $capacidad)
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-eye me-2"></i>
                                        Vista Previa de la Mesa
                                    </h6>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <strong>Mesa:</strong> {{ $numero_mesa }}<br>
                                            <strong>Capacidad:</strong> {{ $capacidad }} personas<br>
                                            <strong>Ubicación:</strong> {{ $ubicaciones[$ubicacion] ?? $ubicacion }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Estado:</strong>
                                            <span
                                                class="badge bg-{{ $estado === 'disponible' ? 'success' : ($estado === 'ocupada' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($estado) }}
                                            </span><br>
                                            <strong>Activa:</strong>
                                            <span class="badge {{ $activa ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $activa ? 'Sí' : 'No' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="modal-footer mt-4">
                            <button type="button" wire:click="closeModal" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <i class="bi bi-check-circle me-1"></i>
                                <span wire:loading.remove>{{ $isEditing ? 'Actualizar' : 'Crear' }} Mesa</span>
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
    @if($mesaToDelete)
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
                    <h6>¿Estás seguro de que deseas eliminar esta mesa?</h6>
                    <p class="text-muted mb-0">
                        Esta acción no se puede deshacer.
                        @if($mesaToDelete)
                        @php
                        $mesa = \App\Models\Mesa::find($mesaToDelete);
                        $reservacionesActivas = $mesa ? $mesa->reservaciones()->whereIn('estado', ['pendiente',
                        'confirmada'])->count() : 0;
                        @endphp
                        @if($reservacionesActivas > 0)
                        <br><strong class="text-warning">Advertencia: Esta mesa tiene {{ $reservacionesActivas }}
                            reservación(es) activa(s).</strong>
                        @endif
                        @endif
                    </p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button wire:click="deleteMesa" class="btn btn-danger" wire:loading.attr="disabled">
                        <i class="bi bi-trash me-2"></i>
                        Eliminar
                    </button>
                    <button wire:click="mesaToDelete = null" class="btn btn-outline-secondary">
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
