<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class Login extends Component
{
    public $username;
    public $password;
    public $remember = false;

    protected $rules = [
        'username' => 'required',
        'password' => 'required',
    ];

    protected $messages = [
        'username.required' => 'El nombre de usuario es obligatorio',
        'password.required' => 'La contraseña es obligatoria',
    ];

    public function login()
    {
        $this->validate();

        // Buscar usuario por nombre_usuario
        $usuario = Usuario::where('nombre_usuario', $this->username)
                         ->where('estado', true)
                         ->first();

        if (!$usuario) {
            session()->flash('error', 'Usuario no encontrado o inactivo');
            return;
        }

        // Intentar autenticar
        $credentials = [
            'nombre_usuario' => $this->username,
            'password' => $this->password // Laravel automáticamente usa getAuthPassword()
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            // Actualizar último acceso
            $usuario->ultimo_acceso = now();
            $usuario->save();

            // Regenerar sesión por seguridad
            request()->session()->regenerate();

            // Redirigir según el rol
            return $this->redirectByRole($usuario);
        }

        session()->flash('error', 'Credenciales incorrectas');
    }

    protected function redirectByRole($usuario)
    {
        $rolNombre = $usuario->rol->nombre;

        switch ($rolNombre) {
            case 'ADMINISTRADOR':
                return redirect()->route('admin.dashboard');

            case 'EMPLEADO':
                return redirect()->route('empleado.dashboard');

            case 'CLIENTE':
                // Los clientes van al home (welcome.blade.php)
                return redirect()->route('home')->with('success', '¡Bienvenido de nuevo!');

            default:
                Auth::logout();
                session()->flash('error', 'Rol no reconocido');
                return redirect()->route('login');
        }
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}
