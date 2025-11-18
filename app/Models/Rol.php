<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
 protected $table = 'roles';
    protected $primaryKey = 'id_rol';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'fecha_creacion' => 'datetime'
    ];

    // RELACIÓN CORREGIDA: hasMany en lugar de belongsToMany
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_rol', 'id_rol');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }
}