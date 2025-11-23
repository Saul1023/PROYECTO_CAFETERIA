<div class="mb-4">
    {{-- Header con Logo y Título --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white overflow-hidden">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        {{-- Logo --}}
                        <div class="col-auto">
                            <div class="bg-white rounded-3 p-3 shadow-lg">
                                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="img-fluid"
                                    style="max-height: 80px; width: auto;">
                            </div>
                        </div>

                        {{-- Información del Reporte --}}
                        <div class="col">
                            <div class="d-flex flex-column">
                                <h1 class="display-6 fw-bold mb-2">REPORTE GENERAL aqui se carga</h1>
                                <p class="mb-1 fs-5 opacity-90">
                                    <i class="bi bi-building me-2"></i>Sistema de Gestión
                                </p>
                                <p class="mb-0 opacity-75">
                                    <i class="bi bi-calendar-range me-2"></i>
                                    Período: <strong>{{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} -
                                        {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</strong>
                                </p>
                            </div>
                        </div>

                        {{-- Badge de Estado --}}
                        <div class="col-auto">
                            <div class="text-center">
                                <div class="bg-white bg-opacity-20 rounded-3 p-3 mb-2">
                                    <i class="bi bi-activity fs-2 d-block"></i>
                                </div>
                                <span class="badge bg-success fs-6 px-3 py-2">
                                    <i class="bi bi-check-circle-fill me-1"></i>ACTIVO
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Onda decorativa --}}
                <div class="position-absolute bottom-0 end-0 opacity-10">
                    <svg width="200" height="100" viewBox="0 0 200 100" fill="currentColor">
                        <path d="M0,50 Q100,0 200,50 L200,100 L0,100 Z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Tarjetas Principales --}}
    <div class="row g-4 mb-4">
        {{-- Ventas --}}
        <div class="col-xl-3 col-md-6">
            <div class="card bg-gradient-success text-white shadow-lg border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-6 fw-semibold opacity-75 mb-1">💰 INGRESOS TOTALES</div>
                            <h2 class="display-6 fw-bold mb-2">Bs
                                {{ number_format($estadisticas['ventas']['ingresos'] ?? 0, 2) }}</h2>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-white bg-opacity-20 text-white fs-7">
                                    <i class="bi bi-cart-check me-1"></i>
                                    {{ $estadisticas['ventas']['total'] ?? 0 }} ventas
                                </span>
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-3 p-3">
                            <i class="bi bi-currency-dollar fs-1"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-top border-white border-opacity-25">
                        <small class="opacity-75">
                            <i class="bi bi-arrow-up-right me-1"></i>
                            Ticket promedio: Bs
                            {{ number_format(($estadisticas['ventas']['ingresos'] ?? 0) / max(1, ($estadisticas['ventas']['total'] ?? 0)), 2) }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reservaciones --}}
        <div class="col-xl-3 col-md-6">
            <div class="card bg-gradient-info text-white shadow-lg border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-6 fw-semibold opacity-75 mb-1">📅 RESERVACIONES</div>
                            <h2 class="display-6 fw-bold mb-2">{{ $estadisticas['reservaciones']['total'] ?? 0 }}</h2>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-white bg-opacity-20 text-white fs-7">
                                    <i class="bi bi-check-circle me-1"></i>
                                    {{ $estadisticas['reservaciones']['confirmadas'] ?? 0 }} confirmadas
                                </span>
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-3 p-3">
                            <i class="bi bi-calendar-heart fs-1"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-top border-white border-opacity-25">
                        <small class="opacity-75">
                            <i class="bi bi-percent me-1"></i>
                            Tasa de confirmación:
                            {{ ($estadisticas['reservaciones']['total'] ?? 0) > 0 ? round((($estadisticas['reservaciones']['confirmadas'] ?? 0) / ($estadisticas['reservaciones']['total'] ?? 1)) * 100, 1) : 0 }}%
                        </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Productos --}}
        <div class="col-xl-3 col-md-6">
            <div class="card bg-gradient-warning text-dark shadow-lg border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-6 fw-semibold opacity-75 mb-1">📦 INVENTARIO</div>
                            <h2 class="display-6 fw-bold mb-2">{{ $estadisticas['productos']['total'] ?? 0 }}</h2>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-dark bg-opacity-10 text-dark fs-7">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    {{ $estadisticas['productos']['stock_bajo'] ?? 0 }} alertas
                                </span>
                            </div>
                        </div>
                        <div class="bg-dark bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-box-seam fs-1"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-top border-dark border-opacity-25">
                        <small class="opacity-75">
                            <i class="bi bi-shield-check me-1"></i>
                            Stock saludable:
                            {{ ($estadisticas['productos']['total'] ?? 0) > 0 ? round((($estadisticas['productos']['total'] ?? 0) - ($estadisticas['productos']['stock_bajo'] ?? 0)) / ($estadisticas['productos']['total'] ?? 1) * 100, 1) : 100 }}%
                        </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Usuarios --}}
        <div class="col-xl-3 col-md-6">
            <div class="card bg-gradient-purple text-white shadow-lg border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-6 fw-semibold opacity-75 mb-1">👥 USUARIOS</div>
                            <h2 class="display-6 fw-bold mb-2">{{ $estadisticas['usuarios']['total'] ?? 0 }}</h2>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-white bg-opacity-20 text-white fs-7">
                                    <i class="bi bi-person-plus me-1"></i>
                                    {{ $estadisticas['usuarios']['nuevos'] ?? 0 }} nuevos
                                </span>
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-3 p-3">
                            <i class="bi bi-people fs-1"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-top border-white border-opacity-25">
                        <small class="opacity-75">
                            <i class="bi bi-graph-up-arrow me-1"></i>
                            Crecimiento:
                            {{ ($estadisticas['usuarios']['total'] ?? 0) > 0 ? round((($estadisticas['usuarios']['nuevos'] ?? 0) / ($estadisticas['usuarios']['total'] ?? 1)) * 100, 1) : 0 }}%
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Métricas de Rendimiento --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-dark text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-speedometer2 fs-3 me-3"></i>
                        <h4 class="mb-0 fw-bold">📈 MÉTRICAS DE RENDIMIENTO</h4>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        {{-- Eficiencia Comercial --}}
                        <div class="col-md-4">
                            <div class="card bg-gradient-primary text-white h-100 border-0">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <i class="bi bi-graph-up-arrow fs-1"></i>
                                    </div>
                                    <h3 class="fw-bold mb-2">{{ $estadisticas['ventas']['total'] ?? 0 }}</h3>
                                    <p class="mb-0 opacity-75">Transacciones Completadas</p>
                                    <div class="mt-3">
                                        <span class="badge bg-white bg-opacity-20">
                                            <i class="bi bi-arrow-repeat me-1"></i>Actividad
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Conversión --}}
                        <div class="col-md-4">
                            <div class="card bg-gradient-success text-white h-100 border-0">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <i class="bi bi-check-all fs-1"></i>
                                    </div>
                                    <h3 class="fw-bold mb-2">
                                        {{ ($estadisticas['reservaciones']['total'] ?? 0) > 0 ?
                                           round((($estadisticas['reservaciones']['confirmadas'] ?? 0) / ($estadisticas['reservaciones']['total'] ?? 1)) * 100, 1) : 0 }}%
                                    </h3>
                                    <p class="mb-0 opacity-75">Tasa de Conversión</p>
                                    <div class="mt-3">
                                        <span class="badge bg-white bg-opacity-20">
                                            <i class="bi bi-lightning me-1"></i>Eficiencia
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Salud del Inventario --}}
                        <div class="col-md-4">
                            <div class="card bg-gradient-warning text-dark h-100 border-0">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <i class="bi bi-shield-check fs-1"></i>
                                    </div>
                                    <h3 class="fw-bold mb-2">
                                        {{ ($estadisticas['productos']['total'] ?? 0) > 0 ?
                                           round((($estadisticas['productos']['total'] ?? 0) - ($estadisticas['productos']['stock_bajo'] ?? 0)) / ($estadisticas['productos']['total'] ?? 1) * 100, 1) : 100 }}%
                                    </h3>
                                    <p class="mb-0 opacity-75">Salud del Inventario</p>
                                    <div class="mt-3">
                                        <span class="badge bg-dark bg-opacity-10">
                                            <i class="bi bi-heart me-1"></i>Estable
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Resumen Ejecutivo --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 bg-gradient-light">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="text-primary fw-bold mb-3">
                                <i class="bi bi-clipboard-data me-2"></i>ANÁLISIS EJECUTIVO
                            </h4>
                            <div class="bg-white rounded-3 p-4 shadow-sm">
                                <p class="mb-0 text-dark fs-5">
                                    @php
                                    $ingresos = $estadisticas['ventas']['ingresos'] ?? 0;
                                    $ventasTotal = $estadisticas['ventas']['total'] ?? 0;
                                    $reservasConfirmadas = $estadisticas['reservaciones']['confirmadas'] ?? 0;
                                    $stockBajo = $estadisticas['productos']['stock_bajo'] ?? 0;

                                    if ($ingresos > 1000) {
                                    echo "🎉 <strong>Excelente desempeño</strong> con ingresos de <span
                                        class='text-success fw-bold'>Bs " . number_format($ingresos, 2) . "</span>. ";
                                    } elseif ($ingresos > 0) {
                                    echo "📈 <strong>Buen rendimiento</strong> con <span class='text-success fw-bold'>Bs
                                        " . number_format($ingresos, 2) . "</span> en ingresos. ";
                                    } elseif ($ventasTotal > 0) {
                                    echo "🛒 Actividad comercial registrada con <span
                                        class='text-info fw-bold'>$ventasTotal ventas</span>. ";
                                    } elseif ($reservasConfirmadas > 0) {
                                    echo "📋 <span class='text-info fw-bold'>$reservasConfirmadas reservaciones</span>
                                    confirmadas. ";
                                    } else {
                                    echo "⏳ Sistema operativo. Esperando actividad comercial en el período
                                    seleccionado.";
                                    }

                                    if ($stockBajo > 0) {
                                    echo " ⚠️ <span class='text-warning fw-bold'>Atención:</span> $stockBajo productos
                                    con stock bajo.";
                                    }
                                    @endphp
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="bg-gradient-primary text-white rounded-3 p-4 shadow-lg">
                                <i class="bi bi-award fs-1 d-block mb-2"></i>
                                <h5 class="fw-bold mb-1">ESTADO</h5>
                                <div class="fs-6 opacity-75">Del Sistema</div>
                                <div class="mt-3">
                                    <span class="badge bg-white text-primary fs-6 px-3 py-2">
                                        <i class="bi bi-check-circle-fill me-1"></i>ÓPTIMO
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(!empty($reporteData) && isset($reporteData['general']))
<div class="alert alert-success border-0 shadow-lg mt-4">
    <div class="d-flex align-items-center">
        <i class="bi bi-check-circle-fill fs-2 text-success me-3"></i>
        <div class="flex-grow-1">
            <h5 class="alert-heading fw-bold mb-1">✅ Reporte Generado Exitosamente</h5>
            <p class="mb-0 text-muted">
                <i class="bi bi-clock-history me-1"></i>
                Actualizado: {{ now()->format('d/m/Y H:i:s') }} |
                Período: {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} -
                {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}
            </p>
        </div>
        <button class="btn btn-outline-success">
            <i class="bi bi-download me-1"></i>Exportar
        </button>
    </div>
</div>
@else
<div class="alert alert-warning border-0 shadow-lg mt-4">
    <div class="d-flex align-items-center">
        <i class="bi bi-exclamation-triangle fs-2 text-warning me-3"></i>
        <div>
            <h5 class="alert-heading fw-bold mb-1">📋 Datos No Disponibles</h5>
            <p class="mb-0 text-muted">
                Seleccione un período válido y genere el reporte para visualizar las estadísticas completas.
            </p>
        </div>
    </div>
</div>
@endif

<style>
.bg-gradient-purple {
    background: linear-gradient(135deg, #6f42c1 0%, #8b5cf6 100%) !important;
}

.bg-gradient-dark {
    background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%) !important;
}

.bg-gradient-light {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.display-6 {
    font-size: 2rem;
}

@media (max-width: 768px) {
    .display-6 {
        font-size: 1.5rem;
    }
}
</style>
