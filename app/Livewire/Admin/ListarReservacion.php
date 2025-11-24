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
    public $estado = 'confirmada';
    public $observaciones;
    public $monto_pago = 30.00;

    // Modal
    public $showModal = false;
    public $isEditing = false;
    public $reservacionToDelete = null;

    // Para disponibilidad
    public $mesasDisponibles = [];
    public $horariosDisponibles = [];
    public $mostrarDisponibilidad = false;

    public $mesas = [];
    public $usuarios = [];

    protected $rules = [
        'id_mesa' => 'required|exists:mesas,id_mesa',
        'id_usuario' => 'nullable|exists:usuarios,id_usuario',
        'fecha_reservacion' => 'required|date|after_or_equal:today',
        'hora_reservacion' => 'required|in:08:00,10:00,12:00,14:00,16:00,18:00,20:00',
        'numero_personas' => 'required|integer|min:1|max:20',
        'estado' => 'required|in:pendiente,confirmada,completada,cancelada,no_asistio',
        'observaciones' => 'nullable|max:500',
        'monto_pago' => 'required|numeric|min:0'
    ];

    protected $messages = [
        'id_mesa.required' => 'La mesa es obligatoria',
        'fecha_reservacion.required' => 'La fecha de reservación es obligatoria',
        'fecha_reservacion.after_or_equal' => 'La fecha no puede ser anterior a hoy',
        'hora_reservacion.required' => 'La hora de reservación es obligatoria',
        'hora_reservacion.in' => 'Debe seleccionar un horario válido (8:00 AM - 8:00 PM, cada 2 horas)',
        'numero_personas.required' => 'El número de personas es obligatorio',
        'numero_personas.min' => 'Debe haber al menos 1 persona',
        'numero_personas.max' => 'Máximo 20 personas por reservación',
        'estado.required' => 'El estado es obligatorio',
        'monto_pago.required' => 'El monto de pago es obligatorio'
    ];

    public function mount()
    {
        $this->mesas = Mesa::where('activa', true)->get();
        $this->usuarios = Usuario::where('estado', true)->get();

        // Establecer valores por defecto
        $this->fecha_reservacion = Carbon::today()->format('Y-m-d');
        $this->hora_reservacion = '12:00';
        $this->monto_pago = 30.00;
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

    // Actualizar disponibilidad cuando cambia fecha o número de personas
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['fecha_reservacion', 'numero_personas'])) {
            $this->verificarDisponibilidad();
        }
    }

    public function verificarDisponibilidad()
    {
        if (!$this->fecha_reservacion || !$this->numero_personas) {
            $this->mesasDisponibles = [];
            $this->mostrarDisponibilidad = false;
            return;
        }

        // Horarios del restaurante (de 8:00 AM a 8:00 PM, cada 2 horas)
        $horariosRestaurante = [
            '08:00', '10:00', '12:00', '14:00',
            '16:00', '18:00', '20:00'
        ];

        // Si es hoy, filtrar horarios que ya pasaron
        $fechaSeleccionada = Carbon::parse($this->fecha_reservacion);
        $esHoy = $fechaSeleccionada->isToday();

        if ($esHoy) {
            $horaActual = Carbon::now();
            $horariosRestaurante = array_filter($horariosRestaurante, function($horario) use ($horaActual) {
                $horarioCarbon = Carbon::parse($horario);
                // Solo mostrar horarios que faltan al menos 2 horas
                return $horarioCarbon->greaterThan($horaActual->copy()->addHours(2));
            });

            // Si no hay horarios disponibles para hoy
            if (empty($horariosRestaurante)) {
                $this->mesasDisponibles = [];
                $this->mostrarDisponibilidad = true;
                session()->flash('warning', 'No hay horarios disponibles para hoy. Por favor selecciona otra fecha.');
                return;
            }
        }

        // Obtener mesas que pueden acomodar el número de personas
        $mesas = Mesa::where('activa', true)
            ->where('capacidad', '>=', $this->numero_personas)
            ->get();

        $this->mesasDisponibles = [];

        foreach ($mesas as $mesa) {
            $horariosLibres = [];

            foreach ($horariosRestaurante as $horario) {
                // Verificar si la mesa está ocupada en este horario
                $ocupada = Reservacion::where('id_mesa', $mesa->id_mesa)
                    ->where('fecha_reservacion', $this->fecha_reservacion)
                    ->where('hora_reservacion', $horario . ':00')
                    ->whereIn('estado', ['pendiente', 'confirmada'])
                    ->when($this->isEditing, function($q) {
                        // Si estamos editando, excluir la reservación actual
                        $q->where('id_reservacion', '!=', $this->reservacionId);
                    })
                    ->exists();

                if (!$ocupada) {
                    $horariosLibres[] = $horario;
                }
            }

            if (count($horariosLibres) > 0) {
                $this->mesasDisponibles[] = [
                    'mesa' => $mesa,
                    'horarios' => $horariosLibres
                ];
            }
        }

        $this->mostrarDisponibilidad = true;
    }

    public function seleccionarMesaHorario($idMesa, $horario)
    {
        $this->id_mesa = $idMesa;
        $this->hora_reservacion = $horario;
        $this->mostrarDisponibilidad = false;
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

        if ($reservacion->hora_reservacion instanceof Carbon) {
            $this->hora_reservacion = $reservacion->hora_reservacion->format('H:i');
        } else {
            $this->hora_reservacion = Carbon::parse($reservacion->hora_reservacion)->format('H:i');
        }

        $this->numero_personas = $reservacion->numero_personas;
        $this->estado = $reservacion->estado;
        $this->observaciones = $reservacion->observaciones;
        $this->monto_pago = $reservacion->monto_pago ?? 30.00;

        $this->showModal = true;
        $this->isEditing = true;
        $this->mostrarDisponibilidad = false;
    }

    public function saveReservacion()
    {
        $this->validate();

        if ($this->id_usuario === '') {
            $this->id_usuario = null;
        }

        $horaFormateada = $this->hora_reservacion;
        if (strlen($horaFormateada) === 5) {
            $horaFormateada .= ':00';
        }

        // Validar SOLO si no es edición Y SOLO si es para HOY
        if (!$this->isEditing) {
            $fechaSeleccionada = Carbon::parse($this->fecha_reservacion);

            // DEBUG - Temporal para ver qué está pasando
            logger('DEBUG Reservación:', [
                'fecha_seleccionada' => $fechaSeleccionada->toDateString(),
                'es_hoy' => $fechaSeleccionada->isToday() ? 'SI' : 'NO',
                'fecha_hoy' => Carbon::today()->toDateString(),
            ]);

            // SOLO validar si es para HOY
            if ($fechaSeleccionada->isToday()) {
                $fechaHoraReserva = Carbon::parse($this->fecha_reservacion . ' ' . $horaFormateada);

                if ($fechaHoraReserva->isPast()) {
                    session()->flash('error', 'No se puede reservar en una hora que ya pasó.');
                    return;
                }

                // Validar que falten al menos 2 horas SOLO para hoy
                $horasRestantes = Carbon::now()->diffInHours($fechaHoraReserva, false);
                if ($horasRestantes < 2) {
                    session()->flash('error', 'Debe reservar con al menos 2 horas de anticipación para reservas del día de hoy. (Faltan ' . $horasRestantes . ' horas)');
                    return;
                }
            } else {
                // DEBUG - Para ver que entra aquí en fechas futuras
                logger('Es fecha futura, no se validan las 2 horas');
            }
        }

        // Verificar disponibilidad
        $mesaOcupada = Reservacion::where('id_mesa', $this->id_mesa)
            ->where('fecha_reservacion', $this->fecha_reservacion)
            ->where('hora_reservacion', $horaFormateada)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->when($this->isEditing, function($q) {
                $q->where('id_reservacion', '!=', $this->reservacionId);
            })
            ->exists();

        if ($mesaOcupada) {
            session()->flash('error', 'La mesa seleccionada ya está reservada para esa fecha y hora.');
            return;
        }

        $data = [
            'id_mesa' => $this->id_mesa,
            'id_usuario' => $this->id_usuario,
            'fecha_reservacion' => $this->fecha_reservacion,
            'hora_reservacion' => $horaFormateada,
            'numero_personas' => $this->numero_personas,
            'estado' => $this->estado,
            'observaciones' => $this->observaciones,
            'monto_pago' => $this->monto_pago,
            'fecha_creacion' => now(),
            'fecha_actualizacion' => now()
        ];

        if ($this->estado === 'confirmada') {
            $data['fecha_confirmacion'] = now();
        }

        try {
            if ($this->isEditing) {
                $reservacion = Reservacion::find($this->reservacionId);
                $data['fecha_actualizacion'] = now();
                $reservacion->update($data);

                session()->flash('success', 'Reservación actualizada correctamente.');
            } else {
                $reservacion = Reservacion::create($data);
                $reservacion->generarCodigoQR();

                session()->flash('success', 'Reservación creada correctamente con código QR: ' . $reservacion->codigo_qr);
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
            $reservacion->fecha_actualizacion = now();

            if ($nuevoEstado === 'confirmada' && !$reservacion->fecha_confirmacion) {
                $reservacion->fecha_confirmacion = now();
            }

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
            'observaciones'
        ]);
        $this->resetErrorBag();

        $this->estado = 'confirmada';
        $this->fecha_reservacion = Carbon::today()->format('Y-m-d');
        $this->hora_reservacion = '19:00';
        $this->numero_personas = 2;
        $this->monto_pago = 30.00;
        $this->mesasDisponibles = [];
        $this->mostrarDisponibilidad = false;
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
                        $q->where('nombre_completo', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('codigo_qr', 'like', '%' . $this->search . '%')
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
