<div class="min-h-screen bg-gray-50 p-6">
    <!-- Header con bienvenida -->
    <div class="mb-6">
        <div class="bg-gradient-to-r from-amber-900 to-amber-700 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold mb-2 flex items-center">
                        👋 ¡Bienvenido, {{ auth()->user()->nombre_completo }}!
                    </h1>
                    <p class="text-amber-100 flex items-center">
                        <span class="bg-amber-800 bg-opacity-50 rounded-full p-1 mr-2">
                            🛡️
                        </span>
                        Rol: <strong class="ml-1">{{ auth()->user()->rol->nombre }}</strong>
                    </p>
                </div>
                <button wire:click="recargar"
                    class="bg-white text-amber-800 px-4 py-2 rounded-lg font-semibold hover:bg-amber-50 transition-all duration-200 flex items-center">
                    🔄 Actualizar
                </button>
            </div>
        </div>
    </div>

    <!-- Grid de estadísticas principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Ventas Hoy -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Ventas Hoy</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">
                        Bs. {{ number_format($ventasHoy['monto'], 2) }}
                    </h3>
                    <div class="flex items-center mt-2">
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full flex items-center">
                            📈 {{ $ventasHoy['cantidad'] }} ventas
                        </span>
                    </div>
                </div>
                <div class="bg-green-50 p-3 rounded-xl">
                    <span class="text-2xl">💰</span>
                </div>
            </div>
        </div>

        <!-- Reservaciones Hoy -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Reservaciones Hoy</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalReservaciones }}</h3>
                    <div class="flex items-center mt-2">
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full flex items-center">
                            🗓️ Para hoy
                        </span>
                    </div>
                </div>
                <div class="bg-blue-50 p-3 rounded-xl">
                    <span class="text-2xl">📅</span>
                </div>
            </div>
        </div>

        <!-- Productos -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Productos</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalProductos }}</h3>
                    <div class="flex items-center mt-2">
                        <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full flex items-center">
                            ⚠️ {{ $stockBajo }} stock bajo
                        </span>
                    </div>
                </div>
                <div class="bg-yellow-50 p-3 rounded-xl">
                    <span class="text-2xl">📦</span>
                </div>
            </div>
        </div>

        <!-- Mesas Disponibles -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Mesas Disponibles</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">
                        {{ $mesasDisponibles['disponibles'] }}/{{ $mesasDisponibles['total'] }}
                    </h3>
                    <div class="flex items-center mt-2">
                        <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full flex items-center">
                            ✅ {{ $mesasDisponibles['porcentaje'] }}% ocupación
                        </span>
                    </div>
                </div>
                <div class="bg-purple-50 p-3 rounded-xl">
                    <span class="text-2xl">🪑</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna izquierda - 2/3 -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Resumen de Ventas -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        📊 Resumen de Ventas Hoy
                    </h2>
                    <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                        Actualizado: {{ now()->format('H:i') }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Total Vendido -->
                    <div
                        class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200">
                        <div class="text-green-600 text-sm font-medium mb-2">Total Vendido</div>
                        <div class="text-2xl font-bold text-green-700 mb-2">
                            Bs. {{ number_format($ventasHoy['monto'], 2) }}
                        </div>
                        <div class="bg-green-200 text-green-800 text-xs px-2 py-1 rounded-full inline-block">
                            🎯 {{ $ventasHoy['cantidad'] }} transacciones
                        </div>
                    </div>

                    <!-- Venta Promedio -->
                    <div
                        class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                        <div class="text-blue-600 text-sm font-medium mb-2">Venta Promedio</div>
                        <div class="text-2xl font-bold text-blue-700 mb-2">
                            @if($ventasHoy['cantidad'] > 0)
                            Bs. {{ number_format($ventasHoy['monto'] / $ventasHoy['cantidad'], 2) }}
                            @else
                            Bs. 0.00
                            @endif
                        </div>
                        <div class="bg-blue-200 text-blue-800 text-xs px-2 py-1 rounded-full inline-block">
                            📦 por venta
                        </div>
                    </div>

                    <!-- Productos Vendidos -->
                    <div
                        class="text-center p-4 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl border border-amber-200">
                        <div class="text-amber-600 text-sm font-medium mb-2">Productos Vendidos</div>
                        <div class="text-2xl font-bold text-amber-700 mb-2">
                            @php
                            $totalProductosVendidos = 0;
                            foreach($productosMasVendidos as $producto) {
                            $totalProductosVendidos += $producto['total_vendido'];
                            }
                            @endphp
                            {{ $totalProductosVendidos }}
                        </div>
                        <div class="bg-amber-200 text-amber-800 text-xs px-2 py-1 rounded-full inline-block">
                            📋 unidades
                        </div>
                    </div>
                </div>
            </div>

            <!-- Productos Más Vendidos -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    🏆 Productos Más Vendidos Hoy
                </h2>

                @if(count($productosMasVendidos) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($productosMasVendidos as $index => $producto)
                    <div
                        class="bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all duration-200">
                        <div class="flex items-start space-x-3">
                            <!-- Número ranking -->
                            <div class="flex-shrink-0">
                                @if($index == 0)
                                <div
                                    class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    1
                                </div>
                                @elseif($index == 1)
                                <div
                                    class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    2
                                </div>
                                @elseif($index == 2)
                                <div
                                    class="w-8 h-8 bg-amber-700 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    3
                                </div>
                                @else
                                <div
                                    class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                                @endif
                            </div>

                            <!-- Imagen del producto -->
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 rounded-lg border border-gray-200 overflow-hidden bg-amber-50 flex items-center justify-center">
                                    @if(!empty($producto['imagen']) && (str_starts_with($producto['imagen'], 'http') ||
                                    file_exists(public_path($producto['imagen']))))
                                    <img src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}"
                                        class="w-full h-full object-cover" loading="lazy"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                    <div class="w-full h-full bg-amber-100 flex items-center justify-center hidden">
                                        <span class="text-amber-600 text-lg">☕</span>
                                    </div>
                                    @else
                                    <div class="w-full h-full bg-amber-100 flex items-center justify-center">
                                        <span class="text-amber-600 text-lg">☕</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <!-- Información del producto -->
                            <div class="flex-grow">
                                <h3 class="font-semibold text-gray-800 text-sm leading-tight">
                                    {{ $producto['nombre'] }}
                                </h3>
                                <p class="text-gray-500 text-xs mt-1">
                                    {{ $producto['categoria_nombre'] }}
                                </p>

                                <!-- Stats -->
                                <div class="flex justify-between items-center mt-2">
                                    <div class="text-center">
                                        <div class="text-xs text-gray-500">Cantidad</div>
                                        <div class="font-bold text-blue-600 text-sm">{{ $producto['total_vendido'] }}
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-xs text-gray-500">Total</div>
                                        <div class="font-bold text-green-600 text-sm">
                                            Bs. {{ number_format($producto['total_recaudado'], 2) }}
                                        </div>
                                    </div>
                                    @if(isset($producto['stock_actual']))
                                    <div class="text-center">
                                        <div class="text-xs text-gray-500">Stock</div>
                                        <div
                                            class="font-bold {{ $producto['stock_actual'] <= 5 ? 'text-red-600' : 'text-gray-600' }} text-sm">
                                            {{ $producto['stock_actual'] }}
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                @if(isset($producto['es_sugerencia']) && $producto['es_sugerencia'])
                                <div class="mt-2 text-center">
                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">
                                        🔍 Producto sugerido
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <!-- Estado vacío -->
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl text-gray-400">🛒</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">No hay ventas hoy</h3>
                    <p class="text-gray-500 text-sm mb-4">Los productos más vendidos aparecerán aquí cuando realices
                        ventas</p>
                    <a href="{{ route('ventas.rapida') }}"
                        class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors duration-200">
                        <span class="mr-2">💰</span>
                        Realizar Primera Venta
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Columna derecha - 1/3 -->
        <div class="space-y-6">
            <!-- Tarjeta de Rendimiento -->
            <div class="bg-gradient-to-br from-amber-600 to-amber-800 rounded-2xl shadow-lg p-6 text-white">
                <div class="text-center">
                    <div
                        class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">⚡</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Rendimiento del Día</h3>
                    @if($ventasHoy['monto'] > 0)
                    <div class="text-2xl font-bold text-amber-100 mb-2">¡Excelente!</div>
                    <p class="text-amber-200 text-sm">Sigue con el buen trabajo</p>
                    @else
                    <div class="text-2xl font-bold text-amber-100 mb-2">Listo para empezar</div>
                    <p class="text-amber-200 text-sm">Realiza tu primera venta del día</p>
                    @endif
                </div>
            </div>

            <!-- Estadísticas Rápidas -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    📈 Estadísticas Rápidas
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <div class="flex items-center">
                            <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <span class="text-blue-600">👥</span>
                            </span>
                            <span class="text-gray-700">Reservaciones Hoy</span>
                        </div>
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $totalReservaciones }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <div class="flex items-center">
                            <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <span class="text-green-600">📦</span>
                            </span>
                            <span class="text-gray-700">Productos Activos</span>
                        </div>
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $totalProductos }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <div class="flex items-center">
                            <span class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                <span class="text-yellow-600">⚠️</span>
                            </span>
                            <span class="text-gray-700">Stock Bajo</span>
                        </div>
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $stockBajo }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-2">
                        <div class="flex items-center">
                            <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <span class="text-purple-600">🪑</span>
                            </span>
                            <span class="text-gray-700">Mesas Disponibles</span>
                        </div>
                        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $mesasDisponibles['disponibles'] }}/{{ $mesasDisponibles['total'] }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Progreso del Día -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    🎯 Progreso del Día
                </h3>
                @php
                $metaVentas = 1000; // Meta ajustable
                $porcentajeMeta = $ventasHoy['monto'] > 0 ? min(100, ($ventasHoy['monto'] / $metaVentas) * 100) : 0;
                @endphp
                <div class="mb-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Meta de Ventas Diarias</span>
                        <span class="font-semibold">{{ number_format($porcentajeMeta, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded-full transition-all duration-500"
                            style="width: {{ $porcentajeMeta }}%">
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <span class="text-sm text-gray-500">
                            Bs. {{ number_format($ventasHoy['monto'], 2) }} de Bs. {{ number_format($metaVentas, 2) }}
                        </span>
                    </div>
                </div>

                @if(auth()->user()->esAdministrador())
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Panel Admin</h4>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="text-center p-2 bg-blue-50 rounded-lg">
                            <div class="text-blue-600 font-bold">{{ $totalUsuarios }}</div>
                            <div class="text-blue-500">Usuarios</div>
                        </div>
                        <div class="text-center p-2 bg-green-50 rounded-lg">
                            <div class="text-green-600 font-bold">{{ $promocionesActivas }}</div>
                            <div class="text-green-500">Promociones</div>
                        </div>
                        <div class="text-center p-2 bg-amber-50 rounded-lg">
                            <div class="text-amber-600 font-bold">Bs. {{ number_format($ingresoMensual, 2) }}</div>
                            <div class="text-amber-500">Ingreso Mensual</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
