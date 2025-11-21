<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $primaryKey = 'id_categoria';

    // Deshabilitar timestamps automáticos
    public $timestamps = false;

    // Especificar el nombre de la columna de fecha de creación
    const CREATED_AT = 'fecha_creacion';

    protected $fillable = [
        'nombre',
        'descripcion',
        'imagen_url',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    // Accessor para la URL completa de la imagen
    public function getImagenUrlCompletaAttribute()
    {
        if ($this->imagen_url) {
            return asset('storage/' . $this->imagen_url);
        }
        return null;
    }

    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_categoria');
    }
}