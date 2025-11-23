<?php

namespace App\Http;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;

class MenuFilter implements FilterInterface
{
    public function transform($item)
    {
        // Si el usuario no está autenticado, ocultar todo
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();

        try {
            // Verificar si tiene el permiso requerido (usando 'can')
            if (isset($item['can'])) {
                $permissions = is_array($item['can']) ? $item['can'] : [$item['can']];
                
                $hasPermission = false;
                foreach ($permissions as $permission) {
                    if (method_exists($user, 'tienePermiso') && $user->tienePermiso($permission)) {
                        $hasPermission = true;
                        break;
                    }
                }
                
                if (!$hasPermission) {
                    return false;
                }
            }

            // Verificar si tiene el rol requerido (usando 'role')
            if (isset($item['role'])) {
                $roles = is_array($item['role']) ? $item['role'] : [$item['role']];
                
                $hasRole = false;
                foreach ($roles as $role) {
                    if (method_exists($user, 'tieneRol') && $user->tieneRol($role)) {
                        $hasRole = true;
                        break;
                    }
                }
                
                if (!$hasRole) {
                    return false;
                }
            }

            return $item;
            
        } catch (\Exception $e) {
            // Log del error pero no detener la aplicación
            Log::error('MenuFilter Error: ' . $e->getMessage());
            // En caso de error, mostrar el item (comportamiento seguro)
            return $item;
        }
    }
}