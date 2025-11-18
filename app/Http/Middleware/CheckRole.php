<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        Log::info('=== CHECKROLE START ===');
        Log::info('URL: ' . $request->url());
        Log::info('User authenticated: ' . (Auth::check() ? 'YES' : 'NO'));

        if (!Auth::check()) {
            Log::warning('User not authenticated, redirecting to login');
            return redirect()->route('login');
        }

        $userId = Auth::id();
        Log::info('User ID: ' . $userId);

        try {
            Log::info('Attempting database queries...');

            $usuario = \Illuminate\Support\Facades\DB::table('usuarios')
                        ->where('id_usuario', $userId)
                        ->first();

            Log::info('User query result: ' . ($usuario ? 'FOUND' : 'NOT FOUND'));

            if (!$usuario) {
                Log::error('User not found in database, logging out');
                Auth::logout();
                return redirect()->route('login')->with('error', 'Usuario no encontrado');
            }

            Log::info('User estado: ' . ($usuario->estado ? 'ACTIVE' : 'INACTIVE'));

            // Verificar que el usuario esté activo
            if (!$usuario->estado) {
                Log::warning('User inactive, logging out');
                Auth::logout();
                return redirect()->route('login')->with('error', 'Usuario inactivo');
            }

            Log::info('User rol ID: ' . $usuario->id_rol);

            // CONSULTA DIRECTA PARA OBTENER EL ROL
            $rol = \Illuminate\Support\Facades\DB::table('roles')
                    ->where('id_rol', $usuario->id_rol)
                    ->first();

            Log::info('Rol query result: ' . ($rol ? 'FOUND' : 'NOT FOUND'));

            if (!$rol) {
                Log::error('Rol not found for user, logging out');
                Auth::logout();
                return redirect()->route('login')->with('error', 'Rol no encontrado');
            }

            $rolUsuario = $rol->nombre;
            Log::info('User rol: ' . $rolUsuario);
            Log::info('Required roles: ' . implode(', ', $roles));

            // Verificar si el rol del usuario está en la lista de roles permitidos
            if (!in_array($rolUsuario, $roles)) {
                Log::warning('User role not allowed, redirecting to dashboard');
                return $this->redirectToUserDashboard($rolUsuario);
            }

            Log::info('=== CHECKROLE SUCCESS - Access granted ===');
            return $next($request); // ← ESTA LÍNEA FALTABA

        } catch (\Exception $e) {
            Log::error('CHECKROLE EXCEPTION: ' . $e->getMessage());
            Log::error('Exception trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    protected function redirectToUserDashboard(string $rol)
    {
        Log::info('Redirecting user to dashboard for role: ' . $rol);
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