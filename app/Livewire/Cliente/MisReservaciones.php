<?php

namespace App\Livewire\Cliente;

use App\Models\Reservacion;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class MisReservaciones extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $filterEstado = '';
    public $perPage = 6;
    public $reservacionDetalle = null;
    public $showModal = false;
    public $reservaToCancelar = null;
    public $mostrarAlertaContacto = false;
    public $horasRestantes = 0;
    public $showModalCancelar = false;

    public function verDetalle($id)
    {
        $this->reservacionDetalle = Reservacion::with('mesa')
            ->where('id_reservacion', $id)
            ->where('id_usuario', Auth::id())
            ->firstOrFail();

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reservacionDetalle = null;
    }

    public function confirmCancelar($id)
    {
        $reserva = Reservacion::findOrFail($id);

        // Verificar si faltan más de 2 horas para la reserva
        $fecha = Carbon::parse($reserva->fecha_reservacion)->format('Y-m-d');
        $hora = Carbon::parse($reserva->hora_reservacion)->format('H:i:s');

        $fechaHoraReserva = Carbon::parse($fecha . ' ' . $hora);
        $horasRestantes = Carbon::now()->diffInHours($fechaHoraReserva, false);

        $this->horasRestantes = $horasRestantes;
        $this->reservaToCancelar = $id;

        // Si faltan menos de 2 horas, mostrar alerta de contacto
        if ($horasRestantes < 2) {
            $this->mostrarAlertaContacto = true;
        } else {
            $this->mostrarAlertaContacto = false;
        }

        // IMPORTANTE: Abrir el modal
        $this->showModalCancelar = true;
    }

    public function closeModalCancelar()
    {
        $this->showModalCancelar = false;
        $this->reservaToCancelar = null;
        $this->mostrarAlertaContacto = false;
    }

    public function cancelarReserva()
    {
        try {
            $reserva = Reservacion::where('id_reservacion', $this->reservaToCancelar)
                ->where('id_usuario', Auth::id())
                ->whereIn('estado', ['pendiente', 'confirmada'])
                ->firstOrFail();

            // Verificar nuevamente las 2 horas antes de cancelar
            $fecha = Carbon::parse($reserva->fecha_reservacion)->format('Y-m-d');
            $hora = Carbon::parse($reserva->hora_reservacion)->format('H:i:s');

            $fechaHoraReserva = Carbon::parse($fecha . ' ' . $hora);
            $horasRestantes = Carbon::now()->diffInHours($fechaHoraReserva, false);

            if ($horasRestantes < 2) {
                session()->flash('error', 'No se puede cancelar la reserva. Deben faltar al menos 2 horas para la hora reservada. Por favor contacte al restaurante.');
                $this->closeModalCancelar();
                return;
            }

            $reserva->estado = 'cancelada';
            $reserva->save();

            session()->flash('success', 'Reserva cancelada correctamente. Se realizará la devolución del 60% del monto pagado (Bs. ' . number_format($reserva->monto_pago * 0.6, 2) . ').');
            $this->closeModalCancelar();
        } catch (\Exception $e) {
            session()->flash('error', 'No se pudo cancelar la reserva. Intente nuevamente: ' . $e->getMessage());
            $this->closeModalCancelar();
        }
    }

    public function render()
    {
        $query = Reservacion::with('mesa')
            ->where('id_usuario', Auth::id())
            ->when($this->filterEstado, function ($q) {
                $q->where('estado', $this->filterEstado);
            })
            ->orderBy('fecha_reservacion', 'desc')
            ->orderBy('hora_reservacion', 'desc');

        $reservaciones = $query->paginate($this->perPage);

        return view('livewire.cliente.mis-reservaciones', [
            'reservaciones' => $reservaciones
        ]);
    }
}
