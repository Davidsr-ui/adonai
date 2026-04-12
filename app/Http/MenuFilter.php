<?php

namespace App\Http;

use Illuminate\Support\Facades\Auth;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;

class MenuFilter implements FilterInterface
{
    public function transform($item)
    {
        // 1. Si no hay usuario logueado, ocultar todo
        if (!Auth::check()) {
            return false;
        }

        // 2. Si es un título o separador, mostrarlo tal cual
        if (is_string($item)) {
            return $item;
        }

        $user = Auth::user();

        // ❌ HE ELIMINADO EL BLOQUE "SI ES ADMIN MUESTRA TODO".
        // Ahora obligamos a que el menú respete estrictamente los roles definidos.

        // 3. Verificar Permisos (campo 'can')
        // Este sí respeta al Admin gracias al AppServiceProvider (Gate::before)
        if (isset($item['can'])) {
            if (!$user->can($item['can'])) {
                return false;
            }
        }

        // 4. Verificar Roles (campo 'role')
        // AQUÍ ESTÁ LA CORRECCIÓN:
        // Si el menú dice 'role' => 'docente', el Admin NO lo verá
        // a menos que también tenga el rol de 'docente' asignado.
        if (isset($item['role'])) {
            $roles = is_array($item['role']) ? $item['role'] : [$item['role']];

            if (!$user->hasAnyRole($roles)) {
                return false;
            }
        }

        // Si pasa los filtros, mostrar el ítem
        return $item;
    }
}