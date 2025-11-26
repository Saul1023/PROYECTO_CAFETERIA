<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-calendar-check me-2"></i>
            Reporte de Reservaciones
        </h5>
    </div>
    <div class="card-body">
        <!-- Sección de Estadísticas -->
        <div class="row mb-4">
            <div class="col-12">
                <h5 class="text-primary mb-3">Estadísticas de Reservaciones</h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title">Total Reservaciones</h6>
                                <h4 class="text-primary">{{ $estadisticas['total'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title">Confirmadas</h6>
                                <h4 class="text-success">{{ $estadisticas['confirmadas'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title">Pendientes</h6>
                                <h4 class="text-warning">{{ $estadisticas['pendientes'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title">Total Personas</h6>
                                <h4 class="text-info">{{ $estadisticas['personas_total'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de Datos Detallados -->
        <div class="row">
            <div class="col-12">
                <h5 class="text-primary mb-3">Datos Detallados de Reservaciones</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Mesa</th>
                                <th>Fecha</th>
                                <th>Personas</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(is_iterable($reporteData) && count($reporteData) > 0)
                            @foreach($reporteData as $res)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $res->usuario->nombre_completo ?? 'No registrado' }}
                                </td>
                                <td>
                                    Mesa #{{ $res->mesa->numero_mesa ?? '-' }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($res->fecha_reservacion)->format('d/m/Y H:i') }}
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold">{{ $res->numero_personas }}</span>
                                </td>
                                <td>
                                    @php
                                    $badgeClass = 'bg-secondary';
                                    switch($res->estado) {
                                    case 'pendiente': $badgeClass = 'bg-warning'; break;
                                    case 'confirmada': $badgeClass = 'bg-success'; break;
                                    case 'completada': $badgeClass = 'bg-primary'; break;
                                    case 'cancelada': $badgeClass = 'bg-danger'; break;
                                    case 'no_asistio': $badgeClass = 'bg-info'; break;
                                    }
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ ucfirst($res->estado) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No hay reservaciones registradas en el período seleccionado
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sección de Distribución por Estado -->
        @if($estadisticas['total'] > 0)
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="text-primary mb-3">Distribución por Estado</h5>
                <div class="row">
                    <div class="col-md-2 mb-2">
                        <div class="card border-warning">
                            <div class="card-body text-center p-2">
                                <small class="text-muted">Pendientes</small>
                                <div class="fw-bold text-warning">{{ $estadisticas['pendientes'] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-2">
                        <div class="card border-success">
                            <div class="card-body text-center p-2">
                                <small class="text-muted">Confirmadas</small>
                                <div class="fw-bold text-success">{{ $estadisticas['confirmadas'] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-2">
                        <div class="card border-primary">
                            <div class="card-body text-center p-2">
                                <small class="text-muted">Completadas</small>
                                <div class="fw-bold text-primary">{{ $estadisticas['completadas'] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-2">
                        <div class="card border-danger">
                            <div class="card-body text-center p-2">
                                <small class="text-muted">Canceladas</small>
                                <div class="fw-bold text-danger">{{ $estadisticas['canceladas'] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-2">
                        <div class="card border-info">
                            <div class="card-body text-center p-2">
                                <small class="text-muted">No Asistió</small>
                                <div class="fw-bold text-info">{{ $estadisticas['no_asistio'] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-2">
                        <div class="card border-secondary">
                            <div class="card-body text-center p-2">
                                <small class="text-muted">Promedio</small>
                                <div class="fw-bold text-dark">{{ $estadisticas['promedio_personas'] }} pers.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
