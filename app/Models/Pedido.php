<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    protected $table = 'pedidos';
    protected $primaryKey = 'id_pedido';

    protected $fillable = [
        'numero_pedido',
        'id_usuario',
        'id_mesa',
        'id_reservacion',
        'estado',
        'tipo_consumo',
        'subtotal',
        'descuento',
        'total',
        'observaciones',
        'fecha_entrega'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'descuento' => 'decimal:2',
        'total' => 'decimal:2',
        'fecha_pedido' => 'datetime',
        'fecha_entrega' => 'datetime',
        'fecha_actualizacion' => 'datetime'
    ];

    // Relaciones
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function mesa(): BelongsTo
    {
        return $this->belongsTo(Mesa::class, 'id_mesa', 'id_mesa');
    }

    public function reservacion(): BelongsTo
    {
        return $this->belongsTo(Reservacion::class, 'id_reservacion', 'id_reservacion');
    }

    public function detallePedidos(): HasMany
    {
        return $this->hasMany(DetallePedido::class, 'id_pedido', 'id_pedido');
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeEnPreparacion($query)
    {
        return $query->where('estado', 'en_preparacion');
    }

    public function scopeListos($query)
    {
        return $query->where('estado', 'listo');
    }

    public function scopeEntregados($query)
    {
        return $query->where('estado', 'entregado');
    }

    public function scopeCancelados($query)
    {
        return $query->where('estado', 'cancelado');
    }

    public function scopePorUsuario($query, $usuarioId)
    {
        return $query->where('id_usuario', $usuarioId);
    }

    public function scopePorMesa($query, $mesaId)
    {
        return $query->where('id_mesa', $mesaId);
    }

    // Accessors
    public function getTotalFormateadoAttribute()
    {
        return 'Bs. ' . number_format($this->total, 2);
    }

    public function getSubtotalFormateadoAttribute()
    {
        return 'Bs. ' . number_format($this->subtotal, 2);
    }

    public function getCantidadProductosAttribute()
    {
        return $this->detallePedidos->sum('cantidad');
    }

    public function getTiempoPreparacionAttribute()
    {
        if ($this->estado === 'entregado' && $this->fecha_pedido && $this->fecha_entrega) {
            return $this->fecha_pedido->diffInMinutes($this->fecha_entrega);
        }
        return null;
    }

    // Methods
    public function calcularTotales()
    {
        $subtotal = $this->detallePedidos->sum('subtotal');
        $this->subtotal = $subtotal;
        $this->total = $subtotal - $this->descuento;
        $this->save();

        return $this->total;
    }

    public function puedeCancelar()
    {
        return in_array($this->estado, ['pendiente', 'en_preparacion']);
    }

    public function cambiarEstado($nuevoEstado)
    {
        $estadosPermitidos = ['pendiente', 'en_preparacion', 'listo', 'entregado', 'cancelado'];

        if (in_array($nuevoEstado, $estadosPermitidos)) {
            $this->estado = $nuevoEstado;

            if ($nuevoEstado === 'entregado') {
                $this->fecha_entrega = now();
            }

            $this->save();
            return true;
        }

        return false;
    }
}
