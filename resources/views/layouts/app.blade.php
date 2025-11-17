<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CaféSystem - Sistema de Gestión de Cafetería</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

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
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar.collapsed .sidebar-brand {
            display: none;
        }

        .nav-item {
            margin: 0.25rem 0;
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

        .main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        .top-navbar {
            background: white;
            padding: 1rem 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
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

        .dropdown-menu {
            min-width: 280px;
        }

        .user-info-dropdown {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
        }

        .user-dropdown-item {
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s;
        }

        .user-dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .user-dropdown-item i {
            width: 20px;
            text-align: center;
        }

        .stat-card {
            border-radius: 1rem;
            padding: 1.5rem;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
            border-left: 4px solid var(--primary-color);
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .coffee-icon {
            font-size: 1.5rem;
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
            <div class="nav-item">
                <a href="#" class="nav-link active">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-people"></i>
                    <span>Usuarios</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-calendar-check"></i>
                    <span>Reservaciones</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-table"></i>
                    <span>Mesas</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-tag"></i>
                    <span>Promociones</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-cart3"></i>
                    <span>Ventas</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-box-seam"></i>
                    <span>Productos</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-graph-up"></i>
                    <span>Reportes</span>
                </a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">
                        <i class="bi bi-cup-hot coffee-icon text-brown"></i>
                        Sistema de Gestión de Cafetería
                    </h4>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- Dropdown del Usuario -->
                    <div class="dropdown">
                        <button class="btn btn-light d-flex align-items-center gap-2" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar">JD</div>
                            <div class="d-none d-md-block text-start">
                                <div class="fw-bold small">Juan Pérez</div>
                                <small class="text-muted">Administrador</small>
                            </div>
                            <i class="bi bi-chevron-down"></i>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                            <!-- Información del Usuario -->
                            <li class="user-info-dropdown">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                        JD
                                    </div>
                                    <div>
                                        <div class="fw-bold">Juan Pérez</div>
                                        <small class="text-muted">Administrador</small>
                                    </div>
                                </div>
                            </li>

                            <!-- Opciones del menú -->
                            <li>
                                <a class="dropdown-item user-dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalPerfil">
                                    <i class="bi bi-person"></i>
                                    <span>Mi Perfil</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item user-dropdown-item" href="#">
                                    <i class="bi bi-gear"></i>
                                    <span>Configuración</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item user-dropdown-item" href="#">
                                    <i class="bi bi-question-circle"></i>
                                    <span>Ayuda</span>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item user-dropdown-item text-danger" href="#" onclick="return confirm('¿Está seguro que desea cerrar sesión?')">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Cerrar Sesión</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="p-4">
            <!-- Dashboard Stats -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Ventas Hoy</h6>
                                <h3 class="mb-0">$1,234</h3>
                            </div>
                            <i class="bi bi-cash-coin text-success" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Reservaciones</h6>
                                <h3 class="mb-0">15</h3>
                            </div>
                            <i class="bi bi-calendar-check text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Mesas Ocupadas</h6>
                                <h3 class="mb-0">8/20</h3>
                            </div>
                            <i class="bi bi-table text-warning" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Productos</h6>
                                <h3 class="mb-0">45</h3>
                            </div>
                            <i class="bi bi-cup-hot text-info" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-speedometer2 me-2"></i>
                        Panel Principal
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Bienvenido al sistema de gestión de cafetería. Aquí podrás administrar todos los aspectos de tu negocio.</p>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Tip del día:</strong> Revisa las reservaciones pendientes para el día de hoy.
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de Perfil -->
    <div class="modal fade" id="modalPerfil" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #6f4e37 0%, #d4a574 100%); color: white;">
                    <h5 class="modal-title">
                        <i class="bi bi-person-circle me-2"></i>
                        Mi Perfil
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="user-avatar mx-auto" style="width: 80px; height: 80px; font-size: 2rem;">
                            JD
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">
                                <i class="bi bi-person me-1"></i>Nombre completo:
                            </label>
                            <div class="form-control bg-light">Juan Pérez Domínguez</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-envelope me-1"></i>Email:
                            </label>
                            <div class="form-control bg-light">juan.perez@cafeteria.com</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-telephone me-1"></i>Teléfono:
                            </label>
                            <div class="form-control bg-light">+591 70123456</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-shield-check me-1"></i>Rol:
                            </label>
                            <div class="form-control bg-light">Administrador</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-person-badge me-1"></i>Usuario:
                            </label>
                            <div class="form-control bg-light">juanperez</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');

            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }
    </script>
</body>
</html>
