<?php

namespace App\Livewire\Admin;

use App\Models\{DetallePedido, DetalleVenta, Mesa, Pedido, Producto, Reservacion, Rol, Usuario, Venta};
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\{Auth, DB, Log};
use Livewire\Component;
use Illuminate\Support\Str;

class VentaRapida extends Component
{
    public $search = '';
    public $categoriaSeleccionada = 'todas'; // Agregar esta línea
    public $categorias = []; // Agregar esta línea
    public $productos = [];
    public $carrito = [];
    public $subtotal = 0;
    public $descuento = 0;
    public $total = 0;
    public $descuentoManual = 0;
    public $descuentoPromociones = 0;
    public $mostrarSelectorMesa = false;
    public $mesasDisponiblesHoy = [];
    public $reservacionesHoy = [];
    public $reservacionSeleccionada = null;
    public $mostrarReservaciones = false;
    public $tipoSeleccion = null; // 'mesa_disponible' o 'reservacion'
    public $metodoPago = 'efectivo';
    public $observaciones = '';
    public $tipoConsumo = 'mesa';
    public $mesaSeleccionada = null;
    public $numeroPersonas = 1; // Agregar esta línea después de las otras propiedades públicas
    public $mesas = [];
    public $clientes = [];
    public $clienteSeleccionado = null;

    public $mostrarComprobante = false;
    public $comprobanteData = null;

    protected $rules = [
        'mesaSeleccionada' => 'required_if:tipoConsumo,mesa|exists:mesas,id_mesa',
        'metodoPago' => 'required|in:efectivo,tarjeta,transferencia,yape,plin,mixto',
        'tipoConsumo' => 'required|in:mesa,para_llevar',
        'clienteSeleccionado' => 'nullable|exists:usuarios,id_usuario'
    ];

    public function mount()
    {
        $this->logAction('VENTA RAPIDA MOUNT START');
        try {
            $this->cargarDatos();
            $this->cargarMesasDisponibles();
            $this->cargarReservacionesHoy();
            $this->logAction('VENTA RAPIDA MOUNT SUCCESS');
        } catch (Exception $e) {
            $this->logError('VENTA RAPIDA MOUNT ERROR', $e);
            throw $e;
        }
    }

    public function mostrarComprobante()
    {
        $this->mostrarComprobante = true;
    }

    public function cerrarComprobante()
    {
        $this->mostrarComprobante = false;
        $this->comprobanteData = null;
        session()->forget('comprobante_venta');
        $this->resetVenta(); // Resetear la venta después de cerrar el comprobante
    }

    public function descargarPDF()
    {
        if ($this->comprobanteData && isset($this->comprobanteData['pdf_path'])) {
            if (file_exists($this->comprobanteData['pdf_path'])) {
                return response()->download(
                    $this->comprobanteData['pdf_path'],
                    "comprobante_{$this->comprobanteData['numero_venta']}.pdf"
                );
            } else {
                session()->flash('error', 'El archivo PDF no se encontró');
            }
        }
        return null;
    }

    public function cargarDatos()
    {
        $this->logAction('Cargando datos...');

        try {
            $this->cargarCategorias();
            $this->cargarProductos();
            $this->cargarMesas();
            $this->cargarClientes();
        } catch (Exception $e) {
            $this->logError('ERROR CARGANDO DATOS', $e);
            throw $e;
        }
    }
    private function cargarCategorias()
    {
        $this->categorias = \App\Models\Categoria::where('estado', true)
            ->orderBy('nombre')
            ->get([ 'id_categoria','nombre']);

       // Log::info('Categorías cargadas: ' . $this->categorias->count());
    }

    private function cargarProductos()
    {
        $query = Producto::with(['categoria', 'promociones' => $this->getPromocionesActivasQuery()])
            ->activos()
            ->conStock();

        // Filtrar por búsqueda
        if ($this->search) {
            $query->where('nombre', 'like', "%{$this->search}%");
        }

        // Filtrar por categoría seleccionada
        if ($this->categoriaSeleccionada && $this->categoriaSeleccionada !== 'todas') {
            $query->where('id_categoria', $this->categoriaSeleccionada);
        }

        $productosGrouped = $query->get()
            ->groupBy(fn($p) => $p->categoria->nombre ?? 'Sin Categoría');

        $this->productos = collect($productosGrouped)->mapWithKeys(function ($productosCategoria, $categoria) {
            return [$categoria => $productosCategoria->map(fn($producto) => $this->formatearProducto($producto))->toArray()];
        })->toArray();

     //   Log::info("Productos cargados - Categoría: {$this->categoriaSeleccionada}, Búsqueda: '{$this->search}', Total: " . collect($this->productos)->flatten(1)->count());
    }

    private function cargarMesas()
    {
        $this->mesas = Mesa::where('activa', true)
            ->get(['id_mesa', 'numero_mesa', 'capacidad', 'ubicacion']); // QUITAR ->where('estado', 'disponible')
    }

    private function cargarClientes()
    {
        // Obtener el ID del rol CLIENTE
        $rolCliente = Rol::where('nombre', 'CLIENTE')->first();

        if ($rolCliente) {
            $this->clientes = Usuario::with('rol')
                ->where('id_rol', $rolCliente->id_rol)
                ->activos()
                ->get(['id_usuario', 'nombre_completo', 'email', 'telefono', 'nombre_usuario']);
        } else {
            $this->clientes = [];
        }

       // Log::info('Clientes cargados: ' . $this->clientes->count());
    }
    private function cargarMesasDisponibles()
    {
        $now = Carbon::now();
        $horaActual = $now->format('H:i:s');

        // Obtener solo las mesas que están reservadas EN ESTE MOMENTO (dentro de ventana de 2 horas)
        $mesasReservadasAhora = Reservacion::whereDate('fecha_reservacion', today())
            ->where('estado', 'confirmada')
            ->where('hora_reservacion', '<=', $horaActual)
            ->where('hora_reservacion', '>=', $now->copy()->subHours(2)->format('H:i:s'))
            ->pluck('id_mesa')
            ->toArray();

        // Obtener mesas ocupadas actualmente
        $mesasOcupadas = Mesa::where('estado', 'ocupada')
            ->pluck('id_mesa')
            ->toArray();

        // Combinar ambas listas
        $mesasNoDisponibles = array_merge($mesasReservadasAhora, $mesasOcupadas);

        // Construir query base - solo excluir mesas ocupadas y reservadas en este momento
        $query = Mesa::where('activa', true)
            ->whereNotIn('id_mesa', $mesasNoDisponibles);

        // Filtrar por capacidad según número de personas
        if ($this->numeroPersonas > 0) {
            $query->where('capacidad', '>=', $this->numeroPersonas);
        }

        $this->mesasDisponiblesHoy = $query->orderBy('capacidad', 'asc')
            ->orderBy('numero_mesa')
            ->get(['id_mesa', 'numero_mesa', 'capacidad', 'ubicacion']);

    /* Log::info("Mesas cargadas: Total activas=" . Mesa::where('activa', true)->count() .
                ", Reservadas ahora=" . count($mesasReservadasAhora) .
                ", Ocupadas=" . count($mesasOcupadas) .
                ", Disponibles=" . $this->mesasDisponiblesHoy->count() .
                " para {$this->numeroPersonas} personas");*/
}

    public function updatedNumeroPersonas()
    {
        $this->cargarMesasDisponibles();
    }
    private function cargarReservacionesHoy()
    {
        $now = Carbon::now();
        $horaActual = $now->format('H:i:s');

        // Actualizar reservaciones que no asistieron (más de 1 hora después de la hora de reservación)
        Reservacion::whereDate('fecha_reservacion', today())
            ->where('estado', 'confirmada')
            ->where('hora_reservacion', '<', $now->copy()->subHour()->format('H:i:s'))
            ->update([
                'estado' => 'no_asistio',
                'fecha_actualizacion' => now()
            ]);

        // Mostrar solo las reservaciones confirmadas que aún son válidas
        $this->reservacionesHoy = Reservacion::with(['mesa', 'usuario'])
            ->whereDate('fecha_reservacion', today())
            ->where('estado', 'confirmada')
            ->orderBy('hora_reservacion', 'asc')
            ->get();

       // Log::info('Reservaciones de hoy cargadas: ' . $this->reservacionesHoy->count());
    }
    public function abrirSelectorMesa()
    {
        $this->cargarMesasDisponibles();
        $this->mostrarSelectorMesa = true;
        $this->tipoSeleccion = 'mesa_disponible';

        //Log::info('Abriendo selector de mesas. Mesas disponibles: ' . $this->mesasDisponiblesHoy->count());
    }

    public function abrirSelectorReservacion()
    {
        $this->cargarReservacionesHoy();
        $this->mostrarReservaciones = true;
        $this->tipoSeleccion = 'reservacion';

        //Log::info('Abriendo selector de reservaciones. Reservaciones encontradas: ' . $this->reservacionesHoy->count());
    }

    public function seleccionarMesaDisponible($mesaId)
    {
        $this->mesaSeleccionada = $mesaId;
        $this->tipoConsumo = 'mesa';
        $this->reservacionSeleccionada = null;
        $this->mostrarSelectorMesa = false;

        session()->flash('success', 'Mesa seleccionada correctamente');
    }

    public function seleccionarReservacion($reservacionId)
    {
        $reservacion = Reservacion::with(['mesa', 'usuario'])->find($reservacionId);

        if (!$reservacion) {
            session()->flash('error', 'Reservación no encontrada');
            return;
        }

        // Cargar datos de la reservación
        $this->reservacionSeleccionada = $reservacionId;
        $this->mesaSeleccionada = $reservacion->id_mesa;
        $this->clienteSeleccionado = $reservacion->id_usuario;
        $this->tipoConsumo = 'mesa';
        $this->observaciones = "Reservación #{$reservacion->id_reservacion} - " . ($reservacion->observaciones ?? '');

        // Calcular totales considerando el monto de reservación
        $this->calcularTotales();

        $this->mostrarReservaciones = false;

        session()->flash('success', "Reservación cargada. Cliente: {$reservacion->usuario->nombre_completo}");
    }

    public function cerrarSelectores()
    {
        $this->mostrarSelectorMesa = false;
        $this->mostrarReservaciones = false;
    }

    public function limpiarSeleccion()
    {
        $this->mesaSeleccionada = null;
        $this->reservacionSeleccionada = null;
        $this->clienteSeleccionado = null;
        $this->tipoSeleccion = null;
        $this->observaciones = '';
        $this->numeroPersonas = 1; // Resetear a 2 personas
        $this->calcularTotales();
    }

    private function formatearProducto($producto)
    {
        return [
            'id_producto' => $producto->id_producto,
            'nombre' => $producto->nombre,
            'precio' => (float)$producto->precio,
            'precio_con_descuento' => (float)$producto->precio_con_descuento,
            'tiene_promocion' => $producto->tiene_promocion,
            'descuento_aplicado' => $producto->descuento_aplicado,
            'ahorro' => $producto->ahorro,
            'stock' => $producto->stock,
            'imagen' => $producto->imagen ? asset('storage/' . $producto->imagen) : null,
            'descripcion' => $producto->descripcion
        ];
    }

    private function getPromocionesActivasQuery()
    {
        return function($query) {
            $query->where('estado', true)
                  ->where('fecha_inicio', '<=', now())
                  ->where('fecha_fin', '>=', now());
        };
    }

    public function updatedSearch()
    {
        $this->logAction("Buscando: {$this->search}");
        $this->cargarProductos();
    }
    public function updatedCategoriaSeleccionada()
    {
        $this->logAction("Filtrando por categoría: {$this->categoriaSeleccionada}");
        $this->cargarProductos();
    }

    public function agregarProducto($productoId)
    {
        $this->logAction("Agregando producto ID: {$productoId}");

        try {
            $producto = Producto::with(['promociones' => $this->getPromocionesActivasQuery()])->find($productoId);

            if (!$this->validarProducto($producto)) return;

            $datosProducto = $this->prepararDatosProducto($producto);
            $this->agregarAlCarrito($datosProducto);

            $this->calcularTotales();
        } catch (Exception $e) {
            $this->logError('ERROR AGREGANDO PRODUCTO', $e);
            session()->flash('error', 'Error al agregar producto');
        }
    }

    private function validarProducto($producto)
    {
        if (!$producto) {
            session()->flash('error', 'Producto no encontrado');
            return false;
        }

        if ($producto->stock <= 0) {
            session()->flash('error', 'Producto no disponible');
            return false;
        }

        $cantidadEnCarrito = collect($this->carrito)
            ->where('id_producto', $producto->id_producto)
            ->sum('cantidad');

        if ($producto->stock < ($cantidadEnCarrito + 1)) {
            session()->flash('error', "Stock insuficiente para {$producto->nombre}. Stock disponible: {$producto->stock}");
            return false;
        }

        return true;
    }

    private function prepararDatosProducto($producto)
    {
        $precioUnitario = (float)$producto->precio_con_descuento;
        $precioOriginal = (float)$producto->precio;
        $tienePromocion = $producto->tiene_promocion;
        $descuentoAplicado = $tienePromocion ? ($precioOriginal - $precioUnitario) : 0;

        return [
            'id_producto' => $producto->id_producto,
            'nombre' => $producto->nombre,
            'precio_unitario' => $precioUnitario,
            'precio_original' => $precioOriginal,
            'cantidad' => 1,
            'subtotal' => $precioUnitario,
            'tiene_promocion' => $tienePromocion,
            'descuento_aplicado' => $descuentoAplicado,
            'descuento_total' => $descuentoAplicado,
        ];
    }

    private function agregarAlCarrito($datosProducto)
    {
        $indexExistente = collect($this->carrito)
            ->search(fn($item) => $item['id_producto'] == $datosProducto['id_producto']);

        if ($indexExistente !== false) {
            $this->incrementarProductoExistente($indexExistente);
        } else {
            $this->carrito[] = $datosProducto;
            $this->logAction("Nuevo producto agregado: {$datosProducto['nombre']}");
        }
    }

    private function incrementarProductoExistente($index)
    {
        $this->carrito[$index]['cantidad'] += 1;
        $this->carrito[$index]['subtotal'] = $this->carrito[$index]['cantidad'] * $this->carrito[$index]['precio_unitario'];
        $this->carrito[$index]['descuento_total'] = $this->carrito[$index]['descuento_aplicado'] * $this->carrito[$index]['cantidad'];

        $this->logAction("Producto existente incrementado: {$this->carrito[$index]['nombre']}");
    }

    public function actualizarCantidad($index, $cantidad)
    {
        $cantidad = intval($cantidad);
        if (!isset($this->carrito[$index])) return;

        if ($cantidad <= 0) {
            $this->eliminarProducto($index);
            return;
        }

        if (!$this->validarStock($index, $cantidad)) return;

        $this->actualizarItemCarrito($index, $cantidad);
        $this->calcularTotales();
    }

    private function validarStock($index, $cantidad)
    {
        $productoId = $this->carrito[$index]['id_producto'];
        $producto = Producto::find($productoId);

        if ($producto && $producto->stock < $cantidad) {
            session()->flash('error', "Stock insuficiente para {$producto->nombre}. Stock disponible: {$producto->stock}");
            return false;
        }

        return true;
    }

    private function actualizarItemCarrito($index, $cantidad)
    {
        $this->carrito[$index]['cantidad'] = $cantidad;
        $this->carrito[$index]['subtotal'] = $cantidad * $this->carrito[$index]['precio_unitario'];
        $this->carrito[$index]['descuento_total'] = $this->carrito[$index]['descuento_aplicado'] * $cantidad;
    }

    public function eliminarProducto($index)
    {
        if (!isset($this->carrito[$index])) return;

        unset($this->carrito[$index]);
        $this->carrito = array_values($this->carrito);
        $this->calcularTotales();
    }

    public function calcularTotales()
    {
        $this->subtotal = collect($this->carrito)->sum('subtotal');
        $this->descuentoPromociones = collect($this->carrito)->sum('descuento_total');

        $descuentoManual = max(0, (float)$this->descuentoManual);

        // Si hay reservación, agregar el monto como descuento
        $montoReservacion = 0;
        if ($this->reservacionSeleccionada) {
            $reservacion = Reservacion::find($this->reservacionSeleccionada);
            if ($reservacion) {
                $montoReservacion = (float)$reservacion->monto_pago;
            }
        }

        $this->descuento = $this->descuentoPromociones + $descuentoManual + $montoReservacion;

        // Validar que el descuento no sea mayor al subtotal
        if ($this->descuento > $this->subtotal) {
            $this->descuento = $this->subtotal;
            $this->descuentoManual = max(0, $this->subtotal - $this->descuentoPromociones - $montoReservacion);
        }

        $this->total = max(0, $this->subtotal - $this->descuento);
    }

    public function incrementarCantidad($index)
    {
        if (!isset($this->carrito[$index])) return;

        if (!$this->validarStock($index, $this->carrito[$index]['cantidad'] + 1)) return;

        $this->actualizarItemCarrito($index, $this->carrito[$index]['cantidad'] + 1);
        $this->calcularTotales();
        $this->dispatch('clear-flash');
    }

    public function decrementarCantidad($index)
    {
        if (!isset($this->carrito[$index]) || $this->carrito[$index]['cantidad'] <= 1) return;

        $this->actualizarItemCarrito($index, $this->carrito[$index]['cantidad'] - 1);
        $this->calcularTotales();
        $this->dispatch('clear-flash');
    }

    public function updatedDescuentoManual()
    {
        $this->calcularTotales();
    }

    public function finalizarVenta()
    {
        $this->logAction('FINALIZAR VENTA START');

        // Validación más específica
        $this->validate([
            'mesaSeleccionada' => 'required',
            'metodoPago' => 'required',
            'carrito' => 'required|array|min:1',
        ], [
            'mesaSeleccionada.required' => 'Debes seleccionar una mesa o reservación antes de continuar',
            'metodoPago.required' => 'Debes seleccionar un método de pago',
            'carrito.required' => 'El carrito está vacío',
            'carrito.min' => 'Debes agregar al menos un producto al carrito',
        ]);

        // Validación adicional específica
        if ($this->tipoConsumo === 'mesa' && !$this->mesaSeleccionada) {
            session()->flash('error', 'Por favor selecciona una mesa o reservación antes de finalizar la venta');
            $this->dispatch('validacionError');
            return;
        }

        if (empty($this->carrito)) {
            session()->flash('error', 'El carrito está vacío. Agrega productos antes de continuar');
            $this->dispatch('validacionError');
            return;
        }

        if (!Auth::check()) {
            session()->flash('error', 'Debe iniciar sesión para registrar la venta.');
            return;
        }

        try {
            $ventaData = DB::transaction(function () {
                $this->registrarStockAntes();

                $pedido = $this->crearPedido();
                $this->procesarItemsCarrito($pedido);

                $venta = $this->crearVenta();
                $this->crearDetallesVenta($venta);

                $this->actualizarMesa();

                // Retornar datos para el comprobante
                return [
                    'venta' => $venta,
                    'pedido' => $pedido,
                    'detalles' => $this->carrito,
                    'cliente' => $this->clienteSeleccionado ?
                        Usuario::find($this->clienteSeleccionado) : null,
                    'mesa' => $this->tipoConsumo === 'mesa' && $this->mesaSeleccionada ?
                        Mesa::find($this->mesaSeleccionada) : null
                ];
            });

            // Generar comprobante
            $this->generarComprobante($ventaData);

            // Recargar las listas para reflejar cambios
            $this->cargarReservacionesHoy();
            $this->cargarMesasDisponibles();

            session()->flash('success', 'Venta registrada exitosamente. Total: Bs. ' . number_format($this->total, 2));

        } catch (\Exception $e) {
        session()->flash('error', 'Error al procesar la venta: ' . $e->getMessage());
        $this->dispatch('validacionError');
        }
    }

    private function crearPedido()
    {
        $numeroPedido = 'PED-' . Str::upper(Str::random(8));

        return Pedido::create([
            'numero_pedido' => $numeroPedido,
            'id_usuario' => Auth::id(),
            'id_mesa' => $this->tipoConsumo === 'mesa' ? $this->mesaSeleccionada : null,
            'id_reservacion' => null,
            'estado' => 'pendiente',
            'tipo_consumo' => $this->tipoConsumo,
            'subtotal' => $this->subtotal,
            'descuento' => $this->descuento,
            'total' => $this->total,
            'observaciones' => $this->observaciones,
            'fecha_pedido' => now(),
        ]);
    }
    public function incrementarPersonas()
    {
        if ($this->numeroPersonas < 20) {
            $this->numeroPersonas++;
            $this->cargarMesasDisponibles();
        }
    }

    public function decrementarPersonas()
    {
        if ($this->numeroPersonas > 1) {
            $this->numeroPersonas--;
            $this->cargarMesasDisponibles();
        }
    }

    private function procesarItemsCarrito($pedido)
    {
        foreach ($this->carrito as $item) {
            $producto = Producto::find($item['id_producto']);

            if (!$producto) throw new Exception("Producto no encontrado: {$item['id_producto']}");
            if ($producto->stock < $item['cantidad']) throw new Exception("Stock insuficiente para {$producto->nombre}");

            $this->crearDetallePedido($pedido, $producto, $item);
            $this->actualizarStockProducto($producto, $item['cantidad']);
        }
    }

    private function crearDetallePedido($pedido, $producto, $item)
    {
        $descuentoIndividual = $item['tiene_promocion'] ?
            ($item['precio_original'] - $item['precio_unitario']) * $item['cantidad'] : 0;

        DetallePedido::create([
            'id_pedido' => $pedido->id_pedido,
            'id_producto' => $producto->id_producto,
            'cantidad' => $item['cantidad'],
            'precio_unitario' => $item['precio_unitario'],
            'descuento' => $descuentoIndividual,
            'subtotal' => $item['subtotal'],
            'estado_preparacion' => 'pendiente',
        ]);
    }

    private function actualizarStockProducto($producto, $cantidad)
    {
        $stockAntes = $producto->stock;
        $producto->decrement('stock', $cantidad);
        $producto->refresh();

        $this->logAction("Stock actualizado: {$producto->nombre} {$stockAntes} -> {$producto->stock}");
    }
    private function crearVenta()
    {
        $numeroVenta = 'VENTA-' . Str::upper(Str::random(8));

        $venta = Venta::create([
            'numero_venta' => $numeroVenta,
            'id_usuario' => Auth::id(),
            'id_cliente' => $this->clienteSeleccionado,
            'id_reserva' => $this->reservacionSeleccionada,
            'subtotal' => $this->subtotal,
            'descuento' => $this->descuento,
            'total' => $this->total,
            'metodo_pago' => $this->metodoPago,
            'estado_venta' => 'completada',
            'observaciones' => $this->observaciones,
            'fecha_venta' => now(),
        ]);

        // Si hay reservación, actualizar su estado a completada
        if ($this->reservacionSeleccionada) {
            Reservacion::find($this->reservacionSeleccionada)->update([
                'estado' => 'completada',
                'fecha_actualizacion' => now()
            ]);

           // Log::info("Reservación #{$this->reservacionSeleccionada} marcada como completada");
        }

        return $venta;
    }
    private function generarComprobante($ventaData)
    {
        try {
            // Usar Carbon para la zona horaria de Bolivia
            $fechaBolivia = Carbon::now('America/La_Paz');
            $reservacionInfo = null;
            if ($this->reservacionSeleccionada) {
                $reservacion = Reservacion::find($this->reservacionSeleccionada);
                $reservacionInfo = [
                    'numero' => $reservacion->id_reservacion,
                    'codigo_qr' => $reservacion->codigo_qr,
                    'monto_pago' => $reservacion->monto_pago
                ];
            }

            $comprobanteData = [
                'numero_venta' => $ventaData['venta']->numero_venta,
                'numero_pedido' => $ventaData['pedido']->numero_pedido,
                'fecha' => $fechaBolivia->format('d/m/Y H:i:s'),
                'vendedor' => Auth::user()->nombre_completo,
                'cliente' => $ventaData['cliente'] ? $ventaData['cliente']->nombre_completo : 'Venta General',
                'reservacion' => $reservacionInfo,
                'mesa' => $ventaData['mesa'] ? 'Mesa ' . $ventaData['mesa']->numero_mesa : 'Para Llevar',
                'tipo_consumo' => $this->tipoConsumo,
                'metodo_pago' => $this->metodoPago,
                'items' => $ventaData['detalles'],
                'subtotal' => $this->subtotal,
                'descuento_promociones' => $this->descuentoPromociones,
                'descuento_manual' => $this->descuentoManual,
                'descuento_total' => $this->descuento,
                'total' => $this->total,
                'observaciones' => $this->observaciones,
                'empresa' => [
                    'nombre' => 'CAFETERÍA RINCÓN SABROSO',
                    'direccion' => 'Av. Principal #123, Potosí, Bolivia',
                    'telefono' => '+591 71234567',
                    'nit' => '12345678901'
                ],
                'fecha_generacion' => $fechaBolivia->format('d/m/Y \a \l\a\s H:i:s')
            ];

            // Generar PDF y guardarlo
            $pdfPath = $this->generarPDF($comprobanteData);
            $comprobanteData['pdf_path'] = $pdfPath;

            // Guardar en sesión y propiedad del componente
            session()->put('comprobante_venta', $comprobanteData);
            $this->comprobanteData = $comprobanteData;
            $this->mostrarComprobante = true;

        } catch (Exception $e) {
            Log::error('Error generando comprobante: ' . $e->getMessage());
            session()->flash('error', 'Error al generar comprobante: ' . $e->getMessage());
        }
    }

    private function generarPDF($comprobanteData)
    {
        try {
            $pdf = Pdf::loadView('comprobantes.venta', $comprobanteData);

            // Crear directorio si no existe
            $directory = storage_path('app/comprobantes');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $filename = 'comprobante_' . $comprobanteData['numero_venta'] . '_' . time() . '.pdf';
            $filePath = $directory . '/' . $filename;

            // Guardar PDF
            $pdf->save($filePath);

            return $filePath;

        } catch (Exception $e) {
            Log::error('Error generando PDF: ' . $e->getMessage());
            throw $e;
        }
    }
    private function crearDetallesVenta($venta)
    {
        foreach ($this->carrito as $item) {
            $descuentoIndividual = $item['tiene_promocion'] ?
                ($item['precio_original'] - $item['precio_unitario']) * $item['cantidad'] : 0;

            DetalleVenta::create([
                'id_venta' => $venta->id_venta,
                'id_producto' => $item['id_producto'],
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio_unitario'],
                'descuento' => $descuentoIndividual,
                'subtotal' => $item['subtotal'],
            ]);
        }
    }

    private function actualizarMesa()
    {
        if ($this->tipoConsumo === 'mesa' && $this->mesaSeleccionada) {
            Mesa::find($this->mesaSeleccionada)?->update(['estado' => 'ocupada']);
        }
    }

    private function registrarStockAntes()
    {
        foreach ($this->carrito as $item) {
            $producto = Producto::find($item['id_producto']);
            Log::info("📦 STOCK ANTES - {$producto->nombre}: {$producto->stock}");
        }
    }

    public function resetVenta()
    {
        $this->carrito = [];
        $this->subtotal = 0;
        $this->descuento = 0;
        $this->descuentoManual = 0;
        $this->descuentoPromociones = 0;
        $this->total = 0;
        $this->observaciones = '';
        $this->mesaSeleccionada = null;
        $this->clienteSeleccionado = null;
        $this->metodoPago = 'efectivo';
        $this->cargarDatos();
    }

    public function clearFlash()
    {
        session()->forget(['error', 'success']);
    }

    private function logAction($message)
    {
        Log::info($message);
    }

    private function logError($context, Exception $e)
    {
        Log::error("{$context}: {$e->getMessage()}");
        Log::error("TRACE: {$e->getTraceAsString()}");
    }

    // Agregar estas propiedades al componente VentaRapida
    public $mostrarModalCliente = false;
    public $clienteTemporal = [
        'nombre_completo' => '',
        'email' => '',
        'telefono' => '',
        'nombre_usuario' => ''
    ];

    // Agregar estas reglas de validación
    protected $rulesCliente = [
        'clienteTemporal.nombre_completo' => 'required|min:3',
        'clienteTemporal.email' => 'nullable|email|unique:usuarios,email',
        'clienteTemporal.telefono' => 'nullable|string|max:20',
        'clienteTemporal.nombre_usuario' => 'required|min:4|unique:usuarios,nombre_usuario'
    ];

    // Agregar estos mensajes de error
    protected $messagesCliente = [
        'clienteTemporal.nombre_completo.required' => 'El nombre completo es obligatorio',
        'clienteTemporal.nombre_completo.min' => 'El nombre debe tener al menos 3 caracteres',
        'clienteTemporal.email.email' => 'Ingresa un email válido',
        'clienteTemporal.email.unique' => 'Este email ya está registrado',
        'clienteTemporal.nombre_usuario.required' => 'El nombre de usuario es obligatorio',
        'clienteTemporal.nombre_usuario.min' => 'El nombre de usuario debe tener al menos 4 caracteres',
        'clienteTemporal.nombre_usuario.unique' => 'Este nombre de usuario ya está en uso',
    ];

    // Agregar estos métodos
    public function abrirModalCliente()
    {
        $this->mostrarModalCliente = true;
        $this->clienteTemporal = [
            'nombre_completo' => '',
            'email' => '',
            'telefono' => '',
            'nombre_usuario' => ''
        ];
        $this->resetValidation();
    }

    public function cerrarModalCliente()
    {
        $this->mostrarModalCliente = false;
        $this->resetValidation();
    }

    public function guardarClienteRapido()
    {
        // Validar datos del cliente
        $this->validate($this->rulesCliente, $this->messagesCliente);

        try {
            // Obtener el ID del rol CLIENTE
            $rolCliente = Rol::where('nombre', 'CLIENTE')->first();

            if (!$rolCliente) {
                session()->flash('error', 'No se pudo encontrar el rol CLIENTE');
                return;
            }

            // Crear el cliente
            $cliente = Usuario::create([
                'nombre_completo' => $this->clienteTemporal['nombre_completo'],
                'email' => $this->clienteTemporal['email'],
                'telefono' => $this->clienteTemporal['telefono'],
                'nombre_usuario' => $this->clienteTemporal['nombre_usuario'],
                'password' => bcrypt('cliente123'), // Password por defecto
                'id_rol' => $rolCliente->id_rol,
                'estado' => true
            ]);

            // Asignar el nuevo cliente a la venta
            $this->clienteSeleccionado = $cliente->id_usuario;

            // Recargar la lista de clientes
            $this->cargarClientes();

            $this->cerrarModalCliente();
            session()->flash('success', 'Cliente registrado exitosamente');

        } catch (Exception $e) {
            session()->flash('error', 'Error al registrar el cliente: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $this->logAction('VENTA RAPIDA RENDER');

        return view('livewire.admin.venta-rapida')
            ->layout('layouts.admin', [
                'title' => 'Venta Rápida',
                'pageTitle' => 'Punto de Venta'
            ]);
    }

}
