<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - {{ $title ?? 'Cliente' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @livewireStyles

    <style>
    :root {
        --primary-color: #6f4e37;
        --secondary-color: #d4a574;
        --accent-color: #c17817;
        --cream-color: #faf7f2;
        --dark-brown: #3e2723;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #faf7f2 0%, #f5efe6 100%);
        min-height: 100vh;
        color: #2c2c2c;
    }

    /* Navbar Mejorado */
    .client-navbar {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-brown) 100%);
        padding: 1rem 2rem;
        box-shadow: 0 4px 20px rgba(107, 68, 35, 0.15);
        position: sticky;
        top: 0;
        z-index: 1000;
        border-bottom: 3px solid var(--accent-color);
    }

    .brand-logo {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        color: white;
        font-weight: 600;
        font-size: 1.25rem;
    }

    .brand-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--accent-color) 0%, var(--secondary-color) 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 4px 12px rgba(193, 120, 23, 0.3);
    }

    .client-menu {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .nav-item {
        position: relative;
    }

    .nav-link {
        color: white;
        text-decoration: none;
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        position: relative;
        overflow: hidden;
    }

    .nav-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left 0.5s ease;
    }

    .nav-link:hover::before {
        left: 100%;
    }

    .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .nav-link.active {
        background: rgba(255, 255, 255, 0.15);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .nav-link i {
        font-size: 1.1rem;
        transition: transform 0.3s ease;
    }

    .nav-link:hover i {
        transform: scale(1.1);
    }

    /* User Info Mejorado */
    .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: white;
        margin-left: auto;
        background: rgba(255, 255, 255, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 12px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent-color) 0%, var(--secondary-color) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .user-details {
        display: flex;
        flex-direction: column;
    }

    .user-name {
        font-weight: 600;
        font-size: 0.9rem;
    }

    .user-role {
        font-size: 0.75rem;
        opacity: 0.8;
    }

    .logout-btn {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 0.5rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .logout-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.5);
        transform: scale(1.05);
    }

    /* Main Content */
    main {
        min-height: calc(100vh - 80px);
        padding: 2rem;
    }

    /* Alertas Mejoradas */
    .alert {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-danger {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    /* Efectos de hover generales */
    .hover-lift {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .client-navbar {
            padding: 1rem;
        }

        .client-menu {
            gap: 0.25rem;
        }

        .nav-link {
            padding: 0.6rem 0.8rem;
            font-size: 0.9rem;
        }

        .nav-link span {
            display: none;
        }

        .user-details {
            display: none;
        }

        .brand-logo span {
            display: none;
        }

        main {
            padding: 1rem;
        }
    }

    @media (max-width: 480px) {
        .client-menu {
            gap: 0.1rem;
        }

        .nav-link {
            padding: 0.5rem;
        }

        .user-info {
            padding: 0.3rem 0.6rem;
        }
    }

    /* Animaciones */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    </style>
</head>

<body>
    <!-- Navbar Mejorado para Cliente -->
    <nav class="client-navbar fade-in">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Logo y Brand -->
            <a href="{{ route('cliente.home') }}" class="brand-logo hover-lift">
                <div class="brand-icon">
                    <i class="bi bi-cup-hot"></i>
                </div>
                <span>CaféSystem</span>
            </a>

            <!-- Menú de Navegación -->
            <div class="client-menu">
                <div class="nav-item">
                    <a href="{{ route('cliente.home') }}"
                        class="nav-link {{ request()->routeIs('cliente.home') ? 'active' : '' }}">
                        <i class="bi bi-house"></i>
                        <span>Inicio</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('cliente.reservar') }}"
                        class="nav-link {{ request()->routeIs('cliente.reservar') ? 'active' : '' }}">
                        <i class="bi bi-calendar-plus"></i>
                        <span>Hacer Reserva</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('cliente.reservaciones') }}"
                        class="nav-link {{ request()->routeIs('cliente.reservaciones') ? 'active' : '' }}">
                        <i class="bi bi-list-check"></i>
                        <span>Mis Reservas</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('cliente.perfil') }}"
                        class="nav-link {{ request()->routeIs('cliente.perfil') ? 'active' : '' }}">
                        <i class="bi bi-person"></i>
                        <span>Mi Perfil</span>
                    </a>
                </div>
            </div>

            <!-- Información de Usuario -->
            <div class="user-info hover-lift">
                <div class="user-avatar">
                    {{ substr(auth()->user()->nombre_completo, 0, 2) }}
                </div>
                <div class="user-details">
                    <div class="user-name">{{ auth()->user()->nombre_completo }}</div>
                    <div class="user-role">Cliente</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn" title="Cerrar Sesión">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="fade-in">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{ $slot ?? '' }}
        @isset($content)
        {{ $content }}
        @endisset
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts

    <script>
    // Efecto de carga suave
    document.addEventListener('DOMContentLoaded', function() {
        // Agregar clase de hover a todos los elementos interactivos
        const interactiveElements = document.querySelectorAll('.nav-link, .logout-btn, .brand-logo');
        interactiveElements.forEach(element => {
            element.classList.add('hover-lift');
        });

        // Smooth scroll para anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
    </script>
</body>

</html>
