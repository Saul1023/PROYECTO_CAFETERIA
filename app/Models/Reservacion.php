<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'observaciones'
    ];

    protected $casts = [
        'fecha_reservacion' => 'datetime',
        'hora_reservacion' => 'datetime',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime'
    ];

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
}
