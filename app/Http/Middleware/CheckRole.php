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
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Verificar si está logueado
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('mensaje', 'Debes iniciar sesión para acceder.')
                ->with('icono', 'error');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 2. BLINDAJE: Si el usuario es 'admin', tiene pase VIP (entra a todo)
        // Esto evita que te quedes fuera de tu propio sistema.
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // 3. Procesar los roles solicitados
        // A veces llegan como "admin|docente", hay que separarlos para que Spatie entienda.
        $rolesPermitidos = [];
        foreach ($roles as $role) {
            // Separa por barra vertical '|' si existe y une al array
            $rolesPermitidos = array_merge($rolesPermitidos, explode('|', $role));
        }

        // 4. Verificar permiso usando la función NATIVA de Spatie (más seguro)
        if ($user->hasAnyRole($rolesPermitidos)) {
            return $next($request);
        }

        // 5. Si falla todo lo anterior, mostramos error
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
}