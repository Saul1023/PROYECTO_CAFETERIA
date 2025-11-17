<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'El Rincón Sabrosito') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=playfair-display:700|poppins:400,500,600" rel="stylesheet" />

        <style>
            /* CSS Variables - Paleta Cafetería */
            :root {
                --color-primary: #6B4423;
                --color-secondary: #D4A574;
                --color-accent: #C17817;
                --color-cream: #FAF7F2;
                --color-dark: #3E2723;
                --color-light: #FFFFFF;
                --shadow-soft: 0 4px 20px rgba(107, 68, 35, 0.1);
                --shadow-medium: 0 8px 30px rgba(107, 68, 35, 0.15);
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Poppins', sans-serif;
                background: linear-gradient(135deg, #FAF7F2 0%, #F5EFE6 100%);
                color: #2c2c2c;
                min-height: 100vh;
                line-height: 1.6;
            }

            /* Header Mejorado con Estilo Cafetería */
            .nav-header {
                background: linear-gradient(135deg, rgba(107, 68, 35, 0.98) 0%, rgba(62, 39, 35, 0.98) 100%);
                backdrop-filter: blur(20px);
                position: sticky;
                top: 0;
                z-index: 1000;
                box-shadow: var(--shadow-medium);
                border-bottom: 3px solid var(--color-accent);
                animation: slideDown 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            }

            @keyframes slideDown {
                from {
                    transform: translateY(-100%);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            .nav-container {
                max-width: 1400px;
                margin: 0 auto;
                padding: 1.2rem 2rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 2rem;
            }

            /* Brand Section con Estilo Elegante */
            .brand-section {
                display: flex;
                align-items: center;
                gap: 3rem;
            }

            .brand-logo {
                display: flex;
                align-items: center;
                gap: 1rem;
                text-decoration: none;
                transition: transform 0.3s ease;
            }

            .brand-logo:hover {
                transform: scale(1.05);
            }

            .brand-icon {
                width: 50px;
                height: 50px;
                background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-secondary) 100%);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.8rem;
                box-shadow: 0 4px 15px rgba(193, 120, 23, 0.4);
                animation: float 3s ease-in-out infinite;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-8px); }
            }

            .brand-content {
                display: flex;
                flex-direction: column;
            }

            .brand-name {
                font-family: 'Playfair Display', serif;
                font-size: 1.6rem;
                font-weight: 700;
                letter-spacing: 2px;
                color: var(--color-cream);
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
                line-height: 1;
            }

            .brand-tagline {
                font-size: 0.75rem;
                letter-spacing: 3px;
                color: var(--color-secondary);
                text-transform: uppercase;
                font-weight: 500;
                margin-top: 0.2rem;
            }

            /* Navigation Links con Efecto Café */
            .nav-links {
                display: flex;
                gap: 2.5rem;
                align-items: center;
            }

            .nav-link {
                color: var(--color-cream);
                text-decoration: none;
                font-weight: 500;
                font-size: 0.95rem;
                letter-spacing: 0.5px;
                position: relative;
                padding: 0.5rem 0;
                transition: all 0.3s ease;
            }

            .nav-link::before {
                content: '';
                position: absolute;
                bottom: -5px;
                left: 50%;
                transform: translateX(-50%);
                width: 0;
                height: 2px;
                background: linear-gradient(90deg, transparent, var(--color-accent), transparent);
                transition: width 0.4s ease;
            }

            .nav-link:hover {
                color: var(--color-accent);
                transform: translateY(-2px);
            }

            .nav-link:hover::before {
                width: 100%;
            }

            /* Auth Section */
            .auth-section {
                display: flex;
                gap: 1rem;
                align-items: center;
            }

            /* Botones Mejorados */
            .btn {
                padding: 0.75rem 1.8rem;
                border-radius: 25px;
                font-weight: 600;
                font-size: 0.9rem;
                text-decoration: none;
                transition: all 0.3s ease;
                cursor: pointer;
                border: 2px solid transparent;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                letter-spacing: 0.5px;
                position: relative;
                overflow: hidden;
            }

            .btn::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 0;
                height: 0;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.2);
                transform: translate(-50%, -50%);
                transition: width 0.5s, height 0.5s;
                z-index: 0;
            }

            .btn:hover::before {
                width: 300px;
                height: 300px;
            }

            .btn > * {
                position: relative;
                z-index: 1;
            }

            .btn-primary {
                background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-secondary) 100%);
                color: white;
                box-shadow: 0 4px 15px rgba(193, 120, 23, 0.3);
            }

            .btn-primary:hover {
                transform: translateY(-3px);
                box-shadow: 0 6px 25px rgba(193, 120, 23, 0.5);
            }

            .btn-secondary {
                background: transparent;
                color: var(--color-cream);
                border-color: var(--color-secondary);
            }

            .btn-secondary:hover {
                background: var(--color-secondary);
                color: var(--color-dark);
                border-color: var(--color-secondary);
                transform: translateY(-3px);
            }

            .btn-logout {
                background: none;
                border: 2px solid transparent;
                color: var(--color-cream);
                padding: 0.75rem 1.8rem;
                border-radius: 25px;
                font-weight: 600;
                font-size: 0.9rem;
                cursor: pointer;
                transition: all 0.3s ease;
                font-family: 'Poppins', sans-serif;
                position: relative;
                overflow: hidden;
            }

            .btn-logout::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 0;
                height: 0;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.1);
                transform: translate(-50%, -50%);
                transition: width 0.5s, height 0.5s;
            }

            .btn-logout:hover::before {
                width: 300px;
                height: 300px;
            }

            .btn-logout:hover {
                border-color: #e74c3c;
                color: #e74c3c;
                transform: translateY(-2px);
            }

            /* Mobile Menu Toggle */
            .menu-toggle {
                display: none;
                background: rgba(255, 255, 255, 0.1);
                border: 2px solid var(--color-secondary);
                border-radius: 10px;
                color: var(--color-cream);
                cursor: pointer;
                padding: 0.6rem;
                transition: all 0.3s ease;
            }

            .menu-toggle:hover {
                background: rgba(255, 255, 255, 0.2);
                transform: scale(1.05);
            }

            .mobile-menu {
                display: none;
                background: linear-gradient(135deg, rgba(107, 68, 35, 0.98) 0%, rgba(62, 39, 35, 0.98) 100%);
                padding: 1.5rem;
                border-radius: 12px;
                box-shadow: 0 5px 25px rgba(0, 0, 0, 0.3);
                margin-top: 1rem;
                width: 100%;
                border-top: 2px solid var(--color-accent);
            }

            .mobile-menu.show {
                display: block;
                animation: slideDown 0.3s ease-out;
            }

            .mobile-menu-list {
                list-style: none;
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .mobile-menu-list .nav-link {
                display: block;
                padding: 0.8rem;
                background: rgba(255, 255, 255, 0.05);
                border-radius: 8px;
                text-align: center;
            }

            .mobile-menu-list .btn {
                width: 100%;
            }

            /* Main Content Container */
            .main-container {
                max-width: 1400px;
                margin: 3rem auto;
                padding: 0 2rem;
                animation: fadeInUp 0.8s ease-out;
            }

            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }

            /* Hero Banner para usuarios NO autenticados */
            .hero-banner {
                background: linear-gradient(135deg, var(--color-primary) 0%, #8B5A3C 100%);
                border-radius: 20px;
                padding: 4rem 3rem;
                color: white;
                text-align: center;
                box-shadow: var(--shadow-medium);
                margin-bottom: 3rem;
                position: relative;
                overflow: hidden;
            }

            .hero-banner::before {
                content: '☕';
                position: absolute;
                top: -20%;
                right: -5%;
                font-size: 20rem;
                opacity: 0.05;
                transform: rotate(-15deg);
            }

            .hero-content {
                position: relative;
                z-index: 1;
            }

            .hero-title {
                font-family: 'Playfair Display', serif;
                font-size: 3.5rem;
                font-weight: 700;
                letter-spacing: 5px;
                margin-bottom: 0.5rem;
                text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.2);
            }

            .hero-subtitle {
                font-size: 1.5rem;
                font-weight: 500;
                letter-spacing: 3px;
                margin-bottom: 1rem;
                color: var(--color-secondary);
            }

            .hero-description {
                font-size: 1.2rem;
                max-width: 700px;
                margin: 0 auto 2rem;
                line-height: 1.6;
                opacity: 0.95;
            }

            /* Dashboard Section para usuarios AUTENTICADOS */
            .martinez-dashboard {
                width: 100%;
            }

            .martinez-header {
                text-align: center;
                margin-bottom: 3rem;
                padding: 3rem 2rem;
                background: white;
                border-radius: 20px;
                box-shadow: var(--shadow-soft);
                border-top: 4px solid var(--color-accent);
            }

            .martinez-brand-name {
                font-family: 'Playfair Display', serif;
                font-size: 3rem;
                font-weight: 700;
                letter-spacing: 5px;
                margin-bottom: 0.5rem;
                color: var(--color-primary);
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            }

            .martinez-brand-subtitle {
                font-size: 1.3rem;
                color: var(--color-accent);
                margin-bottom: 1rem;
                font-weight: 500;
                letter-spacing: 2px;
            }

            .martinez-tagline {
                font-size: 1.1rem;
                font-weight: 400;
                color: #666;
                letter-spacing: 2px;
            }

            /* Products Grid */
            .martinez-products-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
                gap: 2.5rem;
            }

            .martinez-product-card {
                background: white;
                border-radius: 16px;
                overflow: hidden;
                box-shadow: var(--shadow-soft);
                transition: all 0.4s ease;
                position: relative;
            }

            .martinez-product-card:hover {
                transform: translateY(-10px) scale(1.02);
                box-shadow: var(--shadow-medium);
            }

            .product-badge {
                position: absolute;
                top: 15px;
                right: 15px;
                background: linear-gradient(135deg, var(--color-accent), var(--color-secondary));
                color: white;
                padding: 0.4rem 1rem;
                border-radius: 20px;
                font-size: 0.8rem;
                font-weight: 600;
                z-index: 10;
                text-transform: uppercase;
                letter-spacing: 1px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            }

            .martinez-product-image {
                height: 250px;
                background: linear-gradient(135deg, var(--color-secondary) 0%, #c4a57b 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                color: rgba(255, 255, 255, 0.8);
                font-style: italic;
                font-size: 1.1rem;
                position: relative;
                overflow: hidden;
            }

            .martinez-product-image::before {
                content: '☕';
                font-size: 6rem;
                position: absolute;
                opacity: 0.2;
                animation: float 3s ease-in-out infinite;
            }

            .martinez-product-info {
                padding: 2rem;
            }

            .martinez-product-title {
                font-size: 1.3rem;
                font-weight: 600;
                color: var(--color-primary);
                margin-bottom: 1rem;
                line-height: 1.4;
                min-height: 3.5rem;
            }

            .martinez-product-description {
                color: #666;
                font-size: 0.95rem;
                margin-bottom: 1.5rem;
                line-height: 1.6;
            }

            .martinez-product-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 1.5rem;
                padding-top: 1.5rem;
                border-top: 1px solid #eee;
            }

            .martinez-product-price {
                font-size: 1.8rem;
                font-weight: 700;
                color: var(--color-primary);
            }

            .btn-view-product {
                background: var(--color-accent);
                color: white;
                padding: 0.7rem 1.5rem;
                border-radius: 8px;
                border: none;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-block;
                font-family: 'Poppins', sans-serif;
            }

            .btn-view-product:hover {
                background: var(--color-dark);
                transform: scale(1.05);
            }

            /* Welcome Section para NO autenticados */
            .welcome-section {
                background: white;
                border-radius: 20px;
                padding: 3rem;
                box-shadow: var(--shadow-soft);
                margin-bottom: 3rem;
                border-top: 4px solid var(--color-accent);
            }

            .welcome-title {
                font-family: 'Playfair Display', serif;
                font-size: 2rem;
                color: var(--color-primary);
                font-weight: 700;
                margin-bottom: 1rem;
            }

            .welcome-text {
                color: #666;
                font-size: 1.1rem;
                line-height: 1.6;
                margin-bottom: 2rem;
            }

            .features-list {
                list-style: none;
                margin-bottom: 2rem;
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                gap: 1rem;
                width: 100%;
                align-items: stretch;
            }

            .feature-item {
                display: flex;
                align-items: flex-start;
                gap: 1rem;
                padding: 1.5rem;
                margin-bottom: 0;
                background: linear-gradient(135deg, #f9f9f9 0%, #f5f5f5 100%);
                border-radius: 12px;
                transition: all 0.3s ease;
                flex: 1 1 calc(33.333% - 1rem);
                min-width: 220px;
                box-sizing: border-box;
                border: 2px solid transparent;
            }

            .feature-item:hover {
                background: linear-gradient(135deg, #fff 0%, #f9f9f9 100%);
                transform: translateY(-5px);
                border-color: var(--color-secondary);
                box-shadow: var(--shadow-soft);
            }

            .feature-icon {
                font-size: 2.5rem;
                min-width: 50px;
            }

            .feature-content {
                flex: 1;
            }

            .feature-title {
                font-weight: 600;
                color: var(--color-primary);
                margin-bottom: 0.3rem;
                font-size: 1.1rem;
            }

            .feature-description {
                color: #666;
                font-size: 0.95rem;
            }

            /* Alert Messages */
            .alert {
                padding: 2rem;
                border-radius: 12px;
                margin-bottom: 2rem;
                text-align: center;
            }

            .alert-info {
                background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
                border: 2px solid #2196f3;
                color: #0d47a1;
            }

            .alert h4 {
                font-size: 1.5rem;
                margin-bottom: 0.5rem;
            }

            .alert p {
                margin-bottom: 1rem;
            }

            /* Responsive Design */
            @media (max-width: 1024px) {
                .nav-links {
                    display: none;
                }

                .menu-toggle {
                    display: block;
                }

                .brand-name {
                    font-size: 1.3rem;
                }

                .hero-title {
                    font-size: 2.5rem;
                }

                .hero-subtitle {
                    font-size: 1.2rem;
                }

                .martinez-brand-name {
                    font-size: 2rem;
                }

                .martinez-products-grid {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 768px) {
                .nav-header {
                    padding: 1rem;
                }

                .nav-container {
                    padding: 1rem;
                    flex-wrap: wrap;
                }

                .brand-icon {
                    width: 40px;
                    height: 40px;
                    font-size: 1.4rem;
                }

                .brand-name {
                    font-size: 1.1rem;
                }

                .brand-tagline {
                    font-size: 0.65rem;
                }

                .main-container {
                    padding: 0 1rem;
                    margin: 2rem auto;
                }

                .hero-banner {
                    padding: 3rem 1.5rem;
                }

                .hero-title {
                    font-size: 2rem;
                }

                .auth-section {
                    flex-direction: column;
                    width: 100%;
                }

                .btn {
                    width: 100%;
                    text-align: center;
                }

                .welcome-section {
                    padding: 2rem 1.5rem;
                }

                .features-list {
                    flex-direction: column;
                }

                .feature-item {
                    flex: 1 1 100%;
                    min-width: auto;
                }
            }
        </style>
    </head>
    <body>
        <!-- Header Navigation -->
        <header class="nav-header">
            <nav class="nav-container">
                <div class="brand-section">
                    <a href="{{ route('home') }}" class="brand-logo">
                        <div class="brand-icon">☕</div>
                        <div class="brand-content">
                            <div class="brand-name">EL RINCÓN SABROSITO</div>
                            <div class="brand-tagline">Calm Energy</div>
                        </div>
                    </a>
                    <div class="nav-links">
                        <a href="{{ route('home') }}" class="nav-link">Home</a>
                        <a href="{{ route('home') }}#productos" class="nav-link">Productos</a>
                        <a href="{{ route('home') }}#ofertas" class="nav-link">Ofertas</a>
                    </div>
                </div>

                <div class="auth-section">
                    @auth
                        {{-- Botón Dashboard según rol --}}
                        @if(auth()->user()->esAdministrador())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                Dashboard Admin
                            </a>
                        @elseif(auth()->user()->esEmpleado())
                            <a href="{{ route('empleado.dashboard') }}" class="btn btn-secondary">
                                Dashboard
                            </a>
                        @else
                            {{-- Cliente solo ve su perfil o reservaciones --}}
                            <a href="{{ route('cliente.reservaciones') }}" class="btn btn-secondary">
                                Mis Reservaciones
                            </a>
                        @endif

                        {{-- Botón simple de logout --}}
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-logout">
                                Cerrar Sesión
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-secondary">
                            Iniciar Sesión
                        </a>
                        <a href="{{ route('registro') }}" class="btn btn-primary">
                            Registrarse
                        </a>
                    @endauth

                    <!-- Mobile Menu Toggle -->
                    <button id="menuToggle" class="menu-toggle" aria-label="Abrir menú">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 12h18M3 6h18M3 18h18"/>
                        </svg>
                    </button>
                </div>

                <!-- Mobile Menu -->
                <div id="mobileMenu" class="mobile-menu">
                    <ul class="mobile-menu-list">
                        <li><a href="{{ route('home') }}" class="nav-link">Home</a></li>
                        <li><a href="{{ route('home') }}#productos" class="nav-link">Productos</a></li>
                        <li><a href="{{ route('home') }}#ofertas" class="nav-link">Ofertas</a></li>
                        @auth
                            @if(auth()->user()->esAdministrador())
                                <li><a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Dashboard Admin</a></li>
                            @elseif(auth()->user()->esEmpleado())
                                <li><a href="{{ route('empleado.dashboard') }}" class="btn btn-secondary">Dashboard</a></li>
                            @else
                                <li><a href="{{ route('cliente.perfil') }}" class="nav-link">Mi Perfil</a></li>
                                <li><a href="{{ route('cliente.reservaciones') }}" class="nav-link">Mis Reservaciones</a></li>
                                <li><a href="{{ route('cliente.reservar') }}" class="btn btn-primary">Hacer Reservación</a></li>
                            @endif
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn-logout" style="width: 100%;">Cerrar Sesión</button>
                                </form>
                            </li>
                        @else
                            <li><a href="{{ route('login') }}" class="btn btn-secondary">Iniciar Sesión</a></li>
                            <li><a href="{{ route('registro') }}" class="btn btn-primary">Registrarse</a></li>
                        @endauth
                    </ul>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="main-container">
            @auth
                <!-- Vista para USUARIOS AUTENTICADOS (todos los roles) -->
                <div class="martinez-dashboard">
                    <div class="martinez-header">
                        <h1 class="martinez-brand-name">EL RINCÓN SABROSITO</h1>
                        <div class="martinez-brand-subtitle">IPOTIALIANO</div>
                        <div class="martinez-tagline">CALM ENERGY</div>
                    </div>

                    {{-- Mensaje personalizado según el rol --}}
                    @if(auth()->user()->esAdministrador())
                        <div class="alert alert-info">
                            <h4>👋 Bienvenido, Administrador</h4>
                            <p>Accede al panel de administración para gestionar el sistema.</p>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Ir al Dashboard Admin</a>
                        </div>
                    @elseif(auth()->user()->esEmpleado())
                        <div class="alert alert-info">
                            <h4>👋 Bienvenido, {{ auth()->user()->nombre_completo }}</h4>
                            <p>Accede al panel de empleados para gestionar ventas y reservaciones.</p>
                            <a href="{{ route('empleado.dashboard') }}" class="btn btn-primary">Ir al Dashboard</a>
                        </div>
                    @else
                        {{-- CLIENTE: Muestra la página completa con productos --}}
                        <div class="welcome-section">
                            <h2 class="welcome-title">¡Bienvenido, {{ auth()->user()->nombre_completo }}! 👋</h2>
                            <p class="welcome-text">
                                Explora nuestros productos y realiza tus reservaciones de forma rápida y sencilla.
                            </p>
                            <div style="text-align: center; margin-top: 2rem;">
                                <a href="{{ route('cliente.reservar') }}" class="btn btn-primary" style="font-size: 1.1rem; padding: 1rem 2.5rem;">
                                    🗓️ Hacer una Reservación
                                </a>
                                <a href="{{ route('cliente.reservaciones') }}" class="btn btn-secondary" style="font-size: 1.1rem; padding: 1rem 2.5rem; margin-left: 1rem;">
                                    📋 Ver Mis Reservaciones
                                </a>
                            </div>
                        </div>

                        <!-- Productos para CLIENTES autenticados -->
                        <div id="productos" style="margin-top: 4rem;">
                            <h2 class="welcome-title" style="text-align: center; margin-bottom: 2rem;">Nuestros Productos</h2>
                            <div class="martinez-products-grid">
                                <!-- Producto 1 -->
                                <div class="martinez-product-card">
                                    <span class="product-badge">Nuevo</span>
                                    <div class="martinez-product-image">
                                        <span>Combo Desayuno Completo</span>
                                    </div>
                                    <div class="martinez-product-info">
                                        <h3 class="martinez-product-title">Combo Desayuno Cápsulas, Café y Dulces</h3>
                                        <p class="martinez-product-description">
                                            El combo perfecto para comenzar tu día con energía.
                                            Incluye cápsulas de café premium, café molido y dulces artesanales.
                                        </p>
                                        <div class="martinez-product-footer">
                                            <div class="martinez-product-price">$ 45.000</div>
                                            <a href="#" class="btn-view-product">Ver Más</a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Producto 2 -->
                                <div class="martinez-product-card">
                                    <span class="product-badge">Popular</span>
                                    <div class="martinez-product-image">
                                        <span>Cafetera Hudson Premium</span>
                                    </div>
                                    <div class="martinez-product-info">
                                        <h3 class="martinez-product-title">Combo Cafetera Hudson Aluminio Tipo Italiana 6 pocillos + Molidos 250gr.</h3>
                                        <p class="martinez-product-description">
                                            Cafetera de aluminio tipo italiana para 6 pocillos.
                                            Incluye 250gr de café molido de alta calidad.
                                        </p>
                                        <div class="martinez-product-footer">
                                            <div class="martinez-product-price">$ 60.000</div>
                                            <a href="#" class="btn-view-product">Ver Más</a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Producto 3 -->
                                <div class="martinez-product-card">
                                    <span class="product-badge">Oferta</span>
                                    <div class="martinez-product-image">
                                        <span>Café Colombia Premium</span>
                                    </div>
                                    <div class="martinez-product-info">
                                        <h3 class="martinez-product-title">Combo Café Colombia y minis alfajores negros</h3>
                                        <p class="martinez-product-description">
                                            Café 100% colombiano de origen único acompañado de
                                            deliciosos mini alfajores negros artesanales.
                                        </p>
                                        <div class="martinez-product-footer">
                                            <div class="martinez-product-price">$ 32.000</div>
                                            <a href="#" class="btn-view-product">Ver Más</a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Producto 4 -->
                                <div class="martinez-product-card">
                                    <span class="product-badge">Descuento</span>
                                    <div class="martinez-product-image">
                                        <span>Café Espresso Italiano</span>
                                    </div>
                                    <div class="martinez-product-info">
                                        <h3 class="martinez-product-title">Espresso Italiano Tradicional 500gr</h3>
                                        <p class="martinez-product-description">
                                            Blend especial de café tostado oscuro con sabor intenso
                                            y aroma inconfundible. Perfecto para espresso.
                                        </p>
                                        <div class="martinez-product-footer">
                                            <div class="martinez-product-price">$ 28.000</div>
                                            <a href="#" class="btn-view-product">Ver Más</a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Producto 5 -->
                                <div class="martinez-product-card">
                                    <span class="product-badge">Premium</span>
                                    <div class="martinez-product-image">
                                        <span>Café Gourmet Seleccionado</span>
                                    </div>
                                    <div class="martinez-product-info">
                                        <h3 class="martinez-product-title">Café Gourmet Single Origin 250gr</h3>
                                        <p class="martinez-product-description">
                                            Granos seleccionados de finca única con notas de chocolate
                                            y frutas secas. Molienda fina para filtro.
                                        </p>
                                        <div class="martinez-product-footer">
                                            <div class="martinez-product-price">$ 35.000</div>
                                            <a href="#" class="btn-view-product">Ver Más</a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Producto 6 -->
                                <div class="martinez-product-card">
                                    <span class="product-badge">Nuevo</span>
                                    <div class="martinez-product-image">
                                        <span>Kit Iniciante Barista</span>
                                    </div>
                                    <div class="martinez-product-info">
                                        <h3 class="martinez-product-title">Kit Iniciante Barista Completo</h3>
                                        <p class="martinez-product-description">
                                            Todo lo necesario para preparar café como un profesional.
                                            Incluye molinillo, filtros y guía de preparación.
                                        </p>
                                        <div class="martinez-product-footer">
                                            <div class="martinez-product-price">$ 85.000</div>
                                            <a href="#" class="btn-view-product">Ver Más</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <!-- Página de inicio para USUARIOS NO AUTENTICADOS -->
                <div class="hero-banner">
                    <div class="hero-content">
                        <h1 class="hero-title">EL RINCÓN SABROSITO</h1>
                        <p class="hero-subtitle">IPOTIALIANO</p>
                        <p class="hero-description">
                            Experimenta la perfecta fusión entre la calidad del café colombiano
                            y el estilo italiano. Calm Energy para tu día a día.
                        </p>
                    </div>
                </div>

                <div class="welcome-section">
                    <p class="welcome-text">
                        Donde el café de calidad se encuentra con el estilo italiano.
                        Regístrate para realizar pedidos y disfrutar de nuestras ofertas exclusivas.
                    </p>

                    <ul class="features-list">
                        <li class="feature-item">
                            <div class="feature-icon">☕</div>
                            <div class="feature-content">
                                <div class="feature-title">Café Premium</div>
                                <div class="feature-description">
                                    100% colombiano seleccionado de las mejores fincas
                                </div>
                            </div>
                        </li>
                        <li class="feature-item">
                            <div class="feature-icon">🚚</div>
                            <div class="feature-content">
                                <div class="feature-title">Envío Gratis</div>
                                <div class="feature-description">
                                    En compras superiores a $50.000
                                </div>
                            </div>
                        </li>
                        <li class="feature-item">
                            <div class="feature-icon">🎁</div>
                            <div class="feature-content">
                                <div class="feature-title">Combos Únicos</div>
                                <div class="feature-description">
                                    Ofertas especiales diseñadas para ti
                                </div>
                            </div>
                        </li>
                    </ul>

                    <div style="text-align: center;">
                        <a href="{{ route('registro') }}" class="btn btn-primary" style="font-size: 1.1rem; padding: 1rem 2.5rem;">
                            Crear Cuenta Gratis
                        </a>
                    </div>
                </div>

                <!-- Galería de Productos para NO AUTENTICADOS -->
                <div id="productos" style="margin-top: 4rem;">
                    <h2 class="welcome-title" style="text-align: center; margin-bottom: 2rem;">Nuestros Productos</h2>
                    <div class="martinez-products-grid">
                        <!-- Producto 1 -->
                        <div class="martinez-product-card">
                            <span class="product-badge">Nuevo</span>
                            <div class="martinez-product-image">
                                <span>Combo Desayuno Completo</span>
                            </div>
                            <div class="martinez-product-info">
                                <h3 class="martinez-product-title">Combo Desayuno Cápsulas, Café y Dulces</h3>
                                <p class="martinez-product-description">
                                    El combo perfecto para comenzar tu día con energía.
                                    Incluye cápsulas de café premium, café molido y dulces artesanales.
                                </p>
                                <div class="martinez-product-footer">
                                    <div class="martinez-product-price">$ 45.000</div>
                                    <a href="#" class="btn-view-product">Ver Más</a>
                                </div>
                            </div>
                        </div>

                        <!-- Producto 2 -->
                        <div class="martinez-product-card">
                            <span class="product-badge">Popular</span>
                            <div class="martinez-product-image">
                                <span>Cafetera Hudson Premium</span>
                            </div>
                            <div class="martinez-product-info">
                                <h3 class="martinez-product-title">Combo Cafetera Hudson Aluminio Tipo Italiana 6 pocillos + Molidos 250gr.</h3>
                                <p class="martinez-product-description">
                                    Cafetera de aluminio tipo italiana para 6 pocillos.
                                    Incluye 250gr de café molido de alta calidad.
                                </p>
                                <div class="martinez-product-footer">
                                    <div class="martinez-product-price">$ 60.000</div>
                                    <a href="#" class="btn-view-product">Ver Más</a>
                                </div>
                            </div>
                        </div>

                        <!-- Producto 3 -->
                        <div class="martinez-product-card">
                            <span class="product-badge">Oferta</span>
                            <div class="martinez-product-image">
                                <span>Café Colombia Premium</span>
                            </div>
                            <div class="martinez-product-info">
                                <h3 class="martinez-product-title">Combo Café Colombia y minis alfajores negros</h3>
                                <p class="martinez-product-description">
                                    Café 100% colombiano de origen único acompañado de
                                    deliciosos mini alfajores negros artesanales.
                                </p>
                                <div class="martinez-product-footer">
                                    <div class="martinez-product-price">$ 32.000</div>
                                    <a href="#" class="btn-view-product">Ver Más</a>
                                </div>
                            </div>
                        </div>

                        <!-- Producto 4 -->
                        <div class="martinez-product-card">
                            <span class="product-badge">Descuento</span>
                            <div class="martinez-product-image">
                                <span>Café Espresso Italiano</span>
                            </div>
                            <div class="martinez-product-info">
                                <h3 class="martinez-product-title">Espresso Italiano Tradicional 500gr</h3>
                                <p class="martinez-product-description">
                                    Blend especial de café tostado oscuro con sabor intenso
                                    y aroma inconfundible. Perfecto para espresso.
                                </p>
                                <div class="martinez-product-footer">
                                    <div class="martinez-product-price">$ 28.000</div>
                                    <a href="#" class="btn-view-product">Ver Más</a>
                                </div>
                            </div>
                        </div>

                        <!-- Producto 5 -->
                        <div class="martinez-product-card">
                            <span class="product-badge">Premium</span>
                            <div class="martinez-product-image">
                                <span>Café Gourmet Seleccionado</span>
                            </div>
                            <div class="martinez-product-info">
                                <h3 class="martinez-product-title">Café Gourmet Single Origin 250gr</h3>
                                <p class="martinez-product-description">
                                    Granos seleccionados de finca única con notas de chocolate
                                    y frutas secas. Molienda fina para filtro.
                                </p>
                                <div class="martinez-product-footer">
                                    <div class="martinez-product-price">$ 35.000</div>
                                    <a href="#" class="btn-view-product">Ver Más</a>
                                </div>
                            </div>
                        </div>

                        <!-- Producto 6 -->
                        <div class="martinez-product-card">
                            <span class="product-badge">Nuevo</span>
                            <div class="martinez-product-image">
                                <span>Kit Iniciante Barista</span>
                            </div>
                            <div class="martinez-product-info">
                                <h3 class="martinez-product-title">Kit Iniciante Barista Completo</h3>
                                <p class="martinez-product-description">
                                    Todo lo necesario para preparar café como un profesional.
                                    Incluye molinillo, filtros y guía de preparación.
                                </p>
                                <div class="martinez-product-footer">
                                    <div class="martinez-product-price">$ 85.000</div>
                                    <a href="#" class="btn-view-product">Ver Más</a>
                                </div>
                            </div>
                        </div>

                        <!-- Producto 7 -->
                        <div class="martinez-product-card">
                            <span class="product-badge">Popular</span>
                            <div class="martinez-product-image">
                                <span>Bebida Matcha Latte</span>
                            </div>
                            <div class="martinez-product-info">
                                <h3 class="martinez-product-title">Combo Matcha Latte Premium</h3>
                                <p class="martinez-product-description">
                                    Matcha japonés ceremonial 100% puro con leche de almendra.
                                    Energía natural y sabor exótico en cada taza.
                                </p>
                                <div class="martinez-product-footer">
                                    <div class="martinez-product-price">$ 38.000</div>
                                    <a href="#" class="btn-view-product">Ver Más</a>
                                </div>
                            </div>
                        </div>

                        <!-- Producto 8 -->
                        <div class="martinez-product-card">
                            <span class="product-badge">Oferta</span>
                            <div class="martinez-product-image">
                                <span>Caramelos y Dulces</span>
                            </div>
                            <div class="martinez-product-info">
                                <h3 class="martinez-product-title">Pack Dulces Artesanales 300gr</h3>
                                <p class="martinez-product-description">
                                    Selección de alfajores, macarons y chocolates artesanales.
                                    Elaborados con ingredientes premium y técnicas tradicionales.
                                </p>
                                <div class="martinez-product-footer">
                                    <div class="martinez-product-price">$ 24.000</div>
                                    <a href="#" class="btn-view-product">Ver Más</a>
                                </div>
                            </div>
                        </div>

                        <!-- Producto 9 -->
                        <div class="martinez-product-card">
                            <span class="product-badge">Exclusivo</span>
                            <div class="martinez-product-image">
                                <span>Café Tostado Artesanal</span>
                            </div>
                            <div class="martinez-product-info">
                                <h3 class="martinez-product-title">Café Tostado Artesanal en Grano 500gr</h3>
                                <p class="martinez-product-description">
                                    Tostado lento y artesanal que resalta los sabores naturales.
                                    Ideal para preparar en casa con tu método favorito.
                                </p>
                                <div class="martinez-product-footer">
                                    <div class="martinez-product-price">$ 42.000</div>
                                    <a href="#" class="btn-view-product">Ver Más</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth
        </main>

        <!-- JavaScript para Mobile Menu -->
        <script>
            (function(){
                var btn = document.getElementById('menuToggle');
                var menu = document.getElementById('mobileMenu');
                if (!btn || !menu) return;

                btn.addEventListener('click', function(){
                    menu.classList.toggle('show');
                    var isExpanded = menu.classList.contains('show');
                    btn.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
                });
            })();
        </script>
    </body>
</html>
