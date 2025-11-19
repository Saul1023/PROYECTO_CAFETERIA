<div>
    <style>
        .nav-tabs-custom {
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 1.5rem;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            color: #6c757d;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-tabs-custom .nav-link:hover {
            color: #614c13b9;
            background: transparent;
        }

        .nav-tabs-custom .nav-link.active {
            color: #614c13b9;
            background: transparent;
            border-bottom: 3px solid #614c13b9;
        }

        .form-label-custom {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control-custom {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 0.625rem 0.875rem;
            transition: all 0.3s ease;
        }

        .form-control-custom:focus {
            border-color: #614c13b9;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        }

        .btn-custom {
            border-radius: 8px;
            padding: 0.625rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .alert-custom {
            border-radius: 8px;
            border: none;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            z-index: 10;
        }

        .password-toggle:hover {
            color: #614c13b9;
        }

        .input-icon-wrapper {
            position: relative;
        }
    </style>

    <!-- Tabs de navegación -->
    <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab_activa === 'datos' ? 'active' : '' }}"
                    wire:click="cambiarTab('datos')"
                    type="button">
                <i class="bi bi-person-lines-fill me-2"></i>Mis Datos
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab_activa === 'password' ? 'active' : '' }}"
                    wire:click="cambiarTab('password')"
                    type="button">
                <i class="bi bi-shield-lock-fill me-2"></i>Cambiar Contraseña
            </button>
        </li>
    </ul>

    <!-- Mensajes de éxito/error -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Contenido de las pestañas -->
    <div class="tab-content">
        <!-- TAB: MIS DATOS -->
        @if ($tab_activa === 'datos')
            <div class="tab-pane fade show active">
                <form wire:submit.prevent="actualizarDatos">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label-custom">
                                <i class="bi bi-person me-1 text-primary"></i>Nombre Completo
                            </label>
                            <input type="text"
                                   wire:model.defer="nombre_completo"
                                   class="form-control form-control-custom @error('nombre_completo') is-invalid @enderror"
                                   placeholder="Ej: Juan Pérez García">
                            @error('nombre_completo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">
                                <i class="bi bi-at me-1 text-info"></i>Nombre de Usuario
                            </label>
                            <input type="text"
                                   wire:model.defer="nombre_usuario"
                                   class="form-control form-control-custom @error('nombre_usuario') is-invalid @enderror"
                                   placeholder="usuario123">
                            @error('nombre_usuario')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">
                                <i class="bi bi-envelope me-1 text-success"></i>Email
                            </label>
                            <input type="email"
                                   wire:model.defer="email"
                                   class="form-control form-control-custom @error('email') is-invalid @enderror"
                                   placeholder="correo@ejemplo.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label-custom">
                                <i class="bi bi-telephone me-1 text-warning"></i>Teléfono (opcional)
                            </label>
                            <input type="text"
                                   wire:model.defer="telefono"
                                   class="form-control form-control-custom @error('telefono') is-invalid @enderror"
                                   placeholder="70123456">
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary btn-custom">
                                <i class="bi bi-check-circle me-2"></i>Guardar Cambios
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @endif

        <!-- TAB: CAMBIAR CONTRASEÑA -->
        @if ($tab_activa === 'password')
            <div class="tab-pane fade show active">
                <form wire:submit.prevent="cambiarPassword">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label-custom">
                                <i class="bi bi-lock me-1 text-danger"></i>Contraseña Actual
                            </label>
                            <div class="input-icon-wrapper">
                                <input type="password"
                                       id="passwordActual"
                                       wire:model.defer="password_actual"
                                       class="form-control form-control-custom @error('password_actual') is-invalid @enderror"
                                       placeholder="Ingrese su contraseña actual">
                                <i class="bi bi-eye-slash password-toggle"
                                   onclick="togglePassword('passwordActual', this)"></i>
                                @error('password_actual')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label-custom">
                                <i class="bi bi-key me-1 text-primary"></i>Nueva Contraseña
                            </label>
                            <div class="input-icon-wrapper">
                                <input type="password"
                                       id="passwordNuevo"
                                       wire:model.defer="password_nuevo"
                                       class="form-control form-control-custom @error('password_nuevo') is-invalid @enderror"
                                       placeholder="Mínimo 6 caracteres">
                                <i class="bi bi-eye-slash password-toggle"
                                   onclick="togglePassword('passwordNuevo', this)"></i>
                                @error('password_nuevo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label-custom">
                                <i class="bi bi-check2-circle me-1 text-success"></i>Confirmar Nueva Contraseña
                            </label>
                            <div class="input-icon-wrapper">
                                <input type="password"
                                       id="passwordConfirmacion"
                                       wire:model.defer="password_confirmacion"
                                       class="form-control form-control-custom @error('password_confirmacion') is-invalid @enderror"
                                       placeholder="Repita la nueva contraseña">
                                <i class="bi bi-eye-slash password-toggle"
                                   onclick="togglePassword('passwordConfirmacion', this)"></i>
                                @error('password_confirmacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="alert alert-info alert-custom">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Consejos de seguridad:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Use al menos 6 caracteres</li>
                                    <li>Combine letras, números y símbolos</li>
                                    <li>No use información personal obvia</li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary btn-custom">
                                <i class="bi bi-shield-check me-2"></i>Cambiar Contraseña
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <script>
        function togglePassword(inputId, iconElement) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                iconElement.classList.remove('bi-eye-slash');
                iconElement.classList.add('bi-eye');
            } else {
                input.type = 'password';
                iconElement.classList.remove('bi-eye');
                iconElement.classList.add('bi-eye-slash');
            }
        }
    </script>
</div>
