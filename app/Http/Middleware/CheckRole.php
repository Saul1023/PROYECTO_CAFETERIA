<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $usuario = Auth::user();

        // Verificar que el usuario tenga un rol asignado
        if (!$usuario->rol) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Usuario sin rol asignado');
        }

        // Verificar que el usuario esté activo
        if (!$usuario->estado) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Usuario inactivo');
        }

        $rolUsuario = $usuario->rol->nombre;

        // Verificar si el rol del usuario está en la lista de roles permitidos
        if (!in_array($rolUsuario, $roles)) {
            // Redirigir a su dashboard correspondiente
            return $this->redirectToUserDashboard($rolUsuario);
        }

        return $next($request);
    }

    /**
     * Redirigir al dashboard correspondiente según el rol
     */
    protected function redirectToUserDashboard(string $rol)
    {
        return match($rol) {
            'ADMINISTRADOR' => redirect()->route('admin.dashboard')
                ->with('error', 'No tienes permiso para acceder a esa área'),
            'EMPLEADO' => redirect()->route('empleado.dashboard')
                ->with('error', 'No tienes permiso para acceder a esa área'),
            'CLIENTE' => redirect()->route('cliente.home')
                ->with('error', 'No tienes permiso para acceder a esa área'),
            default => redirect()->route('login')
                ->with('error', 'Rol no reconocido')
        };
    }
}
