<div class="container-fluid p-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                <div class="mb-3 mb-md-0">
                    <h1 class="h2 text-brown mb-1">Gestión de Productos</h1>
                    <p class="text-muted mb-0">Administra los productos de la cafetería</p>
                </div>
                <a href="{{ route('productos.crear') }}"
                    class="btn btn-primary d-flex align-items-center">
                    <i class="bi bi-plus-circle me-2"></i>
                    Nuevo Producto
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
                                    placeholder="Buscar productos..."
                                    class="form-control">
                            </div>
                        </div>

                        <!-- Filtro por Categoría -->
                        <div class="col-md-4">
                            <label class="form-label">Filtrar por Categoría</label>
                            <select wire:model.live="filterCategoria" class="form-select">
                                <option value="">Todas las categorías</option>
                                @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
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

            <!-- Tabla de Productos -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Imagen</th>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productos as $producto)
                                <tr>
                                    <td class="ps-4">
                                        @if($producto->imagen_url)
                                        <img src="{{ asset('storage/' . $producto->imagen) }}"
                                            alt="{{ $producto->nombre }}"
                                            class="rounded"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                            style="width: 50px; height: 50px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong class="d-block">{{ $producto->nombre }}</strong>
                                            <small class="text-muted">{{ Str::limit($producto->descripcion, 50) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $producto->categoria->nombre ?? 'Sin categoría' }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ $producto->precio_formateado }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge {{ $producto->stock_bajo ? 'bg-warning' : 'bg-success' }}">
                                                {{ $producto->stock }}
                                            </span>
                                            @if($producto->stock_bajo)
                                            <small class="text-warning ms-2" title="Stock bajo">
                                                <i class="bi bi-exclamation-triangle"></i>
                                            </small>
                                            @endif
                                        </div>
                                        @if($producto->stock_minimo > 0)
                                        <small class="text-muted d-block">Mín: {{ $producto->stock_minimo }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                role="switch"
                                                {{ $producto->estado ? 'checked' : '' }}
                                                wire:click="toggleEstado({{ $producto->id_producto }})">
                                        </div>
                                        <small class="text-muted ms-2">
                                            {{ $producto->estado ? 'Activo' : 'Inactivo' }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('productos.editar', $producto->id_producto) }}"
                                                class="btn btn-sm btn-outline-primary d-flex align-items-center">
                                                <i class="bi bi-pencil me-1"></i>
                                                Editar
                                            </a>
                                            <button wire:click="confirmDelete({{ $producto->id_producto }})"
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
                                            <i class="bi bi-box-seam display-4 d-block mb-3"></i>
                                            <h5>No se encontraron productos</h5>
                                            <p class="mb-0">Intenta ajustar los filtros de búsqueda</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($productos->hasPages())
                    <div class="card-footer bg-transparent">
                        {{ $productos->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación (MANTENEMOS SOLO ESTE MODAL) -->
    @if($productoToDelete)
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
                    <h6>¿Estás seguro de que deseas eliminar este producto?</h6>
                    <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button wire:click="deleteProducto" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>
                        Eliminar
                    </button>
                    <button wire:click="productoToDelete = null" class="btn btn-outline-secondary">
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
