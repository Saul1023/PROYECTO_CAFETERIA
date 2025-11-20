<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ListarUsuarios extends Component
{
        use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $filterRol = '';
    public $filterEstado = '';
    public $perPage = 10;

    // Para confirmar eliminación
    public $usuarioEliminar = null;

    // Para edición
    public $usuarioEditando = null;
    public $editIdRol;
    public $editNombreUsuario;
    public $editNombreCompleto;
    public $editEmail;
    public $editTelefono;
    public $editPassword;
    public $editPasswordConfirmation;
    public $showEditModal = false;

    protected $queryString = ['search', 'filterRol', 'filterEstado'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterRol()
    {
        $this->resetPage();
    }

    public function updatingFilterEstado()
    {
        $this->resetPage();
    }

    public function cambiarEstado($id)
    {
        $usuario = Usuario::find($id);

        if ($usuario && $usuario->id_usuario !== Auth::id()) {
            $usuario->estado = !$usuario->estado;
            $usuario->save();

            session()->flash('success', 'Estado actualizado correctamente');
        } else {
            session()->flash('error', 'No puedes desactivar tu propia cuenta');
        }
    }

    public function editar($id)
    {
        $this->usuarioEditando = Usuario::find($id);

        if ($this->usuarioEditando) {
            $this->editIdRol = $this->usuarioEditando->id_rol;
            $this->editNombreUsuario = $this->usuarioEditando->nombre_usuario;
            $this->editNombreCompleto = $this->usuarioEditando->nombre_completo;
            $this->editEmail = $this->usuarioEditando->email;
            $this->editTelefono = $this->usuarioEditando->telefono;
            $this->editPassword = '';
            $this->editPasswordConfirmation = '';
            $this->showEditModal = true;
        }
    }

    public function actualizarUsuario()
    {
        $this->validate([
            'editIdRol' => 'required|exists:roles,id_rol',
            'editNombreUsuario' => 'required|string|max:50|unique:usuarios,nombre_usuario,' . $this->usuarioEditando->id_usuario . ',id_usuario',
            'editNombreCompleto' => 'required|string|max:100',
            'editEmail' => 'required|email|unique:usuarios,email,' . $this->usuarioEditando->id_usuario . ',id_usuario',
            'editTelefono' => 'nullable|string|max:20',
            'editPassword' => 'nullable|min:6|confirmed',
        ], [
            'editIdRol.required' => 'El rol es obligatorio',
            'editNombreUsuario.required' => 'El nombre de usuario es obligatorio',
            'editNombreUsuario.unique' => 'Este nombre de usuario ya está en uso',
            'editNombreCompleto.required' => 'El nombre completo es obligatorio',
            'editEmail.required' => 'El email es obligatorio',
            'editEmail.email' => 'El email debe ser válido',
            'editEmail.unique' => 'Este email ya está en uso',
            'editPassword.min' => 'La contraseña debe tener al menos 6 caracteres',
            'editPassword.confirmed' => 'Las contraseñas no coinciden',
        ]);

        try {
            DB::beginTransaction();

            $datosActualizar = [
                'id_rol' => $this->editIdRol,
                'nombre_usuario' => $this->editNombreUsuario,
                'nombre_completo' => $this->editNombreCompleto,
                'email' => $this->editEmail,
                'telefono' => $this->editTelefono,
            ];

            // Solo actualizar contraseña si se proporcionó una nueva
            if ($this->editPassword) {
                $datosActualizar['password'] = Hash::make($this->editPassword);
            }

            $this->usuarioEditando->update($datosActualizar);

            DB::commit();

            $this->cerrarModal();
            session()->flash('success', 'Usuario actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    public function cerrarModal()
    {
        $this->showEditModal = false;
        $this->usuarioEditando = null;
        $this->reset([
            'editIdRol',
            'editNombreUsuario',
            'editNombreCompleto',
            'editEmail',
            'editTelefono',
            'editPassword',
            'editPasswordConfirmation'
        ]);
    }

    public function confirmarEliminacion($id)
    {
        $this->usuarioEliminar = $id;
    }

    public function eliminar()
    {
        $usuario = Usuario::find($this->usuarioEliminar);

        if ($usuario && $usuario->id_usuario !== Auth::id()) {
            $usuario->delete();
            session()->flash('success', 'Usuario eliminado correctamente');
        } else {
            session()->flash('error', 'No puedes eliminar tu propia cuenta');
        }

        $this->usuarioEliminar = null;
    }

    public function render()
    {
        $query = Usuario::with('rol')
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('nombre_completo', 'like', '%' . $this->search . '%')
                        ->orWhere('nombre_usuario', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterRol, function ($q) {
                $q->where('id_rol', $this->filterRol);
            })
            ->when($this->filterEstado !== '', function ($q) {
                $q->where('estado', $this->filterEstado);
            })
            ->orderBy('fecha_creacion', 'desc');

        $usuarios = $query->paginate($this->perPage);
        $roles = Rol::where('estado', true)->get();

        return view('livewire.admin.listar-usuarios', [
            'usuarios' => $usuarios,
            'roles' => $roles
        ])->layout('layouts.admin');
    }
}