<?php

namespace App\Livewire\Admin;

use App\Models\Mesa;
use App\Models\Producto;
use App\Models\Reservacion;
use App\Models\Usuario;
use App\Models\Venta;
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
            // Consulta más robusta
            $ventasHoy = Venta::with(['detalles.producto.categoria'])
                ->whereDate('fecha_venta', now()->toDateString())
                ->where('estado_venta', 'completada')
                ->get();

            Log::info('Ventas hoy - Consulta ejecutada');
            Log::info('Ventas encontradas: ' . $ventasHoy->count());
            Log::info('SQL: ' .
                Venta::with(['detalles.producto.categoria'])
                    ->whereDate('fecha_venta', now()->toDateString())
                    ->where('estado_venta', 'completada')
                    ->toSql()
            );

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
            // Verificar la estructura de tu modelo Reservacion
            $this->totalReservaciones = Reservacion::whereDate('fecha_reservacion', today())->count();
            Log::info('Reservaciones hoy: ' . $this->totalReservaciones);
        } catch (\Exception $e) {
            Log::error('Error cargando reservaciones: ' . $e->getMessage());
            $this->totalReservaciones = 0;
        }
    }

    protected function cargarProductosMasVendidos()
    {
        try {
            // Reemplazar scopes por consulta directa
            $ventasHoy = Venta::with(['detalles.producto.categoria'])
                ->whereDate('fecha_venta', now()->toDateString())
                ->where('estado_venta', 'completada')
                ->get();

            Log::info('Ventas para productos más vendidos: ' . $ventasHoy->count());

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

            // Ordenar y tomar top 5
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

    // Método para debugging
    public function debugDatos()
    {
        try {
            // Verificar ventas en la base de datos
            $ventasHoyDB = Venta::whereDate('fecha_venta', today())->get();
            $ventasCompletadasHoyDB = Venta::hoy()->completadas()->get();

            Log::info('=== DEBUG DASHBOARD ===');
            Log::info('Total ventas hoy en BD: ' . $ventasHoyDB->count());
            Log::info('Ventas completadas hoy en BD: ' . $ventasCompletadasHoyDB->count());
            Log::info('Estado de ventas hoy: ' . $ventasHoyDB->pluck('estado_venta'));

            return [
                'total_ventas_hoy' => $ventasHoyDB->count(),
                'ventas_completadas_hoy' => $ventasCompletadasHoyDB->count(),
                'estados_ventas' => $ventasHoyDB->pluck('estado_venta'),
                'datos_cargados' => [
                    'ventas_hoy' => $this->ventasHoy,
                    'productos_mas_vendidos_count' => count($this->productosMasVendidos),
                    'reservaciones' => $this->totalReservaciones
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Error en debug: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
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

    public function debugBaseDeDatos()
    {
        try {
            Log::info('=== DEBUG BASE DE DATOS ===');

            // Verificar ventas en la base de datos
            $ventasHoy = Venta::whereDate('fecha_venta', now()->toDateString())->get();
            $ventasCompletadas = Venta::whereDate('fecha_venta', now()->toDateString())
                                    ->where('estado_venta', 'completada')
                                    ->get();

            Log::info('Total ventas hoy: ' . $ventasHoy->count());
            Log::info('Ventas completadas hoy: ' . $ventasCompletadas->count());

            // Verificar estructura de ventas
            foreach ($ventasHoy as $venta) {
                Log::info("Venta ID: {$venta->id_venta}, Estado: {$venta->estado_venta}, Fecha: {$venta->fecha_venta}, Total: {$venta->total}");
            }

            // Verificar productos
            $productos = Producto::activos()->count();
            Log::info('Productos activos: ' . $productos);

            // Verificar mesas
            $mesas = Mesa::count();
            Log::info('Total mesas: ' . $mesas);

            return [
                'ventas_hoy_total' => $ventasHoy->count(),
                'ventas_completadas' => $ventasCompletadas->count(),
                'productos_activos' => $productos,
                'total_mesas' => $mesas
            ];

        } catch (\Exception $e) {
            Log::error('Error en debug BD: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
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