<?php

namespace App\Livewire\Admin;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ListarProducto extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    // Propiedades para búsqueda y filtros
    public $search = '';
    public $filterCategoria = '';
    public $filterEstado = '';
    public $perPage = 10;

    // Propiedades para edición/creación
    public $productoId;
    public $id_categoria;
    public $nombre;
    public $descripcion;
    public $precio;
    public $stock;
    public $stock_minimo = 0;
    public $imagen_url;
    public $imagen;
    public $estado = true;

    // Modal
    public $showModal = false;
    public $isEditing = false;
    public $productoToDelete = null;

    public $categorias = [];

    protected $rules = [
        'id_categoria' => 'required|exists:categorias,id_categoria',
        'nombre' => 'required|min:3|max:100',
        'descripcion' => 'nullable|max:500',
        'precio' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'stock_minimo' => 'required|integer|min:0',
        'imagen' => 'nullable|image|max:2048',
        'estado' => 'boolean'
    ];

    protected $messages = [
        'id_categoria.required' => 'La categoría es obligatoria',
        'nombre.required' => 'El nombre del producto es obligatorio',
        'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
        'precio.required' => 'El precio es obligatorio',
        'precio.numeric' => 'El precio debe ser un número válido',
        'stock.required' => 'El stock es obligatorio',
        'stock.integer' => 'El stock debe ser un número entero',
    ];

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

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEditing = false;
    }

    public function editProducto($id)
    {
        $producto = Producto::findOrFail($id);

        $this->productoId = $producto->id_producto;
        $this->id_categoria = $producto->id_categoria;
        $this->nombre = $producto->nombre;
        $this->descripcion = $producto->descripcion;
        $this->precio = $producto->precio;
        $this->stock = $producto->stock;
        $this->stock_minimo = $producto->stock_minimo;
        $this->imagen_url = $producto->imagen_url;
        $this->estado = $producto->estado;

        $this->showModal = true;
        $this->isEditing = true;
    }

    public function saveProducto()
    {
        $this->validate();

        $data = [
            'id_categoria' => $this->id_categoria,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'stock' => $this->stock,
            'stock_minimo' => $this->stock_minimo,
            'estado' => $this->estado
        ];

        // Manejar la imagen
        if ($this->imagen) {
            // Eliminar imagen anterior si existe
            if ($this->isEditing && $this->imagen_url) {
                Storage::disk('public')->delete($this->imagen_url);
            }

            $imagePath = $this->imagen->store('productos', 'public');
            $data['imagen'] = $imagePath;
        }

        try {
            if ($this->isEditing) {
                Producto::find($this->productoId)->update($data);
                session()->flash('success', 'Producto actualizado correctamente.');
            } else {
                Producto::create($data);
                session()->flash('success', 'Producto creado correctamente.');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar el producto: ' . $e->getMessage());
        }
    }

    public function toggleEstado($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $producto->estado = !$producto->estado;
            $producto->save();

            session()->flash('success', 'Estado actualizado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar el estado: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->productoToDelete = $id;
    }

    public function deleteProducto()
    {
        try {
            $producto = Producto::find($this->productoToDelete);

            if ($producto) {
                // Eliminar imagen si existe
                if ($producto->imagen_url) {
                    Storage::disk('public')->delete($producto->imagen_url);
                }

                $producto->delete();
                session()->flash('success', 'Producto eliminado correctamente.');
            }

            $this->productoToDelete = null;
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el producto: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset([
            'productoId',
            'id_categoria',
            'nombre',
            'descripcion',
            'precio',
            'stock',
            'stock_minimo',
            'imagen',
            'estado'
        ]);
        $this->imagen_url = null;
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
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
