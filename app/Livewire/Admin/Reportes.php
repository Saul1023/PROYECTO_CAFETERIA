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

    private function generarReporteUsuarios()
    {
        $query = Usuario::with('rol')
            ->whereBetween('fecha_creacion', [$this->fechaInicio, $this->fechaFin . ' 23:59:59']);

        $this->reporteData = $query->get();

        $this->estadisticas = [
            'total' => $query->count(),
            'activos' => $query->where('estado', true)->count(),
            'inactivos' => $query->where('estado', false)->count(),
            'por_rol' => Usuario::select('id_rol', DB::raw('count(*) as total'))
                ->whereBetween('fecha_creacion', [$this->fechaInicio, $this->fechaFin . ' 23:59:59'])
                ->groupBy('id_rol')
                ->with('rol')
                ->get() 
        ];
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
        $this->reporteData = Categoria::withCount('productos')
            ->whereBetween('fecha_creacion', [$this->fechaInicio, $this->fechaFin . ' 23:59:59'])
            ->get();

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
        $query = Venta::with(['usuario', 'detalles.producto'])
            ->whereBetween('fecha_venta', [$this->fechaInicio, $this->fechaFin]);

        $this->reporteData = $query->orderBy('fecha_venta', 'desc')->get();

        $this->estadisticas = [
            'total_ventas' => $query->count(),
            'ingresos_totales' => $query->sum('total'),
            'subtotal' => $query->sum('subtotal'),
            'descuentos' => $query->sum('descuento'),
            'ticket_promedio' => round($query->avg('total'), 2),
            'por_metodo_pago' => Venta::select('metodo_pago', DB::raw('count(*) as total'), DB::raw('sum(total) as monto'))
                ->whereBetween('fecha_venta', [$this->fechaInicio, $this->fechaFin])
                ->groupBy('metodo_pago')
                ->get(),
            'productos_vendidos' => DB::table('detalle_venta')
                ->join('ventas', 'detalle_venta.id_venta', '=', 'ventas.id_venta')
                ->whereBetween('ventas.fecha_venta', [$this->fechaInicio, $this->fechaFin])
                ->sum('detalle_venta.cantidad')
        ];
    }

    private function generarReporteGeneral()
    {
        $this->estadisticas = [
            'ventas' => [
                'total' => Venta::whereBetween('fecha_venta', [$this->fechaInicio, $this->fechaFin])->count(),
                'ingresos' => Venta::whereBetween('fecha_venta', [$this->fechaInicio, $this->fechaFin])->sum('total')
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
    }

    public function exportarPDF()
{
    if (empty($this->reporteData)) {
        session()->flash('error', 'Primero genere un reporte');
        return;
    }

    // Elegir la vista según el tipo de reporte
    $vista = match ($this->tipoReporte) {
        'usuarios' => 'pdf.reportes.usuarios',
        'ventas' => 'pdf.reportes.ventas',
        'mesas' => 'pdf.reportes.mesas',
        'reservaciones' => 'pdf.reportes.reservaciones',
        'productos' => 'pdf.reportes.productos',
        default => 'pdf.reportes.general'
    };

    // Generar PDF
    $pdf = Pdf::loadView($vista, [
        'reporteData'   => $this->reporteData,
        'estadisticas'  => $this->estadisticas,
        'fechaInicio'   => $this->fechaInicio,
        'fechaFin'      => $this->fechaFin
    ])->setPaper('A4', 'portrait');

    // Descargar
    return response()->streamDownload(
        fn() => print($pdf->output()),
        'reporte-' . $this->tipoReporte . '-' . now()->format('Ymd_His') . '.pdf'
    );
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