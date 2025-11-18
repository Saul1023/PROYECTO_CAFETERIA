<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <!-- Card del formulario -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-brown">
                            <i class="bi bi-plus-circle me-2"></i>
                            Crear Nueva Categoría
                        </h5>
                        <a href="{{ route('categorias') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>
                            Volver
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form wire:submit="saveCategoria">
                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">
                                Nombre de la Categoría <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control @error('nombre') is-invalid @enderror"
                                id="nombre"
                                wire:model="nombre"
                                placeholder="Ej: Bebidas Calientes, Postres, etc.">
                            @error('nombre')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                id="descripcion"
                                wire:model="descripcion"
                                rows="3"
                                placeholder="Descripción opcional de la categoría..."></textarea>
                            @error('descripcion')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Imagen -->
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen de la Categoría</label>
                            <input type="file"
                                class="form-control @error('imagen') is-invalid @enderror"
                                id="imagen"
                                wire:model="imagen"
                                accept="image/*">
                            @error('imagen')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                            <!-- Vista previa de la imagen -->
                            @if ($imagen)
                            <div class="mt-2">
                                <p class="text-muted mb-1">Vista previa:</p>
                                <img src="{{ $imagen->temporaryUrl() }}"
                                    class="img-thumbnail"
                                    style="max-height: 150px;">
                            </div>
                            @endif

                            <small class="text-muted">
                                Formatos soportados: JPG, PNG, GIF. Tamaño máximo: 2MB
                            </small>
                        </div>

                        <!-- Estado -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                    type="checkbox"
                                    role="switch"
                                    id="estado"
                                    wire:model="estado"
                                    {{ $estado ? 'checked' : '' }}>
                                <label class="form-check-label" for="estado">
                                    Categoría activa
                                </label>
                            </div>
                            <small class="text-muted">
                                Las categorías inactivas no estarán disponibles para nuevos productos.
                            </small>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button"
                                wire:click="resetForm"
                                class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Limpiar
                            </button>
                            <button type="submit"
                                class="btn btn-primary"
                                wire:loading.attr="disabled">
                                <i class="bi bi-check-circle me-1"></i>
                                <span wire:loading.remove>Crear Categoría</span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Creando...
                                </span>
                            </button>
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
