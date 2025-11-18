<?php

namespace App\Livewire\Admin;

use App\Models\DetallePedido;
use App\Models\DetalleVenta;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Rol;
use App\Models\Usuario;
use App\Models\Venta;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Str;

class VentaRapida extends Component
{
    public $search = '';
    public $productos = [];
    public $carrito = [];
    public $subtotal = 0;
    public $descuento = 0;
    public $total = 0;
    public $descuentoManual = 0;
    public $descuentoPromociones = 0;

    public $metodoPago = 'efectivo';
    public $observaciones = '';
    public $tipoConsumo = 'mesa';
    public $mesaSeleccionada = null;
    public $mesas = [];

    protected $rules = [
        'mesaSeleccionada' => 'required_if:tipoConsumo,mesa|exists:mesas,id_mesa',
        'metodoPago' => 'required|in:efectivo,tarjeta,transferencia,yape,plin,mixto',
        'tipoConsumo' => 'required|in:mesa,para_llevar'
    ];

    public function mount()
    {
        Log::info('=== VENTA RAPIDA MOUNT START ===');
        Log::info('User: ' . (Auth::check() ? Auth::id() : 'No auth'));

        try {
            $this->cargarDatos();
            Log::info('=== VENTA RAPIDA MOUNT SUCCESS ===');
        } catch (Exception $e) {
            Log::error('VENTA RAPIDA MOUNT ERROR: ' . $e->getMessage());
            Log::error('TRACE: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    public function cargarDatos()
    {
        Log::info('Cargando datos...');

        try {
            // Cargar productos agrupados por categoría con promociones
            Log::info('Cargando productos con promociones...');
            $productosGrouped = Producto::with(['categoria', 'promociones' => function($query) {
                $query->where('estado', true)
                      ->where('fecha_inicio', '<=', now())
                      ->where('fecha_fin', '>=', now());
            }])
            ->where('estado', true)
            ->where('stock', '>', 0)
            ->get()
            ->groupBy(fn($p) => $p->categoria->nombre ?? 'Sin Categoría');

            // Convertir a array estructurado para la vista
            $this->productos = [];
            foreach ($productosGrouped as $categoria => $productosCategoria) {
                $this->productos[$categoria] = $productosCategoria->map(function($producto) {
                    $precioOriginal = (float)$producto->precio;
                    $precioConDescuento = (float)$producto->precio_con_descuento;
                    $tienePromocion = $producto->tiene_promocion;
                    $descuentoAplicado = $producto->descuento_aplicado;
                    $ahorro = $producto->ahorro;

                    return [
                        'id_producto' => $producto->id_producto,
                        'nombre' => $producto->nombre,
                        'precio' => $precioOriginal,
                        'precio_con_descuento' => $precioConDescuento,
                        'tiene_promocion' => $tienePromocion,
                        'descuento_aplicado' => $descuentoAplicado,
                        'ahorro' => $ahorro,
                        'stock' => $producto->stock,
                        'imagen' => $producto->imagen ? asset('storage/' . $producto->imagen) : null,
                        'descripcion' => $producto->descripcion
                    ];
                })->toArray();
            }

            Log::info('Productos cargados: ' . collect($this->productos)->flatten(1)->count());

            // Cargar mesas disponibles
            Log::info('Cargando mesas...');
            $this->mesas = Mesa::where('activa', true)
                ->where('estado', 'disponible')
                ->get(['id_mesa', 'numero_mesa', 'capacidad', 'ubicacion']);

            Log::info('Mesas cargadas: ' . $this->mesas->count());

        } catch (Exception $e) {
            Log::error('ERROR CARGANDO DATOS: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updatedSearch()
    {
        Log::info('Buscando: ' . $this->search);

        try {
            $productosGrouped = Producto::with(['categoria', 'promociones' => function($query) {
                $query->where('estado', true)
                      ->where('fecha_inicio', '<=', now())
                      ->where('fecha_fin', '>=', now());
            }])
            ->where('estado', true)
            ->where('stock', '>', 0)
            ->where('nombre', 'like', '%' . $this->search . '%')
            ->get()
            ->groupBy(fn($p) => $p->categoria->nombre ?? 'Sin Categoría');

            // Convertir a array estructurado
            $this->productos = [];
            foreach ($productosGrouped as $categoria => $productosCategoria) {
                $this->productos[$categoria] = $productosCategoria->map(function($producto) {
                    $precioOriginal = (float)$producto->precio;
                    $precioConDescuento = (float)$producto->precio_con_descuento;
                    $tienePromocion = $producto->tiene_promocion;
                    $descuentoAplicado = $producto->descuento_aplicado;
                    $ahorro = $producto->ahorro;

                    return [
                        'id_producto' => $producto->id_producto,
                        'nombre' => $producto->nombre,
                        'precio' => $precioOriginal,
                        'precio_con_descuento' => $precioConDescuento,
                        'tiene_promocion' => $tienePromocion,
                        'descuento_aplicado' => $descuentoAplicado,
                        'ahorro' => $ahorro,
                        'stock' => $producto->stock,
                        'imagen' => $producto->imagen ? asset('storage/' . $producto->imagen) : null,
                        'descripcion' => $producto->descripcion
                    ];
                })->toArray();
            }
        } catch (Exception $e) {
            Log::error('ERROR EN BÚSQUEDA: ' . $e->getMessage());
        }
    }

    public function agregarProducto($productoId)
    {
        Log::info('Agregando producto ID: ' . $productoId);

        try {
            $producto = Producto::with(['promociones' => function($query) {
                $query->where('estado', true)
                      ->where('fecha_inicio', '<=', now())
                      ->where('fecha_fin', '>=', now());
            }])->find($productoId);

            if (!$producto || $producto->stock <= 0) {
                session()->flash('error', 'Producto no disponible');
                return;
            }

            // Obtener precio con descuento si tiene promoción
            $precioUnitario = (float)$producto->precio_con_descuento;
            $precioOriginal = (float)$producto->precio;
            $tienePromocion = $producto->tiene_promocion;
            $descuentoAplicado = $tienePromocion ? ($precioOriginal - $precioUnitario) : 0;

            $foundIndex = null;
            foreach ($this->carrito as $i => $item) {
                if ($item['id_producto'] == $productoId) {
                    $foundIndex = $i;
                    break;
                }
            }

            if (is_null($foundIndex)) {
                $this->carrito[] = [
                    'id_producto' => $producto->id_producto,
                    'nombre' => $producto->nombre,
                    'precio_unitario' => $precioUnitario,
                    'precio_original' => $precioOriginal,
                    'cantidad' => 1,
                    'subtotal' => $precioUnitario,
                    'tiene_promocion' => $tienePromocion,
                    'descuento_aplicado' => $descuentoAplicado,
                    'descuento_total' => $descuentoAplicado, // descuento para esta unidad
                ];
                Log::info('Nuevo producto agregado: ' . $producto->nombre . ' - Precio: ' . $precioUnitario . ' (Descuento: ' . $descuentoAplicado . ')');
            } else {
                $this->carrito[$foundIndex]['cantidad'] += 1;
                $this->carrito[$foundIndex]['subtotal'] =
                    $this->carrito[$foundIndex]['cantidad'] * $this->carrito[$foundIndex]['precio_unitario'];
                $this->carrito[$foundIndex]['descuento_total'] =
                    $this->carrito[$foundIndex]['descuento_aplicado'] * $this->carrito[$foundIndex]['cantidad'];
                Log::info('Producto existente incrementado: ' . $producto->nombre);
            }

            $this->calcularTotales();

        } catch (Exception $e) {
            Log::error('ERROR AGREGANDO PRODUCTO: ' . $e->getMessage());
            session()->flash('error', 'Error al agregar producto');
        }
    }

    public function actualizarCantidad($index, $cantidad)
    {
        Log::info('Actualizando cantidad - Index: ' . $index . ', Cantidad: ' . $cantidad);

        $cantidad = intval($cantidad);
        if (!isset($this->carrito[$index])) return;

        if ($cantidad <= 0) {
            $this->eliminarProducto($index);
            return;
        }

        $this->carrito[$index]['cantidad'] = $cantidad;
        $this->carrito[$index]['subtotal'] = $cantidad * $this->carrito[$index]['precio_unitario'];
        $this->carrito[$index]['descuento_total'] = $this->carrito[$index]['descuento_aplicado'] * $cantidad;

        $this->calcularTotales();
    }

    public function eliminarProducto($index)
    {
        Log::info('Eliminando producto - Index: ' . $index);

        if (!isset($this->carrito[$index])) return;

        unset($this->carrito[$index]);
        $this->carrito = array_values($this->carrito);
        $this->calcularTotales();
    }

    public function calcularTotales()
    {
        Log::info('Calculando totales...');

        $this->subtotal = collect($this->carrito)->sum('subtotal');

        // Calcular descuento total de promociones
        $this->descuentoPromociones = collect($this->carrito)->sum('descuento_total');

        // Descuento manual (ingresado por el usuario)
        $descuentoManual = max(0, (float)$this->descuentoManual);

        // Descuento total es la suma de promociones + descuento manual
        $this->descuento = $this->descuentoPromociones + $descuentoManual;

        // Asegurar que el descuento total no sea mayor al subtotal
        if ($this->descuento > $this->subtotal + $this->descuentoPromociones) {
            $this->descuento = $this->subtotal + $this->descuentoPromociones;
            // Ajustar el descuento manual si es necesario
            if ($this->descuentoManual > $this->subtotal) {
                $this->descuentoManual = $this->subtotal;
            }
        }

        $this->total = $this->subtotal - $this->descuento;

        Log::info('Subtotal: ' . $this->subtotal .
                 ', Descuento Promociones: ' . $this->descuentoPromociones .
                 ', Descuento Manual: ' . $descuentoManual .
                 ', Descuento Total: ' . $this->descuento .
                 ', Total: ' . $this->total);
    }

    public function updatedDescuentoManual()
    {
        $this->calcularTotales();
    }

    public function finalizarVenta()
    {
        Log::info('=== FINALIZAR VENTA START ===');

        $this->validate();

        if (empty($this->carrito)) {
            session()->flash('error', 'El carrito está vacío');
            return;
        }

        if (!Auth::check()) {
            session()->flash('error', 'Debe iniciar sesión para registrar la venta.');
            return;
        }

        try {
            Log::info('Iniciando transacción...');

            DB::transaction(function () {
                $numeroPedido = 'PED-' . Str::upper(Str::random(8));
                $numeroVenta = 'VENTA-' . Str::upper(Str::random(8));

                Log::info('Creando pedido: ' . $numeroPedido);

                // Crear pedido
                $pedido = Pedido::create([
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

                Log::info('Pedido creado ID: ' . $pedido->id_pedido);

                foreach ($this->carrito as $item) {
                    Log::info('Procesando producto: ' . $item['nombre']);

                    $producto = Producto::find($item['id_producto']);
                    if (!$producto) throw new Exception("Producto no encontrado: {$item['id_producto']}");
                    if ($producto->stock < $item['cantidad']) throw new Exception("Stock insuficiente para {$producto->nombre}");

                    // Calcular descuento individual para este producto
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

                    $producto->decrement('stock', $item['cantidad']);
                    Log::info('Stock actualizado para: ' . $producto->nombre . ' - Nuevo stock: ' . $producto->stock);
                }

                // Crear venta
                Log::info('Creando venta: ' . $numeroVenta);
                $venta = Venta::create([
                    'numero_venta' => $numeroVenta,
                    'id_usuario' => Auth::id(),
                    'id_reserva' => null,
                    'subtotal' => $this->subtotal,
                    'descuento' => $this->descuento,
                    'total' => $this->total,
                    'metodo_pago' => $this->metodoPago,
                    'estado_venta' => 'completada',
                    'observaciones' => $this->observaciones,
                    'fecha_venta' => now(),
                ]);

                Log::info('Venta creada ID: ' . $venta->id_venta);

                foreach ($this->carrito as $item) {
                    // Calcular descuento individual para este producto en la venta
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

                if ($this->tipoConsumo === 'mesa' && $this->mesaSeleccionada) {
                    $mesa = Mesa::find($this->mesaSeleccionada);
                    if ($mesa) {
                        $mesa->update(['estado' => 'ocupada']);
                        Log::info('Mesa actualizada a OCUPADA: ' . $mesa->numero_mesa);
                    }
                }
            });

            Log::info('=== VENTA COMPLETADA EXITOSAMENTE ===');
            $this->resetVenta();
            session()->flash('success', 'Venta registrada exitosamente. Total: Bs. ' . number_format($this->total, 2));

        } catch (Exception $e) {
            Log::error('ERROR EN VENTA: ' . $e->getMessage());
            Log::error('TRACE: ' . $e->getTraceAsString());
            session()->flash('error', 'Error al procesar la venta: ' . $e->getMessage());
        }
    }

    public function resetVenta()
    {
        Log::info('Reseteando venta...');

        $this->carrito = [];
        $this->subtotal = 0;
        $this->descuento = 0;
        $this->descuentoManual = 0;
        $this->descuentoPromociones = 0;
        $this->total = 0;
        $this->observaciones = '';
        $this->mesaSeleccionada = null;
        $this->metodoPago = 'efectivo';
        $this->cargarDatos();
    }

    public function render()
    {
        Log::info('=== VENTA RAPIDA RENDER ===');

        try {
            return view('livewire.admin.venta-rapida')
                ->layout('layouts.admin', [
                    'title' => 'Venta Rápida',
                    'pageTitle' => 'Punto de Venta'
                ]);
        } catch (Exception $e) {
            Log::error('RENDER ERROR: ' . $e->getMessage());
            Log::error('RENDER TRACE: ' . $e->getTraceAsString());
            throw $e;
        }
    }
}