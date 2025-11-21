<div class="container-fluid p-4">
    <div class="row">
        <div class="col-12">
            <!-- Panel de Configuración -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Tipo de Reporte -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tipo de Reporte</label>
                            <select wire:model.live="tipoReporte" class="form-select">
                                <option value="general">📊 Reporte General</option>
                                <option value="ventas">💰 Ventas</option>
                                <option value="reservaciones">📅 Reservaciones</option>
                                <option value="mesas">🪑 Mesas</option>
                                <option value="productos">📦 Productos</option>
                                <option value="categorias">🏷️ Categorías</option>
                                <option value="promociones">🎁 Promociones</option>
                                <option value="usuarios">👥 Usuarios</option>
                            </select>
                        </div>

                        <!-- Periodo -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Periodo</label>
                            <select wire:model.live="periodoReporte" class="form-select">
                                <option value="diario">📆 Diario</option>
                                <option value="semanal">📅 Semanal</option>
                                <option value="mensual">🗓️ Mensual</option>
                                <option value="personalizado">⚙️ Personalizado</option>
                            </select>
                        </div>

                        <!-- Fecha Inicio -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Fecha Inicio</label>
                            <input type="date" wire:model="fechaInicio" class="form-control"
                                {{ $periodoReporte !== 'personalizado' ? 'readonly' : '' }}>
                        </div>

                        <!-- Fecha Fin -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Fecha Fin</label>
                            <input type="date" wire:model="fechaFin" class="form-control"
                                {{ $periodoReporte !== 'personalizado' ? 'readonly' : '' }}>
                        </div>

                        <!-- Botón Generar -->
                        <div class="col-md-1 d-flex align-items-end">
                            <button wire:click="generarReporte" class="btn btn-primary w-100"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="bi bi-play-fill"></i>
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Generales -->
            @if(!empty($estadisticas))
            <div class="row g-4 mb-4">
                @if($tipoReporte === 'ventas')
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Total Ventas</p>
                                    <h3 class="mb-0">{{ $estadisticas['total_ventas'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-cart-check text-success fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Ingresos Totales</p>
                                    <h3 class="mb-0 text-success">Bs.
                                        {{ number_format($estadisticas['ingresos_totales'] ?? 0, 2) }}</h3>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-currency-dollar text-primary fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Ticket Promedio</p>
                                    <h3 class="mb-0">Bs. {{ number_format($estadisticas['ticket_promedio'] ?? 0, 2) }}
                                    </h3>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-receipt text-info fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Productos Vendidos</p>
                                    <h3 class="mb-0">{{ $estadisticas['productos_vendidos'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-box-seam text-warning fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @elseif($tipoReporte === 'mesas')
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Total Mesas</p>
                                    <h3 class="mb-0">{{ $estadisticas['total'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-table text-primary fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Disponibles</p>
                                    <h3 class="mb-0 text-success">{{ $estadisticas['disponibles'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-check-circle text-success fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Ocupadas</p>
                                    <h3 class="mb-0 text-danger">{{ $estadisticas['ocupadas'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-danger bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-x-circle text-danger fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Capacidad Total</p>
                                    <h3 class="mb-0">{{ $estadisticas['capacidad_total'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-people text-info fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @elseif($tipoReporte === 'reservaciones')
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Total Reservaciones</p>
                                    <h3 class="mb-0">{{ $estadisticas['total'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-calendar-check text-primary fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Confirmadas</p>
                                    <h3 class="mb-0 text-success">{{ $estadisticas['confirmadas'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-check2-all text-success fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Personas Total</p>
                                    <h3 class="mb-0">{{ $estadisticas['personas_total'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-people-fill text-info fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Promedio Personas</p>
                                    <h3 class="mb-0">{{ $estadisticas['promedio_personas'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-person-check text-warning fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @elseif($tipoReporte === 'productos')
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Total Productos</p>
                                    <h3 class="mb-0">{{ $estadisticas['total'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-box text-primary fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Activos</p>
                                    <h3 class="mb-0 text-success">{{ $estadisticas['activos'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-check-circle text-success fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Sin Stock</p>
                                    <h3 class="mb-0 text-danger">{{ $estadisticas['sin_stock'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-danger bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-exclamation-triangle text-danger fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Valor Inventario</p>
                                    <h3 class="mb-0 text-info">Bs.
                                        {{ number_format($estadisticas['valor_inventario'] ?? 0, 2) }}</h3>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-currency-dollar text-info fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @elseif($tipoReporte === 'usuarios')
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Total Usuarios</p>
                                    <h3 class="mb-0">{{ $estadisticas['total'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-people text-primary fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Activos</p>
                                    <h3 class="mb-0 text-success">{{ $estadisticas['activos'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-check-circle text-success fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Inactivos</p>
                                    <h3 class="mb-0 text-warning">{{ $estadisticas['inactivos'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-person-x text-warning fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Nuevos</p>
                                    <h3 class="mb-0 text-info">{{ $estadisticas['total'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-person-plus text-info fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @elseif($tipoReporte === 'categorias')
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Total Categorías</p>
                                    <h3 class="mb-0">{{ $estadisticas['total'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-tags text-primary fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Activas</p>
                                    <h3 class="mb-0 text-success">{{ $estadisticas['activas'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-check-circle text-success fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Con Productos</p>
                                    <h3 class="mb-0 text-info">{{ $estadisticas['con_productos'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-box-seam text-info fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Sin Productos</p>
                                    <h3 class="mb-0 text-warning">{{ $estadisticas['sin_productos'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-exclamation-triangle text-warning fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @elseif($tipoReporte === 'general')
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Total Ventas</p>
                                    <h3 class="mb-0">{{ $estadisticas['ventas']['total'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-cart-check text-success fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Ingresos</p>
                                    <h3 class="mb-0 text-success">Bs.
                                        {{ number_format($estadisticas['ventas']['ingresos'] ?? 0, 2) }}</h3>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-currency-dollar text-primary fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Reservaciones</p>
                                    <h3 class="mb-0">{{ $estadisticas['reservaciones']['total'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-calendar-check text-info fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <p class="text-muted mb-1 small">Nuevos Usuarios</p>
                                    <h3 class="mb-0">{{ $estadisticas['usuarios']['nuevos'] ?? 0 }}</h3>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-person-plus text-warning fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Tabla de Datos Detallados -->
            @if(!empty($reporteData) && count($reporteData) > 0)
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-table me-2"></i>
                        Datos Detallados
                    </h5>
                    <button wire:click="exportarPDF" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-file-pdf me-1"></i> PDF
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        @if($tipoReporte === 'ventas')
                        @include('livewire.admin.reportes.ventas')
                        @elseif($tipoReporte === 'mesas')
                        @include('livewire.admin.reportes.mesas')
                        @elseif($tipoReporte === 'reservaciones')
                        @include('livewire.admin.reportes.reservaciones')
                        @elseif($tipoReporte === 'productos')
                        @include('livewire.admin.reportes.productos')
                        @elseif($tipoReporte === 'usuarios')
                        @include('livewire.admin.reportes.usuarios')
                        @elseif($tipoReporte === 'categorias')
                        @include('livewire.admin.reportes.categorias')
                        @elseif($tipoReporte === 'promociones')
                        @include('livewire.admin.reportes.promociones')
                        @elseif($tipoReporte === 'general')
                        @include('livewire.admin.reportes.general')
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Mensaje cuando no hay datos -->
            @if(empty($reporteData) && empty($estadisticas))
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-graph-up display-4 text-muted mb-3"></i>
                    <h5 class="text-muted">Selecciona los parámetros y genera un reporte</h5>
                    <p class="text-muted mb-0">Los resultados se mostrarán aquí</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
