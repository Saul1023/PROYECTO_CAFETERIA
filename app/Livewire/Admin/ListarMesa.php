<?php

namespace App\Livewire\Admin;

use App\Models\Mesa;
use Livewire\Component;
use Livewire\WithPagination;

class ListarMesa extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $filterEstado = '';
    public $filterUbicacion = '';
    public $perPage = 10;

    // Propiedades para crear/editar
    public $mesaId;
    public $numero_mesa;
    public $capacidad;
    public $estado = 'disponible';
    public $activa = true;
    public $ubicacion = 'interior';

    // Modal
    public $showModal = false;
    public $isEditing = false;
    public $mesaToDelete = null;

    // Ubicaciones disponibles
    public $ubicaciones = [
        'interior' => 'Interior',
        'terraza' => 'Terraza',
        'vip' => 'VIP',
        'jardin' => 'Jardín'
    ];

    protected $rules = [
        'numero_mesa' => 'required|string|max:10|unique:mesas,numero_mesa',
        'capacidad' => 'required|integer|min:1|max:20',
        'estado' => 'required|in:disponible,ocupada,reservada',
        'activa' => 'boolean',
        'ubicacion' => 'required|in:interior,terraza,vip,jardin'
    ];

    protected $messages = [
        'numero_mesa.required' => 'El número de mesa es obligatorio',
        'numero_mesa.unique' => 'Ya existe una mesa con este número',
        'numero_mesa.max' => 'El número de mesa no puede exceder 10 caracteres',
        'capacidad.required' => 'La capacidad es obligatoria',
        'capacidad.min' => 'La capacidad mínima es 1 persona',
        'capacidad.max' => 'La capacidad máxima es 20 personas',
        'ubicacion.required' => 'La ubicación es obligatoria'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterEstado()
    {
        $this->resetPage();
    }

    public function updatingFilterUbicacion()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEditing = false;
    }

    public function editMesa($id)
    {
        $mesa = Mesa::findOrFail($id);

        $this->mesaId = $mesa->id_mesa;
        $this->numero_mesa = $mesa->numero_mesa;
        $this->capacidad = $mesa->capacidad;
        $this->estado = $mesa->estado;
        $this->activa = $mesa->activa;
        $this->ubicacion = $mesa->ubicacion ?? 'interior';

        $this->showModal = true;
        $this->isEditing = true;

        // Actualizar regla de validación para edición
        $this->rules['numero_mesa'] = "required|string|max:10|unique:mesas,numero_mesa,{$this->mesaId},id_mesa";
    }

    public function saveMesa()
    {
        $this->validate();

        $data = [
            'numero_mesa' => $this->numero_mesa,
            'capacidad' => $this->capacidad,
            'estado' => $this->estado,
            'activa' => $this->activa,
            'ubicacion' => $this->ubicacion
        ];

        try {
            if ($this->isEditing) {
                Mesa::find($this->mesaId)->update($data);
                session()->flash('success', 'Mesa actualizada correctamente.');
            } else {
                Mesa::create($data);
                session()->flash('success', 'Mesa creada correctamente.');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar la mesa: ' . $e->getMessage());
        }
    }

    public function toggleActiva($id)
    {
        try {
            $mesa = Mesa::findOrFail($id);
            $mesa->activa = !$mesa->activa;
            $mesa->save();

            $estado = $mesa->activa ? 'activada' : 'desactivada';
            session()->flash('success', "Mesa {$estado} correctamente.");
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar el estado: ' . $e->getMessage());
        }
    }

    public function cambiarEstado($id, $nuevoEstado)
    {
        try {
            $mesa = Mesa::findOrFail($id);
            $mesa->estado = $nuevoEstado;
            $mesa->save();

            session()->flash('success', 'Estado de la mesa actualizado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar el estado: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->mesaToDelete = $id;
    }

    public function deleteMesa()
    {
        try {
            $mesa = Mesa::find($this->mesaToDelete);

            if ($mesa) {
                // Verificar si la mesa tiene reservaciones activas
                $reservacionesActivas = $mesa->reservaciones()
                    ->whereIn('estado', ['pendiente', 'confirmada'])
                    ->exists();

                if ($reservacionesActivas) {
                    session()->flash('error', 'No se puede eliminar la mesa porque tiene reservaciones activas.');
                    $this->mesaToDelete = null;
                    return;
                }

                $mesa->delete();
                session()->flash('success', 'Mesa eliminada correctamente.');
            }

            $this->mesaToDelete = null;
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar la mesa: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset([
            'mesaId',
            'numero_mesa',
            'capacidad',
            'estado',
            'activa',
            'ubicacion'
        ]);
        $this->resetErrorBag();
        $this->estado = 'disponible';
        $this->activa = true;
        $this->ubicacion = 'interior';

        // Restablecer regla de validación por defecto
        $this->rules['numero_mesa'] = 'required|string|max:10|unique:mesas,numero_mesa';
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $query = Mesa::withCount(['reservaciones as reservaciones_activas' => function($query) {
            $query->whereIn('estado', ['pendiente', 'confirmada']);
        }])
        ->when($this->search, function ($q) {
            $q->where('numero_mesa', 'like', '%' . $this->search . '%')
              ->orWhere('ubicacion', 'like', '%' . $this->search . '%');
        })
        ->when($this->filterEstado, function ($q) {
            $q->where('estado', $this->filterEstado);
        })
        ->when($this->filterUbicacion, function ($q) {
            $q->where('ubicacion', $this->filterUbicacion);
        })
        ->orderBy('activa', 'desc')
        ->orderBy('numero_mesa');

        $mesas = $query->paginate($this->perPage);

    return view('livewire.admin.listar-mesa', [
            'mesas' => $mesas
        ])->layout('layouts.admin', [
            'title' => 'Mesas',
            'pageTitle' => 'Gestión de Mesas'
        ]);
    }
}