<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetallePedido extends Model
{
    protected $table = 'detalle_pedido';
    protected $primaryKey = 'id_detalle_pedido';

    // AGREGAR ESTA LÍNEA
    public $timestamps = false;

    protected $fillable = [
        'id_pedido',
        'id_producto',
        'cantidad',
        'precio_unitario',
        'descuento',
        'subtotal',
        'estado_preparacion',
        'observaciones'
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'descuento' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'cantidad' => 'integer',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime'
    ];

    // Relaciones
    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado_preparacion', 'pendiente');
    }

    public function scopeEnPreparacion($query)
    {
        return $query->where('estado_preparacion', 'en_preparacion');
    }

    public function scopeListos($query)
    {
        return $query->where('estado_preparacion', 'listo');
    }

    public function scopeEntregados($query)
    {
        return $query->where('estado_preparacion', 'entregado');
    }

    public function scopePorPedido($query, $pedidoId)
    {
        return $query->where('id_pedido', $pedidoId);
    }

    public function scopePorProducto($query, $productoId)
    {
        return $query->where('id_producto', $productoId);
    }

    // Accessors
    public function getSubtotalFormateadoAttribute()
    {
        return 'Bs. ' . number_format($this->subtotal, 2);
    }

    public function getPrecioUnitarioFormateadoAttribute()
    {
        return 'Bs. ' . number_format($this->precio_unitario, 2);
    }

    // Methods
    public function calcularSubtotal()
    {
        $this->subtotal = ($this->cantidad * $this->precio_unitario) - $this->descuento;
        $this->save();

        return $this->subtotal;
    }

    public function actualizarStockProducto()
    {
        if ($this->producto) {
            $this->producto->stock -= $this->cantidad;
            $this->producto->save();
        }
    }

    public function puedeModificar()
    {
        return in_array($this->estado_preparacion, ['pendiente', 'en_preparacion']);
    }

    public function cambiarEstadoPreparacion($nuevoEstado)
    {
        $estadosPermitidos = ['pendiente', 'en_preparacion', 'listo', 'entregado'];

        if (in_array($nuevoEstado, $estadosPermitidos)) {
            $this->estado_preparacion = $nuevoEstado;
            $this->save();
            return true;
        }

        return false;
    }

    // Boot method para calcular subtotal automáticamente
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->cantidad && $model->precio_unitario) {
                $model->subtotal = ($model->cantidad * $model->precio_unitario) - $model->descuento;
            }
        });
        static::updated(function ($model) {
            // Recalcular total del pedido si cambia cantidad o precio
            if ($model->pedido && ($model->isDirty('cantidad') || $model->isDirty('precio_unitario') || $model->isDirty('descuento'))) {
                $model->pedido->calcularTotales();
            }
        });

        static::deleted(function ($model) {
            // Recalcular total del pedido cuando se elimina un detalle
            if ($model->pedido) {
                $model->pedido->calcularTotales();
            }
        });
    }
}
