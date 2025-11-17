<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $table = 'mesas';
    protected $primaryKey = 'id_mesa';
    public $timestamps = false;

    protected $fillable = [
        'numero_mesa',
        'capacidad',
        'estado',
        'activa',
        'ubicacion'
    ];

    protected $casts = [
        'activa' => 'boolean',
        'fecha_creacion' => 'datetime'
    ];

    // Relaciones
    public function reservaciones()
    {
        return $this->hasMany(Reservacion::class, 'id_mesa', 'id_mesa');
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    public function scopeDisponibles($query)
    {
        return $query->where('estado', 'disponible');
    }
}
