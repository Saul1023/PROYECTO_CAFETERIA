<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="space-y-2">
                    <h1 class="text-3xl font-bold text-gray-900">Gestión de Categorías</h1>
                    <p class="text-gray-600">Administra las categorías de productos de la cafetería</p>
                </div>
                <a href="{{ route('admin.categorias.crear') }}"
                    class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700">
                    <i class="bi bi-plus-circle mr-2"></i>
                    Nueva Categoría
                </a>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Búsqueda -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-search text-gray-400"></i>
                            </div>
                            <input type="text"
                                wire:model.live="search"
                                placeholder="Buscar categorías..."
                                class="pl-10 w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        </div>
                    </div>

                    <!-- Filtro por Estado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por Estado</label>
                        <select wire:model.live="filterEstado" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            <option value="">Todos los estados</option>
                            <option value="1">Activas</option>
                            <option value="0">Inactivas</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Categorías -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Imagen
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Categoría
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Productos
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($categorias as $categoria)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($categoria->imagen_url)
                                <img src="{{ asset('storage/' . $categoria->imagen_url) }}"
                                    alt="{{ $categoria->nombre }}"
                                    class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                                @else
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                    <i class="bi bi-image text-gray-400"></i>
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $categoria->nombre }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($categoria->descripcion, 50) }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $categoria->productos_count }} productos
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="relative inline-block w-10 h-5 rounded-full cursor-pointer">
                                        <input type="checkbox"
                                            {{ $categoria->estado ? 'checked' : '' }}
                                            wire:click="toggleEstado({{ $categoria->id_categoria }})"
                                            class="absolute w-10 h-5 rounded-full bg-gray-300 checked:bg-amber-500 transition-colors duration-200 appearance-none cursor-pointer">
                                        <span class="absolute left-0.5 top-0.5 bg-white w-4 h-4 rounded-full transition-transform duration-200 transform checked:translate-x-5"></span>
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600">
                                        {{ $categoria->estado ? 'Activa' : 'Inactiva' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.categorias.editar', $categoria->id_categoria) }}"
                                        class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        <i class="bi bi-pencil mr-1"></i>
                                        Editar
                                    </a>
                                    <button wire:click="confirmDelete({{ $categoria->id_categoria }})"
                                        class="inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-red-700 bg-white hover:bg-red-50">
                                        <i class="bi bi-trash mr-1"></i>
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="bi bi-tags text-4xl mb-3 block"></i>
                                    <h3 class="text-lg font-medium">No se encontraron categorías</h3>
                                    <p class="mt-1">Intenta ajustar los filtros de búsqueda</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($categorias->hasPages())
            <div class="bg-white px-6 py-3 border-t border-gray-200">
                {{ $categorias->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    @if($categoriaToDelete)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-4 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-2">Confirmar Eliminación</h3>
                <div class="mt-2 px-4 py-3">
                    <p class="text-sm text-gray-500">
                        ¿Estás seguro de que deseas eliminar esta categoría?
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="flex gap-3 justify-center px-4 py-3">
                    <button wire:click="categoriaToDelete = null"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button wire:click="deleteCategoria"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Mensajes Flash -->
    @if (session()->has('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg z-50">
        {{ session('success') }}
    </div>
    @endif

    @if (session()->has('error'))
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg z-50">
        {{ session('error') }}
    </div>
    @endif
</div>