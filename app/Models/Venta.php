<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';
    protected $primaryKey = 'id_venta';
    public $timestamps = false;

    protected $fillable = [
        'numero_venta',
        'id_usuario',
        'id_cliente',
        'id_reserva',
        'subtotal',
        'descuento',
        'total',
        'metodo_pago',
        'estado_venta',
        'observaciones',
        'fecha_venta'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'descuento' => 'decimal:2',
        'total' => 'decimal:2',
        'fecha_venta' => 'datetime',
        'fecha_actualizacion' => 'datetime'
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function reservacion()
    {
        return $this->belongsTo(Reservacion::class, 'id_reserva', 'id_reservacion');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'id_venta', 'id_venta');
    }

    // Scopes
    public function scopeCompletadas($query)
    {
        return $query->where('estado_venta', 'completada');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado_venta', 'pendiente');
    }

    public function scopeHoy($query)
    {
        return $query->whereDate('fecha_venta', today());
    }

    public function scopePorFecha($query, $fecha)
    {
        return $query->whereDate('fecha_venta', $fecha);
    }
    public function scopePorCliente($query,$clienteId){
        return $query->where('id_cliente',$clienteId);
    }
}
