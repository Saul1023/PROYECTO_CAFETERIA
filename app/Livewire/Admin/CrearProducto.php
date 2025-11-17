<?php

namespace App\Livewire\Admin;

use App\Models\Categoria;
use App\Models\Producto;
use Livewire\Component;
use Livewire\WithFileUploads;

class CrearProducto extends Component
{
    use WithFileUploads;

    // Propiedades del formulario
    public $id_categoria;
    public $nombre;
    public $descripcion;
    public $precio;
    public $stock;
    public $stock_minimo = 0;
    public $imagen;
    public $estado = true;

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

    public function saveProducto()
    {
        $this->validate();

        try {
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
                $imagePath = $this->imagen->store('productos', 'public');
                $data['imagen_url'] = $imagePath;
            }

            Producto::create($data);

            session()->flash('success', 'Producto creado correctamente.');

            // Resetear el formulario
            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el producto: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset([
            'id_categoria',
            'nombre',
            'descripcion',
            'precio',
            'stock',
            'stock_minimo',
            'imagen',
            'estado'
        ]);
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.crear-producto')->layout('layouts.admin');
    }
}
