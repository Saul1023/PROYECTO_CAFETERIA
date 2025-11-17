<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'id_rol',
        'nombre_usuario',
        'password',
        'nombre_completo',
        'email',
        'telefono',
        'estado'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
        'ultimo_acceso' => 'datetime'
    ];

    // Para que Laravel use el campo correcto de contraseña
    public function getAuthPassword()
    {
        return $this->password;
    }

    // Relaciones

    public function reservaciones()
    {
        return $this->hasMany(Reservacion::class, 'id_usuario', 'id_usuario');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_usuario', 'id_usuario');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }
    // Métodos Helper para verificar roles
    public function esAdministrador()
    {
        return $this->id_rol && $this->rol->nombre === 'ADMINISTRADOR';
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function esEmpleado()
    {
        return $this->rol->nombre === 'EMPLEADO';
    }

    public function esCliente()
    {
        return $this->rol->nombre === 'CLIENTE';
    }

    public function scopePorRol($query, $nombreRol)
    {
        return $query->whereHas('rol', function($q) use ($nombreRol) {
            $q->where('nombre', $nombreRol);
        });
    }

    public function scopeAdministradores($query)
    {
        return $query->porRol('ADMINISTRADOR');
    }

    public function scopeEmpleados($query)
    {
        return $query->porRol('EMPLEADO');
    }

    public function scopeClientes($query)
    {
        return $query->porRol('CLIENTE');
    }
}
