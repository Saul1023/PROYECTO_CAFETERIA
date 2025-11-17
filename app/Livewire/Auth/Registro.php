<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Registro extends Component
{
    public $nombre_usuario;
    public $password;
    public $password_confirmation;
    public $nombre_completo;
    public $email;
    public $telefono;

    protected $rules = [
        'nombre_usuario' => 'required|min:4|unique:usuarios,nombre_usuario',
        'password' => 'required|min:6',
        'password_confirmation' => 'required|same:password',
        'nombre_completo' => 'required|min:3',
        'email' => 'required|email|unique:usuarios,email',
        'telefono' => 'nullable|min:8',
    ];

    protected $messages = [
        'nombre_usuario.required' => 'El nombre de usuario es obligatorio',
        'nombre_usuario.unique' => 'Este nombre de usuario ya está en uso',
        'password.required' => 'La contraseña es obligatoria',
        'password.min' => 'La contraseña debe tener al menos 6 caracteres',
        'password_confirmation.same' => 'Las contraseñas no coinciden',
        'nombre_completo.required' => 'El nombre completo es obligatorio',
        'email.required' => 'El email es obligatorio',
        'email.email' => 'Ingresa un email válido',
        'email.unique' => 'Este email ya está registrado',
    ];

    public function registrar()
    {
        $this->validate();

        // Obtener el rol de CLIENTE
        $rolCliente = Rol::where('nombre', 'CLIENTE')->first();

        if (!$rolCliente) {
            session()->flash('error', 'Error del sistema: Rol CLIENTE no encontrado');
            return;
        }

        // Crear el usuario como CLIENTE
        $usuario = Usuario::create([
            'id_rol' => $rolCliente->id_rol,
            'nombre_usuario' => $this->nombre_usuario,
            'password' => Hash::make($this->password),
            'nombre_completo' => $this->nombre_completo,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'estado' => true
        ]);

        // Autenticar automáticamente
        Auth::login($usuario);

        session()->flash('success', '¡Bienvenido ' . $this->nombre_completo . '!');

        // Redirigir al home de clientes
        return redirect()->route('cliente.home');
    }

    public function render()
    {
        return view('livewire.auth.registro')->layout('layouts.guest');
    }
}
