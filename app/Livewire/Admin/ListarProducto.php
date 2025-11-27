<?php

namespace App\Livewire\Admin;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ListarProducto extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Propiedades para búsqueda y filtros
    public $search = '';
    public $filterCategoria = '';
    public $filterEstado = '';
    public $perPage = 10;

    // Modal de eliminación
    public $productoToDelete = null;

    public $categorias = [];

    // Listeners para refrescar el componente
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->categorias = Categoria::where('estado', true)->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterCategoria()
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
            $producto = Producto::findOrFail($id);

            // Solo permitir desactivar si el stock es 0
            if ($producto->estado && $producto->stock > 0) {
                $this->dispatch('show-message', [
                    'type' => 'error',
                    'message' => 'No se puede desactivar un producto con stock disponible. Stock actual: ' . $producto->stock
                ]);
                return;
            }

            $producto->estado = !$producto->estado;
            $producto->save();

            $mensaje = $producto->estado ? 'Producto activado correctamente.' : 'Producto desactivado correctamente.';
            $this->dispatch('show-message', [
                'type' => 'success',
                'message' => $mensaje
            ]);

        } catch (\Exception $e) {
            $this->dispatch('show-message', [
                'type' => 'error',
                'message' => 'Error al cambiar el estado: ' . $e->getMessage()
            ]);
        }
    }

    public function confirmDelete($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            $this->dispatch('show-message', [
                'type' => 'error',
                'message' => 'Producto no encontrado.'
            ]);
            return;
        }

        // Verificar que el stock sea 0
        if ($producto->stock > 0) {
            $this->dispatch('show-message', [
                'type' => 'error',
                'message' => 'No se puede eliminar un producto con stock disponible. Stock actual: ' . $producto->stock
            ]);
            return;
        }

        // Establecer el producto a eliminar - esto hará que el modal se muestre
        $this->productoToDelete = $id;
    }

    public function cancelDelete()
    {
        $this->productoToDelete = null;
    }

    public function deleteProducto()
    {
        try {
            if (!$this->productoToDelete) {
                return;
            }

            $producto = Producto::find($this->productoToDelete);

            if (!$producto) {
                $this->dispatch('show-message', [
                    'type' => 'error',
                    'message' => 'Producto no encontrado.'
                ]);
                $this->productoToDelete = null;
                return;
            }

            // Verificar nuevamente que el stock sea 0
            if ($producto->stock > 0) {
                $this->dispatch('show-message', [
                    'type' => 'error',
                    'message' => 'No se puede eliminar un producto con stock disponible. Stock actual: ' . $producto->stock
                ]);
                $this->productoToDelete = null;
                return;
            }

            // Guardar el nombre del producto para el mensaje
            $nombreProducto = $producto->nombre;

            // Eliminar imagen si existe
            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }

            // Eliminar el producto
            $producto->delete();

            // Cerrar el modal primero
            $this->productoToDelete = null;

            // Luego mostrar el mensaje
            $this->dispatch('show-message', [
                'type' => 'success',
                'message' => "Producto '{$nombreProducto}' eliminado correctamente."
            ]);

            // Refrescar la página si quedó vacía
            $this->resetPage();

        } catch (\Exception $e) {
            $this->dispatch('show-message', [
                'type' => 'error',
                'message' => 'Error al eliminar el producto: ' . $e->getMessage()
            ]);
            $this->productoToDelete = null;
        }
    }

    public function render()
    {
        $query = Producto::with('categoria')
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('descripcion', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterCategoria, function ($q) {
                $q->where('id_categoria', $this->filterCategoria);
            })
            ->when($this->filterEstado !== '', function ($q) {
                $q->where('estado', $this->filterEstado);
            })
            ->orderBy('nombre');

        $productos = $query->paginate($this->perPage);

        return view('livewire.admin.listar-producto', [
            'productos' => $productos
        ])->layout('layouts.admin', [
            'title' => 'Productos',
            'pageTitle' => 'Gestión de Productos'
        ]);
    }
}
