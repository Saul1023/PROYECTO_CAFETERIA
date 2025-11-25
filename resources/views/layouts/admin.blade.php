<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - {{ $title ?? 'Dashboard' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @livewireStyles

    <style>
    /* Definición de la paleta de colores inspirada en el café */
    :root {
        --sidebar-width: 280px;
        /* Ancho normal */
        --sidebar-collapsed-width: 70px;
        /* Ancho colapsado */
        --primary-dark: #4A332B;
        /* Marrón oscuro para fondo sidebar */
        --primary-light: #7B5C4E;
        /* Tono intermedio para gradiente */
        --accent-color: #E6B88A;
        /* Color crema/cálido para acentos/hover */
        --text-on-dark: #F8F5F1;
        /* Texto claro para sidebar */
        --bg-body: #F9FAFB;
        /* Fondo general más limpio */
        --transition-speed: 0.3s;
    }

    /* Configuración de Tailwind para usar variables CSS */
    tailwind.config= {
        theme: {
            extend: {
                colors: {
                    'primary-dark': 'var(--primary-dark)',
                        'accent': 'var(--accent-color)',
                }
            }
        }
    }

    body {
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--bg-body);
    }

    /* Sidebar mejorado y transiciones fluidas */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: var(--sidebar-width);
        background: linear-gradient(180deg, var(--primary-dark) 0%, var(--primary-light) 100%);
        color: var(--text-on-dark);
        /* Transición fluida para el ancho */
        transition: width var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1000;
        overflow-y: auto;
        overflow-x: hidden;
        /* Evita scroll horizontal al colapsar */
        box-shadow: 4px 0 10px rgba(0, 0, 0, 0.2);
    }

    .sidebar.collapsed {
        width: var(--sidebar-collapsed-width);
    }

    /* Header del Sidebar - CORREGIDO */
    .sidebar-header {
        padding: 1rem 0.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 80px;
        position: relative;
        padding-left: 1.5rem;
    }

    .sidebar-brand {
        font-size: 1.5rem;
        font-weight: 700;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: opacity var(--transition-speed) ease, transform var(--transition-speed) ease;
    }

    /* Oculta la marca completa al colapsar */
    .sidebar.collapsed .sidebar-brand {
        opacity: 0;
        transform: translateX(-100%);
        /* Mover fuera de la vista para evitar cualquier click accidental */
        width: 0;
        overflow: hidden;
        pointer-events: none;
    }

    .logo-icon {
        font-size: 2rem;
        color: var(--accent-color);
        position: absolute;
        /* Sigue siendo absoluto para centrar */
        left: 50%;
        transform: translateX(-50%);
        opacity: 0;
        /* Por defecto oculto */
        pointer-events: none;
        transition: opacity var(--transition-speed) ease;
    }

    /* Muestra solo el icono grande al colapsar y lo centra */
    .sidebar.collapsed .logo-icon {
        opacity: 1;
        pointer-events: auto;
    }

    /* Botón de Toggle */
    .btn-toggle-sidebar {
        background: none;
        border: none;
        color: var(--text-on-dark);
        font-size: 1.5rem;
        cursor: pointer;
        transition: color 0.2s ease;
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
    }

    /* Ocultar el botón de toggle en el modo colapsado */
    .sidebar.collapsed .btn-toggle-sidebar {
        display: none;
    }

    /* Enlaces de navegación */
    .nav-link {
        color: rgba(255, 255, 255, 0.85);
        padding: 0.85rem 1.5rem;
        margin: 0.25rem 0.75rem;
        border-radius: 0.75rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 1rem;
        white-space: nowrap;
    }

    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.15);
        color: var(--text-on-dark);
    }

    .nav-link.active {
        background-color: var(--accent-color);
        color: var(--primary-dark);
        font-weight: 600;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
    }

    .nav-link.active i {
        color: var(--primary-dark);
    }

    .nav-link i {
        font-size: 1.5rem;
        width: 1.5rem;
        text-align: center;
        transition: color 0.2s ease;
    }

    /* Comportamiento al colapsar los enlaces */
    .sidebar.collapsed .nav-link {
        justify-content: center;
        padding: 0.85rem 0.5rem;
        margin: 0.25rem 0;
    }

    .sidebar.collapsed .nav-link span {
        opacity: 0;
        width: 0;
        overflow: hidden;
        pointer-events: none;
    }

    /* Sección solo Administrador */
    .admin-only-section {
        border-top: 1px solid rgba(255, 255, 255, 0.3);
        margin-top: 1.5rem;
        padding-top: 1.5rem;
    }

    .section-label {
        color: var(--accent-color);
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        padding: 0.5rem 1.5rem;
        font-weight: 700;
        white-space: nowrap;
        transition: opacity var(--transition-speed) ease;
    }

    .sidebar.collapsed .section-label {
        opacity: 0;
        height: 0;
        overflow: hidden;
        padding: 0;
    }

    /* Contenido principal con ajuste de margen */
    .main-content {
        margin-left: var(--sidebar-width);
        /* Transición fluida para el margen */
        transition: margin-left var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
        min-height: 100vh;
    }

    .main-content.expanded {
        margin-left: var(--sidebar-collapsed-width);
    }

    /* Navbar Superior */
    .top-navbar {
        background: white;
        padding: 1rem 1.5rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        position: sticky;
        top: 0;
        z-index: 999;
        border-bottom: 1px solid #e5e7eb;
    }

    .top-navbar h4 {
        color: var(--primary-dark);
    }

    /* Estilos del Modal */
    #modalPerfil .modal-header,
    #modalConfiguracion .modal-header,
    #modalAyuda .modal-header {
        background-color: var(--primary-dark) !important;
        border-bottom: none;
        padding: 1.5rem;
    }

    #modalPerfil .modal-content,
    #modalConfiguracion .modal-content,
    #modalAyuda .modal-content {
        border-radius: 1rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    /* Estilos específicos del Perfil */
    #modalPerfil .form-control.bg-light {
        background-color: #f3f4f6 !important;
        border: 1px solid #e5e7eb;
        font-weight: 500;
    }

    #modalPerfil .badge {
        font-size: 0.85rem;
        padding: 0.4em 0.7em;
    }

    /* Avatar de usuario */
    .user-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--primary-dark);
        /* Color oscuro para el avatar */
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-on-dark);
        font-weight: bold;
        font-size: 1rem;
        box-shadow: 0 0 0 2px var(--accent-color);
        /* Borde de acento */
    }

    .role-badge {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        border-radius: 0.5rem;
        font-size: 0.7rem;
        font-weight: 600;
        background: var(--accent-color);
        color: var(--primary-dark);
        margin-top: 0.25rem;
    }

    /* Estilos del menú desplegable del usuario */
    .dropdown-menu {
        border-radius: 0.5rem;
        overflow: hidden;
        border: 1px solid #e0e0e0;
    }

    .user-info-dropdown {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #f0f0f0;
        margin-bottom: 0.5rem;
    }

    .user-dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.6rem 1rem;
        transition: background-color 0.2s;
    }

    .user-dropdown-item:hover {
        background-color: var(--accent-color);
        color: var(--primary-dark);
    }

    .user-dropdown-item i {
        width: 1.25rem;
    }

    .user-dropdown-item.text-danger:hover {
        background-color: #ffcccc;
    }
    </style>
</head>

<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-cup-hot-fill logo-icon"></i>

            <span class="sidebar-brand">
                <i class="bi bi-cup-hot-fill text-accent"></i>
                Rincón Sabrosito
            </span>

            <button class="btn-toggle-sidebar d-none d-md-block" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
        </div>

        <nav class="nav flex-column p-1">
            <div class="nav-item">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('reservaciones') }}"
                    class="nav-link {{ request()->routeIs('reservaciones') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i>
                    <span>Reservaciones</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('mesas') }}" class="nav-link {{ request()->routeIs('mesas') ? 'active' : '' }}">
                    <i class="bi bi-table"></i>
                    <span>Mesas</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('ventas.rapida') }}"
                    class="nav-link {{ request()->routeIs('ventas.rapida') ? 'active' : '' }}">
                    <i class="bi bi-cart3"></i>
                    <span>Ventas</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('productos') }}"
                    class="nav-link {{ request()->routeIs('productos*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i>
                    <span>Productos</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('categorias') }}"
                    class="nav-link {{ request()->routeIs('categorias*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i>
                    <span>Categorías</span>
                </a>
            </div>

            @if(auth()->user()->esAdministrador())
            <div class="admin-only-section">
                <div class="section-label">
                    <i class="bi bi-shield-check"></i> Admin
                </div>

                <div class="nav-item">
                    <a href="{{ route('usuarios') }}"
                        class="nav-link {{ request()->routeIs('usuarios*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Usuarios</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('promociones') }}"
                        class="nav-link {{ request()->routeIs('promociones') ? 'active' : '' }}">
                        <i class="bi bi-tag"></i>
                        <span>Promociones</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('reportes') }}"
                        class="nav-link {{ request()->routeIs('reportes') ? 'active' : '' }}">
                        <i class="bi bi-graph-up"></i>
                        <span>Reportes</span>
                    </a>
                </div>
            </div>
            @endif
        </nav>
    </div>

    <div class="main-content" id="mainContent">
        <nav class="top-navbar">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="btn btn-light me-3 d-md-none" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i>
                    </button>
                    <div>
                        <h4 class="mb-0">
                            <i class="bi bi-cup-hot-fill me-2" style="color: var(--primary-dark);"></i>
                            {{ $pageTitle ?? 'Sistema de Gestión' }}
                        </h4>
                    </div>
                </div>

                <div class="dropdown">
                    <button class="btn btn-light d-flex align-items-center gap-2" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar">{{ substr(auth()->user()->nombre_completo, 0, 2) }}</div>
                        <div class="d-none d-lg-block text-start me-2">
                            <div class="fw-bold small text-dark">{{ auth()->user()->nombre_completo }}</div>
                            <span class="role-badge">
                                {{ auth()->user()->rol->nombre }}
                            </span>
                        </div>
                        <i class="bi bi-chevron-down text-secondary"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li class="user-info-dropdown">
                            <div class="d-flex align-items-center gap-3">
                                <div class="user-avatar" style="width: 50px; height: 50px; font-size: 1.1rem;">
                                    {{ substr(auth()->user()->nombre_completo, 0, 2) }}
                                </div>
                                <div class="text-start">
                                    <div class="fw-bold text-dark">{{ auth()->user()->nombre_completo }}</div>
                                    <span class="role-badge">
                                        {{ auth()->user()->rol->nombre }}
                                    </span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a class="dropdown-item user-dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#modalPerfil">
                                <i class="bi bi-person"></i>
                                <span>Mi Perfil</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item user-dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#modalConfiguracion">
                                <i class="bi bi-gear"></i>
                                <span>Configuración</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item user-dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#modalAyuda">
                                <i class="bi bi-question-circle"></i>
                                <span>Ayuda</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                                @csrf
                                <button type="submit" class="dropdown-item user-dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Cerrar Sesión</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="p-4 p-lg-5">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            {{-- Soporte para ambos enfoques (Blade y Livewire) --}}
            @isset($slot)
            {{ $slot }}
            @else
            @yield('content')
            @endisset
        </main>
    </div>

    @auth
    <div class="modal fade" id="modalPerfil" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h5 class="modal-title"><i class="bi bi-person-circle me-2"></i>Mi Perfil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="user-avatar mx-auto"
                            style="width: 80px; height: 80px; font-size: 2rem; box-shadow: 0 0 0 3px var(--accent-color);">
                            {{ substr(Auth::user()->nombre_completo, 0, 2) }}
                        </div>
                        <h4 class="mt-3 mb-1 text-primary-dark" style="color: var(--primary-dark)">
                            {{ Auth::user()->nombre_completo }}</h4>
                        <span class="role-badge" style="font-size: 0.9rem;">
                            {{ Auth::user()->rol->nombre }}
                        </span>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">
                                Nombre completo:
                            </label>
                            <div class="form-control bg-light">{{ Auth::user()->nombre_completo }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">
                                Nombre de usuario:
                            </label>
                            <div class="form-control bg-light">{{ Auth::user()->nombre_usuario }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">
                                Email:
                            </label>
                            <div class="form-control bg-light">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">
                                Teléfono:
                            </label>
                            <div class="form-control bg-light">{{ Auth::user()->telefono ?? '—' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">
                                Estado:
                            </label>
                            <div class="form-control bg-light d-flex align-items-center">
                                @if(Auth::user()->estado)
                                <span class="badge bg-success rounded-pill"><i class="bi bi-check-circle me-1"></i>
                                    Activo</span>
                                @else
                                <span class="badge bg-danger rounded-pill"><i class="bi bi-x-circle me-1"></i>
                                    Inactivo</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalConfiguracion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h5 class="modal-title"><i class="bi bi-gear me-2"></i>Configuración</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    @livewire('perfil-usuario')
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAyuda" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h5 class="modal-title"><i class="bi bi-question-circle me-2"></i>Centro de Ayuda</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-3" style="color: var(--primary-dark)"><i
                            class="bi bi-cup-hot-fill me-2"></i>Bienvenido a El Rincón Sabrosito.</h5>
                    <p class="text-secondary">Esta es tu guía rápida para navegar en el sistema de gestión.</p>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h6 class="fw-bold text-success"><i class="bi bi-check-circle me-2"></i>Funcionalidades
                                Principales</h6>
                            <ul class="list-unstyled mt-2 small">
                                <li><i class="bi bi-speedometer2 me-2 text-primary"></i> <strong>Dashboard:</strong>
                                    Vista general del negocio.</li>
                                <li><i class="bi bi-calendar-check me-2 text-info"></i> <strong>Reservaciones:</strong>
                                    Gestión de mesas reservadas.</li>
                                <li><i class="bi bi-table me-2 text-warning"></i> <strong>Mesas:</strong> Estado actual
                                    de las mesas.</li>
                                <li><i class="bi bi-cart3 me-2 text-danger"></i> <strong>Ventas:</strong> Punto de venta
                                    rápido y pedidos.</li>
                                <li><i class="bi bi-box-seam me-2 text-success"></i> <strong>Productos:</strong>
                                    Inventario y precios.</li>
                            </ul>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h6 class="fw-bold text-secondary"><i class="bi bi-person-fill-gear me-2"></i>Tu Cuenta</h6>
                            <ul class="list-unstyled mt-2 small">
                                <li><i class="bi bi-person me-2 text-primary"></i> <strong>Mi Perfil:</strong> Ver datos
                                    personales.</li>
                                <li><i class="bi bi-gear me-2 text-secondary"></i> <strong>Configuración:</strong>
                                    Cambiar usuario/contraseña.</li>
                                <li><i class="bi bi-box-arrow-right me-2 text-danger"></i> <strong>Cerrar
                                        Sesión:</strong> Finaliza tu sesión.</li>
                            </ul>
                        </div>
                    </div>

                    <hr class="mt-2 mb-4">
                    <h6 class="fw-bold" style="color: var(--primary-dark)"><i class="bi bi-headset me-2"></i>Contacto y
                        Soporte:</h6>
                    <p class="small text-secondary">Si tienes alguna consulta o inconveniente, contáctanos a través de:
                    </p>
                    <div class="d-flex flex-wrap gap-4 small">
                        <span><i class="bi bi-envelope text-info me-1"></i> contacto@rinconsabrosito.com</span>
                        <span><i class="bi bi-telephone text-success me-1"></i> +591 4 1234567</span>
                        <span><i class="bi bi-geo-alt text-danger me-1"></i> Potosí, Bolivia</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts

    <script>
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');

    function collapseSidebar() {
        sidebar.classList.add('collapsed');
        mainContent.classList.add('expanded');
    }

    function expandSidebar() {
        sidebar.classList.remove('collapsed');
        mainContent.classList.remove('expanded');
    }

    function toggleSidebar() {
        const isCollapsed = sidebar.classList.contains('collapsed');
        if (isCollapsed) {
            expandSidebar();
            localStorage.setItem('sidebarCollapsed', 'false');
        } else {
            collapseSidebar();
            localStorage.setItem('sidebarCollapsed', 'true');
        }
    }

    function checkSidebarState() {
        // Cargar el estado guardado al cargar la página
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

        // Si la pantalla es pequeña (menor a 992px, que es el punto de quiebre de Bootstrap 'lg'),
        // forzamos el estado colapsado para ahorrar espacio.
        if (window.innerWidth < 992) {
            collapseSidebar();
            localStorage.setItem('sidebarCollapsed', 'true');
        } else if (isCollapsed) {
            collapseSidebar();
        } else {
            expandSidebar();
        }
    }

    // Inicializar el estado de la barra lateral al cargar la página
    document.addEventListener('DOMContentLoaded', checkSidebarState);

    // Ajustar el estado al cambiar el tamaño de la ventana (para responsividad)
    window.addEventListener('resize', checkSidebarState);
    </script>
</body>

</html>
