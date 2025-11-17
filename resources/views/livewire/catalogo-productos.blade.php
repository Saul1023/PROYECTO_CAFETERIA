<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-orange-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-amber-600 to-orange-600 text-white py-16 px-4">
        <div class="container mx-auto">
            <h1 class="text-4xl md:text-6xl font-bold mb-4 animate-fade-in">
                ☕ Nuestro Menú
            </h1>
            <p class="text-xl text-amber-100">Descubre sabores únicos en cada taza</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Barra de Búsqueda y Filtros -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 sticky top-4 z-40">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Búsqueda -->
                <div class="flex-1">
                    <div class="relative">
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="buscar"
                            placeholder="🔍 Buscar productos..."
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all"
                        >
                        <svg class="absolute left-4 top-4 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Categorías -->
                <div class="flex gap-2 overflow-x-auto pb-2">
                    <button 
                        wire:click="filtrarCategoria(null)"
                        class="px-6 py-3 rounded-xl font-medium whitespace-nowrap transition-all {{ !$categoriaSeleccionada ? 'bg-amber-500 text-white shadow-lg scale-105' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    >
                        Todos
                    </button>
                    @foreach($categorias as $cat)
                        <button 
                            wire:click="filtrarCategoria({{ $cat->id_categoria }})"
                            class="px-6 py-3 rounded-xl font-medium whitespace-nowrap transition-all {{ $categoriaSeleccionada == $cat->id_categoria ? 'bg-amber-500 text-white shadow-lg scale-105' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                        >
                            {{ $cat->nombre }}
                        </button>
                    @endforeach
                </div>

                <!-- Ordenar -->
                <select 
                    wire:model.live="ordenar"
                    class="px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-2 focus:ring-amber-200"
                >
                    <option value="recientes">Más recientes</option>
                    <option value="precio_asc">Precio: Menor a Mayor</option>
                    <option value="precio_desc">Precio: Mayor a Menor</option>
                    <option value="nombre">Nombre A-Z</option>
                </select>
            </div>
        </div>

        <!-- Grid de Productos -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @forelse($productos as $producto)
                <div 
                    class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 hover:shadow-2xl transition-all duration-300 cursor-pointer group"
                    wire:click="verDetalles({{ $producto->id_producto }})"
                >
                    <!-- Imagen -->
                    <div class="relative h-56 bg-gradient-to-br from-amber-100 to-orange-100 overflow-hidden">
                        @if($producto->imagen_url)
                            <img 
                                src="{{ $producto->imagen_url }}" 
                                alt="{{ $producto->nombre }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            >
                        @else
                            <div class="flex items-center justify-center h-full text-6xl">
                                ☕
                            </div>
                        @endif
                        
                        <!-- Badge de stock bajo -->
                        @if($producto->stock_bajo)
                            <span class="absolute top-3 right-3 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                ¡Últimas unidades!
                            </span>
                        @endif
                    </div>

                    <!-- Contenido -->
                    <div class="p-5">
                        <div class="mb-2">
                            <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-3 py-1 rounded-full">
                                {{ $producto->categoria->nombre }}
                            </span>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-amber-600 transition-colors">
                            {{ $producto->nombre }}
                        </h3>
                        
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                            {{ $producto->descripcion }}
                        </p>

                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-amber-600">
                                {{ $producto->precio_formateado }}
                            </span>
                            
                            <button 
                                wire:click.stop="agregarAlCarrito({{ $producto->id_producto }})"
                                class="bg-gradient-to-r from-amber-500 to-orange-500 text-white px-4 py-2 rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-1"
                            >
                                🛒 Agregar
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <div class="text-6xl mb-4">🔍</div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">No se encontraron productos</h3>
                    <p class="text-gray-500 mb-4">Intenta con otros filtros</p>
                    <button 
                        wire:click="limpiarFiltros"
                        class="bg-amber-500 text-white px-6 py-3 rounded-xl hover:bg-amber-600 transition-all"
                    >
                        Limpiar filtros
                    </button>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        <div class="mt-8">
            {{ $productos->links() }}
        </div>
    </div>

    <!-- Modal de Detalles -->
    @if($verModal && $productoSeleccionado)
        <div 
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
            x-data="{ show: @entangle('verModal') }"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click.self="$wire.cerrarModal()"
        >
            <div 
                class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
            >
                <!-- Imagen -->
                <div class="relative h-80 bg-gradient-to-br from-amber-100 to-orange-100">
                    @if($productoSeleccionado->imagen_url)
                        <img 
                            src="{{ $productoSeleccionado->imagen_url }}" 
                            alt="{{ $productoSeleccionado->nombre }}"
                            class="w-full h-full object-cover"
                        >
                    @else
                        <div class="flex items-center justify-center h-full text-9xl">☕</div>
                    @endif
                    
                    <button 
                        wire:click="cerrarModal"
                        class="absolute top-4 right-4 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Contenido -->
                <div class="p-8">
                    <div class="mb-4">
                        <span class="text-sm font-semibold text-amber-600 bg-amber-50 px-4 py-2 rounded-full">
                            {{ $productoSeleccionado->categoria->nombre }}
                        </span>
                    </div>

                    <h2 class="text-3xl font-bold text-gray-800 mb-4">
                        {{ $productoSeleccionado->nombre }}
                    </h2>

                    <p class="text-gray-600 mb-6 text-lg">
                        {{ $productoSeleccionado->descripcion }}
                    </p>

                    <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Precio</p>
                            <p class="text-4xl font-bold text-amber-600">
                                {{ $productoSeleccionado->precio_formateado }}
                            </p>
                        </div>

                        <button 
                            wire:click="agregarAlCarrito({{ $productoSeleccionado->id_producto }})"
                            class="bg-gradient-to-r from-amber-500 to-orange-500 text-white px-8 py-4 rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-lg font-semibold"
                        >
                            🛒 Agregar al carrito
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Carrito Flotante -->
    @if(count($carrito) > 0)
        <div class="fixed bottom-8 right-8 z-50">
            <a 
                href="/reservar" 
                class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-4 rounded-full shadow-2xl hover:shadow-3xl transform hover:scale-110 transition-all flex items-center gap-3 animate-bounce"
            >
                <span class="text-2xl">🛒</span>
                <span class="font-bold">{{ count($carrito) }} producto(s)</span>
                <span class="bg-white text-green-600 px-3 py-1 rounded-full font-bold">
                    Bs. {{ number_format($this->totalCarrito, 2) }}
                </span>
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Animaciones y notificaciones
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('alerta', (event) => {
            const data = event[0] || event;
            const tipo = data.tipo === 'success' ? 'success' : 'error';
            const icono = tipo === 'success' ? '✅' : '❌';
            
            // Crear notificación personalizada
            const notif = document.createElement('div');
            notif.className = `fixed top-4 right-4 z-50 ${tipo === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white px-6 py-4 rounded-xl shadow-2xl transform transition-all duration-300 flex items-center gap-3`;
            notif.innerHTML = `<span class="text-2xl">${icono}</span><span class="font-semibold">${data.mensaje}</span>`;
            document.body.appendChild(notif);
            
            setTimeout(() => {
                notif.style.opacity = '0';
                notif.style.transform = 'translateX(100%)';
                setTimeout(() => notif.remove(), 300);
            }, 3000);
        });
    });
</script>
@endpush