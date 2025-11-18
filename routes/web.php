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

/*
|--------------------------------------------------------------------------
| Rutas de ADMINISTRADOR y EMPLEADO (Dashboard Unificado)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:ADMINISTRADOR,EMPLEADO'])->group(function () {

    // Dashboard (accesible para ambos roles)
    Route::get('/dashboard', function () {
        return view('layouts.admin');
    })->name('dashboard');

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
    Route::get('/ventas', function () {
        return view('dashboard.ventas.index');
    })->name('ventas');

    // Venta Rápida (ambos pueden gestionar)
    Route::get('/ventas-rapida', \App\Livewire\Admin\VentaRapida::class)->name('ventas.rapida');

    // Reservaciones (ambos pueden gestionar)
    Route::get('/reservaciones', \App\Livewire\Admin\ListarReservacion::class)->name('reservaciones');
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

    // Reportes (SOLO ADMINISTRADOR)
    Route::get('/reportes', function () {
        return view('dashboard.reportes.index');
    })->name('reportes');
});

/*
|--------------------------------------------------------------------------
| Rutas de CLIENTE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:CLIENTE'])->prefix('cliente')->name('cliente.')->group(function () {

    // Home del cliente (redirige al welcome con productos)
    Route::get('/home', function () {
        return redirect()->route('home');
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