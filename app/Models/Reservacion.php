<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservacion extends Model
{
    protected $table = 'reservaciones';
    protected $primaryKey = 'id_reservacion';
    public $timestamps = false;

    protected $fillable = [
        'id_mesa',
        'id_usuario',
        'fecha_reservacion',
        'hora_reservacion',
        'numero_personas',
        'estado',
        'observaciones',
        'comprobante_pago',
        'monto_pago',
        'fecha_pago',
        'fecha_confirmacion',
        'codigo_qr'
    ];

    protected $casts = [
        'fecha_reservacion' => 'date',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
        'fecha_pago' => 'datetime',
        'fecha_confirmacion' => 'datetime',
        'monto_pago' => 'decimal:2'
    ];

    // Accessor para hora_reservacion
    public function getHoraReservacionAttribute($value)
    {
        if (!$value) return null;
        return Carbon::createFromFormat('H:i:s', $value);
    }

    // Mutator para hora_reservacion
    public function setHoraReservacionAttribute($value)
    {
        if ($value instanceof Carbon) {
            $this->attributes['hora_reservacion'] = $value->format('H:i:s');
        } else {
            $this->attributes['hora_reservacion'] = $value;
        }
    }

    // Relaciones
    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'id_mesa', 'id_mesa');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_reserva', 'id_reservacion');
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeConfirmadas($query)
    {
        return $query->where('estado', 'confirmada');
    }

    public function scopeHoy($query)
    {
        return $query->whereDate('fecha_reservacion', today());
    }

    public function scopeActivas($query)
    {
        return $query->whereIn('estado', ['pendiente', 'confirmada']);
    }

    // Métodos de utilidad
    public function generarCodigoQR()
    {
        $this->codigo_qr = 'RES-' . str_pad($this->id_reservacion, 6, '0', STR_PAD_LEFT) . '-' . strtoupper(substr(md5($this->id_reservacion . $this->id_usuario), 0, 6));
        $this->save();
        return $this->codigo_qr;
    }

    public function estaConfirmada()
    {
        return $this->estado === 'confirmada';
    }

    public function estaPendiente()
    {
        return $this->estado === 'pendiente';
    }

    public function puedeSerCancelada()
    {
        return in_array($this->estado, ['pendiente', 'confirmada']);
    }

    // Verificar si la reserva ya pasó
    public function haPasado()
    {
        $fechaHora = Carbon::parse($this->fecha_reservacion->format('Y-m-d') . ' ' . $this->hora_reservacion->format('H:i:s'));
        return $fechaHora->isPast();
    }

    // Obtener horario formateado
    public function getHorarioFormateadoAttribute()
    {
        return $this->hora_reservacion->format('h:i A');
    }

    // Obtener fecha formateada
    public function getFechaFormateadaAttribute()
    {
        return $this->fecha_reservacion->format('d/m/Y');
    }
}
