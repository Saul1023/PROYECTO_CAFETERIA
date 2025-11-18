<div class="container-fluid p-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                <div class="mb-3 mb-md-0">
                    <h1 class="h2 text-brown mb-1">Gestión de Promociones</h1>
                    <p class="text-muted mb-0">Administra las promociones y descuentos de productos</p>
                </div>
                <a href="{{ route('admin.promociones.crear') }}" class="btn btn-primary d-flex align-items-center">
                    <i class="bi bi-plus-circle me-2"></i>
                    Nueva Promoción
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
                                <input type="text" wire:model.live="search" placeholder="Buscar promociones..."
                                    class="form-control">
                            </div>
                        </div>

                        <!-- Filtro por Estado -->
                        <div class="col-md-4">
                            <label class="form-label">Filtrar por Estado</label>
                            <select wire:model.live="filterEstado" class="form-select">
                                <option value="">Todos los estados</option>
                                <option value="1">Activas</option>
                                <option value="0">Inactivas</option>
                            </select>
                        </div>

                        <!-- Filtro por Vigencia -->
                        <div class="col-md-4">
                            <label class="form-label">Filtrar por Vigencia</label>
                            <select wire:model.live="filterVigencia" class="form-select">
                                <option value="">Todas las promociones</option>
                                <option value="vigentes">Vigentes</option>
                                <option value="futuras">Futuras</option>
                                <option value="expiradas">Expiradas</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Promociones -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Promoción</th>
                                    <th>Descuento</th>
                                    <th>Productos</th>
                                    <th>Vigencia</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($promociones as $promocion)
                                <tr>
                                    <td class="ps-4">
                                        <div>
                                            <strong class="d-block">{{ $promocion->nombre }}</strong>
                                            <small
                                                class="text-muted">{{ Str::limit($promocion->descripcion, 50) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success fs-6">
                                            {{ $promocion->valor_descuento }}%
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($promocion->productos->take(3) as $producto)
                                            <span class="badge bg-info">{{ $producto->nombre }}</span>
                                            @endforeach
                                            @if($promocion->productos->count() > 3)
                                            <span class="badge bg-secondary">+{{ $promocion->productos->count() - 3 }}
                                                más</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <small><strong>Inicio:</strong>
                                                {{ $promocion->fecha_inicio->format('d/m/Y') }}</small>
                                            <small><strong>Fin:</strong>
                                                {{ $promocion->fecha_fin->format('d/m/Y') }}</small>
                                            @php
                                            $hoy = \Carbon\Carbon::today();
                                            $diasRestantes = $hoy->diffInDays($promocion->fecha_fin, false);
                                            @endphp
                                            @if($diasRestantes >= 0)
                                            <small class="text-success">
                                                <i class="bi bi-clock"></i> {{ $diasRestantes }} días restantes
                                            </small>
                                            @else
                                            <small class="text-danger">
                                                <i class="bi bi-clock"></i> Expirada
                                            </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                {{ $promocion->estado ? 'checked' : '' }}
                                                wire:click="toggleEstado({{ $promocion->id_promocion }})">
                                        </div>
                                        <small class="text-muted ms-2">
                                            {{ $promocion->estado ? 'Activa' : 'Inactiva' }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <button wire:click="editPromocion({{ $promocion->id_promocion }})"
                                                class="btn btn-sm btn-outline-primary d-flex align-items-center">
                                                <i class="bi bi-pencil me-1"></i>
                                                Editar
                                            </button>
                                            <button wire:click="confirmDelete({{ $promocion->id_promocion }})"
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
                                            <i class="bi bi-tag display-4 d-block mb-3"></i>
                                            <h5>No se encontraron promociones</h5>
                                            <p class="mb-0">Crea tu primera promoción para comenzar</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($promociones->hasPages())
                    <div class="card-footer bg-transparent">
                        {{ $promociones->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Crear/Editar Promoción -->
    @if($showModal)
    <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5)" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-tag me-2"></i>
                        {{ $isEditing ? 'Editar Promoción' : 'Nueva Promoción' }}
                    </h5>
                    <button type="button" wire:click="closeModal" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit="savePromocion">
                        <div class="row g-3">
                            <!-- Nombre -->
                            <div class="col-md-6">
                                <label class="form-label">Nombre de la Promoción <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    wire:model="nombre" placeholder="Ej: Descuento de Verano">
                                @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tipo de Descuento -->
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Descuento <span class="text-danger">*</span></label>
                                <select class="form-select @error('tipo_descuento') is-invalid @enderror"
                                    wire:model="tipo_descuento">
                                    <option value="porcentaje">Porcentaje (%)</option>
                                </select>
                                @error('tipo_descuento')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Valor Descuento -->
                            <div class="col-md-6">
                                <label class="form-label">Valor de Descuento <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number"
                                        class="form-control @error('valor_descuento') is-invalid @enderror"
                                        wire:model="valor_descuento" min="0" max="100" step="0.01" placeholder="0.00">
                                    <span class="input-group-text">%</span>
                                    @error('valor_descuento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Estado -->
                            <div class="col-md-6">
                                <label class="form-label">Estado</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" role="switch" wire:model="estado"
                                        id="estadoPromocion">
                                    <label class="form-check-label" for="estadoPromocion">
                                        {{ $estado ? 'Activa' : 'Inactiva' }}
                                    </label>
                                </div>
                            </div>

                            <!-- Fechas -->
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Inicio <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror"
                                    wire:model="fecha_inicio" min="{{ date('Y-m-d') }}">
                                @error('fecha_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Fecha de Fin <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror"
                                    wire:model="fecha_fin"
                                    min="{{ $fecha_inicio ? date('Y-m-d', strtotime($fecha_inicio . ' +1 day')) : date('Y-m-d', strtotime('+1 day')) }}">
                                @error('fecha_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Descripción -->
                            <div class="col-12">
                                <label class="form-label">Descripción</label>
                                <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                    wire:model="descripcion" rows="3"
                                    placeholder="Descripción opcional de la promoción..."></textarea>
                                @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Selección de Productos -->
                            <div class="col-12">
                                <label class="form-label">Productos <span class="text-danger">*</span></label>
                                <div class="border rounded p-3 @error('productosSeleccionados') border-danger @enderror"
                                    style="max-height: 200px; overflow-y: auto;">
                                    @foreach($productos as $producto)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            value="{{ $producto->id_producto }}" wire:model="productosSeleccionados"
                                            id="producto{{ $producto->id_producto }}">
                                        <label class="form-check-label" for="producto{{ $producto->id_producto }}">
                                            {{ $producto->nombre }} - {{ $producto->precio_formateado }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @error('productosSeleccionados')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Seleccionados: {{ count($productosSeleccionados) }}
                                    productos</small>
                            </div>
                        </div>

                        <div class="modal-footer mt-4">
                            <button type="button" wire:click="closeModal" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <i class="bi bi-check-circle me-1"></i>
                                <span wire:loading.remove>{{ $isEditing ? 'Actualizar' : 'Crear' }} Promoción</span>
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
    @if($promocionToDelete)
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
                    <h6>¿Estás seguro de que deseas eliminar esta promoción?</h6>
                    <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button wire:click="deletePromocion" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>
                        Eliminar
                    </button>
                    <button wire:click="promocionToDelete = null" class="btn btn-outline-secondary">
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