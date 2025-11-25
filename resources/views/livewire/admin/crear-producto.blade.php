<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4">
        <div class="mb-8 border-b border-gray-200 pb-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-3xl font-extrabold text-teal-900 flex items-center">
                        <i class="bi bi-box-seam-fill text-teal-600 mr-3"></i>
                        Crear Nuevo Producto
                    </h1>
                    <p class="text-gray-500">Complete los detalles esenciales para agregar un nuevo artículo al
                        inventario.</p>
                </div>
                <a href="{{ route('productos') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm font-medium text-gray-700 hover:bg-gray-100 transition duration-150 ease-in-out">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Volver
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100">
            <div class="p-8">
                <form wire:submit.prevent="saveProducto" class="space-y-8">

                    <div class="space-y-6">
                        <h2 class="text-xl font-semibold text-teal-700 border-b border-teal-100 pb-2 flex items-center">
                            <i class="bi bi-info-circle-fill mr-2 text-teal-400"></i>
                            Información General
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Categoría <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="id_categoria"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-teal-500 focus:border-teal-500 @error('id_categoria') border-red-500 @enderror shadow-sm appearance-none transition duration-150">
                                    <option value="">Seleccionar categoría...</option>
                                    @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('id_categoria')
                                <p class="text-red-500 text-sm mt-1 flex items-center"><i
                                        class="bi bi-exclamation-circle-fill mr-1"></i> {{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre del Producto <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="nombre"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-teal-500 focus:border-teal-500 @error('nombre') border-red-500 @enderror shadow-sm transition duration-150"
                                    placeholder="Ej: Café Americano, Sandwich de Pollo">
                                @error('nombre')
                                <p class="text-red-500 text-sm mt-1 flex items-center"><i
                                        class="bi bi-exclamation-circle-fill mr-1"></i> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                            <textarea wire:model="descripcion" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-teal-500 focus:border-teal-500 @error('descripcion') border-red-500 @enderror shadow-sm transition duration-150"
                                placeholder="Describa el producto, ingredientes o características especiales..."></textarea>
                            @error('descripcion')
                            <p class="text-red-500 text-sm mt-1 flex items-center"><i
                                    class="bi bi-exclamation-circle-fill mr-1"></i> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-6 pt-6 border-t border-gray-100">
                        <h2 class="text-xl font-semibold text-teal-700 border-b border-teal-100 pb-2 flex items-center">
                            <i class="bi bi-currency-dollar mr-2 text-teal-400"></i>
                            Precios e Inventario
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Precio (Bs.) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-sm">Bs.</span>
                                    <input type="number" wire:model="precio" step="0.01" min="0"
                                        class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2.5 focus:ring-teal-500 focus:border-teal-500 @error('precio') border-red-500 @enderror shadow-sm transition duration-150"
                                        placeholder="0.00">
                                </div>
                                @error('precio')
                                <p class="text-red-500 text-sm mt-1 flex items-center"><i
                                        class="bi bi-exclamation-circle-fill mr-1"></i> {{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Stock Actual <span class="text-red-500">*</span>
                                </label>
                                <input type="number" wire:model="stock" min="0"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-teal-500 focus:border-teal-500 @error('stock') border-red-500 @enderror shadow-sm transition duration-150"
                                    placeholder="0">
                                @error('stock')
                                <p class="text-red-500 text-sm mt-1 flex items-center"><i
                                        class="bi bi-exclamation-circle-fill mr-1"></i> {{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Stock Mínimo (Alerta) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" wire:model="stock_minimo" min="0"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-teal-500 focus:border-teal-500 @error('stock_minimo') border-red-500 @enderror shadow-sm transition duration-150"
                                    placeholder="0">
                                @error('stock_minimo')
                                <p class="text-red-500 text-sm mt-1 flex items-center"><i
                                        class="bi bi-exclamation-circle-fill mr-1"></i> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 pt-6 border-t border-gray-100">
                        <h2 class="text-xl font-semibold text-teal-700 border-b border-teal-100 pb-2 flex items-center">
                            <i class="bi bi-image-fill mr-2 text-teal-400"></i>
                            Multimedia y Visibilidad
                        </h2>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Imagen del Producto</label>
                            <input type="file" wire:model="imagen"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 border border-gray-300 rounded-lg shadow-sm @error('imagen') border-red-500 @enderror"
                                accept="image/*">
                            @error('imagen')
                            <p class="text-red-500 text-sm mt-1 flex items-center"><i
                                    class="bi bi-exclamation-circle-fill mr-1"></i> {{ $message }}</p>
                            @enderror

                            @if($imagen)
                            <div class="mt-4 p-3 bg-gray-50 rounded-lg inline-block">
                                <p class="text-xs font-semibold text-gray-600 mb-2">Vista previa:</p>
                                <img src="{{ $imagen->temporaryUrl() }}" alt="Vista previa de la imagen cargada"
                                    class="max-w-[150px] h-auto rounded-lg border-2 border-teal-200 shadow-md object-cover">
                            </div>
                            @endif
                        </div>

                        <div class="flex items-start pt-2">
                            <input type="checkbox" wire:model="estado" id="estado"
                                class="w-5 h-5 text-teal-600 border-gray-300 rounded focus:ring-teal-500 mt-0.5">
                            <label for="estado" class="ml-3 text-sm font-medium text-gray-700">
                                Producto activo
                                <p class="text-xs text-gray-500 mt-0.5">Si está activo, el producto estará disponible
                                    para la venta y aparecerá en las listas.</p>
                            </label>
                        </div>
                    </div>


                    <div class="flex flex-col sm:flex-row gap-3 justify-end pt-8 border-t border-gray-200">
                        <button type="button" wire:click="resetForm"
                            class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 shadow-sm transition duration-150 ease-in-out">
                            <i class="bi bi-arrow-counterclockwise mr-2"></i>
                            Limpiar
                        </button>

                        <a href="{{ route('productos') }}"
                            class="inline-flex items-center justify-center px-4 py-2.5 border border-red-400 rounded-lg text-red-700 hover:bg-red-50 shadow-sm transition duration-150 ease-in-out text-center">
                            <i class="bi bi-x-circle-fill mr-2"></i>
                            Cancelar
                        </a>

                        <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-2.5 bg-teal-600 text-white rounded-lg shadow-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition duration-150 ease-in-out"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove><i class="bi bi-check-circle-fill mr-2"></i> Crear Producto</span>
                            <span wire:loading class="flex items-center">
                                <span
                                    class="animate-spin mr-2 h-4 w-4 border-t-2 border-b-2 border-teal-300 rounded-full"></span>
                                Guardando...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if (session()->has('success'))
        <div
            class="fixed bottom-6 right-6 bg-green-500 text-white px-6 py-3 rounded-lg shadow-xl flex items-center z-50 transition duration-300 ease-out animate-pulse">
            <i class="bi bi-check-circle-fill mr-3 text-lg"></i>
            {{ session('success') }}
        </div>
        @endif
        @if (session()->has('error'))
        <div
            class="fixed bottom-6 right-6 bg-red-600 text-white px-6 py-3 rounded-lg shadow-xl flex items-center z-50 transition duration-300 ease-out animate-pulse">
            <i class="bi bi-exclamation-triangle-fill mr-3 text-lg"></i>
            {{ session('error') }}
        </div>
        @endif
    </div>
</div>
