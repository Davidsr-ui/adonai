<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole; // <--- CAMBIO IMPORTANTE
use Illuminate\Support\Facades\DB;

class Role extends SpatieRole // <--- EXTENDEMOS DE SPATIE
{
    use HasFactory;

    // Spatie ya define la tabla y el guard_name internamente.
    // Solo definimos lo extra que necesitemos.

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'guard_name', // Agregado por seguridad
    ];

    // NOTA: He eliminado las funciones users() y permissions() 
    // porque Spatie ya las trae integradas y causaban conflicto.

    // =========================================
    // SCOPES (Tus filtros personalizados)
    // =========================================

    public function scopeBuscar($query, $termino)
    {
        return $query->where('name', 'like', "%{$termino}%")
            ->orWhere('display_name', 'like', "%{$termino}%")
            ->orWhere('description', 'like', "%{$termino}%");
    }

    public function scopeConUsuarios($query)
    {
        return $query->has('users');
    }

    public function scopeSinUsuarios($query)
    {
        return $query->doesntHave('users');
    }

    // =========================================
    // ACCESSORS (Tus estilos visuales)
    // =========================================

    public function getCantidadUsuariosAttribute()
    {
        return $this->users()->count();
    }

    public function getCantidadPermisosAttribute()
    {
        return $this->permissions()->count();
    }

    public function getBadgeColorAttribute()
    {
        // Convertimos a minúsculas para comparar mejor si usas 'admin' o 'Administrador'
        $name = strtolower($this->name);
        
        if (str_contains($name, 'admin')) return 'danger';
        if (str_contains($name, 'director')) return 'warning';
        if (str_contains($name, 'docente')) return 'success';
        if (str_contains($name, 'estudiante')) return 'primary';
        if (str_contains($name, 'tutor')) return 'info';

        return 'secondary';
    }

    public function getIconoAttribute()
    {
        $name = strtolower($this->name);

        if (str_contains($name, 'admin')) return 'fa-user-shield';
        if (str_contains($name, 'director')) return 'fa-user-tie';
        if (str_contains($name, 'docente')) return 'fa-chalkboard-teacher';
        if (str_contains($name, 'estudiante')) return 'fa-user-graduate';
        if (str_contains($name, 'tutor')) return 'fa-users';

        return 'fa-user-tag';
    }

    // =========================================
    // MÉTODOS DE AYUDA (Wrappers para Spatie)
    // =========================================
    
    // Spatie usa 'hasPermissionTo', pero mantenemos tu nombre 'tienePermiso'
    // para que no rompa tu código antiguo si lo usas en vistas.
    public function tienePermiso($permisoName)
    {
        return $this->hasPermissionTo($permisoName);
    }
}