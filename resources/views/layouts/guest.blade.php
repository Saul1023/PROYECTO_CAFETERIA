<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Autenticación</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @livewireStyles

    <style>
        body {
            background: linear-gradient(135deg, #6f4e37 0%, #d4a574 50%, #6f4e37 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Header con el logo/nombre del negocio */
        .brand-header {
            text-align: center;
            padding: 1.5rem 1rem 1rem 1rem;
            position: relative;
            flex-shrink: 0;
        }

        .brand-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 0.75rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .brand-logo i {
            font-size: 2.5rem;
            background: linear-gradient(135deg, #6f4e37 0%, #d4a574 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand-name {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            letter-spacing: 2px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
            margin-bottom: 0.3rem;
            line-height: 1.2;
        }

        .brand-subtitle {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.9);
            letter-spacing: 1.5px;
            font-weight: 500;
            text-transform: uppercase;
        }

        /* Contenedor principal */
        .main-container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-bottom: 60px; /* Espacio para el footer */
        }

        .content-wrapper {
            flex: 1;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 0 1rem 2rem 1rem;
            overflow-y: auto;
        }

        .content-wrapper > * {
            width: 100%;
            max-width: 480px;
        }

        /* Ancho fijo en pantallas grandes */
        @media (min-width: 992px) {
            .content-wrapper > * {
                max-width: 480px;
                min-width: 480px;
            }
        }

        /* Estilos de las tarjetas de autenticación */
        .auth-card {
            border-radius: 1rem;
            border: none;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .coffee-icon-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            background: linear-gradient(135deg, #6f4e37 0%, #d4a574 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(111, 78, 55, 0.3);
        }

        .coffee-icon {
            font-size: 2.5rem;
            color: white;
        }

        .auth-title {
            color: #6f4e37;
        }

        .btn-auth {
            background: linear-gradient(135deg, #6f4e37 0%, #d4a574 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }

        .btn-auth:hover {
            background: linear-gradient(135deg, #5a3d2a 0%, #c49563 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(111, 78, 55, 0.4);
        }

        .btn-auth:active {
            transform: translateY(0);
        }

        .auth-link {
            color: #6f4e37;
            transition: color 0.3s ease;
            font-weight: 500;
        }

        .auth-link:hover {
            color: #5a3d2a;
            text-decoration: underline !important;
        }

        .input-group-text {
            border-right: none;
            background-color: #f8f9fa;
        }

        .form-control {
            border-left: none;
        }

        .form-control:focus {
            border-color: #d4a574;
            box-shadow: 0 0 0 0.2rem rgba(111, 78, 55, 0.15);
        }

        .input-group:focus-within .input-group-text {
            border-color: #d4a574;
        }

        .divider-container {
            position: relative;
            text-align: center;
        }

        .divider-line {
            margin: 0;
        }

        .divider-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 0 1rem;
            color: #6c757d;
            font-weight: 500;
        }

        /* Footer */
        .auth-footer {
            text-align: center;
            padding: 1rem;
            color: rgba(255, 255, 255, 0.8);
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(111, 78, 55, 0.3);
            backdrop-filter: blur(10px);
        }

        .auth-footer small {
            font-size: 0.8rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .brand-name {
                font-size: 1.5rem;
                letter-spacing: 1.5px;
            }

            .brand-subtitle {
                font-size: 0.75rem;
                letter-spacing: 1px;
            }

            .brand-logo {
                width: 60px;
                height: 60px;
            }

            .brand-logo i {
                font-size: 2rem;
            }

            .brand-header {
                padding: 1rem 1rem 0.5rem 1rem;
            }

            .content-wrapper {
                padding: 0 0.5rem 1rem 0.5rem;
            }
        }

        @media (min-width: 769px) {
            .content-wrapper > * {
                max-width: 550px;
            }
        }

        @media (min-width: 1200px) {
            .brand-name {
                font-size: 2.5rem;
            }

            .brand-logo {
                width: 90px;
                height: 90px;
            }

            .brand-logo i {
                font-size: 3rem;
            }
        }

        /* Animación de entrada */
        .auth-card {
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mejora visual del card-footer */
        .card-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Header con logo y nombre -->
        <div class="brand-header">
            <div class="brand-logo">
                <i class="bi bi-cup-hot-fill"></i>
            </div>
            <div class="brand-name">EL RINCÓN SABROSITO</div>
            <div class="brand-subtitle">Sistema de Gestión</div>
        </div>

        <!-- Contenido principal (Login o Registro) -->
        <div class="content-wrapper">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="auth-footer">
            <small>
                <i class="bi bi-shield-check me-1"></i>
                © 2025 El Rincón Sabrosito. Todos los derechos reservados.
            </small>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts

    <script>
        // Auto-cerrar alertas después de 5 segundos
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>
