<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promocion extends Model
{
     protected $table = 'promociones';
    protected $primaryKey = 'id_promocion';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo_descuento',
        'valor_descuento',
        'fecha_inicio',
        'fecha_fin',
        'estado'
    ];

    protected $casts = [
        'valor_descuento' => 'decimal:2',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'estado' => 'boolean',
        'fecha_creacion' => 'datetime'
    ];

    // Relaciones - CORREGIDAS
    public function productos()
    {
        return $this->belongsToMany(
            Producto::class,
            'promocion_producto',
            'id_promocion',
            'id_producto',
            'id_promocion',
            'id_producto' // ← Especificar la clave del producto
        );
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('estado', true);
    }

    public function scopeVigentes($query)
    {
        $hoy = Carbon::today();
        return $query->where('fecha_inicio', '<=', $hoy)
                    ->where('fecha_fin', '>=', $hoy)
                    ->where('estado', true);
    }
}