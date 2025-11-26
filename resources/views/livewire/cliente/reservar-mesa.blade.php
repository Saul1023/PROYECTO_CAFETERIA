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

    @if (!$showConfirmacion)
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header text-white text-center py-4"
                     style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-dark) 100%);">
                    <h2 class="mb-0">
                        <i class="bi bi-calendar-check me-2"></i>
                        Reservar Mesa
                    </h2>
                    <p class="mb-0 mt-2" style="opacity: 0.9;">Completa los datos para tu reserva</p>
                </div>

                <div class="card-body p-4">
                    <form wire:submit.prevent="crearReserva">
                        <!-- Paso 1: Fecha y Número de Personas -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-1-circle-fill me-2"></i>Información Básica
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-calendar3 me-1"></i>Fecha de Reserva
                                    </label>
                                    <input type="date"
                                           class="form-control @error('fecha_reservacion') is-invalid @enderror"
                                           wire:model.live="fecha_reservacion"
                                           min="{{ date('Y-m-d') }}">
                                    @error('fecha_reservacion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-people me-1"></i>Número de Personas
                                    </label>
                                    <input type="number"
                                           class="form-control @error('numero_personas') is-invalid @enderror"
                                           wire:model.live="numero_personas"
                                           min="1"
                                           max="20">
                                    @error('numero_personas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Paso 2: Mesas y Horarios Disponibles -->
                        @if($mostrarDisponibilidad)
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-2-circle-fill me-2"></i>Selecciona Mesa y Horario
                            </h5>

                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Mesas Disponibles</strong> - Haz clic en un horario para seleccionar tu reserva
                            </div>

                            @if(count($mesasDisponibles) > 0)
                                <div class="row g-4">
                                    @foreach($mesasDisponibles as $disponible)
                                    <div class="col-lg-6">
                                        <div class="card h-100 border-2 {{ $id_mesa == $disponible['mesa']->id_mesa ? 'border-success' : 'border-primary' }}"
                                             style="transition: all 0.3s ease;">
                                            <div class="card-header {{ $id_mesa == $disponible['mesa']->id_mesa ? 'bg-success' : 'bg-primary' }} text-white">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-0">
                                                            <i class="bi bi-table me-2"></i>
                                                            Mesa {{ $disponible['mesa']->numero_mesa }}
                                                        </h6>
                                                        <small class="opacity-75">
                                                            <i class="bi bi-people me-1"></i>Capacidad: {{ $disponible['mesa']->capacidad }} personas
                                                            @if($disponible['mesa']->ubicacion)
                                                                | <i class="bi bi-geo-alt me-1"></i>{{ $disponible['mesa']->ubicacion }}
                                                            @endif
                                                        </small>
                                                    </div>
                                                    @if($id_mesa == $disponible['mesa']->id_mesa)
                                                        <i class="bi bi-check-circle-fill" style="font-size: 1.5rem;"></i>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted small mb-3">
                                                    <i class="bi bi-clock me-1"></i>Horarios disponibles:
                                                </p>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($disponible['horarios'] as $horario)
                                                    <button type="button"
                                                        wire:click="seleccionarMesaHorario({{ $disponible['mesa']->id_mesa }}, '{{ $horario['hora'] }}')"
                                                        class="btn {{ ($id_mesa == $disponible['mesa']->id_mesa && $hora_reservacion == $horario['hora']) ? 'btn-success' : 'btn-outline-success' }} btn-sm">
                                                        <i class="bi bi-clock me-1"></i>{{ $horario['etiqueta'] }}
                                                        @if($id_mesa == $disponible['mesa']->id_mesa && $hora_reservacion == $horario['hora'])
                                                            <i class="bi bi-check-circle-fill ms-1"></i>
                                                        @endif
                                                    </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                @if($id_mesa && $hora_reservacion)
                                    <div class="alert alert-success mt-3">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        <strong>Selección:</strong> Mesa {{ collect($mesasDisponibles)->firstWhere('mesa.id_mesa', $id_mesa)['mesa']->numero_mesa ?? '' }}
                                        a las {{ collect($mesasDisponibles)->first(function($m) {
                                            return $m['mesa']->id_mesa == $this->id_mesa;
                                        })['horarios'] ? collect(collect($mesasDisponibles)->first(function($m) {
                                            return $m['mesa']->id_mesa == $this->id_mesa;
                                        })['horarios'])->firstWhere('hora', $hora_reservacion)['etiqueta'] ?? '' : '' }}
                                    </div>
                                @endif

                                @error('id_mesa')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                                @error('hora_reservacion')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    No hay mesas disponibles para la fecha y número de personas seleccionados.
                                    Intenta con otra fecha o reduce el número de personas.
                                </div>
                            @endif
                        </div>

                        <hr class="my-4">
                        @endif

                        <!-- Paso 3: Comprobante de Pago (solo si hay mesa seleccionada) -->
                        @if($id_mesa && $hora_reservacion)
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-3-circle-fill me-2"></i>Comprobante de Pago
                            </h5>

                            <div class="alert alert-info">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <i class="bi bi-cash-coin me-2"></i>
                                        <strong>Monto de reserva: Bs. 30.00</strong>
                                        <p class="mb-0 mt-2 small">
                                            Realiza el pago y sube una foto del comprobante. Tu reserva será confirmada por el administrador una vez verificado el pago.
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-center mt-3 mt-md-0">
                                        <img src="/img/escanear.png"
                                            alt="Código QR para pago"
                                            class="img-fluid rounded"
                                            style="max-width: 150px;">
                                        <p class="small text-muted mt-2 mb-0">Escanea para pagar</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-image me-1"></i>Subir Comprobante
                                </label>
                                <input type="file"
                                       class="form-control @error('comprobante_pago') is-invalid @enderror"
                                       wire:model="comprobante_pago"
                                       accept="image/*">
                                @error('comprobante_pago')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div wire:loading wire:target="comprobante_pago" class="text-primary mt-2">
                                    <i class="bi bi-hourglass-split me-2"></i>Subiendo imagen...
                                </div>

                                @if ($comprobante_pago)
                                    <div class="mt-3">
                                        <p class="text-muted small mb-2">Vista previa:</p>
                                        <img src="{{ $comprobante_pago->temporaryUrl() }}"
                                             class="img-thumbnail"
                                             style="max-width: 300px;">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Paso 4: Observaciones -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-4-circle-fill me-2"></i>Observaciones (Opcional)
                            </h5>
                            <label class="form-label fw-bold">
                                <i class="bi bi-chat-left-text me-1"></i>¿Alguna solicitud especial?
                            </label>
                            <textarea class="form-control @error('observaciones') is-invalid @enderror"
                                      wire:model="observaciones"
                                      rows="3"
                                      placeholder="Ejemplo: Necesito una silla para bebé, celebración especial, etc."></textarea>
                            @error('observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif

                        <!-- Botones de Acción -->
                        <div class="d-flex gap-3 justify-content-end mt-4">
                            <a href="{{ route('home') }}" class="btn btn-secondary px-4">
                                <i class="bi bi-x-circle me-2"></i>Cancelar
                            </a>
                            <button type="submit"
                                    class="btn btn-primary px-5"
                                    wire:loading.attr="disabled"
                                    @if(!$id_mesa || !$hora_reservacion || !$comprobante_pago) disabled @endif>
                                <span wire:loading.remove wire:target="crearReserva">
                                    <i class="bi bi-check-circle me-2"></i>Crear Reserva
                                </span>
                                <span wire:loading wire:target="crearReserva">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Creando...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @else
    <!-- Confirmación de Reserva -->
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-lg border-0 text-center" style="border-radius: 20px;">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="text-success mb-3">¡Reserva Creada!</h2>
                    <p class="text-muted mb-4">Tu reserva ha sido registrada exitosamente</p>

                    <div class="bg-light p-4 rounded-3 mb-4">
                        <div class="row g-3 text-start">
                            <div class="col-12">
                                <strong>Código de Reserva:</strong>
                                <div class="badge bg-primary fs-6 mt-1">{{ $reservaCreada->codigo_qr }}</div>
                            </div>
                            <div class="col-6">
                                <strong>Mesa:</strong>
                                <div>{{ $reservaCreada->mesa->numero_mesa }}</div>
                            </div>
                            <div class="col-6">
                                <strong>Fecha:</strong>
                                <div>{{ $reservaCreada->fecha_formateada }}</div>
                            </div>
                            <div class="col-6">
                                <strong>Hora:</strong>
                                <div>{{ $reservaCreada->horario_formateado }}</div>
                            </div>
                            <div class="col-6">
                                <strong>Personas:</strong>
                                <div>{{ $reservaCreada->numero_personas }}</div>
                            </div>
                            <div class="col-12">
                                <strong>Monto:</strong>
                                <div>Bs. {{ number_format($reservaCreada->monto_pago, 2) }}</div>
                            </div>
                            <div class="col-12">
                                <strong>Estado:</strong>
                                <div>
                                    <span class="badge bg-warning">Pendiente de Confirmación</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Tu reserva será confirmada una vez que el administrador verifique el comprobante de pago.
                        Recibirás una notificación cuando esté confirmada.
                    </div>

                    <div class="d-flex gap-3 justify-content-center">
                        <button wire:click="$set('showConfirmacion', false)" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-x-circle me-2"></i>Cerrar
                        </button>
                        <button wire:click="resetForm" class="btn btn-outline-primary px-4">
                            <i class="bi bi-plus-circle me-2"></i>Nueva Reserva
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }

    .btn-outline-success:hover {
        transform: scale(1.05);
    }

    .btn-success {
        transform: scale(1.05);
    }
</style>
</div>
