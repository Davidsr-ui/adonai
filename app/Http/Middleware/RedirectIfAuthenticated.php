<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Si ya está autenticado, redirigimos según su rol
                return redirect($this->redirectTo());
            }
        }

        return $next($request);
    }

    /**
     * Determinar a dónde redirigir según el rol del usuario.
     */
    protected function redirectTo()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            // Ruta por defecto si por alguna razón no hay usuario
            return '/home';
        }

        // Admin (acepta roles: Administrador o admin)
        if ($user->tieneRol('Administrador') || $user->tieneRol('admin')) {
            return '/admin/dashboard';
        }

        // Docente
        if ($user->tieneRol('docente')) {
            return '/docente/dashboard';
        }

        // Tutor
        if ($user->tieneRol('tutor')) {
            return '/tutor/dashboard';
        }

        // Por defecto
        return '/home';
    }
}
