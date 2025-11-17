<?php

namespace App\Livewire\Admin;

use App\Models\Categoria;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class EditarCategoria extends Component
{
    public $categoria;
    public $categoriaId;

    // Propiedades del formulario
    public $nombre;
    public $descripcion;
    public $estado = true;

    // Reglas de validación
    protected $rules = [
        'nombre' => 'required|min:3|max:100|unique:categorias,nombre',
        'descripcion' => 'nullable|max:255',
        'estado' => 'boolean'
    ];

    // Mensajes personalizados
    protected $messages = [
        'nombre.required' => 'El nombre de la categoría es obligatorio',
        'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
        'nombre.max' => 'El nombre no debe exceder los 100 caracteres',
        'nombre.unique' => 'Ya existe una categoría con este nombre',
        'descripcion.max' => 'La descripción no debe exceder los 255 caracteres',
    ];

    public function mount($id)
    {
        $this->categoriaId = $id;
        $this->cargarCategoria();
    }

    public function cargarCategoria()
    {
        $this->categoria = Categoria::findOrFail($this->categoriaId);

        $this->nombre = $this->categoria->nombre;
        $this->descripcion = $this->categoria->descripcion;
        $this->estado = $this->categoria->estado;

        // Actualizar regla de validación para ignorar el nombre actual
        $this->rules['nombre'] = "required|min:3|max:100|unique:categorias,nombre,{$this->categoriaId},id_categoria";
    }

    public function actualizarCategoria()
    {
        try {
            // Validar datos
            $this->validate();

            // Actualizar categoría
            $this->categoria->update([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'estado' => $this->estado
            ]);

            // Mensaje de éxito
            session()->flash('success', 'Categoría actualizada correctamente.');

            // Redirigir a la lista de categorías
            return redirect()->route('admin.categorias');
        } catch (\Exception $e) {
            Log::error('Error al actualizar categoría: ' . $e->getMessage());
            session()->flash('error', 'Error al actualizar la categoría: ' . $e->getMessage());
        }
    }

    public function cancelar()
    {
        return redirect()->route('admin.categorias');
    }

    public function render()
    {
        return view('livewire.admin.editar-categoria')
            ->layout('layouts.admin', [
                'title' => 'Editar Categoría',
                'pageTitle' => 'Editar Categoría: ' . ($this->categoria->nombre ?? '')
            ]);
    }
}
