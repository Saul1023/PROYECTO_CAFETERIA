<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 text-brown mb-1">Crear Nueva Promoción</h1>
                    <p class="text-muted mb-0">Completa el formulario para crear una nueva promoción</p>
                </div>
                <a href="{{ route('admin.promociones') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>
                    Volver a Promociones
                </a>
            </div>

            <!-- Formulario -->
            <div class="card">
                <div class="card-body">
                    <form wire:submit="savePromocion">
                        <div class="row g-4">
                            <!-- Información Básica -->
                            <div class="col-12">
                                <h5 class="text-brown mb-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Información Básica
                                </h5>
                            </div>

                            <!-- Nombre -->
                            <div class="col-md-6">
                                <label class="form-label">Nombre de la Promoción <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    wire:model="nombre" placeholder="Ej: Descuento de Verano, Oferta Especial..."
                                    maxlength="100">
                                @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Máximo 100 caracteres</small>
                            </div>

                            <!-- Tipo de Descuento -->
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Descuento <span class="text-danger">*</span></label>
                                <select class="form-select @error('tipo_descuento') is-invalid @enderror"
                                    wire:model="tipo_descuento" disabled>
                                    <option value="porcentaje">Porcentaje (%)</option>
                                </select>
                                @error('tipo_descuento')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Actualmente solo disponible porcentaje</small>
                            </div>

                            <!-- Valor Descuento -->
                            <div class="col-md-6">
                                <label class="form-label">Valor de Descuento <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number"
                                        class="form-control @error('valor_descuento') is-invalid @enderror"
                                        wire:model="valor_descuento" min="0.01" max="100" step="0.01"
                                        placeholder="0.00">
                                    <span class="input-group-text">%</span>
                                    @error('valor_descuento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Entre 0.01% y 100%</small>
                            </div>

                            <!-- Estado -->
                            <div class="col-md-6">
                                <label class="form-label">Estado</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" role="switch" wire:model="estado"
                                        id="estadoPromocion">
                                    <label class="form-check-label" for="estadoPromocion">
                                        {{ $estado ? 'Promoción Activa' : 'Promoción Inactiva' }}
                                    </label>
                                </div>
                                <small class="text-muted">
                                    Las promociones inactivas no se aplicarán a los productos
                                </small>
                            </div>

                            <!-- Fechas -->
                            <div class="col-12">
                                <h5 class="text-brown mb-3 mt-4">
                                    <i class="bi bi-calendar-event me-2"></i>
                                    Período de Vigencia
                                </h5>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Fecha de Inicio <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror"
                                    wire:model="fecha_inicio" min="{{ date('Y-m-d') }}">
                                @error('fecha_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">No puede ser anterior a hoy</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Fecha de Fin <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror"
                                    wire:model="fecha_fin"
                                    min="{{ $fecha_inicio ? date('Y-m-d', strtotime($fecha_inicio . ' +1 day')) : date('Y-m-d', strtotime('+1 day')) }}">
                                @error('fecha_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Debe ser posterior a la fecha de inicio</small>
                            </div>

                            <!-- Descripción -->
                            <div class="col-12">
                                <label class="form-label">Descripción</label>
                                <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                    wire:model="descripcion" rows="3"
                                    placeholder="Describe los detalles de la promoción, condiciones especiales, etc..."
                                    maxlength="500"></textarea>
                                @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Máximo 500 caracteres</small>
                            </div>

                            <!-- Selección de Productos -->
                            <div class="col-12">
                                <h5 class="text-brown mb-3 mt-4">
                                    <i class="bi bi-box-seam me-2"></i>
                                    Productos en Promoción
                                </h5>

                                <label class="form-label">Seleccionar Productos <span
                                        class="text-danger">*</span></label>

                                <!-- Filtro rápido -->
                                <div class="mb-3">
                                    <input type="text" class="form-control" placeholder="Buscar productos..."
                                        id="buscarProducto">
                                    <small class="text-muted">Escribe para filtrar la lista de productos</small>
                                </div>

                                <div class="border rounded p-3 @error('productosSeleccionados') border-danger @enderror"
                                    style="max-height: 300px; overflow-y: auto;">

                                    @if($productos->count() > 0)
                                    <div class="row g-2">
                                        @foreach($productos as $producto)
                                        <div class="col-md-6 col-lg-4 producto-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    value="{{ $producto->id_producto }}"
                                                    wire:model="productosSeleccionados"
                                                    id="producto{{ $producto->id_producto }}">
                                                <label class="form-check-label d-block"
                                                    for="producto{{ $producto->id_producto }}">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <strong>{{ $producto->nombre }}</strong>
                                                            <br>
                                                            <small
                                                                class="text-muted">{{ $producto->precio_formateado }}</small>
                                                        </div>
                                                        <span class="badge bg-info ms-2">
                                                            Stock: {{ $producto->stock }}
                                                        </span>
                                                    </div>
                                                    @if($producto->categoria)
                                                    <small class="text-muted">
                                                        Categoría: {{ $producto->categoria->nombre }}
                                                    </small>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="text-center py-4">
                                        <i class="bi bi-box-seam display-4 text-muted mb-3"></i>
                                        <p class="text-muted mb-0">No hay productos disponibles</p>
                                        <small class="text-muted">Activa algunos productos para poder asignarlos a
                                            promociones</small>
                                    </div>
                                    @endif
                                </div>

                                @error('productosSeleccionados')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror

                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Seleccionados: <strong>{{ count($productosSeleccionados) }}</strong> productos
                                    </small>
                                </div>
                            </div>

                            <!-- Resumen -->
                            @if($nombre && $valor_descuento && count($productosSeleccionados) > 0)
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-eye me-2"></i>
                                        Vista Previa de la Promoción
                                    </h6>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <strong>Nombre:</strong> {{ $nombre }}<br>
                                            <strong>Descuento:</strong> {{ $valor_descuento }}%<br>
                                            <strong>Estado:</strong>
                                            <span class="badge {{ $estado ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $estado ? 'Activa' : 'Inactiva' }}
                                            </span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Vigencia:</strong>
                                            {{ \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') }} -
                                            {{ \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y') }}<br>
                                            <strong>Productos:</strong> {{ count($productosSeleccionados) }}
                                            productos<br>
                                            <strong>Duración:</strong>
                                            {{ \Carbon\Carbon::parse($fecha_inicio)->diffInDays($fecha_fin) }} días
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Botones -->
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center pt-4 border-top">
                                    <button type="button" wire:click="resetForm" class="btn btn-outline-secondary"
                                        wire:loading.attr="disabled">
                                        <i class="bi bi-arrow-clockwise me-2"></i>
                                        Limpiar Formulario
                                    </button>

                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.promociones') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-x-circle me-2"></i>
                                            Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                                            {{ $productos->count() == 0 ? 'disabled' : '' }}>
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
        </div>
    </div>

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

<!-- Script para filtro de productos -->
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
            const bsToast = new bootstrap.Toast(toast);
            bsToast.hide();
        }, 5000);
    });
});
</script>
