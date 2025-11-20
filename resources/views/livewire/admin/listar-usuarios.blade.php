<div class="min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6">
        <div class="mb-4 lg:mb-0">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-2">Gestión de Usuarios</h1>
            <p class="text-gray-600">Administra los usuarios del sistema</p>
        </div>
        <a href="{{ route('usuarios.crear') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
            <i class="bi bi-plus-circle mr-2"></i>
            Nuevo Usuario
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Búsqueda -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-search text-gray-400"></i>
                        </div>
                        <input type="text" wire:model.live="search" placeholder="Buscar por nombre, usuario o email..."
                            class="pl-10 pr-3 w-full border border-gray-300 rounded-lg py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Filtro por Rol -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por Rol</label>
                    <select wire:model.live="filterRol"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Todos los roles</option>
                        @foreach($roles as $rol)
                        <option value="{{ $rol->id_rol }}">{{ $rol->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por Estado -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por Estado</label>
                    <select wire:model.live="filterEstado"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Todos los estados</option>
                        <option value="1">Activos</option>
                        <option value="0">Inactivos</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Usuario</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nombre Completo</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($usuarios as $usuario)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $usuario->nombre_usuario }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-gray-700">{{ $usuario->nombre_completo }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-gray-700">{{ $usuario->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $usuario->rol->nombre }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" {{ $usuario->estado ? 'checked' : '' }}
                                        wire:click="cambiarEstado({{ $usuario->id_usuario }})" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </label>
                                <span class="ml-3 text-sm text-gray-600">
                                    {{ $usuario->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex justify-center space-x-2">
                                <button wire:click="editar({{ $usuario->id_usuario }})"
                                    class="inline-flex items-center px-3 py-1.5 border border-blue-300 text-sm font-medium rounded-lg text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                                    <i class="bi bi-pencil mr-1.5"></i>
                                    Editar
                                </button>
                                <button wire:click="confirmarEliminacion({{ $usuario->id_usuario }})"
                                    class="inline-flex items-center px-3 py-1.5 border border-red-300 text-sm font-medium rounded-lg text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors duration-200">
                                    <i class="bi bi-trash mr-1.5"></i>
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <i class="bi bi-people text-4xl mb-3 opacity-50"></i>
                                <h3 class="text-lg font-medium mb-1">No se encontraron usuarios</h3>
                                <p class="text-sm">Intenta ajustar los filtros de búsqueda</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($usuarios->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $usuarios->links() }}
        </div>
        @endif
    </div>

    <!-- Modal de Edición - CORREGIDO PARA EVITAR SUPERPOSICIÓN -->
    @if($showEditModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-start justify-center p-4 z-[9999] overflow-y-auto py-8"
        x-data x-on:keydown.escape="window.livewire.emit('cerrarModal')">
        <div class="bg-white rounded-xl shadow-lg max-w-3xl w-full my-8">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 p-2 rounded-lg">
                            <i class="bi bi-person-gear text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-gray-900">Editar Usuario</h3>
                            <p class="text-sm text-gray-600">Actualiza la información del usuario seleccionado</p>
                        </div>
                    </div>
                    <button wire:click="cerrarModal"
                        class="text-gray-400 hover:text-gray-500 transition-colors duration-200 p-1 rounded-full hover:bg-gray-100">
                        <i class="bi bi-x-lg text-xl"></i>
                    </button>
                </div>
            </div>

            <div class="p-6 max-h-[60vh] overflow-y-auto">
                <form wire:submit.prevent="actualizarUsuario" class="space-y-6">
                    <!-- Información Básica -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre de Usuario -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Nombre de Usuario <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-person text-gray-400"></i>
                                </div>
                                <input type="text" wire:model="editNombreUsuario"
                                    class="pl-10 pr-3 w-full border border-gray-300 rounded-lg py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                    placeholder="Ingrese nombre de usuario">
                            </div>
                            @error('editNombreUsuario')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Rol -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Rol <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-shield-check text-gray-400"></i>
                                </div>
                                <select wire:model="editIdRol"
                                    class="pl-10 pr-3 w-full border border-gray-300 rounded-lg py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    <option value="">Seleccionar rol...</option>
                                    @foreach($roles as $rol)
                                    <option value="{{ $rol->id_rol }}">{{ $rol->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('editIdRol')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Información Personal -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre Completo -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Nombre Completo <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-card-heading text-gray-400"></i>
                                </div>
                                <input type="text" wire:model="editNombreCompleto"
                                    class="pl-10 pr-3 w-full border border-gray-300 rounded-lg py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                    placeholder="Ingrese nombre completo">
                            </div>
                            @error('editNombreCompleto')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Teléfono -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-telephone text-gray-400"></i>
                                </div>
                                <input type="text" wire:model="editTelefono"
                                    class="pl-10 pr-3 w-full border border-gray-300 rounded-lg py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                    placeholder="Teléfono (opcional)">
                            </div>
                            @error('editTelefono')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-envelope text-gray-400"></i>
                            </div>
                            <input type="email" wire:model="editEmail"
                                class="pl-10 pr-3 w-full border border-gray-300 rounded-lg py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="correo@ejemplo.com">
                        </div>
                        @error('editEmail')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Cambio de Contraseña -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex items-center mb-4">
                            <i class="bi bi-key text-blue-500 mr-2"></i>
                            <h4 class="text-md font-medium text-gray-900">Cambio de Contraseña</h4>
                            <span class="ml-2 text-xs text-gray-500">(Opcional)</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nueva Contraseña -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="bi bi-lock text-gray-400"></i>
                                    </div>
                                    <input type="password" wire:model="editPassword"
                                        class="pl-10 pr-3 w-full border border-gray-300 rounded-lg py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                        placeholder="Dejar en blanco para no cambiar">
                                </div>
                                @error('editPassword')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                                @enderror
                            </div>

                            <!-- Confirmar Contraseña -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="bi bi-lock-fill text-gray-400"></i>
                                    </div>
                                    <input type="password" wire:model="editPasswordConfirmation"
                                        class="pl-10 pr-3 w-full border border-gray-300 rounded-lg py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                        placeholder="Confirmar nueva contraseña">
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 bg-blue-50 p-3 rounded-lg">
                            <p class="text-sm text-blue-700 flex items-start">
                                <i class="bi bi-info-circle mr-2 mt-0.5"></i>
                                La contraseña debe tener al menos 8 caracteres, incluyendo letras mayúsculas, minúsculas
                                y números.
                            </p>
                        </div>
                    </div>
                </form>
            </div>

            <div
                class="px-6 py-4 bg-gray-50 rounded-b-xl flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                <div class="text-sm text-gray-500 flex items-center">
                    <i class="bi bi-clock-history mr-2"></i>
                    Última actualización: {{ now()->format('d/m/Y H:i') }}
                </div>
                <div class="flex space-x-3">
                    <button wire:click="cerrarModal"
                        class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors duration-200 flex items-center">
                        <i class="bi bi-x-circle mr-2"></i>
                        Cancelar
                    </button>
                    <button wire:click="actualizarUsuario" wire:loading.attr="disabled"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200 flex items-center">
                        <i class="bi bi-check-circle mr-2"></i>
                        <span wire:loading.remove>Actualizar Usuario</span>
                        <span wire:loading>
                            <i class="bi bi-arrow-clockwise animate-spin mr-2"></i>
                            Guardando...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal de Confirmación de Eliminación -->
    @if($usuarioEliminar)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-start justify-center p-4 z-[9999] overflow-y-auto py-8">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full my-8">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-exclamation-triangle text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-gray-900">Confirmar Eliminación</h3>
                    </div>
                </div>
            </div>
            <div class="p-6 text-center">
                <i class="bi bi-person-x text-red-500 text-4xl mb-4"></i>
                <h4 class="text-lg font-medium text-gray-900 mb-2">¿Estás seguro de que deseas eliminar este usuario?
                </h4>
                <p class="text-gray-600">Esta acción no se puede deshacer.</p>
            </div>
            <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex justify-center space-x-3">
                <button wire:click="eliminar"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                    <i class="bi bi-trash mr-2"></i>
                    Eliminar
                </button>
                <button wire:click="$set('usuarioEliminar', null)"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                    <i class="bi bi-x-circle mr-2"></i>
                    Cancelar
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Mensajes Flash -->
    @if (session()->has('success'))
    <div class="fixed top-20 right-4 z-[10000] animate-fade-in-up">
        <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center">
            <i class="bi bi-check-circle text-xl mr-3"></i>
            <div>
                <div class="font-semibold">Éxito</div>
                <div class="text-sm">{{ session('success') }}</div>
            </div>
            <button type="button"
                class="ml-4 text-white hover:text-green-200 focus:outline-none transition-colors duration-200"
                onclick="this.parentElement.style.display='none'">
                <i class="bi bi-x text-lg"></i>
            </button>
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="fixed top-20 right-4 z-[10000] animate-fade-in-up">
        <div class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center">
            <i class="bi bi-exclamation-circle text-xl mr-3"></i>
            <div>
                <div class="font-semibold">Error</div>
                <div class="text-sm">{{ session('error') }}</div>
            </div>
            <button type="button"
                class="ml-4 text-white hover:text-red-200 focus:outline-none transition-colors duration-200"
                onclick="this.parentElement.style.display='none'">
                <i class="bi bi-x text-lg"></i>
            </button>
        </div>
    </div>
    @endif
</div>
