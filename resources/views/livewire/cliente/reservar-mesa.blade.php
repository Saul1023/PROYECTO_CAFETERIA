<div>
<div class="container py-5" style="background-color: var(--color-light-bg);">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (!$showConfirmacion)
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-xl border-0" style="border-radius: 24px; overflow: hidden; background-color: var(--color-card-bg);">
                <div class="card-header text-white text-center py-4"
                    style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-dark) 100%);">
                    <h2 class="mb-0">
                        <i class="bi bi-calendar-check me-2"></i>
                        Reservar Mesa
                    </h2>
                    <p class="mb-0 mt-2" style="opacity: 0.9;">Completa los datos para tu reserva</p>
                </div>

                <div class="card-body p-5">
                    <form wire:submit.prevent="crearReserva">
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-1-circle-fill me-2"></i>Información Básica
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" style="color: var(--color-dark);">
                                        <i class="bi bi-calendar3 me-1"></i>Fecha de Reserva
                                    </label>
                                    <input type="date"
                                            class="form-control @error('fecha_reservacion') is-invalid @enderror"
                                            style="border-radius: 8px;"
                                            wire:model.live="fecha_reservacion"
                                            min="{{ date('Y-m-d') }}">
                                    @error('fecha_reservacion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold" style="color: var(--color-dark);">
                                        <i class="bi bi-people me-1"></i>Número de Personas
                                    </label>
                                    <input type="number"
                                            class="form-control @error('numero_personas') is-invalid @enderror"
                                            style="border-radius: 8px;"
                                            wire:model.live="numero_personas"
                                            min="1"
                                            max="20">
                                    @error('numero_personas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-5" style="border-top: 1px solid rgba(0,0,0,0.1);">

                        @if($mostrarDisponibilidad)
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-2-circle-fill me-2"></i>Selecciona Mesa y Horario
                            </h5>

                            <div class="alert alert-info" style="border-left: 5px solid var(--color-primary); border-radius: 10px;">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Mesas Disponibles</strong> - Haz clic en un horario para seleccionar tu reserva
                            </div>

                            @if(count($mesasDisponibles) > 0)
                                <div class="row g-4">
                                    @foreach($mesasDisponibles as $disponible)
                                    <div class="col-lg-6">
                                        <div class="card h-100 table-option {{ $id_mesa == $disponible['mesa']->id_mesa ? 'selected-table' : '' }}"
                                            style="border-radius: 12px;">
                                            <div class="card-header {{ $id_mesa == $disponible['mesa']->id_mesa ? 'bg-secondary' : 'bg-primary' }} text-white"
                                                style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
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
                                                            class="btn btn-sm {{ ($id_mesa == $disponible['mesa']->id_mesa && $hora_reservacion == $horario['hora']) ? 'btn-success-custom' : 'btn-outline-success-custom' }}"
                                                            style="border-radius: 8px;">
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
                                    <div class="alert alert-success mt-3" style="border-radius: 10px;">
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
                                <div class="alert alert-warning" style="border-radius: 10px;">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    No hay mesas disponibles para la fecha y número de personas seleccionados.
                                    Intenta con otra fecha o reduce el número de personas.
                                </div>
                            @endif
                        </div>

                        <hr class="my-5" style="border-top: 1px solid rgba(0,0,0,0.1);">
                        @endif

                        @if($id_mesa && $hora_reservacion)
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-3-circle-fill me-2"></i>Comprobante de Pago
                            </h5>

                            <div class="alert alert-light border p-4" style="border-left: 5px solid var(--color-secondary); border-radius: 10px;">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <i class="bi bi-cash-coin me-2 fs-4 align-middle text-secondary"></i>
                                        <h4 class="d-inline-block mb-0 text-dark">
                                            Monto de reserva: <span class="text-secondary fw-bold">Bs. 30.00</span>
                                        </h4>
                                        <p class="mb-0 mt-2 small text-muted">
                                            Realiza el pago y sube una foto del comprobante. Tu reserva será confirmada por el administrador una vez verificado el pago.
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-center mt-3 mt-md-0">
                                        <div class="p-2 border rounded-3 bg-white d-inline-block">
                                            <img src="/img/escanear.png"
                                                alt="Código QR para pago"
                                                class="img-fluid"
                                                style="max-width: 120px; border-radius: 6px;">
                                        </div>
                                        <p class="small text-muted mt-2 mb-0">Escanea para pagar</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold" style="color: var(--color-dark);">
                                    <i class="bi bi-image me-1"></i>Subir Comprobante
                                </label>
                                <input type="file"
                                        class="form-control @error('comprobante_pago') is-invalid @enderror"
                                        style="border-radius: 8px;"
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
                                                style="max-width: 300px; border-radius: 10px;">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr class="my-5" style="border-top: 1px solid rgba(0,0,0,0.1);">

                        <div class="mb-4">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-4-circle-fill me-2"></i>Observaciones (Opcional)
                            </h5>
                            <label class="form-label fw-bold" style="color: var(--color-dark);">
                                <i class="bi bi-chat-left-text me-1"></i>¿Alguna solicitud especial?
                            </label>
                            <textarea class="form-control @error('observaciones') is-invalid @enderror"
                                    style="border-radius: 8px;"
                                    wire:model="observaciones"
                                    rows="3"
                                    placeholder="Ejemplo: Necesito una silla para bebé, celebración especial, etc."></textarea>
                            @error('observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif

                        <div class="d-flex gap-3 justify-content-end mt-4 pt-3">
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4 fw-bold" style="border-radius: 50px;">
                                <i class="bi bi-x-circle me-2"></i>Cancelar
                            </a>
                            <button type="submit"
                                        class="btn btn-primary px-5 fw-bold"
                                        style="border-radius: 50px; min-width: 180px;"
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
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-lg border-0 text-center" style="border-radius: 20px;">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem; color: var(--color-secondary) !important;"></i>
                    </div>
                    <h2 style="color: var(--color-secondary);" class="mb-3">¡Reserva Creada!</h2>
                    <p class="text-muted mb-4">Tu reserva ha sido registrada exitosamente</p>

                    <div class="bg-light p-4 rounded-3 mb-4" style="border: 1px solid #e2e8f0;">
                        <div class="row g-3 text-start">
                            <div class="col-12">
                                <strong>Código de Reserva:</strong>
                                <div class="badge bg-primary fs-5 mt-1 px-3 py-2"
                                    style="letter-spacing: 2px; border-radius: 10px;">{{ $reservaCreada->codigo_qr }}</div>
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
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Pendiente de Confirmación</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info" style="border-radius: 10px;">
                        <i class="bi bi-info-circle me-2"></i>
                        Tu reserva será confirmada una vez que el administrador verifique el comprobante de pago.
                        Recibirás una notificación cuando esté confirmada.
                    </div>

                    <div class="d-flex gap-3 justify-content-center">
                        <button wire:click="$set('showConfirmacion', false)" class="btn btn-outline-secondary px-4 fw-bold" style="border-radius: 50px;">
                            <i class="bi bi-x-circle me-2"></i>Cerrar
                        </button>
                        <button wire:click="resetForm" class="btn btn-outline-primary px-4 fw-bold" style="border-radius: 50px;">
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
    /* 1. Definición de Paleta */
    :root {
        --color-primary: #863712; /* Azul vibrante */
        --color-secondary: #aa7411; /* Verde esmeralda para éxito/selección */
        --color-dark: #1f2937; /* Gris oscuro para texto */
        --color-light-bg: #f8fafc; /* Fondo muy claro */
        --color-card-bg: #ffffff; /* Fondo de la tarjeta */
    }

    /* 2. Estilos Globales de Tarjeta */
    .card {
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); /* Curva de transición elegante */
        border: none;
    }

    .card:hover {
        transform: translateY(-5px); /* Más levantamiento en hover */
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important; /* Sombra más suave y grande */
    }

    /* 3. Estilos de Selección de Mesa */
    .table-option {
        cursor: pointer;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        overflow: hidden; /* Necesario para el card-header redondeado */
    }

    .table-option:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .selected-table {
        border-color: var(--color-secondary) !important;
        box-shadow: 0 0 0 3px rgba(170, 81, 9, 0.5) !important; /* Anillo de enfoque verde */
        transform: scale(1.02);
    }

    /* 4. Estilos de Botones de Horario */
    .btn-outline-success-custom {
        border-color: var(--color-secondary);
        color: var(--color-secondary);
        background-color: transparent;
        transition: all 0.2s ease;
    }

    .btn-outline-success-custom:hover {
        background-color: var(--color-secondary);
        color: white;
    }

    .btn-success-custom {
        background-color: var(--color-secondary) !important;
        border-color: var(--color-secondary) !important;
        color: white !important;
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(168, 81, 9, 0.4);
    }
</style>
</div>
