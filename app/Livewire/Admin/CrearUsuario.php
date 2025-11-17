<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CrearUsuario extends Component
{
    public $nombre_usuario;
    public $password;
    public $password_confirmation;
    public $nombre_completo;
    public $email;
    public $telefono;
    public $id_rol;
    public $estado = true;

    public $roles = [];

    protected $rules = [
        'nombre_usuario' => 'required|min:4|unique:usuarios,nombre_usuario',
        'password' => 'required|min:6',
        'password_confirmation' => 'required|same:password',
        'nombre_completo' => 'required|min:3',
        'email' => 'required|email|unique:usuarios,email',
        'telefono' => 'nullable|min:8',
        'id_rol' => 'required|exists:roles,id_rol',
        'estado' => 'boolean',
    ];

    protected $messages = [
        'nombre_usuario.required' => 'El nombre de usuario es obligatorio',
        'nombre_usuario.min' => 'El nombre de usuario debe tener al menos 4 caracteres',
        'nombre_usuario.unique' => 'Este nombre de usuario ya está en uso',
        'password.required' => 'La contraseña es obligatoria',
        'password.min' => 'La contraseña debe tener al menos 6 caracteres',
        'password_confirmation.same' => 'Las contraseñas no coinciden',
        'nombre_completo.required' => 'El nombre completo es obligatorio',
        'email.required' => 'El email es obligatorio',
        'email.email' => 'Ingresa un email válido',
        'email.unique' => 'Este email ya está registrado',
        'id_rol.required' => 'Debes seleccionar un rol',
        'id_rol.exists' => 'El rol seleccionado no es válido',
    ];

    public function mount()
    {
        // Solo mostrar roles de ADMINISTRADOR y EMPLEADO
        // Los CLIENTES se auto-registran
        $this->roles = Rol::whereIn('nombre', ['ADMINISTRADOR', 'EMPLEADO'])
                          ->where('estado', true)
                          ->get();
    }

    public function guardar()
    {
        $this->validate();

        // Verificar que el usuario autenticado sea ADMINISTRADOR
        if (Auth::user()->id_rol !== 1) { // Assuming 1 is ADMINISTRADOR
            session()->flash('error', 'No tienes permisos para crear usuarios');
            return;
        }

        try {
            Usuario::create([
                'id_rol' => $this->id_rol,
                'nombre_usuario' => $this->nombre_usuario,
                'password' => Hash::make($this->password),
                'nombre_completo' => $this->nombre_completo,
                'email' => $this->email,
                'telefono' => $this->telefono,
                'estado' => $this->estado
            ]);

            session()->flash('success', 'Usuario creado exitosamente');

            // Limpiar formulario
            $this->reset([
                'nombre_usuario',
                'password',
                'password_confirmation',
                'nombre_completo',
                'email',
                'telefono',
                'id_rol'
            ]);

            // Mantener estado en true
            $this->estado = true;

        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear usuario: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.crear-usuario')->layout('layouts.admin');
    }
}
