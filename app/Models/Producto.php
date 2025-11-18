<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Producto extends Model
{
protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    public $timestamps = false;

    protected $fillable = [
        'id_categoria',
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'stock_minimo',
        'imagen',
        'estado'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'estado' => 'boolean',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime'
    ];

    // Relaciones
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function getImagenUrlAttribute()
    {
        return $this->imagen;
    }

    public function detallePedidos(): HasMany
    {
        return $this->hasMany(DetallePedido::class, 'id_producto', 'id_producto');
    }

    public function promociones(): BelongsToMany
    {
        return $this->belongsToMany(
            Promocion::class,
            'promocion_producto',
            'id_producto',
            'id_promocion',
            'id_producto',
            'id_promocion'
        );
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }

    public function scopeConStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeStockBajo($query)
    {
        return $query->whereColumn('stock', '<=', 'stock_minimo');
    }

    public function scopeConPromocionesActivas($query)
    {
        return $query->whereHas('promociones', function($q) {
            $q->where('estado', true)
              ->where('fecha_inicio', '<=', now())
              ->where('fecha_fin', '>=', now());
        });
    }

    // Accessors
    public function getPrecioFormateadoAttribute()
    {
        return 'Bs. ' . number_format($this->precio, 2);
    }

    public function getTieneStockAttribute()
    {
        return $this->stock > 0;
    }

    public function getStockBajoAttribute()
    {
        return $this->stock <= $this->stock_minimo;
    }

    // Nuevos accessors para promociones
    public function getPromocionActivaAttribute()
    {
        return $this->promociones()
            ->where('estado', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->first();
    }

    public function getTienePromocionAttribute()
    {
        return $this->promocion_activa !== null;
    }

    public function getPrecioConDescuentoAttribute()
    {
        $promocion = $this->promocion_activa;

        if ($promocion) {
            if ($promocion->tipo_descuento === 'porcentaje') {
                return $this->precio * (1 - ($promocion->valor_descuento / 100));
            } else { // descuento fijo
                return max(0, $this->precio - $promocion->valor_descuento);
            }
        }

        return $this->precio;
    }

    public function getDescuentoAplicadoAttribute()
    {
        $promocion = $this->promocion_activa;

        if ($promocion) {
            if ($promocion->tipo_descuento === 'porcentaje') {
                return $promocion->valor_descuento . '%';
            } else {
                return 'Bs. ' . number_format($promocion->valor_descuento, 2);
            }
        }

        return null;
    }

    public function getAhorroAttribute()
    {
        return $this->precio - $this->precio_con_descuento;
    }

    public function getAhorroFormateadoAttribute()
    {
        return 'Bs. ' . number_format($this->ahorro, 2);
    }
}