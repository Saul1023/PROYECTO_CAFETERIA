<?php

namespace App\Livewire\Admin;

use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class ListarCategoria extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $filterEstado = '';
    public $perPage = 10;
    public $categoriaToDelete = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterEstado()
    {
        $this->resetPage();
    }

    public function toggleEstado($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->estado = !$categoria->estado;
            $categoria->save();

            session()->flash('success', 'Estado de la categoría actualizado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar el estado: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->categoriaToDelete = $id;
    }

    public function deleteCategoria()
    {
        try {
            $categoria = Categoria::find($this->categoriaToDelete);

            if ($categoria) {
                // Verificar si hay productos asociados
                if ($categoria->productos()->count() > 0) {
                    session()->flash('error', 'No se puede eliminar la categoría porque tiene productos asociados.');
                    $this->categoriaToDelete = null;
                    return;
                }

                // Eliminar imagen si existe - USANDO Storage CORRECTAMENTE
                if ($categoria->imagen_url) {
                    Storage::disk('public')->delete($categoria->imagen_url);
                }

                $categoria->delete();
                session()->flash('success', 'Categoría eliminada correctamente.');
            }

            $this->categoriaToDelete = null;
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar la categoría: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Categoria::withCount('productos')
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('descripcion', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterEstado !== '', function ($q) {
                $q->where('estado', $this->filterEstado);
            })
            ->orderBy('nombre');

        $categorias = $query->paginate($this->perPage);

        return view('livewire.admin.listar-categoria', [
            'categorias' => $categorias
        ])->layout('layouts.admin', [
            'title' => 'Categorías',
            'pageTitle' => 'Gestión de Categorías'
        ]);
    }
}
