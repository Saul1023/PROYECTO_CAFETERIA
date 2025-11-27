
<div>
<div class="container py-5">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="bi bi-calendar-check me-2" style="color: var(--color-primary);"></i>
                Mis Reservaciones
            </h2>
            <p class="text-muted mt-2">Gestiona tus reservas de mesas</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Filtrar por Estado</label>
                    <select class="form-select" wire:model.live="filterEstado">
                        <option value="">Todos</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="confirmada">Confirmada</option>
                        <option value="completada">Completada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Reservaciones -->
    @if ($reservaciones->count() > 0)
        <div class="row g-4">
            @foreach ($reservaciones as $reserva)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm hover-card">
                        <div class="card-header py-3"
                             style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-white">
                                    <i class="bi bi-table me-2"></i>Mesa {{ $reserva->mesa->numero_mesa }}
                                </h5>
                                @if ($reserva->estado === 'pendiente')
                                    <span class="badge bg-warning">Pendiente</span>
                                @elseif ($reserva->estado === 'confirmada')
                                    <span class="badge bg-success">Confirmada</span>
                                @elseif ($reserva->estado === 'completada')
                                    <span class="badge bg-info">Completada</span>
                                @else
                                    <span class="badge bg-danger">Cancelada</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-calendar3 text-primary me-2"></i>
                                    <strong>Fecha:</strong>
                                    <span class="ms-2">{{ $reserva->fecha_formateada }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-clock text-primary me-2"></i>
                                    <strong>Hora:</strong>
                                    <span class="ms-2">{{ $reserva->horario_formateado }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-people text-primary me-2"></i>
                                    <strong>Personas:</strong>
                                    <span class="ms-2">{{ $reserva->numero_personas }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-upc text-primary me-2"></i>
                                    <strong>Código:</strong>
                                    <span class="ms-2 badge bg-primary">{{ $reserva->codigo_qr }}</span>
                                </div>
                            </div>

                            @if ($reserva->observaciones)
                                <div class="alert alert-light mb-3">
                                    <small><strong>Observaciones:</strong> {{ $reserva->observaciones }}</small>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex gap-2">
                                <button wire:click="verDetalle({{ $reserva->id_reservacion }})"
                                        class="btn btn-sm btn-outline-primary flex-fill">
                                    <i class="bi bi-eye me-1"></i>Ver Detalle
                                </button>
                                @if ($reserva->puedeSerCancelada() && !$reserva->haPasado())
                                    <button wire:click="confirmCancelar({{ $reserva->id_reservacion }})"
                                            class="btn btn-sm btn-outline-danger"
                                            wire:loading.attr="disabled"
                                            wire:loading.class="opacity-50">
                                        <span wire:loading.remove wire:target="confirmCancelar">
                                            <i class="bi bi-x-circle"></i>
                                        </span>
                                        <span wire:loading wire:target="confirmCancelar">
                                            <i class="bi bi-hourglass-split"></i>
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $reservaciones->links() }}
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-calendar-x" style="font-size: 4rem; color: var(--color-secondary);"></i>
                <h4 class="mt-3 mb-2">No tienes reservaciones</h4>
                <p class="text-muted mb-4">Crea tu primera reserva para disfrutar de nuestro servicio</p>
                <a href="{{ route('cliente.reservar') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Hacer una Reserva
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Modal Detalle -->
@if ($showModal && $reservacionDetalle)
<div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--color-primary); color: white;">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle me-2"></i>Detalle de Reservación
                </h5>
                <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="alert alert-primary">
                            <strong>Código QR:</strong>
                            <div class="badge bg-dark fs-6 mt-1">{{ $reservacionDetalle->codigo_qr }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <strong>Mesa:</strong>
                        <div>{{ $reservacionDetalle->mesa->numero_mesa }}</div>
                    </div>
                    <div class="col-6">
                        <strong>Capacidad:</strong>
                        <div>{{ $reservacionDetalle->mesa->capacidad }} personas</div>
                    </div>
                    <div class="col-6">
                        <strong>Fecha:</strong>
                        <div>{{ $reservacionDetalle->fecha_formateada }}</div>
                    </div>
                    <div class="col-6">
                        <strong>Hora:</strong>
                        <div>{{ $reservacionDetalle->horario_formateado }}</div>
                    </div>
                    <div class="col-6">
                        <strong>Personas:</strong>
                        <div>{{ $reservacionDetalle->numero_personas }}</div>
                    </div>
                    <div class="col-6">
                        <strong>Monto:</strong>
                        <div>Bs. {{ number_format($reservacionDetalle->monto_pago, 2) }}</div>
                    </div>
                    <div class="col-12">
                        <strong>Estado:</strong>
                        <div>
                            @if ($reservacionDetalle->estado === 'pendiente')
                                <span class="badge bg-warning">Pendiente de Confirmación</span>
                            @elseif ($reservacionDetalle->estado === 'confirmada')
                                <span class="badge bg-success">Confirmada</span>
                            @elseif ($reservacionDetalle->estado === 'completada')
                                <span class="badge bg-info">Completada</span>
                            @else
                                <span class="badge bg-danger">Cancelada</span>
                            @endif
                        </div>
                    </div>
                    @if ($reservacionDetalle->observaciones)
                        <div class="col-12">
                            <strong>Observaciones:</strong>
                            <div class="alert alert-light mt-2">{{ $reservacionDetalle->observaciones }}</div>
                        </div>
                    @endif
                    @if ($reservacionDetalle->comprobante_pago)
                        <div class="col-12">
                            <strong>Comprobante de Pago:</strong>
                            <img src="{{ asset('storage/' . $reservacionDetalle->comprobante_pago) }}"
                                 class="img-thumbnail mt-2 d-block mx-auto"
                                 style="max-width: 300px;">
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="closeModal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal Cancelar -->
@if ($showModalCancelar)
<div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 1050;" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle me-2"></i>Cancelar Reserva
                </h5>
                <button type="button" class="btn-close btn-close-white" wire:click="closeModalCancelar"></button>
            </div>
            <div class="modal-body">
                @if ($mostrarAlertaContacto)
                    <!-- Mensaje cuando faltan menos de 2 horas -->
                    <div class="alert alert-warning">
                        <i class="bi bi-clock-history me-2"></i>
                        <strong>¡Atención!</strong>
                    </div>
                    <p class="text-danger">
                        <i class="bi bi-x-circle me-2"></i>
                        No se puede cancelar la reserva de forma automática porque faltan menos de 2 horas para la hora reservada.
                    </p>
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="bi bi-telephone-fill me-2"></i>Para cancelar esta reserva:
                        </h6>
                        <hr>
                        <p class="mb-2">Por favor contacte directamente al restaurante:</p>
                        <ul class="mb-2">
                            <li><strong>Teléfono:</strong> <a href="tel:+59171234567" class="text-decoration-none">+591 71234567</a></li>
                            <li><strong>WhatsApp:</strong> <a href="https://wa.me/59171234567" target="_blank" class="text-decoration-none">+591 71234567</a></li>
                        </ul>
                        <p class="mb-0">
                            <small>
                                <i class="bi bi-info-circle me-1"></i>
                                Al cancelar a través del restaurante, se realizará la devolución del <strong>60% del monto pagado</strong> (Bs. 18.00 de Bs. 30.00).
                            </small>
                        </p>
                    </div>
                @else
                    <!-- Mensaje normal de cancelación -->
                    <p>¿Estás seguro de que deseas cancelar esta reserva?</p>
                    <div class="alert alert-warning">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Política de cancelación:</strong> Se devolverá el <strong>67% del monto pagado</strong> (Bs. 20.00 de Bs. 30.00).
                    </div>
                    <p class="text-danger mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Esta acción no se puede deshacer.</strong>
                    </p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="closeModalCancelar">
                    @if ($mostrarAlertaContacto)
                        Entendido
                    @else
                        No, mantener
                    @endif
                </button>
                @if (!$mostrarAlertaContacto)
                    <button type="button"
                            class="btn btn-danger"
                            wire:click="cancelarReserva">
                        <i class="bi bi-check-circle me-2"></i>Sí, cancelar reserva
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<style>
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }

</style>

</div>
