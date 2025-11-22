<?php

use App\Livewire\PerfilUsuario;
use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Registro;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

// Landing page pública
Route::get('/', function () {
    $productos = Producto::with('categoria')
        ->where('estado', true)
        ->where('stock', '>', 0)
        ->orderBy('nombre')
        ->get();

    return view('welcome', compact('productos'));
})->name('home');

// Autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/registro', Registro::class)->name('registro');
});

// Logout (disponible para todos los autenticados)
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('home')->with('success', 'Sesión cerrada exitosamente');
})->middleware('auth')->name('logout');
// Ruta para descargar comprobante (accesible para ADMIN y EMPLEADO)
Route::get('/descargar-comprobante/{venta}', function ($numeroVenta) {
    try {
        // Buscar el archivo PDF
        $files = glob(storage_path("app/comprobantes/comprobante_{$numeroVenta}_*.pdf"));

        if (empty($files)) {
            abort(404, 'Comprobante no encontrado');
        }

        $filePath = $files[0];

        return response()->download($filePath, "comprobante_{$numeroVenta}.pdf", [
            'Content-Type' => 'application/pdf',
        ]);

    } catch (\Exception $e) {
        abort(404, 'Error al descargar comprobante: ' . $e->getMessage());
    }
})->middleware(['auth', 'role:ADMINISTRADOR,EMPLEADO'])->name('descargar.comprobante');
/*
|--------------------------------------------------------------------------
| Rutas de ADMINISTRADOR y EMPLEADO (Dashboard Unificado)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:ADMINISTRADOR,EMPLEADO'])->group(function () {

    // Dashboard (accesible para ambos roles)
Route::get('/dashboard', \App\Livewire\Admin\DashboardVista::class)->name('dashboard');

    // Gestión de Productos (ambos pueden gestionar)
    Route::get('/productos', \App\Livewire\Admin\ListarProducto::class)->name('productos');
    Route::get('/productos/crear', \App\Livewire\Admin\CrearProducto::class)->name('productos.crear');
    Route::get('/productos/editar/{id}', \App\Livewire\Admin\EditarProducto::class)->name('productos.editar');

    // Gestión de Categorías (ambos pueden gestionar)
    Route::get('/categorias', \App\Livewire\Admin\ListarCategoria::class)->name('categorias');
    Route::get('/categorias/crear', \App\Livewire\Admin\CrearCategoria::class)->name('categorias.crear');
    Route::get('/categorias/editar/{id}', \App\Livewire\Admin\EditarCategoria::class)->name('categorias.editar');

    // Gestión de Mesas (ambos pueden gestionar)
    Route::get('/mesas', \App\Livewire\Admin\ListarMesa::class)->name('mesas');

    // Ventas (ambos pueden gestionar)
    Route::get('/ventas', \App\Livewire\Admin\VentaRapida::class)->name('ventas');
    // Venta Rápida (ambos pueden gestionar)
    Route::get('/ventas-rapida', \App\Livewire\Admin\VentaRapida::class)->name('ventas.rapida');

    // Reservaciones (ambos pueden gestionar)
    Route::get('/reservaciones', \App\Livewire\Admin\ListarReservacion::class)->name('reservaciones');
    // Reportes (ambos puedes gesionar)
    Route::get('/reportes', \App\Livewire\Admin\Reportes::class)->name('reportes');

});

/*
|--------------------------------------------------------------------------
| Rutas SOLO para ADMINISTRADOR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:ADMINISTRADOR'])->group(function () {

    // Gestión de Usuarios (SOLO ADMINISTRADOR)
    Route::get('/usuarios', \App\Livewire\Admin\ListarUsuarios::class)->name('usuarios');
    Route::get('/usuarios/crear', \App\Livewire\Admin\CrearUsuario::class)->name('usuarios.crear');

    // Gestión de Promociones (SOLO ADMINISTRADOR)
    Route::get('/promociones', \App\Livewire\Admin\ListarPromocion::class)->name('promociones');
    Route::get('/promociones/crear', \App\Livewire\Admin\CrearPromocion::class)->name('promociones.crear');

});

/*
|--------------------------------------------------------------------------
| Rutas de CLIENTE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:CLIENTE'])->prefix('cliente')->name('cliente.')->group(function () {

    // Home del cliente (usa la vista pública con productos)
    Route::get('/home', function () {
        $productos = Producto::with('categoria')
            ->where('estado', true)
            ->where('stock', '>', 0)
            ->orderBy('nombre')
            ->get();

        return view('welcome', compact('productos'));
    })->name('home');

    // Mis Reservaciones (usa layout admin pero con contenido simple)
    Route::get('/reservar', function () {
        return redirect()->route('home')->with('openModal', 'reservar');
    })->name('reservar');

    Route::get('/reservaciones', function () {
        return redirect()->route('home')->with('openModal', 'reservaciones');
    })->name('reservaciones');

    // Mi Perfil (usa layout admin pero con perfil simple)
    Route::get('/perfil',PerfilUsuario::class)->name('perfil');

});
