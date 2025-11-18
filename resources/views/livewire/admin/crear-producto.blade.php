<div class="min-h-screen bg-gray-50 py-8">
    <!-- Contenedor Principal -->
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="space-y-2">
                    <h1 class="text-3xl font-bold text-gray-900">Crear Nuevo Producto</h1>
                    <p class="text-gray-600">Complete el formulario para agregar un nuevo producto</p>
                </div>
                <a href="{{ route('productos') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Volver
                </a>
            </div>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6">
                <form wire:submit.prevent="saveProducto" class="space-y-6">
                    <!-- Categoría y Nombre -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Categoría -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Categoría *
                            </label>
                            <select wire:model="id_categoria"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('id_categoria') border-red-500 @enderror">
                                <option value="">Seleccionar categoría...</option>
                                @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                            @error('id_categoria')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nombre -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre del Producto *
                            </label>
                            <input type="text"
                                wire:model="nombre"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('nombre') border-red-500 @enderror"
                                placeholder="Ej: Café Americano">
                            @error('nombre')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                        <textarea wire:model="descripcion"
                            rows="3"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('descripcion') border-red-500 @enderror"
                            placeholder="Descripción del producto..."></textarea>
                        @error('descripcion')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Precio, Stock, Stock Mínimo -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Precio -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Precio (Bs.) *
                            </label>
                            <input type="number"
                                wire:model="precio"
                                step="0.01"
                                min="0"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('precio') border-red-500 @enderror"
                                placeholder="0.00">
                            @error('precio')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stock -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Stock *
                            </label>
                            <input type="number"
                                wire:model="stock"
                                min="0"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('stock') border-red-500 @enderror"
                                placeholder="0">
                            @error('stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stock Mínimo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Stock Mínimo *
                            </label>
                            <input type="number"
                                wire:model="stock_minimo"
                                min="0"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('stock_minimo') border-red-500 @enderror"
                                placeholder="0">
                            @error('stock_minimo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Imagen -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Imagen del Producto</label>
                        <input type="file"
                            wire:model="imagen"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('imagen') border-red-500 @enderror"
                            accept="image/*">
                        @error('imagen')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        @if($imagen)
                        <div class="mt-3">
                            <p class="text-sm text-gray-600 mb-2">Vista previa:</p>
                            <img src="{{ $imagen->temporaryUrl() }}"
                                class="max-w-xs rounded-lg border border-gray-300">
                        </div>
                        @endif
                    </div>

                    <!-- Estado -->
                    <div class="flex items-center">
                        <input type="checkbox"
                            wire:model="estado"
                            id="estado"
                            class="w-4 h-4 text-amber-600 border-gray-300 rounded focus:ring-amber-500">
                        <label for="estado" class="ml-2 text-sm text-gray-700">
                            Producto activo
                        </label>
                    </div>

                    <!-- Botones -->
                    <div class="flex flex-col sm:flex-row gap-3 justify-end pt-6 border-t border-gray-200">
                        <button type="button"
                            wire:click="resetForm"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Limpiar
                        </button>
                        <a href="{{ route('productos') }}"
                            class="px-4 py-2 border border-red-300 rounded-lg text-red-700 hover:bg-red-50 text-center">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700">
                            Crear Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Mensajes Flash - DENTRO del mismo contenedor principal -->
        @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
        @endif
        @if (session()->has('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg">
            {{ session('error') }}
        </div>
        @endif
    </div>
</div>
