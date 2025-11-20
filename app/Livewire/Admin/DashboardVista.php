<?php

namespace App\Livewire\Admin;

use App\Models\DetalleVenta;
use App\Models\Mesa;
use App\Models\Producto;
use App\Models\Reservacion;
use App\Models\Usuario;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\Support\Str;

class DashboardVista extends Component
{
    public $ventasHoy = ['cantidad' => 0, 'monto' => 0];
    public $productosMasVendidos = [];
    public $totalReservaciones = 0;
    public $mesasDisponibles = ['disponibles' => 0, 'total' => 0, 'porcentaje' => 0];
    public $totalProductos = 0;
    public $stockBajo = 0;
    public $totalUsuarios = 0;
    public $promocionesActivas = 0;
    public $ingresoMensual = 0;

    public function mount()
    {
        $this->cargarEstadisticas();
    }

    public function cargarEstadisticas()
    {
        try {
            // Ventas de hoy
            $this->cargarVentasHoy();

            // Productos más vendidos
            $this->cargarProductosMasVendidos();

            // Reservaciones de hoy
            $this->cargarReservacionesHoy();

            // Mesas disponibles
            $this->cargarMesasDisponibles();

            // Productos y stock bajo
            $this->cargarProductosStock();

            // Estadísticas solo para admin
            if (auth()->user()->esAdministrador()) {
                $this->cargarEstadisticasAdmin();
            }

        } catch (\Exception $e) {
            Log::error('Error en DashboardVista: ' . $e->getMessage());
            $this->setValoresPorDefecto();
        }
    }

    protected function cargarVentasHoy()
    {
        try {
            $ventasHoy = Venta::hoy()->completadas()->get();
            $this->ventasHoy = [
                'cantidad' => $ventasHoy->count(),
                'monto' => $ventasHoy->sum('total')
            ];
        } catch (\Exception $e) {
            Log::error('Error cargando ventas hoy: ' . $e->getMessage());
            $this->ventasHoy = ['cantidad' => 0, 'monto' => 0];
        }
    }

    protected function cargarReservacionesHoy()
    {
        try {
            $this->totalReservaciones = Reservacion::hoy()->count();
        } catch (\Exception $e) {
            Log::error('Error cargando reservaciones: ' . $e->getMessage());
            $this->totalReservaciones = 0;
        }
    }

    protected function cargarProductosMasVendidos()
    {
        try {
            // Obtener ventas de hoy completadas con detalles
            $ventasHoy = Venta::with(['detalles.producto.categoria'])
                ->hoy()
                ->completadas()
                ->get();

            $productosVendidos = [];

            foreach ($ventasHoy as $venta) {
                foreach ($venta->detalles as $detalle) {
                    if ($detalle->producto) {
                        $producto = $detalle->producto;
                        $productoId = $producto->id_producto;

                        if (!isset($productosVendidos[$productoId])) {
                            $productosVendidos[$productoId] = [
                                'id_producto' => $productoId,
                                'nombre' => $producto->nombre,
                                'imagen' => $producto->imagen,
                                'categoria_nombre' => $producto->categoria->nombre ?? 'Sin categoría',
                                'total_vendido' => 0,
                                'total_recaudado' => 0
                            ];
                        }

                        $productosVendidos[$productoId]['total_vendido'] += $detalle->cantidad;
                        $productosVendidos[$productoId]['total_recaudado'] += $detalle->subtotal;
                    }
                }
            }

            // Ordenar por cantidad vendida y tomar top 5
            usort($productosVendidos, function($a, $b) {
                return $b['total_vendido'] - $a['total_vendido'];
            });

            $this->productosMasVendidos = array_slice($productosVendidos, 0, 5);

        } catch (\Exception $e) {
            Log::error('Error cargando productos más vendidos: ' . $e->getMessage());
            $this->productosMasVendidos = [];
        }
    }

    protected function cargarMesasDisponibles()
    {
        try {
            $totalMesas = Mesa::count();
            $mesasOcupadas = Mesa::where('estado', 'ocupada')->count();
            $this->mesasDisponibles = [
                'disponibles' => $totalMesas - $mesasOcupadas,
                'total' => $totalMesas,
                'porcentaje' => $totalMesas > 0 ? round(($mesasOcupadas / $totalMesas) * 100) : 0
            ];
        } catch (\Exception $e) {
            Log::error('Error cargando mesas: ' . $e->getMessage());
            $this->mesasDisponibles = ['disponibles' => 0, 'total' => 0, 'porcentaje' => 0];
        }
    }

    protected function cargarProductosStock()
    {
        try {
            $this->totalProductos = Producto::activos()->count();

            // Versión simple - contar productos con stock menor o igual a 5
            $this->stockBajo = Producto::activos()
                ->where('stock', '<=', 5)
                ->count();

        } catch (\Exception $e) {
            Log::error('Error cargando productos: ' . $e->getMessage());
            $this->totalProductos = 0;
            $this->stockBajo = 0;
        }
    }

    protected function cargarEstadisticasAdmin()
    {
        try {
            $this->totalUsuarios = Usuario::where('estado', true)->count();

            if (class_exists(\App\Models\Promocion::class)) {
                $this->promocionesActivas = \App\Models\Promocion::where('estado', true)
                    ->where('fecha_inicio', '<=', now())
                    ->where('fecha_fin', '>=', now())
                    ->count();
            }

            $this->ingresoMensual = Venta::completadas()
                ->whereYear('fecha_venta', now()->year)
                ->whereMonth('fecha_venta', now()->month)
                ->sum('total');

        } catch (\Exception $e) {
            Log::error('Error cargando estadísticas admin: ' . $e->getMessage());
            $this->totalUsuarios = 0;
            $this->promocionesActivas = 0;
            $this->ingresoMensual = 0;
        }
    }

    protected function setValoresPorDefecto()
    {
        $this->ventasHoy = ['cantidad' => 0, 'monto' => 0];
        $this->productosMasVendidos = [];
        $this->totalReservaciones = 0;
        $this->mesasDisponibles = ['disponibles' => 0, 'total' => 0, 'porcentaje' => 0];
        $this->totalProductos = 0;
        $this->stockBajo = 0;

        if (auth()->user()->esAdministrador()) {
            $this->totalUsuarios = 0;
            $this->promocionesActivas = 0;
            $this->ingresoMensual = 0;
        }
    }

    // Método para recargar datos
    public function recargar()
    {
        $this->cargarEstadisticas();
        $this->dispatch('estadisticasActualizadas');
    }

    /**
     * Verifica si la imagen es válida
     */
    public function isValidImage($imagePath)
    {
        if (empty($imagePath)) {
            return false;
        }

        // Si es una URL
        if (Str::startsWith($imagePath, ['http://', 'https://'])) {
            return true;
        }

        // Si es una ruta de almacenamiento
        if (Storage::exists($imagePath)) {
            return true;
        }

        // Si el archivo existe en public path
        if (file_exists(public_path($imagePath))) {
            return true;
        }

        return false;
    }

    /**
     * Obtiene la URL correcta de la imagen
     */
    public function getImageUrl($imagePath)
    {
        if (empty($imagePath)) {
            return null;
        }

        // Si es una URL completa
        if (Str::startsWith($imagePath, ['http://', 'https://'])) {
            return $imagePath;
        }

        // Si es una ruta de almacenamiento
        if (Storage::exists($imagePath)) {
            return Storage::url($imagePath);
        }

        // Si existe en public path
        if (file_exists(public_path($imagePath))) {
            return asset($imagePath);
        }

        return null;
    }

    public function render()
    {
        return view('livewire.admin.dashboard-vista')
            ->layout('layouts.admin', [
                'title' => 'Dashboard',
                'pageTitle' => 'Dashboard'
            ]);
    }
}
