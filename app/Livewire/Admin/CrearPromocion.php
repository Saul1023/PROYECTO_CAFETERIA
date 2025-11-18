<?php

namespace App\Livewire\Admin;

use App\Models\Producto;
use App\Models\Promocion;
use Carbon\Carbon;
use Livewire\Component;

class CrearPromocion extends Component
{
     // Propiedades del formulario
    public $nombre;
    public $descripcion;
    public $tipo_descuento = 'porcentaje';
    public $valor_descuento;
    public $fecha_inicio;
    public $fecha_fin;
    public $estado = true;
    public $productosSeleccionados = [];

    public $productos = [];

    protected $rules = [
        'nombre' => 'required|min:3|max:100|unique:promociones,nombre',
        'descripcion' => 'nullable|max:500',
        'tipo_descuento' => 'required|in:porcentaje',
        'valor_descuento' => 'required|numeric|min:0.01|max:100',
        'fecha_inicio' => 'required|date|after_or_equal:today',
        'fecha_fin' => 'required|date|after:fecha_inicio',
        'estado' => 'boolean',
        'productosSeleccionados' => 'required|array|min:1'
    ];

    protected $messages = [
        'nombre.required' => 'El nombre de la promoción es obligatorio',
        'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
        'nombre.unique' => 'Ya existe una promoción con este nombre',
        'valor_descuento.required' => 'El valor del descuento es obligatorio',
        'valor_descuento.numeric' => 'El descuento debe ser un número válido',
        'valor_descuento.min' => 'El descuento debe ser mayor a 0',
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
        // Cargar productos activos - ESPECIFICAR COLUMNAS
        $this->productos = Producto::where('estado', true)
            ->where('stock', '>', 0)
            ->select('id_producto', 'nombre', 'precio', 'stock', 'id_categoria') // ← Especificar columnas
            ->with(['categoria' => function($query) {
                $query->select('id_categoria', 'nombre');
            }])
            ->orderBy('nombre')
            ->get();

        // Establecer fechas por defecto
        $this->fecha_inicio = Carbon::today()->format('Y-m-d');
        $this->fecha_fin = Carbon::today()->addDays(7)->format('Y-m-d');
    }

    public function savePromocion()
    {
        $this->validate();

        try {
            // Crear la promoción
            $promocion = Promocion::create([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'tipo_descuento' => $this->tipo_descuento,
                'valor_descuento' => $this->valor_descuento,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
                'estado' => $this->estado
            ]);

            // Asociar productos seleccionados
            $promocion->productos()->attach($this->productosSeleccionados);

            session()->flash('success', 'Promoción creada correctamente.');
            $this->resetForm();

        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear la promoción: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset([
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

        // Restablecer valores por defecto
        $this->tipo_descuento = 'porcentaje';
        $this->estado = true;
        $this->fecha_inicio = Carbon::today()->format('Y-m-d');
        $this->fecha_fin = Carbon::today()->addDays(7)->format('Y-m-d');
    }

    public function updated($propertyName)
    {
        // Validación en tiempo real
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.admin.crear-promocion')
            ->layout('layouts.admin', [
                'title' => 'Crear Promoción',
                'pageTitle' => 'Crear Nueva Promoción'
            ]);
    }
}
