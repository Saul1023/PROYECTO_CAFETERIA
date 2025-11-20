@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Mensaje de bienvenida con rol -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient"
                style="background: linear-gradient(135deg, #6f4e37 0%, #d4a574 100%);">
                <div class="card-body text-white p-4">
                    <h2 class="mb-2">
                        <i class="bi bi-emoji-smile"></i>
                        ¡Bienvenido luis, {{ auth()->user()->nombre_completo }}!
                    </h2>
                    <p class="mb-0 opacity-75">
                        <i class="bi bi-shield-check"></i>
                        Rol: <strong>{{ auth()->user()->rol->nombre }}</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas generales -->
    <div class="row g-4 mb-4">
        <!-- Ventas del día -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted mb-1 small">Ventas Hoy</p>
                            <h3 class="mb-0">Bs. 1,234</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-currency-dollar text-success fs-4"></i>
                        </div>
                    </div>
                    <small class="text-success">
                        <i class="bi bi-arrow-up"></i> +12% vs ayer
                    </small>
                </div>
            </div>
        </div>

        <!-- Reservaciones -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted mb-1 small">Reservaciones</p>
                            <h3 class="mb-0">24</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-calendar-check text-primary fs-4"></i>
                        </div>
                    </div>
                    <small class="text-muted">
                        <i class="bi bi-clock"></i> 8 pendientes
                    </small>
                </div>
            </div>
        </div>

        <!-- Productos -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted mb-1 small">Productos</p>
                            <h3 class="mb-0">156</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-box-seam text-warning fs-4"></i>
                        </div>
                    </div>
                    <small class="text-warning">
                        <i class="bi bi-exclamation-triangle"></i> 5 stock bajo
                    </small>
                </div>
            </div>
        </div>

        <!-- Mesas disponibles -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted mb-1 small">Mesas Disponibles</p>
                            <h3 class="mb-0">12/20</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-table text-info fs-4"></i>
                        </div>
                    </div>
                    <small class="text-info">
                        <i class="bi bi-check-circle"></i> 60% ocupación
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas adicionales solo para administradores -->
    @if(auth()->user()->esAdministrador())
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-check text-primary"></i>
                        Panel de Administración
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="bi bi-people fs-3 text-primary me-3"></i>
                                <div>
                                    <p class="mb-0 small text-muted">Total Usuarios</p>
                                    <h4 class="mb-0">45</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="bi bi-tag fs-3 text-success me-3"></i>
                                <div>
                                    <p class="mb-0 small text-muted">Promociones Activas</p>
                                    <h4 class="mb-0">8</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="bi bi-graph-up fs-3 text-info me-3"></i>
                                <div>
                                    <p class="mb-0 small text-muted">Ingreso Mensual</p>
                                    <h4 class="mb-0">Bs. 45,230</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Sección de acceso rápido -->
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-charge"></i>
                        Acceso Rápido
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="{{ route('ventas') }}" class="text-decoration-none">
                                <div class="d-flex flex-column align-items-center p-3 border rounded hover-shadow">
                                    <i class="bi bi-cart-plus fs-1 text-primary mb-2"></i>
                                    <span class="fw-semibold">Nueva Venta</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('reservaciones') }}" class="text-decoration-none">
                                <div class="d-flex flex-column align-items-center p-3 border rounded hover-shadow">
                                    <i class="bi bi-calendar-plus fs-1 text-success mb-2"></i>
                                    <span class="fw-semibold">Nueva Reservación</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('productos') }}" class="text-decoration-none">
                                <div class="d-flex flex-column align-items-center p-3 border rounded hover-shadow">
                                    <i class="bi bi-box-seam fs-1 text-warning mb-2"></i>
                                    <span class="fw-semibold">Ver Productos</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle"></i>
                        Información
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                                <div>
                                    <strong>Sistema actualizado</strong>
                                    <p class="mb-0 small text-muted">Última actualización: Hoy</p>
                                </div>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-clock-fill text-primary me-2 mt-1"></i>
                                <div>
                                    <strong>Último acceso</strong>
                                    <p class="mb-0 small text-muted">
                                        {{ auth()->user()->ultimo_acceso ? auth()->user()->ultimo_acceso->diffForHumans() : 'Primer acceso' }}
                                    </p>
                                </div>
                            </div>
                        </li>
                        @if(auth()->user()->esAdministrador())
                        <li>
                            <div class="d-flex align-items-start">
                                <i class="bi bi-shield-check-fill text-warning me-2 mt-1"></i>
                                <div>
                                    <strong>Permisos especiales</strong>
                                    <p class="mb-0 small text-muted">Acceso completo al sistema</p>
                                </div>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    transform: translateY(-2px);
}
</style>
@endsection