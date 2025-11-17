<?php

namespace App\Livewire\Admin;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

class EditarProducto extends Component
{
    use WithFileUploads;

    public $productoId;
    public $id_categoria;
    public $nombre;
    public $descripcion;
    public $precio;
    public $stock;
    public $stock_minimo = 0;
    public $imagen;
    public $imagen_actual;
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

    public function mount($id)
    {
        $this->productoId = $id;
        $this->categorias = Categoria::where('estado', true)->get();

        $producto = Producto::findOrFail($id);

        $this->id_categoria = $producto->id_categoria;
        $this->nombre = $producto->nombre;
        $this->descripcion = $producto->descripcion;
        $this->precio = $producto->precio;
        $this->stock = $producto->stock;
        $this->stock_minimo = $producto->stock_minimo;
        $this->imagen_actual = $producto->imagen;
        $this->estado = $producto->estado;
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

            if ($this->imagen) {
                $imagePath = $this->imagen->store('productos', 'public');
                $data['imagen'] = $imagePath;

                if ($this->imagen_actual) {
                    $this->eliminarImagenAnterior($this->imagen_actual);
                }
            }

            Producto::find($this->productoId)->update($data);

            session()->flash('success', 'Producto actualizado correctamente.');
            return redirect()->route('admin.productos');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el producto: ' . $e->getMessage());
        }
    }

    protected function eliminarImagenAnterior($imagenPath)
    {
        try {
            $fullPath = storage_path('app/public/' . $imagenPath);

            if (file_exists($fullPath) && is_file($fullPath)) {
                unlink($fullPath);
            }
        } catch (\Exception $e) {
            // Ahora Log funciona porque está importado
            Log::warning('No se pudo eliminar la imagen anterior: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.editar-producto')
            ->layout('layouts.admin', [
                'title' => 'Editar Producto',
                'pageTitle' => 'Editar Producto'
            ]);
    }
}
