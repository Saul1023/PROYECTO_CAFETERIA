<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <div>
                    <h1 class="h3 text-primary mb-1 d-flex align-items-center">
                        <i class="bi bi-tags-fill me-3 fs-4 text-primary"></i>
                        Crear Nueva Promoción
                    </h1>
                    <p class="text-secondary mb-0">Define los detalles y productos para aplicar un descuento.</p>
                </div>
                <a href="{{ route('promociones') }}" class="btn btn-outline-secondary d-flex align-items-center">
                    <i class="bi bi-arrow-left me-2"></i>
                    Volver a Promociones
                </a>
            </div>

            <form wire:submit="savePromocion">
                <div class="row g-4">

                    <div class="col-12 col-xl-7">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0 text-dark d-flex align-items-center">
                                    <i class="bi bi-info-circle me-2 text-primary"></i>
                                    Detalles de la Promoción
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-bold">Nombre de la Promoción <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                            wire:model="nombre"
                                            placeholder="Ej: Descuento de Verano, Oferta Especial..." maxlength="100">
                                        @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Máximo 100 caracteres. Debe ser
                                            claro.</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Tipo de Descuento <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('tipo_descuento') is-invalid @enderror"
                                            wire:model="tipo_descuento" disabled>
                                            <option value="porcentaje">Porcentaje (%)</option>
                                        </select>
                                        @error('tipo_descuento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Actualmente solo disponible
                                            porcentaje.</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Valor de Descuento <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group @error('valor_descuento') has-validation @enderror">
                                            <input type="number"
                                                class="form-control @error('valor_descuento') is-invalid @enderror"
                                                wire:model="valor_descuento" min="0.01" max="100" step="0.01"
                                                placeholder="0.00">
                                            <span class="input-group-text bg-light fw-bold">%</span>
                                            @error('valor_descuento')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <small class="form-text text-muted">Entre 0.01% y 100%.</small>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-bold d-block">Estado de la Promoción</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                wire:model="estado" id="estadoPromocion">
                                            <label
                                                class="form-check-label fw-bold text-{{ $estado ? 'success' : 'secondary' }}"
                                                for="estadoPromocion">
                                                {{ $estado ? 'Promoción Activa' : 'Promoción Inactiva' }}
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">
                                            Las promociones inactivas no se aplicarán a los productos.
                                        </small>
                                    </div>

                                    <div class="col-12">
                                        <hr class="my-3">
                                        <h5 class="mb-3 text-dark d-flex align-items-center">
                                            <i class="bi bi-calendar-event me-2 text-primary"></i>
                                            Período de Vigencia
                                        </h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Fecha de Inicio <span
                                                        class="text-danger">*</span></label>
                                                <input type="date"
                                                    class="form-control @error('fecha_inicio') is-invalid @enderror"
                                                    wire:model="fecha_inicio" min="{{ date('Y-m-d') }}">
                                                @error('fecha_inicio')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="form-text text-muted">No puede ser anterior a hoy.</small>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Fecha de Fin <span
                                                        class="text-danger">*</span></label>
                                                <input type="date"
                                                    class="form-control @error('fecha_fin') is-invalid @enderror"
                                                    wire:model="fecha_fin"
                                                    min="{{ $fecha_inicio ? date('Y-m-d', strtotime($fecha_inicio . ' +1 day')) : date('Y-m-d', strtotime('+1 day')) }}">
                                                @error('fecha_fin')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="form-text text-muted">Debe ser posterior a la fecha de
                                                    inicio.</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-bold">Descripción</label>
                                        <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                            wire:model="descripcion" rows="3"
                                            placeholder="Describe los detalles de la promoción, condiciones especiales, etc..."
                                            maxlength="500"></textarea>
                                        @error('descripcion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Máximo 500 caracteres.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-5">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0 text-dark d-flex align-items-center">
                                    <i class="bi bi-box-seam-fill me-2 text-primary"></i>
                                    Productos en Promoción <span class="text-danger ms-1">*</span>
                                </h5>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <label class="form-label fw-bold">Seleccionar Productos</label>

                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input type="text" class="form-control"
                                            placeholder="Buscar productos por nombre..." id="buscarProducto">
                                    </div>
                                    <small class="form-text text-muted">Escribe para filtrar la lista de productos
                                        disponibles.</small>
                                </div>

                                <div class="flex-grow-1 border rounded p-3 bg-white shadow-sm @error('productosSeleccionados') border-danger @enderror"
                                    style="max-height: 450px; overflow-y: auto;">

                                    @if($productos->count() > 0)
                                    <div class="row g-2">
                                        @foreach($productos as $producto)
                                        <div class="col-12 producto-item">
                                            <div class="form-check p-0">
                                                <input class="form-check-input float-start mt-2 me-3" type="checkbox"
                                                    value="{{ $producto->id_producto }}"
                                                    wire:model="productosSeleccionados"
                                                    id="producto{{ $producto->id_producto }}"
                                                    style="margin-left: 0.5rem;">
                                                <label
                                                    class="form-check-label d-block py-2 px-4 border rounded-pill bg-light w-100"
                                                    for="producto{{ $producto->id_producto }}"
                                                    style="margin-left: -2.25rem; cursor: pointer;">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong
                                                                class="text-truncate d-block">{{ $producto->nombre }}</strong>
                                                            <small
                                                                class="text-success fw-bold">{{ $producto->precio_formateado }}</small>
                                                            @if($producto->categoria)
                                                            <small class="text-muted d-block fst-italic">
                                                                ({{ $producto->categoria->nombre }})
                                                            </small>
                                                            @endif
                                                        </div>
                                                        <span class="badge bg-info text-dark ms-2 py-2 px-3">
                                                            Stock: {{ $producto->stock }}
                                                        </span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="text-center py-5">
                                        <i class="bi bi-box-seam-fill display-4 text-muted mb-3"></i>
                                        <p class="text-muted fw-bold mb-1">No hay productos disponibles</p>
                                        <small class="text-muted">Activa o crea productos para poder asignarlos a
                                            promociones.</small>
                                    </div>
                                    @endif
                                </div>

                                @error('productosSeleccionados')
                                <div class="text-danger small mt-2 fw-bold"><i
                                        class="bi bi-exclamation-circle me-1"></i> {{ $message }}</div>
                                @enderror

                                <div class="mt-3 pt-2 border-top">
                                    <small class="text-muted">
                                        <i class="bi bi-check-circle me-1 text-primary"></i>
                                        Productos Seleccionados: <strong
                                            class="text-dark">{{ count($productosSeleccionados) }}</strong>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($nombre && $valor_descuento && $fecha_inicio && $fecha_fin && count($productosSeleccionados) >
                    0)
                    <div class="col-12">
                        <div class="alert alert-primary p-4 shadow-sm" role="alert">
                            <h4 class="alert-heading d-flex align-items-center">
                                <i class="bi bi-eye-fill me-3"></i>
                                Vista Previa y Resumen
                            </h4>
                            <hr>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong><i class="bi bi-info-circle me-1"></i> Nombre:</strong>
                                        <span class="text-primary">{{ $nombre }}</span></p>
                                    <p class="mb-1"><strong><i class="bi bi-percent me-1"></i> Descuento:</strong> <span
                                            class="text-danger fw-bold fs-5">{{ $valor_descuento }}%</span></p>
                                    <p class="mb-1"><strong><i class="bi bi-check-circle me-1"></i> Estado:</strong>
                                        <span class="badge {{ $estado ? 'bg-success' : 'bg-secondary' }} ms-1">
                                            {{ $estado ? 'Activa' : 'Inactiva' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong><i class="bi bi-calendar-range me-1"></i> Vigencia:</strong>
                                        {{ \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') }} -
                                        {{ \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y') }}
                                    </p>
                                    <p class="mb-1"><strong><i class="bi bi-box-seam me-1"></i> Productos
                                            Afectados:</strong> <span
                                            class="text-dark fw-bold">{{ count($productosSeleccionados) }}</span>
                                        productos</p>
                                    <p class="mb-1"><strong><i class="bi bi-clock-history me-1"></i> Duración:</strong>
                                        <span
                                            class="text-dark">{{ \Carbon\Carbon::parse($fecha_inicio)->diffInDays($fecha_fin) }}</span>
                                        días
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="col-12 mt-4">
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <button type="button" wire:click="resetForm"
                                class="btn btn-outline-secondary d-flex align-items-center"
                                wire:loading.attr="disabled">
                                <i class="bi bi-arrow-clockwise me-2"></i>
                                Limpiar Formulario
                            </button>

                            <div class="d-flex gap-2">
                                <a href="{{ route('promociones') }}"
                                    class="btn btn-outline-danger d-flex align-items-center">
                                    <i class="bi bi-x-circle me-2"></i>
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary d-flex align-items-center"
                                    wire:loading.attr="disabled" {{ $productos->count() == 0 ? 'disabled' : '' }}>
                                    <i class="bi bi-check-circle me-2"></i>
                                    <span wire:loading.remove>Crear Promoción</span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        Creando...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if (session()->has('success'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <i class="bi bi-check-circle me-2"></i>
                <strong class="me-auto">Éxito</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"
                    aria-label="Cerrar"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-danger text-white">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"
                    aria-label="Cerrar"></button>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const buscarProducto = document.getElementById('buscarProducto');
    const productoItems = document.querySelectorAll('.producto-item');

    if (buscarProducto) {
        buscarProducto.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();

            productoItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Auto-ocultar toasts después de 5 segundos
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(toast => {
        setTimeout(() => {
            // Asegurarse de que Bootstrap esté cargado si se usa la clase Toast
            if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
                const bsToast = new bootstrap.Toast(toast);
                bsToast.hide();
            } else {
                // Alternativa simple si no se carga la librería completa
                toast.style.display = 'none';
            }
        }, 5000);
    });
});
</script>
