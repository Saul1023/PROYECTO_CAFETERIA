<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'El Rincón Sabrosito') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:700|poppins:400,500,600" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

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

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-8px);
        }
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

    .btn>* {
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
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Hero Banner */
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

    /* Products Grid - MÁS COMPACTO PARA 3 CARDS */
    .martinez-products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .martinez-product-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        transition: all 0.4s ease;
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .martinez-product-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-medium);
    }

    .product-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: linear-gradient(135deg, var(--color-accent), var(--color-secondary));
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 10;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .product-badge.stock-bajo {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
    }

    .product-badge.popular {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
    }

    .martinez-product-image {
        height: 200px;
        /* MÁS COMPACTO */
        background: linear-gradient(135deg, var(--color-secondary) 0%, #c4a57b 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
    }

    .martinez-product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .martinez-product-card:hover .martinez-product-image img {
        transform: scale(1.05);
    }

    .martinez-product-image .no-image {
        color: rgba(255, 255, 255, 0.8);
        font-style: italic;
        font-size: 0.9rem;
        text-align: center;
        padding: 1rem;
    }

    .martinez-product-image .no-image i {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        display: block;
        opacity: 0.7;
    }

    .martinez-product-info {
        padding: 1.25rem;
        /* MÁS COMPACTO */
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .martinez-product-title {
        font-size: 1.1rem;
        /* MÁS PEQUEÑO */
        font-weight: 600;
        color: var(--color-primary);
        margin-bottom: 0.75rem;
        line-height: 1.3;
        min-height: 2.8rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .martinez-product-description {
        color: #666;
        font-size: 0.85rem;
        /* MÁS PEQUEÑO */
        margin-bottom: 1rem;
        line-height: 1.5;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .martinez-product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid #eee;
    }

    .martinez-product-price {
        font-size: 1.5rem;
        /* MÁS PEQUEÑO */
        font-weight: 700;
        color: var(--color-primary);
    }

    .product-stock {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.8rem;
        color: #666;
    }

    .product-stock .stock-icon {
        color: var(--color-accent);
        font-size: 0.9rem;
    }

    .product-stock.stock-bajo {
        color: #e74c3c;
    }

    .product-stock.stock-bajo .stock-icon {
        color: #e74c3c;
    }

    .product-category {
        margin-top: 0.5rem;
    }

    .category-badge {
        background: var(--color-secondary);
        color: var(--color-dark);
        padding: 0.25rem 0.6rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* Welcome Section */
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

    /* No Products State */
    .no-products {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow-soft);
    }

    .no-products i {
        font-size: 4rem;
        color: var(--color-secondary);
        margin-bottom: 1rem;
        opacity: 0.7;
    }

    .no-products h5 {
        color: var(--color-primary);
        margin-bottom: 0.5rem;
    }

    .no-products p {
        color: #666;
        margin-bottom: 0;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .martinez-products-grid {
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }
    }

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

        .martinez-products-grid {
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
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

        .martinez-products-grid {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }

        .martinez-product-image {
            height: 180px;
        }

        .martinez-product-info {
            padding: 1rem;
        }
    }

    @media (max-width: 640px) {
        .martinez-products-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .martinez-product-footer {
            flex-direction: column;
            gap: 0.8rem;
            align-items: flex-start;
        }

        .product-stock {
            align-self: flex-end;
        }

        .martinez-product-image {
            height: 160px;
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
                </div>
            </div>

            <div class="auth-section">
                @auth
                <!-- MENÚ PARA CLIENTES AUTENTICADOS -->
                @if(auth()->user()->esCliente())
                <div class="nav-links" style="display: flex; gap: 1.5rem; margin-right: 1rem;">
                    <a href="{{ route('cliente.reservar') }}" class="nav-link">
                        <i class="bi bi-calendar-plus" style="margin-right: 0.3rem;"></i>
                        Reservar
                    </a>
                    <a href="{{ route('cliente.reservaciones') }}" class="nav-link">
                        <i class="bi bi-list-check" style="margin-right: 0.3rem;"></i>
                        Mis Reservas
                    </a>
                    <a href="{{ route('cliente.perfil') }}" class="nav-link">
                        <i class="bi bi-person" style="margin-right: 0.3rem;"></i>
                        Mi Perfil
                    </a>
                </div>

                <div class="user-info" style="display: flex; align-items: center; gap: 1rem; color: white;">
                    <div class="user-avatar"
                        style="width: 35px; height: 35px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.8rem;">
                        {{ substr(auth()->user()->nombre_completo, 0, 2) }}
                    </div>
                    <span class="small">{{ auth()->user()->nombre_completo }}</span>
                </div>
                @endif

                <!-- Botón de logout para TODOS los autenticados -->
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-logout">
                        Cerrar Sesión
                    </button>
                </form>
                @else
                <!-- Botones de login y register para usuarios NO autenticados -->
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
                        <path d="M3 12h18M3 6h18M3 18h18" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="mobile-menu">
                <ul class="mobile-menu-list">
                    <li><a href="{{ route('home') }}" class="nav-link">Home</a></li>
                    <li><a href="{{ route('home') }}#productos" class="nav-link">Productos</a></li>
                    @guest
                    <li><a href="{{ route('login') }}" class="btn btn-secondary">Iniciar Sesión</a></li>
                    <li><a href="{{ route('registro') }}" class="btn btn-primary">Registrarse</a></li>
                    @endguest
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content - SOLO PRODUCTOS -->
    <main class="main-container">
        <!-- Hero Banner -->
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

        <!-- Información para usuarios -->
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

            @guest
            <div style="text-align: center;">
                <a href="{{ route('registro') }}" class="btn btn-primary"
                    style="font-size: 1.1rem; padding: 1rem 2.5rem;">
                    Crear Cuenta Gratis
                </a>
            </div>
            @endguest
        </div>

        <!-- Galería de Productos - MÁS COMPACTA -->
        <div id="productos" style="margin-top: 4rem;">
            <h2 class="welcome-title" style="text-align: center; margin-bottom: 2rem;">Nuestros Productos</h2>

            @if($productos->count() > 0)
            <div class="martinez-products-grid">
                @foreach($productos as $producto)
                <div class="martinez-product-card">
                    @if($producto->stock <= $producto->stock_minimo)
                        <span class="product-badge stock-bajo">
                            <i class="bi bi-exclamation-triangle-fill"></i> Últimas unidades
                        </span>
                        @elseif($producto->stock < 10) <span class="product-badge popular">
                            <i class="bi bi-star-fill"></i> Popular
                            </span>
                            @else
                            <span class="product-badge">
                                <i class="bi bi-check-circle-fill"></i> Disponible
                            </span>
                            @endif

                            <div class="martinez-product-image">
                                @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}"
                                    loading="lazy">
                                @else
                                <div class="no-image">
                                    <i class="bi bi-cup-hot"></i>
                                    <span>{{ $producto->nombre }}</span>
                                </div>
                                @endif
                            </div>

                            <div class="martinez-product-info">
                                <h3 class="martinez-product-title">{{ $producto->nombre }}</h3>
                                <p class="martinez-product-description">
                                    {{ $producto->descripcion ?: 'Producto de alta calidad con los mejores ingredientes.' }}
                                </p>

                                <div class="martinez-product-footer">
                                    <div class="martinez-product-price">{{ $producto->precio_formateado }}</div>
                                    <div
                                        class="product-stock {{ $producto->stock <= $producto->stock_minimo ? 'stock-bajo' : '' }}">
                                        <i class="bi bi-box-seam stock-icon"></i>
                                        <span>{{ $producto->stock }} disp.</span>
                                    </div>
                                </div>

                                @if($producto->categoria)
                                <div class="product-category">
                                    <span class="category-badge">{{ $producto->categoria->nombre }}</span>
                                </div>
                                @endif
                            </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="no-products">
                <i class="bi bi-box-seam"></i>
                <h5>No hay productos disponibles</h5>
                <p>Próximamente tendremos nuevos productos para ti.</p>
            </div>
            @endif
        </div>
    </main>

    <!-- JavaScript para Mobile Menu -->
    <script>
    (function() {
        var btn = document.getElementById('menuToggle');
        var menu = document.getElementById('mobileMenu');
        if (!btn || !menu) return;

        btn.addEventListener('click', function() {
            menu.classList.toggle('show');
            var isExpanded = menu.classList.contains('show');
            btn.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
        });
    })();
    </script>
</body>

</html>