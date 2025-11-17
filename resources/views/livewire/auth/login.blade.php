
<div class="card auth-card shadow-lg">
    <div class="card-body p-4">
        <!-- Logo y Título -->
        <div class="text-center mb-4">
            <div class="coffee-icon-wrapper mb-3">
                <i class="bi bi-cup-hot coffee-icon"></i>
            </div>
            <h2 class="fw-bold auth-title">Iniciar Sesión</h2>
            <p class="text-muted">Bienvenido a El Rincón Sabrosito</p>
        </div>

        <!-- Mensajes de Error/Éxito -->
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Formulario -->
        <form wire:submit.prevent="login">
            <!-- Username -->
            <div class="mb-3">
                <label for="username" class="form-label fw-semibold">
                    <i class="bi bi-person me-1"></i>Usuario
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-person text-muted"></i>
                    </span>
                    <input
                        type="text"
                        class="form-control border-start-0 @error('username') is-invalid @enderror"
                        id="username"
                        wire:model="username"
                        placeholder="Ingresa tu usuario"
                        autofocus
                    >
                </div>
                @error('username')
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">
                    <i class="bi bi-lock me-1"></i>Contraseña
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input
                        type="password"
                        class="form-control border-start-0 @error('password') is-invalid @enderror"
                        id="password"
                        wire:model="password"
                        placeholder="Ingresa tu contraseña"
                    >
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Remember Me y Olvidaste -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="remember"
                        wire:model="remember"
                    >
                    <label class="form-check-label" for="remember">
                        Recordarme
                    </label>
                </div>
                <a href="#" class="text-decoration-none small auth-link">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <!-- Botón Submit -->
            <div class="d-grid">
                <button
                    type="submit"
                    class="btn btn-auth btn-lg"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Iniciar Sesión
                    </span>
                    <span wire:loading>
                        <span class="spinner-border spinner-border-sm me-2"></span>
                        Iniciando sesión...
                    </span>
                </button>
            </div>
        </form>

        <!-- Divider -->
        <div class="divider-container my-4">
            <hr class="divider-line">
            <span class="divider-text">O</span>
        </div>

        <!-- Link a Registro -->
        <div class="text-center">
            <p class="mb-0">
                ¿No tienes cuenta?
                <a href="{{ route('registro') }}" class="text-decoration-none auth-link fw-semibold">
                    Regístrate aquí
                </a>
            </p>
        </div>
    </div>

    <!-- Footer del Card -->
    <div class="card-footer bg-light text-center py-3">
        <small class="text-muted">
            <i class="bi bi-shield-check me-1"></i>
            Sistema Seguro © 2025
        </small>
    </div>
</div>
