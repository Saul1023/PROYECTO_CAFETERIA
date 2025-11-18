<?php

namespace App\Livewire\Admin;

use App\Models\Mesa;
use App\Models\Reservacion;
use App\Models\Usuario;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ListarReservacion extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $filterEstado = '';
    public $filterFecha = '';
    public $perPage = 10;

    // Propiedades para crear/editar
    public $reservacionId;
    public $id_mesa;
    public $id_usuario;
    public $fecha_reservacion;
    public $hora_reservacion;
    public $numero_personas;
    public $estado = 'pendiente';
    public $observaciones;

    // Modal
    public $showModal = false;
    public $isEditing = false;
    public $reservacionToDelete = null;

    public $mesas = [];
    public $usuarios = [];

    protected $rules = [
        'id_mesa' => 'required|exists:mesas,id_mesa',
        'id_usuario' => 'nullable|exists:usuarios,id_usuario',
        'fecha_reservacion' => 'required|date|after_or_equal:today',
        'hora_reservacion' => 'required|date_format:H:i',
        'numero_personas' => 'required|integer|min:1|max:20',
        'estado' => 'required|in:pendiente,confirmada,completada,cancelada,no_asistio',
        'observaciones' => 'nullable|max:500'
    ];

    protected $messages = [
        'id_mesa.required' => 'La mesa es obligatoria',
        'fecha_reservacion.required' => 'La fecha de reservación es obligatoria',
        'fecha_reservacion.after_or_equal' => 'La fecha no puede ser anterior a hoy',
        'hora_reservacion.required' => 'La hora de reservación es obligatoria',
        'numero_personas.required' => 'El número de personas es obligatorio',
        'numero_personas.min' => 'Debe haber al menos 1 persona',
        'numero_personas.max' => 'Máximo 20 personas por reservación',
        'estado.required' => 'El estado es obligatorio'
    ];

    public function mount()
    {
        $this->mesas = Mesa::where('activa', true)->get();
        $this->usuarios = Usuario::where('estado', true)->get(); // Esto está bien

        // Establecer valores por defecto
        $this->fecha_reservacion = Carbon::today()->format('Y-m-d');
        $this->hora_reservacion = '19:00';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterEstado()
    {
        $this->resetPage();
    }

    public function updatingFilterFecha()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEditing = false;
    }

    public function editReservacion($id)
    {
        $reservacion = Reservacion::findOrFail($id);

        $this->reservacionId = $reservacion->id_reservacion;
        $this->id_mesa = $reservacion->id_mesa;
        $this->id_usuario = $reservacion->id_usuario;
        $this->fecha_reservacion = $reservacion->fecha_reservacion->format('Y-m-d');

        // Extraer solo la hora (HH:MM) del timestamp
        $this->hora_reservacion = $reservacion->hora_reservacion->format('H:i');

        $this->numero_personas = $reservacion->numero_personas;
        $this->estado = $reservacion->estado;
        $this->observaciones = $reservacion->observaciones;

        $this->showModal = true;
        $this->isEditing = true;
    }

    public function saveReservacion()
    {
        $this->validate();

        // Convertir id_usuario vacío a null
        if ($this->id_usuario === '') {
            $this->id_usuario = null;
        }

        // Verificar disponibilidad de mesa
        if (!$this->isEditing) {
            $mesaOcupada = Reservacion::where('id_mesa', $this->id_mesa)
                ->where('fecha_reservacion', $this->fecha_reservacion)
                ->where('hora_reservacion', $this->hora_reservacion . ':00')
                ->whereIn('estado', ['pendiente', 'confirmada'])
                ->exists();

            if ($mesaOcupada) {
                session()->flash('error', 'La mesa seleccionada ya está reservada para esa fecha y hora.');
                return;
            }
        }

        $data = [
            'id_mesa' => $this->id_mesa,
            'id_usuario' => $this->id_usuario, // Ya convertido a null si estaba vacío
            'fecha_reservacion' => $this->fecha_reservacion,
            'hora_reservacion' => $this->hora_reservacion . ':00',
            'numero_personas' => $this->numero_personas,
            'estado' => $this->estado,
            'observaciones' => $this->observaciones
        ];

        try {
            if ($this->isEditing) {
                Reservacion::find($this->reservacionId)->update($data);
                session()->flash('success', 'Reservación actualizada correctamente.');
            } else {
                Reservacion::create($data);
                session()->flash('success', 'Reservación creada correctamente.');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar la reservación: ' . $e->getMessage());
        }
    }

    public function cambiarEstado($id, $nuevoEstado)
    {
        try {
            $reservacion = Reservacion::findOrFail($id);
            $reservacion->estado = $nuevoEstado;
            $reservacion->save();

            session()->flash('success', 'Estado actualizado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar el estado: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->reservacionToDelete = $id;
    }

    public function deleteReservacion()
    {
        try {
            $reservacion = Reservacion::find($this->reservacionToDelete);

            if ($reservacion) {
                $reservacion->delete();
                session()->flash('success', 'Reservación eliminada correctamente.');
            }

            $this->reservacionToDelete = null;
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar la reservación: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset([
            'reservacionId',
            'id_mesa',
            'id_usuario',
            'fecha_reservacion',
            'hora_reservacion',
            'numero_personas',
            'estado',
            'observaciones'
        ]);
        $this->resetErrorBag();
        $this->estado = 'pendiente';
        $this->fecha_reservacion = Carbon::today()->format('Y-m-d');
        $this->hora_reservacion = '19:00';
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $query = Reservacion::with(['mesa', 'usuario'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->whereHas('mesa', function ($q) {
                        $q->where('numero_mesa', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('usuario', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('observaciones', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterEstado, function ($q) {
                $q->where('estado', $this->filterEstado);
            })
            ->when($this->filterFecha, function ($q) {
                if ($this->filterFecha === 'hoy') {
                    $q->whereDate('fecha_reservacion', today());
                } elseif ($this->filterFecha === 'futuras') {
                    $q->whereDate('fecha_reservacion', '>=', today());
                } elseif ($this->filterFecha === 'pasadas') {
                    $q->whereDate('fecha_reservacion', '<', today());
                }
            })
            ->orderBy('fecha_reservacion', 'desc')
            ->orderBy('hora_reservacion', 'desc');

        $reservaciones = $query->paginate($this->perPage);

        return view('livewire.admin.listar-reservacion', [
            'reservaciones' => $reservaciones
        ])->layout('layouts.admin', [
            'title' => 'Reservaciones',
            'pageTitle' => 'Gestión de Reservaciones'
        ]);
    }
}
