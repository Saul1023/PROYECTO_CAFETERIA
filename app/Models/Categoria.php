<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'id_categoria';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'imagen_url',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'fecha_creacion' => 'datetime'
    ];

    // Relaciones
    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_categoria', 'id_categoria');
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('estado', true);
    }
}
