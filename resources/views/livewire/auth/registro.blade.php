
<div class="card auth-card shadow-lg">
    <div class="card-body p-4">
        <!-- Logo y Título -->
        <div class="text-center mb-4">
            <div class="coffee-icon-wrapper mb-3">
                <i class="bi bi-person-plus-fill coffee-icon"></i>
            </div>
            <h2 class="fw-bold auth-title">Crear Cuenta</h2>
            <p class="text-muted">Únete a nuestra comunidad cafetera</p>
        </div>

        <!-- Formulario -->
        <form wire:submit.prevent="registrar">
            <!-- Nombre Completo -->
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-person me-1"></i>Nombre Completo *
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-person text-muted"></i>
                    </span>
                    <input
                        type="text"
                        class="form-control border-start-0 @error('nombre_completo') is-invalid @enderror"
                        wire:model="nombre_completo"
                        placeholder="Ej: Juan Pérez"
                    >
                </div>
                @error('nombre_completo')
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Nombre de Usuario -->
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-at me-1"></i>Nombre de Usuario *
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-at text-muted"></i>
                    </span>
                    <input
                        type="text"
                        class="form-control border-start-0 @error('nombre_usuario') is-invalid @enderror"
                        wire:model="nombre_usuario"
                        placeholder="usuario123"
                    >
                </div>
                @error('nombre_usuario')
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Email y Teléfono en fila -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-envelope me-1"></i>Email *
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-envelope text-muted"></i>
                        </span>
                        <input
                            type="email"
                            class="form-control border-start-0 @error('email') is-invalid @enderror"
                            wire:model="email"
                            placeholder="correo@ejemplo.com"
                        >
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-telephone me-1"></i>Teléfono
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-telephone text-muted"></i>
                        </span>
                        <input
                            type="text"
                            class="form-control border-start-0 @error('telefono') is-invalid @enderror"
                            wire:model="telefono"
                            placeholder="70123456"
                        >
                    </div>
                    @error('telefono')
                        <div class="invalid-feedback d-block">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Contraseña -->
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-lock me-1"></i>Contraseña *
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input
                        type="password"
                        class="form-control border-start-0 @error('password') is-invalid @enderror"
                        wire:model="password"
                        placeholder="Mínimo 8 caracteres"
                    >
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Confirmar Contraseña -->
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-shield-check me-1"></i>Confirmar Contraseña *
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-shield-check text-muted"></i>
                    </span>
                    <input
                        type="password"
                        class="form-control border-start-0 @error('password_confirmation') is-invalid @enderror"
                        wire:model="password_confirmation"
                        placeholder="Repite tu contraseña"
                    >
                </div>
                @error('password_confirmation')
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Términos y Condiciones -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="terminos" required>
                <label class="form-check-label small" for="terminos">
                    Acepto los <a href="#" class="auth-link">términos y condiciones</a>
                </label>
            </div>

            <!-- Botón Submit -->
            <div class="d-grid">
                <button
                    type="submit"
                    class="btn btn-auth btn-lg"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>
                        <i class="bi bi-person-check me-2"></i>
                        Crear Cuenta
                    </span>
                    <span wire:loading>
                        <span class="spinner-border spinner-border-sm me-2"></span>
                        Registrando...
                    </span>
                </button>
            </div>
        </form>

        <!-- Divider -->
        <div class="divider-container my-4">
            <hr class="divider-line">
            <span class="divider-text">O</span>
        </div>

        <!-- Link a Login -->
        <div class="text-center">
            <p class="mb-0">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}" class="text-decoration-none auth-link fw-semibold">
                    Inicia sesión aquí
                </a>
            </p>
        </div>
    </div>

    <!-- Footer del Card -->
    <div class="card-footer bg-light text-center py-3">
        <small class="text-muted">
            <i class="bi bi-shield-check me-1"></i>
            Tus datos están seguros con nosotros
        </small>
    </div>
</div>
