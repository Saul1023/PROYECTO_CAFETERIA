<div class="container-fluid p-4 bg-light min-vh-100">
    <div class="row">
        <div class="col-12">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5">
                <div class="mb-3 mb-md-0">
                    <h1 class="h2 text-dark fw-bold mb-1">🏷️ Gestión de Promociones</h1>
                    <p class="text-secondary mb-0">Administra las promociones y descuentos de productos de tu negocio.
                    </p>
                </div>
                <a href="{{ route('promociones.crear') }}"
                    class="btn btn-success btn-lg shadow-sm d-flex align-items-center">
                    <i class="bi bi-plus-circle me-2"></i>
                    Nueva Promoción
                </a>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="card-title text-dark mb-3"><i class="bi bi-funnel me-2"></i>Filtros de Búsqueda</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-secondary-emphasis">Buscar</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" wire:model.live="search"
                                    placeholder="Nombre, descripción o productos..."
                                    class="form-control border-start-0 shadow-sm">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold text-secondary-emphasis">Estado</label>
                            <select wire:model.live="filterEstado" class="form-select shadow-sm">
                                <option value="">Todos los estados</option>
                                <option value="1">✅ Activas</option>
                                <option value="0">❌ Inactivas</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold text-secondary-emphasis">Vigencia</label>
                            <select wire:model.live="filterVigencia" class="form-select shadow-sm">
                                <option value="">Todas las promociones</option>
                                <option value="vigentes">🟢 Vigentes</option>
                                <option value="futuras">⏳ Futuras</option>
                                <option value="expiradas">🔴 Expiradas</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-lg border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-4 text-uppercase small">Promoción</th>
                                    <th class="text-uppercase small">Descuento</th>
                                    <th class="text-uppercase small">Productos Aplicados</th>
                                    <th class="text-uppercase small">Vigencia</th>
                                    <th class="text-uppercase small">Estado</th>
                                    <th class="text-center text-uppercase small">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($promociones as $promocion)
                                <tr>
                                    <td class="ps-4 align-middle">
                                        <div>
                                            <strong class="d-block text-primary">{{ $promocion->nombre }}</strong>
                                            <small
                                                class="text-muted">{{ Str::limit($promocion->descripcion, 50) }}</small>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <span
                                            class="badge rounded-pill bg-success-subtle text-success fs-6 fw-bold p-2">
                                            {{ $promocion->valor_descuento }}%
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($promocion->productos->take(3) as $producto)
                                            <span
                                                class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $producto->nombre }}</span>
                                            @endforeach
                                            @if($promocion->productos->count() > 3)
                                            <span class="badge bg-secondary">+{{ $promocion->productos->count() - 3 }}
                                                más</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex flex-column small">
                                            <span class="text-dark"><strong>Inicio:</strong>
                                                {{ $promocion->fecha_inicio->format('d/m/Y') }}</span>
                                            <span class="text-dark"><strong>Fin:</strong>
                                                {{ $promocion->fecha_fin->format('d/m/Y') }}</span>
                                            @php
                                            $hoy = \Carbon\Carbon::today();
                                            $diasRestantes = $hoy->diffInDays($promocion->fecha_fin, false);
                                            $vigenciaClass = $diasRestantes >= 0 && $diasRestantes <= 7 ? 'text-warning'
                                                : ($diasRestantes> 7 ? 'text-success' : 'text-danger');
                                                $vigenciaIcon = $diasRestantes >= 0 ? 'bi-clock' : 'bi-x-octagon';
                                                $vigenciaText = $diasRestantes >= 0 ? "$diasRestantes días restantes" :
                                                'Expirada';
                                                @endphp
                                                <span class="{{ $vigenciaClass }} mt-1 fw-bold">
                                                    <i class="bi {{ $vigenciaIcon }} me-1"></i> {{ $vigenciaText }}
                                                </span>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="form-check form-switch d-flex align-items-center">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                style="transform: scale(1.2);" {{ $promocion->estado ? 'checked' : '' }}
                                                wire:click="toggleEstado({{ $promocion->id_promocion }})">
                                            <small class="text-dark ms-2 fw-bold">
                                                {{ $promocion->estado ? 'Activa' : 'Inactiva' }}
                                            </small>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button wire:click="editPromocion({{ $promocion->id_promocion }})"
                                                class="btn btn-sm btn-outline-primary d-flex align-items-center rounded-pill shadow-sm"
                                                title="Editar Promoción">
                                                <i class="bi bi-pencil me-1"></i>
                                                Editar
                                            </button>
                                            <button wire:click="confirmDelete({{ $promocion->id_promocion }})"
                                                class="btn btn-sm btn-outline-danger d-flex align-items-center rounded-pill shadow-sm"
                                                title="Eliminar Promoción">
                                                <i class="bi bi-trash me-1"></i>
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted p-4">
                                            <i
                                                class="bi bi-tag-fill display-4 d-block mb-3 text-secondary-emphasis"></i>
                                            <h5 class="fw-bold">No se encontraron promociones</h5>
                                            <p class="mb-0">Crea tu primera promoción para comenzar a impulsar tus
                                                ventas.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($promociones->hasPages())
                    <div class="card-footer bg-light border-0 pt-3 pb-2">
                        {{ $promociones->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($showModal)
    <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.6);" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-tag-fill me-2"></i>
                        {{ $isEditing ? 'Editar Promoción' : 'Nueva Promoción' }}
                    </h5>
                    <button type="button" wire:click="closeModal" class="btn-close btn-close-white"></button>
                </div>
                <div class="modal-body p-4">
                    <form wire:submit="savePromocion">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Nombre de la Promoción <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    wire:model="nombre" placeholder="Ej: Descuento de Verano">
                                @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Tipo de Descuento <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('tipo_descuento') is-invalid @enderror"
                                    wire:model="tipo_descuento">
                                    <option value="porcentaje">Porcentaje (%)</option>
                                </select>
                                @error('tipo_descuento')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Valor de Descuento <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number"
                                        class="form-control @error('valor_descuento') is-invalid @enderror"
                                        wire:model="valor_descuento" min="0" max="100" step="0.01" placeholder="0.00">
                                    <span class="input-group-text bg-light fw-bold">%</span>
                                    @error('valor_descuento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Estado</label>
                                <div class="form-check form-switch mt-2 p-0 d-flex align-items-center">
                                    <input class="form-check-input ms-3" type="checkbox" role="switch"
                                        wire:model="estado" id="estadoPromocion" style="transform: scale(1.3);">
                                    <label class="form-check-label ms-3 fw-bold text-dark" for="estadoPromocion">
                                        {{ $estado ? 'Activa' : 'Inactiva' }}
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Fecha de Inicio <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror"
                                    wire:model="fecha_inicio" min="{{ date('Y-m-d') }}">
                                @error('fecha_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Fecha de Fin <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror"
                                    wire:model="fecha_fin"
                                    min="{{ $fecha_inicio ? date('Y-m-d', strtotime($fecha_inicio . ' +1 day')) : date('Y-m-d', strtotime('+1 day')) }}">
                                @error('fecha_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-dark">Descripción</label>
                                <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                    wire:model="descripcion" rows="3"
                                    placeholder="Detalles sobre el alcance o condiciones de la promoción..."></textarea>
                                @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-dark">Productos Aplicables <span
                                        class="text-danger">*</span></label>
                                <div class="border rounded p-3 bg-light @error('productosSeleccionados') border-danger border-2 @else border-secondary-subtle @enderror"
                                    style="max-height: 250px; overflow-y: auto;">
                                    @foreach($productos as $producto)
                                    <div class="form-check py-1">
                                        <input class="form-check-input" type="checkbox"
                                            value="{{ $producto->id_producto }}" wire:model="productosSeleccionados"
                                            id="producto{{ $producto->id_producto }}">
                                        <label class="form-check-label text-dark"
                                            for="producto{{ $producto->id_producto }}">
                                            {{ $producto->nombre }} <span
                                                class="text-muted">({{ $producto->precio_formateado }})</span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @error('productosSeleccionados')
                                <div class="text-danger small mt-1 fw-bold">{{ $message }}</div>
                                @enderror
                                <small class="text-secondary d-block mt-1">Seleccionados: <span
                                        class="fw-bold text-primary">{{ count($productosSeleccionados) }}</span>
                                    productos</small>
                            </div>
                        </div>

                        <div class="modal-footer mt-4 border-top pt-3">
                            <button type="button" wire:click="closeModal"
                                class="btn btn-outline-secondary rounded-pill">
                                <i class="bi bi-x-circle me-1"></i>
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary rounded-pill shadow-sm"
                                wire:loading.attr="disabled">
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

    @if($promocionToDelete)
    <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.6);" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header border-0 pb-0 d-block">
                    <button type="button" wire:click="promocionToDelete = null" class="btn-close float-end"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-exclamation-triangle-fill display-4 text-danger mb-3"></i>
                    <h5 class="modal-title text-danger fw-bold mb-2">
                        Confirmar Eliminación
                    </h5>
                    <p class="text-muted small">¿Estás seguro de que deseas eliminar la promoción
                        **{{ $promocionToDelete->nombre ?? 'seleccionada' }}**?</p>
                    <p class="text-danger small mb-0">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center pt-0">
                    <button wire:click="deletePromocion" class="btn btn-danger rounded-pill shadow-sm">
                        <i class="bi bi-trash-fill me-2"></i>
                        Eliminar
                    </button>
                    <button wire:click="promocionToDelete = null" class="btn btn-outline-secondary rounded-pill">
                        <i class="bi bi-x-circle me-2"></i>
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if (session()->has('success'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show border-success border-3" role="alert" style="min-width: 300px;">
            <div class="toast-header bg-success text-white">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <strong class="me-auto">Éxito</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body text-dark bg-white">
                {{ session('success') }}
            </div>
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show border-danger border-3" role="alert" style="min-width: 300px;">
            <div class="toast-header bg-danger text-white">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body text-dark bg-white">
                {{ session('error') }}
            </div>
        </div>
    </div>
    @endif
</div>
