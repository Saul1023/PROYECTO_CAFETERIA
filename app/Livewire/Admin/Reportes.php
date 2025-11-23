<?php

namespace App\Livewire\Admin;

use App\Models\Usuario;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Promocion;
use App\Models\Mesa;
use App\Models\Reservacion;
use App\Models\Venta;
use Carbon\Carbon;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Reportes extends Component
{
    public $tipoReporte = 'general';
    public $periodoReporte = 'diario';
    public $fechaInicio;
    public $fechaFin;

    // Datos para los reportes
    public $reporteData = [];
    public $estadisticas = [];

    public function mount()
    {
        // Establecer fechas por defecto
        $this->fechaInicio = Carbon::today()->format('Y-m-d');
        $this->fechaFin = Carbon::today()->format('Y-m-d');
    }

    public function updatedPeriodoReporte()
    {
        // Actualizar fechas según el periodo seleccionado
        switch ($this->periodoReporte) {
            case 'diario':
                $this->fechaInicio = Carbon::today()->format('Y-m-d');
                $this->fechaFin = Carbon::today()->format('Y-m-d');
                break;
            case 'semanal':
                $this->fechaInicio = Carbon::now()->startOfWeek()->format('Y-m-d');
                $this->fechaFin = Carbon::now()->endOfWeek()->format('Y-m-d');
                break;
            case 'mensual':
                $this->fechaInicio = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->fechaFin = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
        }
    }

    public function generarReporte()
    {
        $this->reporteData = [];
        $this->estadisticas = [];

        switch ($this->tipoReporte) {
            case 'usuarios':
                $this->generarReporteUsuarios();
                break;
            case 'productos':
                $this->generarReporteProductos();
                break;
            case 'categorias':
                $this->generarReporteCategorias();
                break;
            case 'promociones':
                $this->generarReportePromociones();
                break;
            case 'mesas':
                $this->generarReporteMesas();
                break;
            case 'reservaciones':
                $this->generarReporteReservaciones();
                break;
            case 'ventas':
                $this->generarReporteVentas();
                break;
            case 'general':
                $this->generarReporteGeneral();
                break;
        }
    }

    public function updatedTipoReporte($value)
    {
        // Limpiar datos anteriores cuando se cambia el tipo de reporte
        $this->reporteData = [];
        $this->estadisticas = [];

        // También puedes resetear las fechas si es necesario
        $this->updatedPeriodoReporte();
    }

    private function generarReporteUsuarios()
    {
        try {
            Log::info('=== INICIANDO REPORTE USUARIOS ===');

            // CONSULTA CORREGIDA - Usar nombre_completo en lugar de nombre
            $this->reporteData = Usuario::with(['rol' => function($query) {
                    $query->select('id_rol', 'nombre');
                }])
                ->select([
                    'id_usuario',
                    'nombre_completo',  // ← CORREGIDO
                    'nombre_usuario',   // ← CORREGIDO
                    'email',
                    'id_rol',
                    'estado',
                    'fecha_creacion'
                ])
                ->whereBetween('fecha_creacion', [$this->fechaInicio, $this->fechaFin . ' 23:59:59'])
                ->get();

            Log::info('Usuarios obtenidos: ' . $this->reporteData->count());

            // Debug
            if ($this->reporteData->count() > 0) {
                $primerUsuario = $this->reporteData->first();
                Log::info('Primer usuario:', $primerUsuario->toArray());
            }

            // Estadísticas
            $this->estadisticas = [
                'total' => $this->reporteData->count(),
                'activos' => $this->reporteData->where('estado', true)->count(),
                'inactivos' => $this->reporteData->where('estado', false)->count(),
                'por_rol' => $this->reporteData->groupBy('id_rol')->map(function($group) {
                    return [
                        'total' => $group->count(),
                        'rol_nombre' => $group->first()->rol->nombre ?? 'Sin rol'
                    ];
                })
            ];

            Log::info('=== FIN REPORTE USUARIOS ===');

        } catch (\Exception $e) {
            Log::error('Error en generarReporteUsuarios: ' . $e->getMessage());

            $this->reporteData = collect();
            $this->estadisticas = [
                'total' => 0,
                'activos' => 0,
                'inactivos' => 0,
                'por_rol' => []
            ];
        }
    }

    private function generarReporteProductos()
    {
        $query = Producto::with('categoria')
            ->whereBetween('fecha_creacion', [$this->fechaInicio, $this->fechaFin . ' 23:59:59']);

        $this->reporteData = $query->get();

        $this->estadisticas = [
            'total' => $query->count(),
            'activos' => $query->where('estado', true)->count(),
            'sin_stock' => $query->where('stock', 0)->count(),
            'stock_bajo' => $query->whereColumn('stock', '<=', 'stock_minimo')->count(),
            'valor_inventario' => $query->sum(DB::raw('precio * stock')),
            'por_categoria' => Producto::select('id_categoria', DB::raw('count(*) as total'))
                ->whereBetween('fecha_creacion', [$this->fechaInicio, $this->fechaFin . ' 23:59:59'])
                ->groupBy('id_categoria')
                ->with('categoria')
                ->get()
        ];
    }

    private function generarReporteCategorias()
    {
        // Quitar el filtro de fecha para mostrar todas las categorías
        $this->reporteData = Categoria::withCount('productos')->get();

        $this->estadisticas = [
            'total' => Categoria::count(),
            'activas' => Categoria::where('estado', true)->count(),
            'con_productos' => Categoria::has('productos')->count(),
            'sin_productos' => Categoria::doesntHave('productos')->count()
        ];
    }

    private function generarReportePromociones()
    {
        $query = Promocion::with('productos')
            ->whereBetween('fecha_inicio', [$this->fechaInicio, $this->fechaFin]);

        $this->reporteData = $query->get();

        $hoy = Carbon::today();
        $this->estadisticas = [
            'total' => $query->count(),
            'activas' => $query->where('estado', true)->count(),
            'vigentes' => $query->where('fecha_inicio', '<=', $hoy)
                ->where('fecha_fin', '>=', $hoy)
                ->where('estado', true)
                ->count(),
            'expiradas' => $query->where('fecha_fin', '<', $hoy)->count(),
            'futuras' => $query->where('fecha_inicio', '>', $hoy)->count()
        ];
    }

    private function generarReporteMesas()
    {
        $this->reporteData = Mesa::withCount([
            'reservaciones' => function($query) {
                $query->whereBetween('fecha_reservacion', [$this->fechaInicio, $this->fechaFin]);
            }
        ])->get();

        $reservacionesHoy = Reservacion::whereBetween('fecha_reservacion', [$this->fechaInicio, $this->fechaFin])
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->count();

        $this->estadisticas = [
            'total' => Mesa::count(),
            'activas' => Mesa::where('activa', true)->count(),
            'disponibles' => Mesa::where('estado', 'disponible')->count(),
            'ocupadas' => Mesa::where('estado', 'ocupada')->count(),
            'reservadas' => Mesa::where('estado', 'reservada')->count(),
            'reservaciones_periodo' => $reservacionesHoy,
            'capacidad_total' => Mesa::sum('capacidad'),
            'por_ubicacion' => Mesa::select('ubicacion', DB::raw('count(*) as total'))
                ->groupBy('ubicacion')
                ->get()
        ];
    }

    private function generarReporteReservaciones()
    {
        $query = Reservacion::with(['mesa', 'usuario'])
            ->whereBetween('fecha_reservacion', [$this->fechaInicio, $this->fechaFin]);

        $this->reporteData = $query->orderBy('fecha_reservacion', 'desc')->get();

        $this->estadisticas = [
            'total' => $query->count(),
            'pendientes' => $query->where('estado', 'pendiente')->count(),
            'confirmadas' => $query->where('estado', 'confirmada')->count(),
            'completadas' => $query->where('estado', 'completada')->count(),
            'canceladas' => $query->where('estado', 'cancelada')->count(),
            'no_asistio' => $query->where('estado', 'no_asistio')->count(),
            'personas_total' => $query->sum('numero_personas'),
            'promedio_personas' => round($query->avg('numero_personas'), 2)
        ];
    }

    private function generarReporteVentas()
    {
        try {
            Log::info('=== INICIANDO REPORTE VENTAS ===');
            Log::info('Fechas:', ['inicio' => $this->fechaInicio, 'fin' => $this->fechaFin]);

            // Obtener datos para la tabla
            $this->reporteData = Venta::with(['usuario', 'detalles.producto'])
                ->whereBetween('fecha_venta', [$this->fechaInicio, $this->fechaFin])
                ->orderBy('fecha_venta', 'desc')
                ->get();

            Log::info('Total registros encontrados: ' . $this->reporteData->count());

            // Debug del primer elemento
            if ($this->reporteData->count() > 0) {
                $primerElemento = $this->reporteData->first();
                Log::info('Tipo del primer elemento: ' . gettype($primerElemento));
                Log::info('Clase del primer elemento: ' . (is_object($primerElemento) ? get_class($primerElemento) : 'No es objeto'));
                Log::info('Valor del primer elemento: ' . json_encode($primerElemento));
            } else {
                Log::info('No se encontraron ventas en el período');
            }

            // Obtener estadísticas
            $estadisticasBase = Venta::whereBetween('fecha_venta', [$this->fechaInicio, $this->fechaFin])
                ->select(
                    DB::raw('COUNT(*) as total_ventas'),
                    DB::raw('SUM(total) as ingresos_totales'),
                    DB::raw('SUM(subtotal) as subtotal'),
                    DB::raw('SUM(descuento) as descuentos'),
                    DB::raw('AVG(total) as ticket_promedio')
                )
                ->first();

            $this->estadisticas = [
                'total_ventas' => $estadisticasBase->total_ventas ?? 0,
                'ingresos_totales' => $estadisticasBase->ingresos_totales ?? 0,
                'subtotal' => $estadisticasBase->subtotal ?? 0,
                'descuentos' => $estadisticasBase->descuentos ?? 0,
                'ticket_promedio' => round($estadisticasBase->ticket_promedio ?? 0, 2),
                'por_metodo_pago' => Venta::select('metodo_pago', DB::raw('count(*) as total'), DB::raw('sum(total) as monto'))
                    ->whereBetween('fecha_venta', [$this->fechaInicio, $this->fechaFin])
                    ->groupBy('metodo_pago')
                    ->get(),
                'productos_vendidos' => DB::table('detalle_venta')
                    ->join('ventas', 'detalle_venta.id_venta', '=', 'ventas.id_venta')
                    ->whereBetween('ventas.fecha_venta', [$this->fechaInicio, $this->fechaFin])
                    ->sum('detalle_venta.cantidad')
            ];

            Log::info('=== FIN REPORTE VENTAS ===');

        } catch (\Exception $e) {
            Log::error('Error en generarReporteVentas: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());

            $this->reporteData = [];
            $this->estadisticas = [
                'total_ventas' => 0,
                'ingresos_totales' => 0,
                'subtotal' => 0,
                'descuentos' => 0,
                'ticket_promedio' => 0,
                'por_metodo_pago' => [],
                'productos_vendidos' => 0
            ];
        }
    }

    private function generarReporteGeneral()
    {
        try {
            $this->estadisticas = [
                'ventas' => [
                    'total' => Venta::whereBetween('fecha_venta', [$this->fechaInicio, $this->fechaFin])->count(),
                    'ingresos' => Venta::whereBetween('fecha_venta', [$this->fechaInicio, $this->fechaFin])->sum('total') ?? 0
                ],
                'reservaciones' => [
                    'total' => Reservacion::whereBetween('fecha_reservacion', [$this->fechaInicio, $this->fechaFin])->count(),
                    'confirmadas' => Reservacion::whereBetween('fecha_reservacion', [$this->fechaInicio, $this->fechaFin])
                        ->where('estado', 'confirmada')->count()
                ],
                'productos' => [
                    'total' => Producto::count(),
                    'stock_bajo' => Producto::whereColumn('stock', '<=', 'stock_minimo')->count()
                ],
                'usuarios' => [
                    'total' => Usuario::count(),
                    'nuevos' => Usuario::whereBetween('fecha_creacion', [$this->fechaInicio, $this->fechaFin])->count()
                ]
            ];

            // Asegurar que reporteData no esté vacío para el PDF
            $this->reporteData = [
                'general' => true,
                'periodo' => [
                    'inicio' => $this->fechaInicio,
                    'fin' => $this->fechaFin
                ]
            ];

        } catch (\Exception $e) {
            // En caso de error, establecer valores por defecto
            $this->estadisticas = [
                'ventas' => ['total' => 0, 'ingresos' => 0],
                'reservaciones' => ['total' => 0, 'confirmadas' => 0],
                'productos' => ['total' => 0, 'stock_bajo' => 0],
                'usuarios' => ['total' => 0, 'nuevos' => 0]
            ];
            $this->reporteData = ['general' => true];
        }
    }

    public function exportarPDF()
    {
        if (empty($this->reporteData)) {
            session()->flash('error', 'Primero genere un reporte');
            return;
        }

        // Determinar qué vista usar según el tipo de reporte
        $vista = $this->obtenerVistaPDF();

        $pdf = Pdf::loadView($vista, [
            'reporteData'   => $this->reporteData,
            'estadisticas'  => $this->estadisticas,
            'fechaInicio'   => $this->fechaInicio,
            'fechaFin'      => $this->fechaFin,
            'tipoReporte'   => $this->tipoReporte
        ])->setPaper('A4', 'portrait')
        ->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans'
        ]);

        $nombreArchivo = $this->generarNombreArchivo();

        return response()->streamDownload(
            fn() => print($pdf->output()),
            $nombreArchivo
        );
    }

    private function obtenerVistaPDF()
    {
        // Mapear tipos de reporte a sus vistas específicas
        $vistas = [
            'general' => 'pdf.reportes.general',
            'usuarios' => 'pdf.reportes.usuarios',
            'productos' => 'pdf.reportes.productos',
            'categorias' => 'pdf.reportes.categorias',
            'promociones' => 'pdf.reportes.promociones',
            'mesas' => 'pdf.reportes.mesas',
            'reservaciones' => 'pdf.reportes.reservaciones',
            'ventas' => 'pdf.reportes.ventas',
        ];

        return $vistas[$this->tipoReporte] ?? 'pdf.reportes.general';
    }

    private function generarNombreArchivo()
    {
        $nombres = [
            'general' => 'reporte-general',
            'usuarios' => 'reporte-usuarios',
            'productos' => 'reporte-productos',
            'categorias' => 'reporte-categorias',
            'promociones' => 'reporte-promociones',
            'mesas' => 'reporte-mesas',
            'reservaciones' => 'reporte-reservaciones',
            'ventas' => 'reporte-ventas',
        ];

        $baseNombre = $nombres[$this->tipoReporte] ?? 'reporte';

        return $baseNombre . '-' . now()->format('Ymd_His') . '.pdf';
    }

    public function render()
    {
        return view('livewire.admin.reportes')
            ->layout('layouts.admin', [
                'title' => 'Reportes',
                'pageTitle' => 'Sistema de Reportes'
            ]);
    }
}
