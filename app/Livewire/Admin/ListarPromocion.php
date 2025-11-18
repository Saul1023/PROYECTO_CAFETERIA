<?php

namespace App\Livewire\Admin;

use App\Models\Producto;
use App\Models\Promocion;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ListarPromocion extends Component
{
     use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $filterEstado = '';
    public $filterVigencia = '';
    public $perPage = 10;

    // Propiedades para crear/editar
    public $promocionId;
    public $nombre;
    public $descripcion;
    public $tipo_descuento = 'porcentaje';
    public $valor_descuento;
    public $fecha_inicio;
    public $fecha_fin;
    public $estado = true;
    public $productosSeleccionados = [];

    // Modal
    public $showModal = false;
    public $isEditing = false;
    public $promocionToDelete = null;

    public $productos = [];

    protected $rules = [
        'nombre' => 'required|min:3|max:100',
        'descripcion' => 'nullable|max:500',
        'tipo_descuento' => 'required|in:porcentaje',
        'valor_descuento' => 'required|numeric|min:0|max:100',
        'fecha_inicio' => 'required|date|after_or_equal:today',
        'fecha_fin' => 'required|date|after:fecha_inicio',
        'estado' => 'boolean',
        'productosSeleccionados' => 'required|array|min:1'
    ];

    protected $messages = [
        'nombre.required' => 'El nombre de la promoción es obligatorio',
        'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
        'valor_descuento.required' => 'El valor del descuento es obligatorio',
        'valor_descuento.numeric' => 'El descuento debe ser un número válido',
        'valor_descuento.max' => 'El descuento no puede ser mayor a 100%',
        'fecha_inicio.required' => 'La fecha de inicio es obligatoria',
        'fecha_inicio.after_or_equal' => 'La fecha de inicio no puede ser anterior a hoy',
        'fecha_fin.required' => 'La fecha de fin es obligatoria',
        'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio',
        'productosSeleccionados.required' => 'Debe seleccionar al menos un producto',
        'productosSeleccionados.min' => 'Debe seleccionar al menos un producto'
    ];

    public function mount()
    {
        $this->productos = Producto::where('estado', true)->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterEstado()
    {
        $this->resetPage();
    }

    public function updatingFilterVigencia()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEditing = false;
    }

    public function editPromocion($id)
    {
        $promocion = Promocion::with('productos')->findOrFail($id);

        $this->promocionId = $promocion->id_promocion;
        $this->nombre = $promocion->nombre;
        $this->descripcion = $promocion->descripcion;
        $this->tipo_descuento = $promocion->tipo_descuento;
        $this->valor_descuento = $promocion->valor_descuento;
        $this->fecha_inicio = $promocion->fecha_inicio->format('Y-m-d');
        $this->fecha_fin = $promocion->fecha_fin->format('Y-m-d');
        $this->estado = $promocion->estado;
        $this->productosSeleccionados = $promocion->productos->pluck('id_producto')->toArray();

        $this->showModal = true;
        $this->isEditing = true;
    }

    public function savePromocion()
    {
        $this->validate();

        $data = [
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'tipo_descuento' => $this->tipo_descuento,
            'valor_descuento' => $this->valor_descuento,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'estado' => $this->estado
        ];

        try {
            if ($this->isEditing) {
                $promocion = Promocion::find($this->promocionId);
                $promocion->update($data);
                $promocion->productos()->sync($this->productosSeleccionados);
                session()->flash('success', 'Promoción actualizada correctamente.');
            } else {
                $promocion = Promocion::create($data);
                $promocion->productos()->attach($this->productosSeleccionados);
                session()->flash('success', 'Promoción creada correctamente.');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar la promoción: ' . $e->getMessage());
        }
    }

    public function toggleEstado($id)
    {
        try {
            $promocion = Promocion::findOrFail($id);
            $promocion->estado = !$promocion->estado;
            $promocion->save();

            session()->flash('success', 'Estado actualizado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar el estado: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->promocionToDelete = $id;
    }

    public function deletePromocion()
    {
        try {
            $promocion = Promocion::find($this->promocionToDelete);

            if ($promocion) {
                $promocion->delete();
                session()->flash('success', 'Promoción eliminada correctamente.');
            }

            $this->promocionToDelete = null;
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar la promoción: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset([
            'promocionId',
            'nombre',
            'descripcion',
            'tipo_descuento',
            'valor_descuento',
            'fecha_inicio',
            'fecha_fin',
            'estado',
            'productosSeleccionados'
        ]);
        $this->resetErrorBag();
        $this->tipo_descuento = 'porcentaje';
        $this->estado = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

public function render()
{
    $query = Promocion::with(['productos' => function($query) {
        $query->select('productos.id_producto', 'productos.nombre', 'productos.precio'); // ← Especificar tabla
    }])
    ->when($this->search, function ($q) {
        $q->where('nombre', 'like', '%' . $this->search . '%')
          ->orWhere('descripcion', 'like', '%' . $this->search . '%');
    })
    ->when($this->filterEstado !== '', function ($q) {
        $q->where('estado', $this->filterEstado);
    })
    ->when($this->filterVigencia, function ($q) {
        $hoy = Carbon::today();
        if ($this->filterVigencia === 'vigentes') {
            $q->where('fecha_inicio', '<=', $hoy)
              ->where('fecha_fin', '>=', $hoy)
              ->where('estado', true);
        } elseif ($this->filterVigencia === 'futuras') {
            $q->where('fecha_inicio', '>', $hoy);
        } elseif ($this->filterVigencia === 'expiradas') {
            $q->where('fecha_fin', '<', $hoy);
        }
    })
    ->orderBy('fecha_inicio', 'desc');

    $promociones = $query->paginate($this->perPage);

    return view('livewire.admin.listar-promocion', [
        'promociones' => $promociones
    ])->layout('layouts.admin', [
        'title' => 'Promociones',
        'pageTitle' => 'Gestión de Promociones'
    ]);
}
}
