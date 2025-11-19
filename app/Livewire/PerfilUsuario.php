<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PerfilUsuario extends Component
{
    // Datos del usuario
    public $nombre_completo;
    public $nombre_usuario;
    public $email;
    public $telefono;

    // Datos para cambiar contraseña
    public $password_actual;
    public $password_nuevo;
    public $password_confirmacion;

    // Control de pestañas
    public $tab_activa = 'datos';

    protected $rules = [
        'nombre_completo' => 'required|string|max:255',
        'nombre_usuario' => 'required|string|max:100|unique:usuarios,nombre_usuario',
        'email' => 'required|email|max:255|unique:usuarios,email',
        'telefono' => 'nullable|string|max:20',
    ];

    protected $messages = [
        'nombre_completo.required' => 'El nombre completo es obligatorio',
        'nombre_usuario.required' => 'El nombre de usuario es obligatorio',
        'nombre_usuario.unique' => 'Este nombre de usuario ya está en uso',
        'email.required' => 'El email es obligatorio',
        'email.email' => 'Ingrese un email válido',
        'email.unique' => 'Este email ya está registrado',
    ];

    public function mount()
    {
        $usuario = Auth::user();
        $this->nombre_completo = $usuario->nombre_completo;
        $this->nombre_usuario = $usuario->nombre_usuario;
        $this->email = $usuario->email;
        $this->telefono = $usuario->telefono;
    }

    public function actualizarDatos()
    {
        $usuario = Auth::user();

        // Validar con excepción del usuario actual
        $this->validate([
            'nombre_completo' => 'required|string|max:255',
            'nombre_usuario' => 'required|string|max:100|unique:usuarios,nombre_usuario,' . $usuario->id_usuario . ',id_usuario',
            'email' => 'required|email|max:255|unique:usuarios,email,' . $usuario->id_usuario . ',id_usuario',
            'telefono' => 'nullable|string|max:20',
        ]);

        try {
            $usuario->update([
                'nombre_completo' => $this->nombre_completo,
                'nombre_usuario' => $this->nombre_usuario,
                'email' => $this->email,
                'telefono' => $this->telefono,
            ]);

            session()->flash('success', '¡Datos actualizados correctamente!');
            $this->emit('datosActualizados');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar los datos: ' . $e->getMessage());
        }
    }

    public function cambiarPassword()
    {
        $this->validate([
            'password_actual' => 'required',
            'password_nuevo' => 'required|min:6|different:password_actual',
            'password_confirmacion' => 'required|same:password_nuevo',
        ], [
            'password_actual.required' => 'Ingrese su contraseña actual',
            'password_nuevo.required' => 'Ingrese la nueva contraseña',
            'password_nuevo.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password_nuevo.different' => 'La nueva contraseña debe ser diferente a la actual',
            'password_confirmacion.required' => 'Confirme la nueva contraseña',
            'password_confirmacion.same' => 'Las contraseñas no coinciden',
        ]);

        $usuario = Auth::user();

        if (!Hash::check($this->password_actual, $usuario->password)) {
            $this->addError('password_actual', 'La contraseña actual es incorrecta');
            return;
        }

        try {
            $usuario->update([
                'password' => Hash::make($this->password_nuevo),
            ]);

            // Limpiar campos
            $this->reset(['password_actual', 'password_nuevo', 'password_confirmacion']);

            session()->flash('success', '¡Contraseña actualizada correctamente!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar la contraseña: ' . $e->getMessage());
        }
    }

    public function cambiarTab($tab)
    {
        $this->tab_activa = $tab;
        $this->resetErrorBag();
        session()->forget(['success', 'error']);
    }

    public function render()
    {
        return view('livewire.perfil-usuario');
    }
}
