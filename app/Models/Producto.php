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
}
