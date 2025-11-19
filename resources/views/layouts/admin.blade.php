<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - {{ $title ?? 'Dashboard' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @livewireStyles

    <style>
    :root {
        --sidebar-width: 260px;
        --sidebar-collapsed-width: 80px;
        --primary-color: #6f4e37;
        --secondary-color: #d4a574;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
    }

    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: var(--sidebar-width);
        background: linear-gradient(180deg, #6f4e37 0%, #d4a574 100%);
        color: white;
        transition: all 0.3s ease;
        z-index: 1000;
        overflow-y: auto;
    }

    .sidebar.collapsed {
        width: var(--sidebar-collapsed-width);
    }

    .sidebar-header {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .sidebar-brand {
        font-size: 1.25rem;
        font-weight: bold;
        white-space: nowrap;
    }

    .sidebar.collapsed .sidebar-brand {
        display: none;
    }

    .nav-link {
        color: rgba(255, 255, 255, 0.8);
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .nav-link.active {
        background-color: rgba(255, 255, 255, 0.15);
        color: white;
    }

    .nav-link i {
        font-size: 1.25rem;
        width: 1.5rem;
        text-align: center;
    }

    .sidebar.collapsed .nav-link span {
        display: none;
    }

    /* Separador visual para secciones solo admin */
    .admin-only-section {
        border-top: 2px solid rgba(255, 255, 255, 0.2);
        margin-top: 1rem;
        padding-top: 1rem;
    }

    .section-label {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
    }

    .sidebar.collapsed .section-label {
        display: none;
    }

    .main-content {
        margin-left: var(--sidebar-width);
        transition: margin-left 0.3s ease;
        min-height: 100vh;
    }

    .main-content.expanded {
        margin-left: var(--sidebar-collapsed-width);
    }

    .top-navbar {
        background: white;
        padding: 1rem 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        position: sticky;
        top: 0;
        z-index: 999;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6f4e37 0%, #d4a574 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 0.9rem;
    }

    .btn-toggle-sidebar {
        background: none;
        border: none;
        color: white;
        font-size: 1.25rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 0.5rem;
    }

    .btn-toggle-sidebar:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .role-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.2);
        margin-top: 0.25rem;
    }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <span class="sidebar-brand">
                <i class="bi bi-cup-hot"></i>
                CaféSystem
            </span>
            <button class="btn-toggle-sidebar" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
        </div>

        <nav class="nav flex-column p-3">
            <!-- Dashboard - Accesible para todos -->
            <div class="nav-item">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <!-- Secciones comunes para Admin y Empleado -->
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

            <!-- Sección SOLO para Administradores -->
            @if(auth()->user()->esAdministrador())
            <div class="admin-only-section">
                <div class="section-label">
                    <i class="bi bi-shield-check"></i> Solo Administrador
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

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">
                        <i class="bi bi-cup-hot text-brown"></i>
                        {{ $pageTitle ?? 'Sistema de Gestión' }}
                    </h4>
                </div>

                <div class="dropdown">
                    <button class="btn btn-light d-flex align-items-center gap-2" type="button"
                        data-bs-toggle="dropdown">
                        <div class="user-avatar">{{ substr(auth()->user()->nombre_completo, 0, 2) }}</div>
                        <div class="d-none d-md-block text-start">
                            <div class="fw-bold small">{{ auth()->user()->nombre_completo }}</div>
                            <span class="role-badge">
                                {{ auth()->user()->rol->nombre }}
                            </span>
                        </div>
                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-person me-2"></i>Mi Perfil
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="p-4">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- Soporte para ambos enfoques --}}
            @isset($slot)
            {{ $slot }}
            @else
            @yield('content')
            @endisset
        </main>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts

    <script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('collapsed');
        document.getElementById('mainContent').classList.toggle('expanded');
    }
    </script>
</body>

</html>