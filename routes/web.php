<?php

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

// Landing page pública (la vista que me mostraste)
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

/*
|--------------------------------------------------------------------------
| Rutas de ADMINISTRADOR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:ADMINISTRADOR'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Gestión de Usuarios
    Route::get('/usuarios', \App\Livewire\Admin\ListarUsuarios::class)->name('usuarios');
    Route::get('/usuarios/crear', \App\Livewire\Admin\CrearUsuario::class)->name('usuarios.crear');

    // Gestión de Productos
    Route::get('/productos', \App\Livewire\Admin\ListarProducto::class)->name('productos');
    Route::get('/productos/crear', \App\Livewire\Admin\CrearProducto::class)->name('productos.crear');
    Route::get('/productos/editar/{id}', \App\Livewire\Admin\EditarProducto::class)->name('productos.editar');

    // Gestión de Categorías
    Route::get('/categorias', \App\Livewire\Admin\ListarCategoria::class)->name('categorias');
    Route::get('/categorias/crear', \App\Livewire\Admin\CrearCategoria::class)->name('categorias.crear');
    Route::get('/categorias/editar/{id}', \App\Livewire\Admin\EditarCategoria::class)->name('categorias.editar');

    // Gestión de Mesas
    Route::get('/mesas', function () {
        return view('admin.mesas.index');
    })->name('mesas');

    // Gestión de Promociones
    Route::get('/promociones', function () {
        return view('admin.promociones.index');
    })->name('promociones');

    // Reportes
    Route::get('/reportes', function () {
        return view('admin.reportes.index');
    })->name('reportes');

    // Ventas
    Route::get('/ventas', function () {
        return view('admin.ventas.index');
    })->name('ventas');
});

/*
|--------------------------------------------------------------------------
| Rutas de EMPLEADO
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:EMPLEADO'])->prefix('empleado')->name('empleado.')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('empleado.dashboard');
    })->name('dashboard');

    // Ventas
    Route::get('/ventas', function () {
        return view('empleado.ventas.index');
    })->name('ventas');

    // Reservaciones
    Route::get('/reservaciones', function () {
        return view('empleado.reservaciones.index');
    })->name('reservaciones');

    // Productos (solo lectura)
    Route::get('/productos', function () {
        return view('empleado.productos.index');
    })->name('productos');
});

/*
|--------------------------------------------------------------------------
| Rutas de CLIENTE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:CLIENTE'])->prefix('cliente')->name('cliente.')->group(function () {

    // Home del cliente (redirige al welcome con productos)
    Route::get('/home', function () {
        return redirect()->route('home'); // Los clientes ven el welcome
    })->name('home');

    // Mis Reservaciones
    Route::get('/mis-reservaciones', function () {
        return view('cliente.reservaciones');
    })->name('reservaciones');

    // Hacer Reservación
    Route::get('/reservar', function () {
        return view('cliente.reservar');
    })->name('reservar');

    // Mi Perfil
    Route::get('/perfil', function () {
        return view('cliente.perfil');
    })->name('perfil');
});