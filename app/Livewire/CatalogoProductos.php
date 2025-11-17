<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Producto;
use App\Models\Categoria;

class CatalogoProductos extends Component
{
    use WithPagination;

    public $buscar = '';
    public $categoriaSeleccionada = null;
    public $ordenar = 'recientes';
    public $verModal = false;
    public $productoSeleccionado = null;
    
    // Carrito temporal (se guardará en sesión)
    public $carrito = [];

    protected $queryString = [
        'buscar' => ['except' => ''],
        'categoriaSeleccionada' => ['except' => null],
        'ordenar' => ['except' => 'recientes']
    ];

    public function mount()
    {
        // Cargar carrito desde sesión
        $this->carrito = session()->get('carrito', []);
    }

    public function updatingBuscar()
    {
        $this->resetPage();
    }

    public function updatingCategoriaSeleccionada()
    {
        $this->resetPage();
    }

    public function filtrarCategoria($categoriaId)
    {
        $this->categoriaSeleccionada = $categoriaId;
    }

    public function limpiarFiltros()
    {
        $this->buscar = '';
        $this->categoriaSeleccionada = null;
        $this->ordenar = 'recientes';
        $this->resetPage();
    }

    public function verDetalles($productoId)
    {
        $this->productoSeleccionado = Producto::with('categoria', 'promociones')
            ->find($productoId);
        $this->verModal = true;
    }

    public function cerrarModal()
    {
        $this->verModal = false;
        $this->productoSeleccionado = null;
    }

    public function agregarAlCarrito($productoId, $cantidad = 1)
    {
        $producto = Producto::find($productoId);
        
        if (!$producto || !$producto->tiene_stock) {
            $this->dispatch('alerta', [
                'tipo' => 'error',
                'mensaje' => 'Producto no disponible'
            ]);
            return;
        }

        $existe = false;
        foreach ($this->carrito as $key => $item) {
            if ($item['id'] == $productoId) {
                $this->carrito[$key]['cantidad'] += $cantidad;
                $existe = true;
                break;
            }
        }

        if (!$existe) {
            $this->carrito[] = [
                'id' => $producto->id_producto,
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => $cantidad,
                'imagen' => $producto->imagen_url
            ];
        }

        session()->put('carrito', $this->carrito);
        
        $this->dispatch('carrito-actualizado');
        $this->dispatch('alerta', [
            'tipo' => 'success',
            'mensaje' => 'Producto agregado al carrito'
        ]);
    }

    public function eliminarDelCarrito($index)
    {
        unset($this->carrito[$index]);
        $this->carrito = array_values($this->carrito);
        session()->put('carrito', $this->carrito);
        
        $this->dispatch('carrito-actualizado');
    }

    public function actualizarCantidad($index, $cantidad)
    {
        if ($cantidad <= 0) {
            $this->eliminarDelCarrito($index);
            return;
        }

        $this->carrito[$index]['cantidad'] = $cantidad;
        session()->put('carrito', $this->carrito);
        
        $this->dispatch('carrito-actualizado');
    }

    public function getTotalCarritoProperty()
    {
        return collect($this->carrito)->sum(function ($item) {
            return $item['precio'] * $item['cantidad'];
        });
    }

    public function render()
    {
        $query = Producto::with('categoria')
            ->activos()
            ->conStock();

        // Búsqueda
        if ($this->buscar) {
            $query->where(function ($q) {
                $q->where('nombre', 'ilike', '%' . $this->buscar . '%')
                  ->orWhere('descripcion', 'ilike', '%' . $this->buscar . '%');
            });
        }

        // Filtro por categoría
        if ($this->categoriaSeleccionada) {
            $query->where('id_categoria', $this->categoriaSeleccionada);
        }

        // Ordenamiento
        switch ($this->ordenar) {
            case 'precio_asc':
                $query->orderBy('precio', 'asc');
                break;
            case 'precio_desc':
                $query->orderBy('precio', 'desc');
                break;
            case 'nombre':
                $query->orderBy('nombre', 'asc');
                break;
            default:
                $query->orderBy('fecha_creacion', 'desc');
        }

        $productos = $query->paginate(12);
        $categorias = Categoria::where('estado', true)->get();

        return view('livewire.catalogo-productos', [
            'productos' => $productos,
            'categorias' => $categorias
        ]);
    }
}