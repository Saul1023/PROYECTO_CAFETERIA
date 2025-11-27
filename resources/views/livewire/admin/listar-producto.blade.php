<div>
    @push('styles')
    <style>
    /* Estilos Personalizados para Listar Productos (Alineados con Tailwind Teal) */
    :root {
        --teal-600: #047878;
        --teal-50: #f0fdfa;
        --teal-500: #14b8a6;
    }

    .h2.text-teal {
        color: var(--teal-600) !important;
        font-weight: 800;
    }

    /* Botón Principal (Nuevo Producto) */
    .btn-teal {
        background-color: var(--teal-600);
        border-color: var(--teal-600);
        color: white;
        transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
    }

    .btn-teal:hover {
        background-color: #0d9488;
        border-color: #0d9488;
        color: white;
    }

    .btn-teal:focus,
    .btn-teal:active {
        box-shadow: 0 0 0 0.25rem rgba(4, 120, 120, 0.5) !important;
    }

    /* Botones de Acción (Editar) */
    .btn-outline-teal {
        color: var(--teal-600);
        border-color: var(--teal-600);
        transition: all 0.15s ease-in-out;
    }

    .btn-outline-teal:hover {
        background-color: var(--teal-600);
        color: white;
    }

    /* Badges / Etiquetas */
    .badge.bg-teal {
        background-color: var(--teal-500) !important;
        color: white;
        font-weight: 600;
    }

    /* Switch de Estado */
    .form-check-input:checked {
        background-color: var(--teal-600);
        border-color: var(--teal-600);
    }

    /* Botones deshabilitados */
    .btn-outline-danger:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .form-check-input:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Estilos para paginación */
    .pagination {
        margin: 0;
    }

    .page-link {
        color: var(--teal-600);
        border-color: #dee2e6;
    }

    .page-link:hover {
        color: white;
        background-color: var(--teal-600);
        border-color: var(--teal-600);
    }

    .page-item.active .page-link {
        background-color: var(--teal-600);
        border-color: var(--teal-600);
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
    }

    /* Toast notifications container */
    #toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    /* Asegurar que el modal se muestre correctamente */
    .modal.show {
        display: block !important;
    }

    /* Prevenir clicks fuera del modal */
    .modal {
        pointer-events: auto;
    }
    </style>
    @endpush

    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 border-bottom border-gray-200 pb-4">
                    <div class="mb-3 mb-md-0">
                        <h1 class="h2 text-teal mb-1 d-flex align-items-center">
                            <i class="bi bi-box-seam-fill text-teal me-2"></i>
                            Gestión de Productos
                        </h1>
                        <p class="text-muted mb-0">Administra los productos de la cafetería</p>
                    </div>
                    <a href="{{ route('productos.crear') }}" class="btn btn-teal d-flex align-items-center shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i>
                        Nuevo Producto
                    </a>
                </div>

                <div class="card mb-4 shadow-sm border border-gray-100">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-muted">Buscar</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-search text-teal"></i>
                                    </span>
                                    <input type="text" wire:model.live="search" placeholder="Buscar productos..."
                                        class="form-control border-start-0">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-muted">Filtrar por Categoría</label>
                                <select wire:model.live="filterCategoria" class="form-select">
                                    <option value="">Todas las categorías</option>
                                    @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-muted">Filtrar por Estado</label>
                                <select wire:model.live="filterEstado" class="form-select">
                                    <option value="">Todos los estados</option>
                                    <option value="1">Activos</option>
                                    <option value="0">Inactivos</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-lg border border-gray-100">
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
                                            @if($producto->imagen)
                                            <img src="{{ asset('storage/' . $producto->imagen) }}"
                                                alt="{{ $producto->nombre }}" class="rounded"
                                                style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center border"
                                                style="width: 50px; height: 50px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <strong class="d-block text-dark">{{ $producto->nombre }}</strong>
                                                <small
                                                    class="text-muted">{{ Str::limit($producto->descripcion, 50) }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-teal">
                                                {{ $producto->categoria->nombre ?? 'Sin categoría' }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-success">Bs. {{ number_format($producto->precio, 2) }}</strong>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span
                                                    class="badge {{ $producto->stock == 0 ? 'bg-danger' : ($producto->stock <= $producto->stock_minimo ? 'bg-warning' : 'bg-success') }}">
                                                    {{ $producto->stock }}
                                                </span>
                                                @if($producto->stock > 0 && $producto->stock <= $producto->stock_minimo)
                                                <small class="text-warning ms-2" title="Stock bajo">
                                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                                </small>
                                                @endif
                                            </div>
                                            @if($producto->stock_minimo > 0)
                                            <small class="text-muted d-block">Mín: {{ $producto->stock_minimo }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    {{ $producto->estado ? 'checked' : '' }}
                                                    {{ $producto->estado && $producto->stock > 0 ? 'disabled' : '' }}
                                                    wire:click="toggleEstado({{ $producto->id_producto }})"
                                                    title="{{ $producto->estado && $producto->stock > 0 ? 'No se puede desactivar con stock disponible' : '' }}">
                                            </div>
                                            <small class="text-muted ms-2">
                                                {{ $producto->estado ? 'Activo' : 'Inactivo' }}
                                            </small>
                                            @if($producto->estado && $producto->stock > 0)
                                            <div>
                                                <small class="text-warning d-block mt-1">
                                                    <i class="bi bi-lock-fill"></i> Con stock
                                                </small>
                                            </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('productos.editar', $producto->id_producto) }}"
                                                    class="btn btn-sm btn-outline-teal d-flex align-items-center">
                                                    <i class="bi bi-pencil me-1"></i>
                                                    Editar
                                                </a>
                                                <button wire:click="confirmDelete({{ $producto->id_producto }})"
                                                    class="btn btn-sm btn-outline-danger d-flex align-items-center"
                                                    {{ $producto->stock > 0 ? 'disabled' : '' }}
                                                    title="{{ $producto->stock > 0 ? 'No se puede eliminar con stock disponible' : 'Eliminar producto' }}">
                                                    <i class="bi bi-trash me-1"></i>
                                                    Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 bg-gray-50">
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

                        @if($productos->hasPages())
                        <div class="card-footer bg-white border-top">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div class="text-muted small">
                                    Mostrando {{ $productos->firstItem() ?? 0 }} a {{ $productos->lastItem() ?? 0 }} de {{ $productos->total() }} resultados
                                </div>
                                <nav aria-label="Paginación">
                                    {{ $productos->onEachSide(1)->links() }}
                                </nav>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($productoToDelete)
        <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5)" tabindex="-1" wire:key="delete-modal-{{ $productoToDelete }}">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title text-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Confirmar Eliminación
                        </h5>
                        <button type="button" class="btn-close" wire:click="cancelDelete" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-trash display-4 text-danger mb-3 d-block"></i>
                        <h6>¿Estás seguro de que deseas eliminar este producto?</h6>
                        <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
                    </div>
                    <div class="modal-footer border-0 justify-content-center">
                        <button wire:click="deleteProducto" class="btn btn-danger" type="button">
                            <span wire:loading.remove wire:target="deleteProducto">
                                <i class="bi bi-trash me-2"></i>
                                Eliminar
                            </span>
                            <span wire:loading wire:target="deleteProducto">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Eliminando...
                            </span>
                        </button>
                        <button wire:click="cancelDelete" class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-x-circle me-2"></i>
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Container para notificaciones dinámicas -->
        <div id="toast-container"></div>
    </div>

    @push('scripts')
    <script>
        // Sistema de notificaciones
        document.addEventListener('livewire:init', () => {
            Livewire.on('show-message', (event) => {
                showToast(event[0].type, event[0].message);
            });
        });

        function showToast(type, message) {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const bgColor = type === 'success' ? 'bg-success' : 'bg-danger';
            const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';

            const toast = document.createElement('div');
            toast.className = `toast-notification alert ${bgColor} text-white d-flex align-items-center shadow-lg`;
            toast.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; animation: slideIn 0.3s ease-out;';
            toast.innerHTML = `
                <i class="bi ${icon} me-3 fs-5"></i>
                <div class="flex-grow-1">${message}</div>
                <button type="button" class="btn-close btn-close-white ms-3" onclick="this.parentElement.remove()"></button>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease-in';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Prevenir scroll cuando el modal está abierto
        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', () => {
                const modal = document.querySelector('.modal.show');
                if (modal) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });
        });

        // Asegurar que la paginación funcione correctamente
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('click', function(e) {
                if (e.target.matches('.page-link') || e.target.closest('.page-link')) {
                    e.preventDefault();
                }
            });
        });
    </script>
    @endpush
</div>
