<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
