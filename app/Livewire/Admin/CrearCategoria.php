<?php

namespace App\Livewire\Admin;

use App\Models\Categoria;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class CrearCategoria extends Component
{
    use WithFileUploads;

    public $nombre;
    public $descripcion;
    public $imagen;
    public $estado = true;

    protected $rules = [
        'nombre' => 'required|min:3|max:100|unique:categorias,nombre',
        'descripcion' => 'nullable|max:500',
        'imagen' => 'nullable|image|max:2048',
        'estado' => 'boolean'
    ];

    protected $messages = [
        'nombre.required' => 'El nombre de la categoría es obligatorio',
        'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
        'nombre.unique' => 'Ya existe una categoría con este nombre',
        'imagen.image' => 'El archivo debe ser una imagen válida',
        'imagen.max' => 'La imagen no debe pesar más de 2MB',
    ];

    public function saveCategoria()
    {
        $this->validate();

        try {
            $data = [
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'estado' => $this->estado
            ];

            // Manejar la imagen
            if ($this->imagen) {
                $imagePath = $this->imagen->store('categorias', 'public');
                $data['imagen_url'] = $imagePath;
            }

            Categoria::create($data);

            session()->flash('success', 'Categoría creada correctamente.');
            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear la categoría: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset(['nombre', 'descripcion', 'imagen', 'estado']);
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.crear-categoria')
            ->layout('layouts.admin', [
                'title' => 'Crear Categoría',
                'pageTitle' => 'Crear Nueva Categoría'
            ]);
    }
}
