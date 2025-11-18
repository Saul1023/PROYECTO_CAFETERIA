<div class="container-fluid p-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 text-brown mb-1">Punto de Venta</h1>
                    <p class="text-muted mb-0">Venta rápida - Directo en local</p>
                </div>
                <button wire:click="resetVenta" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise me-2"></i>
                    Nueva Venta
                </button>
            </div>

            <!-- Mensajes Flash -->
            @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="row">
                <!-- Columna Izquierda: Configuración y Productos -->
                <div class="col-md-8">
                    <!-- Configuración de Venta -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-gear me-2"></i>
                                Configuración de Venta
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Tipo de Consumo -->
                                <div class="col-md-6">
                                    <label class="form-label">Tipo de Consumo <span class="text-danger">*</span></label>
                                    <select wire:model="tipoConsumo" class="form-select">
                                        <option value="mesa">En Mesa</option>
                                        <option value="para_llevar">Para Llevar</option>
                                    </select>
                                </div>

                                <!-- Selección de Mesa -->
                                @if($tipoConsumo === 'mesa')
                                <div class="col-md-6">
                                    <label class="form-label">Mesa <span class="text-danger">*</span></label>
                                    <select wire:model="mesaSeleccionada"
                                        class="form-select @error('mesaSeleccionada') is-invalid @enderror">
                                        <option value="">Seleccionar mesa...</option>
                                        @foreach($mesas as $mesa)
                                        <option value="{{ $mesa->id_mesa }}">
                                            Mesa {{ $mesa->numero_mesa }}
                                            ({{ $mesa->capacidad }} personas) - {{ $mesa->ubicacion }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('mesaSeleccionada')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Catálogo de Productos -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-grid me-2"></i>
                                Productos Disponibles
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Barra de búsqueda -->
                            <div class="mb-4">
                                <input type="text" wire:model.live="search" class="form-control"
                                    placeholder="Buscar productos...">
                            </div>

                            @if(count($productos) > 0)
                            <!-- Navegación por Categorías -->
                            <ul class="nav nav-pills mb-4" id="categoriasTab" role="tablist">
                                @foreach($productos as $categoria => $productosCategoria)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="pill"
                                        data-bs-target="#cat-{{ \Illuminate\Support\Str::slug($categoria) }}"
                                        type="button">
                                        {{ $categoria ?: 'Sin Categoría' }}
                                    </button>
                                </li>
                                @endforeach
                            </ul>

                            <!-- Productos por Categoría -->
                            <div class="tab-content">
                                @foreach($productos as $categoria => $productosCategoria)
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                    id="cat-{{ \Illuminate\Support\Str::slug($categoria) }}">
                                    <div class="row g-3">
                                        @foreach($productosCategoria as $producto)
                                        <div class="col-xl-3 col-lg-4 col-md-6">
                                            <div class="card product-card h-100 border">
                                                <div class="card-body text-center">
                                                    @if($producto['imagen'])
                                                    <img src="{{ $producto['imagen'] }}" class="card-img-top mb-3"
                                                        alt="{{ $producto['nombre'] }}"
                                                        style="height: 100px; object-fit: cover; width: 100%;">
                                                    @else
                                                    <div class="bg-light rounded mb-3 d-flex align-items-center justify-content-center"
                                                        style="height: 100px;">
                                                        <i class="bi bi-cup-hot text-muted"
                                                            style="font-size: 2rem;"></i>
                                                    </div>
                                                    @endif

                                                    <h6 class="card-title">{{ $producto['nombre'] }}</h6>
                                                    <p class="card-text text-success fw-bold mb-1">
                                                        Bs. {{ number_format($producto['precio'], 2) }}
                                                    </p>
                                                    <p class="card-text small text-muted mb-2">
                                                        Stock: {{ $producto['stock'] }}
                                                    </p>
                                                    <button wire:click="agregarProducto({{ $producto['id_producto'] }})"
                                                        class="btn btn-primary btn-sm w-100"
                                                        {{ $producto['stock'] <= 0 ? 'disabled' : '' }}>
                                                        <i class="bi bi-plus-circle me-1"></i>
                                                        Agregar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="bi bi-exclamation-triangle display-4 text-muted"></i>
                                <p class="mt-3">No hay productos disponibles</p>
                                @if($search)
                                <p class="text-muted">Intenta con otros términos de búsqueda</p>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Carrito y Resumen -->
                <div class="col-md-4">
                    <div class="card sticky-top" style="top: 20px;">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-cart me-2"></i>
                                Carrito de Venta
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Lista de Productos en Carrito -->
                            @if(count($carrito) > 0)
                            <div class="table-responsive" style="max-height: 300px;">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th class="text-center">Cant</th>
                                            <th class="text-end">Subtotal</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($carrito as $index => $item)
                                        <tr>
                                            <td>
                                                <small class="d-block fw-bold">{{ $item['nombre'] }}</small>
                                                <small class="text-muted">Bs.
                                                    {{ number_format($item['precio_unitario'], 2) }} c/u</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="input-group input-group-sm" style="width: 90px;">
                                                    <button
                                                        wire:click="actualizarCantidad({{ $index }}, {{ $item['cantidad'] - 1 }})"
                                                        class="btn btn-outline-secondary" type="button"
                                                        {{ $item['cantidad'] <= 1 ? 'disabled' : '' }}>
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                    <input type="number" class="form-control text-center"
                                                        value="{{ $item['cantidad'] }}"
                                                        wire:change="actualizarCantidad({{ $index }}, $event.target.value)"
                                                        min="1" max="100">
                                                    <button
                                                        wire:click="actualizarCantidad({{ $index }}, {{ $item['cantidad'] + 1 }})"
                                                        class="btn btn-outline-secondary" type="button">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <strong>Bs. {{ number_format($item['subtotal'], 2) }}</strong>
                                            </td>
                                            <td>
                                                <button wire:click="eliminarProducto({{ $index }})"
                                                    class="btn btn-outline-danger btn-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Resumen de Totales -->
                            <div class="border-top pt-3 mt-3">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <strong>Subtotal:</strong>
                                    </div>
                                    <div class="col-6 text-end">
                                        <strong>Bs. {{ number_format($subtotal, 2) }}</strong>
                                    </div>

                                    @if($descuentoPromociones > 0)
                                    <div class="col-6">
                                        <strong class="text-success">Descuento Promociones:</strong>
                                    </div>
                                    <div class="col-6 text-end">
                                        <strong class="text-success">-Bs.
                                            {{ number_format($descuentoPromociones, 2) }}</strong>
                                    </div>
                                    @endif

                                    <div class="col-6">
                                        <label class="form-label mb-1">Descuento Adicional:</label>
                                        <input type="number" wire:model.live="descuentoManual"
                                            class="form-control form-control-sm" min="0" max="{{ $subtotal }}"
                                            step="0.01" placeholder="0.00">
                                    </div>
                                    <div class="col-6 text-end pt-3">
                                        <strong>-Bs. {{ number_format($descuentoManual, 2) }}</strong>
                                    </div>

                                    <div class="col-12 border-top mt-2 pt-2">
                                        <div class="row">
                                            <div class="col-6">
                                                <h5 class="mb-0">Total:</h5>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h5 class="text-success mb-0">Bs. {{ number_format($total, 2) }}</h5>
                                            </div>
                                        </div>
                                    </div>

                                    @if($descuentoPromociones > 0)
                                    <div class="col-12">
                                        <small class="text-muted">
                                            <i class="bi bi-tag me-1"></i>
                                            Se aplicaron descuentos de promoción automáticamente
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Información de Pago -->
                            <div class="mt-4">
                                <label class="form-label">Método de Pago <span class="text-danger">*</span></label>
                                <select wire:model="metodoPago"
                                    class="form-select @error('metodoPago') is-invalid @enderror">
                                    <option value="efectivo">Efectivo</option>
                                    <option value="tarjeta">Tarjeta</option>
                                    <option value="transferencia">Transferencia</option>
                                    <option value="yape">Yape</option>
                                    <option value="plin">Plin</option>
                                    <option value="mixto">Mixto</option>
                                </select>
                                @error('metodoPago')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Observaciones -->
                            <div class="mt-3">
                                <label class="form-label">Observaciones</label>
                                <textarea wire:model="observaciones" class="form-control" rows="2"
                                    placeholder="Notas especiales, instrucciones..."></textarea>
                            </div>

                            <!-- Botón Finalizar Venta -->
                            <div class="mt-4">
                                <button wire:click="finalizarVenta" wire:loading.attr="disabled"
                                    class="btn btn-success w-100 py-3">
                                    <i class="bi bi-check-circle me-2"></i>
                                    <span wire:loading.remove>Finalizar Venta</span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        Procesando...
                                    </span>
                                </button>
                            </div>
                            @else
                            <!-- Carrito Vacío -->
                            <div class="text-center py-5">
                                <i class="bi bi-cart-x display-4 text-muted"></i>
                                <p class="mt-3 text-muted">El carrito está vacío</p>
                                <small class="text-muted">Agrega productos desde el catálogo</small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-ocultar mensajes flash después de 5 segundos
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Inicializar los tabs de Bootstrap
    const triggerTabList = [].slice.call(document.querySelectorAll('#categoriasTab button'));
    triggerTabList.forEach(function(triggerEl) {
        const tabTrigger = new bootstrap.Tab(triggerEl);
        triggerEl.addEventListener('click', function(event) {
            event.preventDefault();
            tabTrigger.show();
        });
    });
});
</script>
@endpush