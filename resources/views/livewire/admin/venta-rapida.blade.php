

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
                                    <select wire:model.live="tipoConsumo" class="form-select @error('tipoConsumo') is-invalid @enderror">
                                        <option value="mesa">En Mesa</option>
                                    </select>
                                    @error('tipoConsumo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Selección de Mesa/Reservación -->
                                @if($tipoConsumo === 'mesa')
                                <!-- Número de Personas -->
                                    <div class="col-md-12">
                                    </div>
                                    <!-- Alerta cuando no hay mesa seleccionada -->
                                    @if(!$mesaSeleccionada)
                                    <div class="col-12">
                                        <div class="alert alert-warning d-flex align-items-center">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            <span>Por favor selecciona una <strong>Mesa Disponible</strong> o una <strong>Reservación</strong> para continuar con la venta</span>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-md-6">
                                        <label class="form-label">Seleccionar Mesa/Reservación <span class="text-danger">*</span></label>
                                        <div class="d-flex gap-2">
                                            <button type="button" wire:click="abrirSelectorMesa"
                                                class="btn btn-outline-primary flex-fill">
                                                <i class="bi bi-table me-1"></i> Mesa Disponible
                                            </button>
                                            <button type="button" wire:click="abrirSelectorReservacion"
                                                class="btn btn-outline-success flex-fill">
                                                <i class="bi bi-calendar-check me-1"></i> Reservación
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Mostrar Mesa Seleccionada -->
                                    @if($mesaSeleccionada)
                                    <div class="col-12">
                                        <div class="alert alert-info d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-check-circle me-2"></i>
                                                <strong>Mesa seleccionada:</strong>
                                                @php
                                                $mesa = \App\Models\Mesa::find($mesaSeleccionada);
                                                @endphp
                                                Mesa {{ $mesa->numero_mesa }} - {{ $mesa->ubicacion }} (Cap.
                                                {{ $mesa->capacidad }})

                                                @if($reservacionSeleccionada)
                                                @php
                                                $reservacion = \App\Models\Reservacion::find($reservacionSeleccionada);
                                                @endphp
                                                <span class="badge bg-success ms-2">Con Reservación</span>
                                                <small class="d-block mt-1">
                                                    Cliente: {{ $reservacion->usuario->nombre_completo }} |
                                                    Monto a favor: Bs. {{ number_format($reservacion->monto_pago, 2) }}
                                                </small>
                                                @else
                                                <span class="badge bg-primary ms-2">Sin Reservación</span>
                                                @endif
                                            </div>
                                            <button type="button" wire:click="limpiarSeleccion"
                                                class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-x-circle"></i> Cambiar
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                @endif

                                <!-- Selección de Cliente (solo si no hay reservación) -->
                                @if(!$reservacionSeleccionada)
                                <div class="col-md-6">
                                    <label class="form-label">Cliente</label>
                                    <div class="input-group">
                                        <select wire:model="clienteSeleccionado" class="form-select">
                                            <option value="">Venta general (sin cliente)</option>
                                            @foreach($clientes as $cliente)
                                            <option value="{{ $cliente->id_usuario }}">
                                                {{ $cliente->nombre_completo }}
                                                @if($cliente->nombre_usuario)
                                                ({{ $cliente->nombre_usuario }})
                                                @endif
                                            </option>
                                            @endforeach
                                        </select>
                                        <button wire:click="abrirModalCliente" type="button"
                                            class="btn btn-outline-primary">
                                            <i class="bi bi-person-plus"></i> Nuevo
                                        </button>
                                    </div>
                                    <small class="text-muted">Selecciona un cliente existente o registra uno
                                        nuevo</small>
                                </div>

                                <!-- Información del Cliente Seleccionado -->
                                @if($clienteSeleccionado)
                                @php
                                $clienteSeleccionadoObj = $clientes->firstWhere('id_usuario', $clienteSeleccionado);
                                @endphp
                                <div class="col-md-6">
                                    <div class="alert alert-info py-2">
                                        <small>
                                            <strong>Cliente seleccionado:</strong><br>
                                            👤 {{ $clienteSeleccionadoObj->nombre_completo }}<br>
                                            @if($clienteSeleccionadoObj->email)
                                            📧 {{ $clienteSeleccionadoObj->email }}<br>
                                            @endif
                                            @if($clienteSeleccionadoObj->telefono)
                                            📞 {{ $clienteSeleccionadoObj->telefono }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                @endif
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
                            <!-- Filtros de Búsqueda y Categoría -->
                            <div class="row g-3 mb-4">
                                <!-- Barra de búsqueda -->
                                <div class="col-md-6">
                                    <label class="form-label small text-muted mb-1">Buscar producto</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-search"></i>
                                        </span>
                                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                                            placeholder="Buscar por nombre...">
                                        @if($search)
                                        <button class="btn btn-outline-secondary" type="button"
                                            wire:click="$set('search', '')">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Filtro por Categoría -->
                                <div class="col-md-6">
                                    <label class="form-label small text-muted mb-1">Filtrar por categoría</label>
                                    <select wire:model.live="categoriaSeleccionada" class="form-select">
                                        <option value="todas">📦 Todas las categorías</option>
                                        @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id_categoria }}">
                                            {{ $categoria->nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Indicadores de filtros activos -->
                            @if($search || $categoriaSeleccionada !== 'todas')
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="bi bi-funnel me-1"></i>
                                    Filtros activos:
                                    @if($search)
                                    <span class="badge bg-primary ms-1">
                                        Búsqueda: "{{ $search }}"
                                        <button type="button" class="btn-close btn-close-white ms-1"
                                            style="font-size: 0.6rem;" wire:click="$set('search', '')"></button>
                                    </span>
                                    @endif
                                    @if($categoriaSeleccionada !== 'todas')
                                    @php
                                    $categoriaNombre = $categorias->firstWhere('id_categoria',
                                    $categoriaSeleccionada)->nombre ?? '';
                                    @endphp
                                    <span class="badge bg-info ms-1">
                                        Categoría: {{ $categoriaNombre }}
                                        <button type="button" class="btn-close btn-close-white ms-1"
                                            style="font-size: 0.6rem;"
                                            wire:click="$set('categoriaSeleccionada', 'todas')"></button>
                                    </span>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-outline-secondary ms-2"
                                        wire:click="$set('search', ''); $set('categoriaSeleccionada', 'todas')">
                                        <i class="bi bi-x-circle me-1"></i> Limpiar filtros
                                    </button>
                                </small>
                            </div>
                            @endif

                            @if(count($productos) > 0)
                            <!-- Navegación por Categorías (tabs) -->
                            <ul class="nav nav-pills mb-4 flex-wrap" id="categoriasTab" role="tablist">
                                @foreach($productos as $categoria => $productosCategoria)
                                <li class="nav-item mb-2" role="presentation">
                                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="pill"
                                        data-bs-target="#cat-{{ \Illuminate\Support\Str::slug($categoria) }}"
                                        type="button">
                                        {{ $categoria ?: 'Sin Categoría' }}
                                        <span
                                            class="badge bg-light text-dark ms-1">{{ count($productosCategoria) }}</span>
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
                                            <div class="card product-card h-100 border hover-card">
                                                <div class="card-body text-center p-3">
                                                    @if($producto['imagen'])
                                                    <img src="{{ $producto['imagen'] }}"
                                                        class="card-img-top mb-3 rounded"
                                                        alt="{{ $producto['nombre'] }}"
                                                        style="height: 100px; object-fit: cover; width: 100%;">
                                                    @else
                                                    <div class="bg-light rounded mb-3 d-flex align-items-center justify-content-center"
                                                        style="height: 100px;">
                                                        <i class="bi bi-cup-hot text-muted"
                                                            style="font-size: 2rem;"></i>
                                                    </div>
                                                    @endif

                                                    <h6 class="card-title mb-2">{{ $producto['nombre'] }}</h6>

                                                    @if($producto['tiene_promocion'])
                                                    <div class="mb-2">
                                                        <small class="text-decoration-line-through text-muted">
                                                            Bs.
                                                            {{ number_format($producto['precio_original'] ?? $producto['precio'], 2) }}
                                                        </small>
                                                        <span class="badge bg-success ms-1">
                                                            <i class="bi bi-tag-fill"></i> Oferta
                                                        </span>
                                                    </div>
                                                    @endif

                                                    <p class="card-text text-success fw-bold mb-1 fs-5">
                                                        Bs. {{ number_format($producto['precio'], 2) }}
                                                    </p>

                                                    <p class="card-text small mb-2">
                                                        <span
                                                            class="badge {{ $producto['stock'] > 10 ? 'bg-success' : ($producto['stock'] > 0 ? 'bg-warning' : 'bg-danger') }}">
                                                            <i class="bi bi-box me-1"></i>
                                                            Stock: {{ $producto['stock'] }}
                                                        </span>
                                                    </p>

                                                    @if($producto['descripcion'])
                                                    <p class="card-text small text-muted mb-3"
                                                        style="font-size: 0.75rem;">
                                                        {{ Str::limit($producto['descripcion'], 50) }}
                                                    </p>
                                                    @endif

                                                    <button wire:click="agregarProducto({{ $producto['id_producto'] }})"
                                                        class="btn btn-primary btn-sm w-100"
                                                        {{ $producto['stock'] <= 0 ? 'disabled' : '' }}>
                                                        <i class="bi bi-plus-circle me-1"></i>
                                                        {{ $producto['stock'] <= 0 ? 'Sin Stock' : 'Agregar' }}
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
                            <!-- Sin resultados -->
                            <div class="text-center py-5">
                                <i class="bi bi-inbox display-4 text-muted"></i>
                                <h5 class="mt-3">No se encontraron productos</h5>
                                @if($search || $categoriaSeleccionada !== 'todas')
                                <p class="text-muted">
                                    No hay productos que coincidan con los filtros aplicados.
                                </p>
                                <button class="btn btn-outline-primary mt-2"
                                    wire:click="$set('search', ''); $set('categoriaSeleccionada', 'todas')">
                                    <i class="bi bi-arrow-clockwise me-2"></i>
                                    Ver todos los productos
                                </button>
                                @else
                                <p class="text-muted">No hay productos disponibles en este momento</p>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                 <!-- Columna Derecha: Carrito y Resumen -->
                <div class="col-md-4">
                    <div class="card sticky-carrito">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-cart me-2"></i>
                                Carrito de Venta
                            </h5>
                        </div>
                        <div class="card-body carrito-body">
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
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <button wire:click="decrementarCantidad({{ $index }})"
                                                        wire:loading.attr="disabled"
                                                        class="btn btn-outline-secondary btn-sm" type="button"
                                                        {{ $item['cantidad'] <= 1 ? 'disabled' : '' }}>
                                                        <i class="bi bi-dash"></i>
                                                    </button>

                                                    <span class="mx-3 fw-bold"
                                                        style="min-width: 30px; text-align: center;">
                                                        {{ $item['cantidad'] }}
                                                    </span>

                                                    <button wire:click="incrementarCantidad({{ $index }})"
                                                        wire:loading.attr="disabled"
                                                        class="btn btn-outline-secondary btn-sm" type="button">
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
                                    <div class="col-6 text-end pt-3">
                                        <strong>-Bs. {{ number_format($descuentoManual, 2) }}</strong>
                                    </div>

                                    @if($reservacionSeleccionada)
                                    @php
                                    $reservacion = \App\Models\Reservacion::find($reservacionSeleccionada);
                                    @endphp
                                    <div class="col-6">
                                        <strong class="text-primary">Monto Reservación:</strong>
                                        <small class="d-block text-muted">Código: {{ $reservacion->codigo_qr }}</small>
                                    </div>
                                    <div class="col-6 text-end">
                                        <strong class="text-primary">-Bs.
                                            {{ number_format($reservacion->monto_pago, 2) }}</strong>
                                    </div>
                                    @endif

                                    <div class="col-12 border-top mt-2 pt-2">
                                        <div class="row">
                                            <div class="col-6">
                                                <h5 class="mb-0">Total a Pagar:</h5>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h5 class="text-success mb-0">Bs. {{ number_format($total, 2) }}</h5>
                                            </div>
                                        </div>
                                    </div>

                                    @if($reservacionSeleccionada)
                                    <div class="col-12">
                                        <div class="alert alert-success py-2 mb-0">
                                            <small>
                                                <i class="bi bi-info-circle me-1"></i>
                                                <strong>Con Reservación:</strong> El cliente ya pagó Bs.
                                                {{ number_format($reservacion->monto_pago, 2) }}
                                            </small>
                                        </div>
                                    </div>
                                    @endif

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
                                <button wire:click="finalizarVenta"
                                    wire:loading.attr="disabled"
                                    {{ !$mesaSeleccionada || count($carrito) === 0 ? 'disabled' : '' }}
                                    class="btn btn-success w-100 py-3"
                                    title="{{ !$mesaSeleccionada ? 'Selecciona una mesa o reservación' : (count($carrito) === 0 ? 'Agrega productos al carrito' : 'Finalizar venta') }}">
                                    <i class="bi bi-check-circle me-2"></i>
                                    <span wire:loading.remove>
                                        @if(!$mesaSeleccionada)
                                            Selecciona Mesa/Reservación
                                        @elseif(count($carrito) === 0)
                                            Carrito Vacío
                                        @else
                                            Finalizar Venta
                                        @endif
                                    </span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        Procesando...
                                    </span>
                                </button>

                                @if(!$mesaSeleccionada && count($carrito) > 0)
                                <small class="text-danger d-block text-center mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Debes seleccionar una mesa o reservación para continuar
                                </small>
                                @endif
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

            <!-- Modal para Registrar Cliente Rápido -->
            @if($mostrarModalCliente)
            <div class="modal fade show d-block" tabindex="-1"
                style="background-color: rgba(0,0,0,0.5); position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 1050;"
                wire:click.self="cerrarModalCliente">
                <div class="modal-dialog modal-lg" style="position: relative; top: 50%; transform: translateY(-50%);">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-person-plus me-2"></i>
                                Registrar Nuevo Cliente
                            </h5>
                            <button wire:click="cerrarModalCliente" type="button"
                                class="btn-close btn-close-white"></button>
                        </div>
                        <div class="modal-body">
                            <form wire:submit.prevent="guardarClienteRapido">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre Completo *</label>
                                        <input type="text" wire:model="clienteTemporal.nombre_completo"
                                            class="form-control @error('clienteTemporal.nombre_completo') is-invalid @enderror"
                                            placeholder="Ej: Juan Pérez García">
                                        @error('clienteTemporal.nombre_completo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Nombre de Usuario *</label>
                                        <input type="text" wire:model="clienteTemporal.nombre_usuario"
                                            class="form-control @error('clienteTemporal.nombre_usuario') is-invalid @enderror"
                                            placeholder="Ej: juan.perez">
                                        @error('clienteTemporal.nombre_usuario')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" wire:model="clienteTemporal.email"
                                            class="form-control @error('clienteTemporal.email') is-invalid @enderror"
                                            placeholder="Ej: cliente@email.com">
                                        @error('clienteTemporal.email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Teléfono</label>
                                        <input type="text" wire:model="clienteTemporal.telefono"
                                            class="form-control @error('clienteTemporal.telefono') is-invalid @enderror"
                                            placeholder="Ej: 70000000">
                                        @error('clienteTemporal.telefono')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-4 alert alert-info">
                                    <small>
                                        <i class="bi bi-info-circle me-1"></i>
                                        El cliente recibirá la contraseña por defecto: <strong>cliente123</strong>
                                    </small>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button wire:click="cerrarModalCliente" type="button" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancelar
                            </button>
                            <button wire:click="guardarClienteRapido" type="button" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Registrar Cliente
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Modal de Mesas Disponibles -->
            @if($mostrarSelectorMesa)
            <div class="modal fade show d-block" tabindex="-1"
                style="background-color: rgba(0,0,0,0.5); position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 1050;"
                wire:click.self="cerrarSelectores">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">
                                        <i class="bi bi-table me-2"></i>
                                        Mesas Disponibles - {{ date('d/m/Y') }}
                                        <span class="badge bg-light text-primary ms-2">
                                            <i class="bi bi-people-fill me-1"></i>{{ $numeroPersonas }} {{ $numeroPersonas == 1 ? 'persona' : 'personas' }}
                                        </span>
                                    </h5>
                                <button wire:click="cerrarSelectores" type="button"
                                    class="btn-close btn-close-white"></button>
                        </div>
                                <div class="modal-body">
                                    @if(count($mesasDisponiblesHoy) > 0)
                                        <div class="row g-3">
                                        @foreach($mesasDisponiblesHoy as $mesa)
                                        <div class="col-md-6">
                                        <div class="card border-primary h-100 hover-shadow" style="cursor: pointer;"
                                        wire:click="seleccionarMesaDisponible({{ $mesa->id_mesa }})">
                                        <div class="card-body text-center">
                                            <i class="bi bi-table display-4 text-primary mb-3"></i>
                                            <h5 class="card-title">Mesa {{ $mesa->numero_mesa }}</h5>
                                            <p class="card-text">
                                                <span class="badge bg-info">{{ $mesa->ubicacion }}</span>
                                            </p>
                                            <p class="text-muted mb-0">
                                                <i class="bi bi-people me-1"></i>
                                                Capacidad: {{ $mesa->capacidad }} personas
                                            </p>

                                            <!-- AGREGAR ESTE BADGE -->
                                            @if($mesa->capacidad >= $numeroPersonas && $mesa->capacidad < ($numeroPersonas + 3))
                                            <span class="badge bg-success mt-2">
                                                <i class="bi bi-star-fill me-1"></i>Recomendada
                                            </span>
                                            @elseif($mesa->capacidad >= ($numeroPersonas + 3))
                                            <span class="badge bg-warning mt-2">
                                                <i class="bi bi-arrow-up me-1"></i>Mayor capacidad
                                            </span>
                                            @endif

                                            <button class="btn btn-primary btn-sm mt-3">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Seleccionar Mesa
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="bi bi-exclamation-triangle display-4 text-warning"></i>
                                <h5 class="mt-3">No hay mesas disponibles</h5>
                                <p class="text-muted">Todas las mesas están ocupadas o reservadas para hoy</p>
                            </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button wire:click="cerrarSelectores" type="button" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Modal de Reservaciones -->
            @if($mostrarReservaciones)
            <div class="modal fade show d-block" tabindex="-1"
                style="background-color: rgba(0,0,0,0.5); position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 1050;"
                wire:click.self="cerrarSelectores">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-calendar-check me-2"></i>
                                Reservaciones de Hoy - {{ date('d/m/Y') }}
                            </h5>
                            <button wire:click="cerrarSelectores" type="button"
                                class="btn-close btn-close-white"></button>
                        </div>
                        <div class="modal-body">
                            @if(count($reservacionesHoy) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Código</th>
                                            <th>Mesa</th>
                                            <th>Cliente</th>
                                            <th>Hora</th>
                                            <th>Personas</th>
                                            <th>Monto</th>
                                            <th class="text-center">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reservacionesHoy as $reservacion)
                                        <tr>
                                            <td>
                                                <strong>#{{ $reservacion->id_reservacion }}</strong>
                                                @if($reservacion->codigo_qr)
                                                <br><small
                                                    class="badge bg-secondary">{{ $reservacion->codigo_qr }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">Mesa
                                                    {{ $reservacion->mesa->numero_mesa }}</span>
                                                <br><small
                                                    class="text-muted">{{ $reservacion->mesa->ubicacion }}</small>
                                            </td>
                                            <td>
                                                @if($reservacion->usuario)
                                                <strong>{{ $reservacion->usuario->nombre_completo }}</strong>
                                                <br><small class="text-muted">{{ $reservacion->usuario->email }}</small>
                                                @if($reservacion->usuario->telefono)
                                                <br><small><i
                                                        class="bi bi-telephone me-1"></i>{{ $reservacion->usuario->telefono }}</small>
                                                @endif
                                                @else
                                                <span class="text-muted">Sin cliente</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ \Carbon\Carbon::parse($reservacion->hora_reservacion)->format('H:i') }}</strong>
                                                @php
                                                $horaReservacion =
                                                \Carbon\Carbon::parse($reservacion->hora_reservacion);
                                                $ahora = \Carbon\Carbon::now();
                                                $diff = $ahora->diffInMinutes($horaReservacion, false);
                                                @endphp
                                                @if($diff < 0) <br><small class="text-danger">Hace {{ abs($diff) }}
                                                        min</small>
                                                    @elseif($diff < 30) <br><small class="text-warning">En {{ $diff }}
                                                            min</small>
                                                        @else
                                                        <br><small class="text-muted">En {{ $diff }} min</small>
                                                        @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-secondary">{{ $reservacion->numero_personas }}</span>
                                            </td>
                                            <td>
                                                <strong class="text-success">Bs.
                                                    {{ number_format($reservacion->monto_pago, 2) }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <button
                                                    wire:click="seleccionarReservacion({{ $reservacion->id_reservacion }})"
                                                    class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Seleccionar
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="bi bi-calendar-x display-4 text-muted"></i>
                                <h5 class="mt-3">No hay reservaciones para hoy</h5>
                                <p class="text-muted">No se encontraron reservaciones confirmadas para la fecha actual
                                </p>
                            </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button wire:click="cerrarSelectores" type="button" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Modal Comprobante -->
            @if($mostrarComprobante && $comprobanteData)
            <div class="modal fade show d-block" tabindex="-1"
                style="background-color: rgba(0,0,0,0.5); position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 1050;"
                wire:click.self="cerrarComprobante">
                <div class="modal-dialog modal-xl" style="position: relative; top: 50%; transform: translateY(-50%);">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-receipt me-2"></i>
                                Comprobante de Venta - {{ $comprobanteData['numero_venta'] }}
                            </h5>
                            <button wire:click="cerrarComprobante" type="button"
                                class="btn-close btn-close-white"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Contenido del comprobante -->
                            <div class="comprobante-preview bg-white p-4 border rounded">
                                <!-- Encabezado -->
                                <div class="text-center mb-4 border-bottom pb-3">
                                    <h3 class="text-primary fw-bold">{{ $comprobanteData['empresa']['nombre'] }}</h3>
                                    <p class="text-muted mb-1">{{ $comprobanteData['empresa']['direccion'] }}</p>
                                    <p class="text-muted mb-1">Tel: {{ $comprobanteData['empresa']['telefono'] }} • NIT:
                                        {{ $comprobanteData['empresa']['nit'] }}</p>
                                    <h4 class="text-warning mt-2">COMPROBANTE DE VENTA</h4>
                                </div>

                                <!-- Información de la venta -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <p><strong>N° Venta:</strong> {{ $comprobanteData['numero_venta'] }}</p>
                                        <p><strong>N° Pedido:</strong> {{ $comprobanteData['numero_pedido'] }}</p>
                                        <p><strong>Fecha:</strong> {{ $comprobanteData['fecha'] }}</p>
                                        <p><strong>Método Pago:</strong> {{ ucfirst($comprobanteData['metodo_pago']) }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Cliente:</strong> {{ $comprobanteData['cliente'] }}</p>
                                        <p><strong>Vendedor:</strong> {{ $comprobanteData['vendedor'] }}</p>
                                        <p><strong>Tipo Consumo:</strong>
                                            {{ $comprobanteData['tipo_consumo'] === 'mesa' ? 'En Mesa' : 'Para Llevar' }}
                                        </p>
                                        <p><strong>Mesa:</strong> {{ $comprobanteData['mesa'] }}</p>
                                    </div>
                                </div>

                                <!-- Productos -->
                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-primary">
                                            <tr>
                                                <th width="10%">Cant</th>
                                                <th width="50%">Producto</th>
                                                <th width="20%" class="text-end">P. Unitario</th>
                                                <th width="20%" class="text-end">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($comprobanteData['items'] as $item)
                                            <tr>
                                                <td>{{ $item['cantidad'] }}</td>
                                                <td>
                                                    {{ $item['nombre'] }}
                                                    @if($item['tiene_promocion'])
                                                    <span class="badge bg-success ms-1">Promoción</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">Bs.
                                                    {{ number_format($item['precio_unitario'], 2) }}</td>
                                                <td class="text-end">Bs. {{ number_format($item['subtotal'], 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Totales -->
                                <div class="row justify-content-end">
                                    <div class="col-md-6">
                                        <div class="border-top pt-2">
                                            <div class="d-flex justify-content-between">
                                                <span>Subtotal:</span>
                                                <span>Bs. {{ number_format($comprobanteData['subtotal'], 2) }}</span>
                                            </div>
                                            @if($comprobanteData['descuento_promociones'] > 0)
                                            <div class="d-flex justify-content-between text-success">
                                                <span>Desc. Promociones:</span>
                                                <span>-Bs.
                                                    {{ number_format($comprobanteData['descuento_promociones'], 2) }}</span>
                                            </div>
                                            @endif
                                            @if($comprobanteData['descuento_manual'] > 0)
                                            <div class="d-flex justify-content-between text-success">
                                                <span>Desc. Adicional:</span>
                                                <span>-Bs.
                                                    {{ number_format($comprobanteData['descuento_manual'], 2) }}</span>
                                            </div>
                                            @endif
                                            @if(isset($comprobanteData['reservacion']) &&
                                            $comprobanteData['reservacion'])
                                            <div class="d-flex justify-content-between text-primary">
                                                <span>
                                                    Monto Reservación:
                                                    <small class="d-block text-muted">
                                                        Código: {{ $comprobanteData['reservacion']['codigo_qr'] }}
                                                    </small>
                                                </span>
                                                <span>-Bs.
                                                    {{ number_format($comprobanteData['reservacion']['monto_pago'], 2) }}</span>
                                            </div>
                                            @endif
                                            <div
                                                class="d-flex justify-content-between fw-bold fs-5 border-top pt-2 mt-2">
                                                <span>TOTAL:</span>
                                                <span class="text-success">Bs.
                                                    {{ number_format($comprobanteData['total'], 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($comprobanteData['observaciones'])
                                <div class="mt-4 p-3 bg-light rounded">
                                    <strong>Observaciones:</strong> {{ $comprobanteData['observaciones'] }}
                                </div>
                                @endif

                                <div class="text-center mt-4 text-muted">
                                    <small>¡Gracias por su preferencia! - Comprobante generado el
                                        {{ date('d/m/Y H:i:s') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="cerrarComprobante">
                                <i class="bi bi-x-circle me-1"></i> Cerrar y Continuar
                            </button>
                            <button type="button" class="btn btn-primary" onclick="imprimirComprobante()">
                                <i class="bi bi-printer me-1"></i> Imprimir
                            </button>
                            @if(isset($comprobanteData['pdf_path']))
                            <a href="{{ route('descargar.comprobante', ['venta' => $comprobanteData['numero_venta']]) }}"
                                class="btn btn-success">
                                <i class="bi bi-download me-1"></i> Descargar PDF
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
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
// Scroll al primer error cuando hay validación fallida
document.addEventListener('livewire:init', () => {
    Livewire.on('validacionError', () => {
        // Scroll al mensaje de error
        setTimeout(() => {
            const alertElement = document.querySelector('.alert-danger, .alert-warning');
            if (alertElement) {
                alertElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 100);
    });
});

// Función para imprimir el comprobante
function imprimirComprobante() {
    const comprobante = document.querySelector('.comprobante-preview');
    const ventana = window.open('', '_blank');
    ventana.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Comprobante de Venta</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .text-center { text-align: center; }
                .text-end { text-align: right; }
                .fw-bold { font-weight: bold; }
                .table { width: 100%; border-collapse: collapse; }
                .table th, .table td { border: 1px solid #ddd; padding: 8px; }
                .table th { background-color: #f8f9fa; }
                .border-top { border-top: 1px solid #ddd; }
                .pt-2 { padding-top: 8px; }
                .mt-2 { margin-top: 8px; }
                .fs-5 { font-size: 1.25rem; }
            </style>
        </head>
        <body>
            ${comprobante.innerHTML}
        </body>
        </html>
    `);
    ventana.document.close();
    ventana.print();
}
@push('styles') <
    style >
    .hover - shadow: hover {
        box - shadow: 0 0.5 rem 1 rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-2 px);
        transition: all 0.3 s ease;
    }

    .hover - card {
        transition: all 0.3 s ease;
    }

    .hover - card: hover {
        transform: translateY(-5 px);
        box - shadow: 0 0.5 rem 1 rem rgba(0, 0, 0, 0.15);
        border - color: #0d6efd !important;
}
/* Asegurar que el carrito no se superponga al navbar */
    .sticky-carrito {
        position: sticky;
        top: 80px;
        z-index: 1020;
        max-height: calc(100vh - 100px);
        overflow-y: auto;
    }

    /* Scroll suave para el contenido del carrito */
    .sticky-carrito .card-body {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }
.product-card .btn-primary:hover {
    transform: scale(1.05);
    transition: transform 0.2s ease;
}

.nav-pills .nav-link {
    border-radius: 20px;
}

.nav-pills .nav-link.active {
    background-color: # 0 d6efd;
    } <
    /style>
@endpush
</script>
@endpush
