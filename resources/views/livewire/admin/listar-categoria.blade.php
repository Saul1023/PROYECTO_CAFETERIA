<style>
/* Estilos Personalizados para Listar Categorías (Alineados con Tailwind Teal) */
:root {
    --teal-600: #047878;
    --teal-50: #f0fdfa;
    --teal-500: #14b8a6;
}

.h2.text-teal {
    color: var(--teal-600) !important;
    font-weight: 800;
    /* Asegura que el título principal se vea fuerte */
}

/* Botón Principal (Nueva Categoría) */
.btn-teal {
    background-color: var(--teal-600);
    border-color: var(--teal-600);
    color: white;
    transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
}

.btn-teal:hover {
    background-color: #0d9488;
    /* Tono más oscuro de teal para hover */
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

/* Badges / Etiquetas (Contador de Productos) */
.badge.bg-teal {
    background-color: var(--teal-500) !important;
    color: #047878;
    /* Color oscuro para que el texto sea legible */
    font-weight: 600;
}

/* Switch de Estado */
.form-check-input:checked {
    background-color: var(--teal-600);
    border-color: var(--teal-600);
}
</style>

<div class="container-fluid p-4">
    <div class="row">
        <div class="col-12">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 border-bottom border-gray-200 pb-4">
                <div class="mb-3 mb-md-0">
                    <h1 class="h2 text-teal mb-1 d-flex align-items-center">
                        <i class="bi bi-tags-fill text-teal me-2"></i>
                        Gestión de Categorías
                    </h1>
                    <p class="text-muted mb-0">Administra las categorías de productos de la cafetería</p>
                </div>
                <a href="{{ route('categorias.crear') }}" class="btn btn-teal d-flex align-items-center shadow-sm">
                    <i class="bi bi-plus-circle me-2"></i>
                    Nueva Categoría
                </a>
            </div>

            <div class="card mb-4 shadow-sm border border-gray-100">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Buscar</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-search text-teal"></i>
                                </span>
                                <input type="text" wire:model.live="search" placeholder="Buscar categorías..."
                                    class="form-control border-start-0">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Filtrar por Estado</label>
                            <select wire:model.live="filterEstado" class="form-select">
                                <option value="">Todos los estados</option>
                                <option value="1">Activas</option>
                                <option value="0">Inactivas</option>
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
                                    <th>Categoría</th>
                                    <th>Productos</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categorias as $categoria)
                                <tr>
                                    <td class="ps-4">
                                        @if($categoria->imagen_url)
                                        <img src="{{ asset('storage/' . $categoria->imagen_url) }}"
                                            alt="{{ $categoria->nombre }}" class="rounded"
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
                                            <strong class="d-block text-dark">{{ $categoria->nombre }}</strong>
                                            <small
                                                class="text-muted">{{ Str::limit($categoria->descripcion, 50) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-teal">
                                            {{ $categoria->productos_count }} productos
                                        </span>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                {{ $categoria->estado ? 'checked' : '' }}
                                                wire:click="toggleEstado({{ $categoria->id_categoria }})"
                                                style="--bs-form-switch-bg: url(&quot;data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e&quot;);">
                                        </div>
                                        <small class="text-muted ms-2">
                                            {{ $categoria->estado ? 'Activa' : 'Inactiva' }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('categorias.editar', $categoria->id_categoria) }}"
                                                class="btn btn-sm btn-outline-teal d-flex align-items-center">
                                                <i class="bi bi-pencil me-1"></i>
                                                Editar
                                            </a>
                                            <button wire:click="confirmDelete({{ $categoria->id_categoria }})"
                                                class="btn btn-sm btn-outline-danger d-flex align-items-center">
                                                <i class="bi bi-trash me-1"></i>
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 bg-gray-50">
                                        <div class="text-muted">
                                            <i class="bi bi-tags display-4 d-block mb-3"></i>
                                            <h5>No se encontraron categorías</h5>
                                            <p class="mb-0">Intenta ajustar los filtros de búsqueda</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(isset($categorias) && method_exists($categorias, 'hasPages') && $categorias->hasPages())
                    <div class="card-footer bg-white">
                        {{ $categorias->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($categoriaToDelete)
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
                    <h6>¿Estás seguro de que deseas eliminar esta categoría?</h6>
                    <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button wire:click="deleteCategoria" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>
                        Eliminar
                    </button>
                    <button wire:click="categoriaToDelete = null" class="btn btn-outline-secondary">
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
