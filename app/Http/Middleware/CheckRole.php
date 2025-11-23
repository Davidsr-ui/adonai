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
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('mensaje', 'Debes iniciar sesión para acceder.')
                ->with('icono', 'error');
        }

        $user = Auth::user();

        // Verificar si el usuario tiene alguno de los roles permitidos
        // Usa el método tieneAlgunRol() del modelo User
        if ($user->tieneAlgunRol($roles)) {
            return $next($request);
        }

        // Si no tiene ningún rol permitido, mostrar error 403
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
}