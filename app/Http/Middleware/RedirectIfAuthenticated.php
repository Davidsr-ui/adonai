<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
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
                // Si el usuario ya está autenticado, redirigir según su rol
                return redirect($this->redirectTo());
            }
        }

        return $next($request);
    }

    /**
     * Determinar a dónde redirigir según el rol del usuario
     */
    protected function redirectTo()
    {
        $user = Auth::user();

        // Administrador (acepta AMBOS roles: Administrador y admin)
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

        // Estudiante
        if ($user->tieneRol('estudiante')) {
            return '/estudiante/dashboard';
        }

        // Por defecto (si no tiene rol específico)
        return '/home';
    }
}