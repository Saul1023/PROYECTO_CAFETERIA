<?php

namespace App\Livewire\Cliente;

use App\Models\Mesa;
use App\Models\Reservacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ReservarMesa extends Component
{
    use WithFileUploads;

    public $fecha_reservacion;
    public $hora_reservacion;
    public $numero_personas = 2;
    public $observaciones;
    public $id_mesa;
    public $comprobante_pago;

    public $mesasDisponibles = [];
    public $mostrarDisponibilidad = false;
    public $showConfirmacion = false;
    public $reservaCreada = null;

    protected $rules = [
        'fecha_reservacion' => 'required|date|after_or_equal:today',
        'hora_reservacion' => 'required|in:08:00,10:00,12:00,14:00,16:00,18:00,20:00',
        'numero_personas' => 'required|integer|min:1|max:20',
        'id_mesa' => 'required|exists:mesas,id_mesa',
        'observaciones' => 'nullable|string|max:500',
        'comprobante_pago' => 'required|image|max:2048'
    ];

    protected $messages = [
        'fecha_reservacion.required' => 'Selecciona una fecha para tu reserva',
        'fecha_reservacion.after_or_equal' => 'La fecha debe ser hoy o posterior',
        'hora_reservacion.required' => 'Selecciona un horario',
        'hora_reservacion.in' => 'Selecciona un horario válido',
        'numero_personas.required' => 'Indica el número de personas',
        'numero_personas.min' => 'Mínimo 1 persona',
        'numero_personas.max' => 'Máximo 20 personas',
        'id_mesa.required' => 'Selecciona una mesa',
        'comprobante_pago.required' => 'Debes subir el comprobante de pago',
        'comprobante_pago.image' => 'El comprobante debe ser una imagen',
        'comprobante_pago.max' => 'La imagen no debe superar 2MB'
    ];

    public function mount()
    {
        $this->fecha_reservacion = Carbon::today()->format('Y-m-d');
    }

    // Actualizar disponibilidad cuando cambia fecha o número de personas
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['fecha_reservacion', 'numero_personas'])) {
            $this->verificarDisponibilidad();
            // Resetear selecciones
            $this->hora_reservacion = null;
            $this->id_mesa = null;
        }
    }

    public function verificarDisponibilidad()
    {
        if (!$this->fecha_reservacion || !$this->numero_personas) {
            $this->mesasDisponibles = [];
            $this->mostrarDisponibilidad = false;
            return;
        }

        $fecha = Carbon::parse($this->fecha_reservacion);
        $esHoy = $fecha->isToday();
        $horaActual = Carbon::now();

        // Horarios del restaurante (de 8:00 AM a 8:00 PM, cada 2 horas)
        $horariosRestaurante = [
            '08:00' => '8:00 AM',
            '10:00' => '10:00 AM',
            '12:00' => '12:00 PM',
            '14:00' => '2:00 PM',
            '16:00' => '4:00 PM',
            '18:00' => '6:00 PM',
            '20:00' => '8:00 PM'
        ];

        // Obtener mesas que pueden acomodar el número de personas
        $mesas = Mesa::where('activa', true)
            ->where('capacidad', '>=', $this->numero_personas)
            ->get();

        $this->mesasDisponibles = [];

        foreach ($mesas as $mesa) {
            $horariosLibres = [];

            foreach ($horariosRestaurante as $horario => $etiqueta) {
                // Si es hoy, verificar que falten al menos 2 horas
                if ($esHoy) {
                    $horarioReserva = Carbon::parse($fecha->format('Y-m-d') . ' ' . $horario . ':00');
                    $horasRestantes = $horaActual->diffInHours($horarioReserva, false);

                    // Si el horario ya pasó o faltan menos de 2 horas, no mostrar
                    if ($horasRestantes < 2) {
                        continue;
                    }
                }

                // Verificar si la mesa está ocupada en este horario
                $ocupada = Reservacion::where('id_mesa', $mesa->id_mesa)
                    ->where('fecha_reservacion', $this->fecha_reservacion)
                    ->where('hora_reservacion', $horario . ':00')
                    ->whereIn('estado', ['pendiente', 'confirmada'])
                    ->exists();

                if (!$ocupada) {
                    $horariosLibres[] = [
                        'hora' => $horario,
                        'etiqueta' => $etiqueta
                    ];
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
    }

    public function crearReserva()
    {
        $this->validate();

        try {
            // Verificar nuevamente disponibilidad antes de crear
            $mesaOcupada = Reservacion::where('id_mesa', $this->id_mesa)
                ->where('fecha_reservacion', $this->fecha_reservacion)
                ->where('hora_reservacion', $this->hora_reservacion . ':00')
                ->whereIn('estado', ['pendiente', 'confirmada'])
                ->exists();

            if ($mesaOcupada) {
                session()->flash('error', 'Lo sentimos, esta mesa ya fue reservada. Por favor selecciona otra.');
                $this->verificarDisponibilidad();
                $this->hora_reservacion = null;
                $this->id_mesa = null;
                return;
            }

            $rutaComprobante = $this->comprobante_pago->store('comprobantes', 'public');

            $reservacion = Reservacion::create([
                'id_mesa' => $this->id_mesa,
                'id_usuario' => Auth::id(),
                'fecha_reservacion' => $this->fecha_reservacion,
                'hora_reservacion' => $this->hora_reservacion . ':00',
                'numero_personas' => $this->numero_personas,
                'estado' => 'pendiente',
                'observaciones' => $this->observaciones,
                'comprobante_pago' => $rutaComprobante,
                'monto_pago' => 30.00,
                'fecha_pago' => now(),
                'fecha_creacion' => now(),
                'fecha_actualizacion' => now()
            ]);

            $reservacion->generarCodigoQR();

            $this->reservaCreada = $reservacion->load('mesa');
            $this->showConfirmacion = true;

            session()->flash('success', '¡Reserva creada exitosamente! Espera la confirmación del administrador.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear la reserva: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset([
            'hora_reservacion',
            'observaciones',
            'id_mesa',
            'comprobante_pago',
            'showConfirmacion',
            'reservaCreada',
            'mesasDisponibles',
            'mostrarDisponibilidad'
        ]);
        $this->numero_personas = 2;
        $this->fecha_reservacion = Carbon::today()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.cliente.reservar-mesa');
    }
}
