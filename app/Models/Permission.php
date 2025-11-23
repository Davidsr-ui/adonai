<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;




class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'module',
    ];

    // =========================================
    // RELACIONES
    // =========================================

    /**
     * Relación con roles (N:N)
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role')
                    ->withTimestamps();
    }

    // =========================================
    // SCOPES
    // =========================================

    /**
     * Scope para buscar permisos
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where('name', 'like', "%{$termino}%")
                    ->orWhere('display_name', 'like', "%{$termino}%")
                    ->orWhere('description', 'like', "%{$termino}%")
                    ->orWhere('module', 'like', "%{$termino}%");
    }

    /**
     * Scope para filtrar por módulo
     */
    public function scopePorModulo($query, $modulo)
    {
        return $query->where('module', $modulo);
    }

    /**
     * Scope para permisos con roles
     */
    public function scopeConRoles($query)
    {
        return $query->has('roles');
    }

    /**
     * Scope para permisos sin roles
     */
    public function scopeSinRoles($query)
    {
        return $query->doesntHave('roles');
    }

    // =========================================
    // ACCESSORS
    // =========================================

    /**
     * Obtener cantidad de roles
     */
    public function getCantidadRolesAttribute()
    {
        return $this->roles()->count();
    }

    /**
     * Badge de color según módulo
     */
    public function getBadgeColorAttribute()
    {
        $colors = [
            'dashboard' => 'secondary',
            'configuracion' => 'secondary',
            'academico' => 'primary',
            'personal' => 'info',
            'estudiantes' => 'success',
            'docentes' => 'warning',
            'reportes' => 'danger',
            'administracion' => 'dark',
            'Usuarios' => 'primary',
            'Academico' => 'success',
            'Comunicacion' => 'info',
            'Procesos' => 'warning',
            'Registros' => 'secondary',
            'Roles' => 'danger',
            'Seguridad' => 'dark',
            'Sistema' => 'indigo',
        ];
        
        return $colors[$this->module] ?? 'secondary';
    }

    /**
     * Badge de color según módulo (alias)
     */
    public function getModuloBadgeAttribute()
    {
        return $this->badge_color;
    }

    /**
     * Icono del módulo
     */
    public function getIconoAttribute()
    {
        $icons = [
            'dashboard' => 'fa-tachometer-alt',
            'configuracion' => 'fa-cog',
            'academico' => 'fa-book',
            'personal' => 'fa-users',
            'estudiantes' => 'fa-user-graduate',
            'docentes' => 'fa-chalkboard-teacher',
            'reportes' => 'fa-file-alt',
            'administracion' => 'fa-user-shield',
            'Usuarios' => 'fa-users',
            'Academico' => 'fa-graduation-cap',
            'Comunicacion' => 'fa-comments',
            'Procesos' => 'fa-cogs',
            'Registros' => 'fa-clipboard-list',
            'Roles' => 'fa-user-tag',
            'Seguridad' => 'fa-shield-alt',
            'Sistema' => 'fa-server',
        ];
        
        return $icons[$this->module] ?? 'fa-key';
    }

    /**
     * Icono del módulo (alias)
     */
    public function getModuloIconAttribute()
    {
        return $this->icono;
    }

    // =========================================
    // MÉTODOS
    // =========================================

    /**
     * Verificar si tiene roles asignados
     */
    public function tieneRoles()
    {
        return $this->roles()->count() > 0;
    }

    /**
     * Asignar rol al permiso
     */
    public function asignarRol($roleId)
    {
        if (!$this->roles()->where('role_id', $roleId)->exists()) {
            $this->roles()->attach($roleId);
            return true;
        }
        return false;
    }

    /**
     * Asignar permiso a un rol (alias)
     */
    public function asignarARol($roleId)
    {
        return $this->asignarRol($roleId);
    }

    /**
     * Remover rol del permiso
     */
    public function removerRol($roleId)
    {
        $this->roles()->detach($roleId);
        return true;
    }

    /**
     * Remover permiso de un rol (alias)
     */
    public function removerDeRol($roleId)
    {
        return $this->removerRol($roleId);
    }

    /**
     * Sincronizar roles
     */
    public function sincronizarRoles($rolesIds)
    {
        $this->roles()->sync($rolesIds);
    }

    // =========================================
    // MÉTODOS ESTÁTICOS
    // =========================================

    /**
     * Obtener estadísticas
     */
    public static function obtenerEstadisticas()
    {
        return [
            'total' => self::count(),
            'asignados' => self::has('roles')->count(),
            'sin_asignar' => self::doesntHave('roles')->count(),
            'con_roles' => self::has('roles')->count(),
            'sin_roles' => self::doesntHave('roles')->count(),
            'por_modulo' => self::select('module', DB::raw('count(*) as total'))
                                ->whereNotNull('module')
                                ->groupBy('module')
                                ->pluck('total', 'module')
                                ->toArray(),
        ];
    }

    /**
     * Buscar permiso por nombre
     */
    public static function buscarPorNombre($nombre)
    {
        return self::where('name', $nombre)->first();
    }

    /**
     * Obtener permisos agrupados por módulo
     */
    public static function obtenerAgrupadosPorModulo()
    {
        return self::orderBy('module')->orderBy('display_name')->get()->groupBy('module');
    }

    /**
     * Obtener permisos por módulo agrupado (para estadísticas)
     */
    public static function obtenerPorModuloAgrupado()
    {
        return self::select('module', DB::raw('count(*) as total'))
                    ->whereNotNull('module')
                    ->groupBy('module')
                    ->orderBy('module')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->module => $item->total];
                    });
    }

    /**
     * Obtener todos los módulos únicos
     */
    public static function obtenerModulos()
    {
        return self::select('module')
                    ->distinct()
                    ->whereNotNull('module')
                    ->orderBy('module')
                    ->pluck('module');
    }

    /**
     * Crear permisos CRUD para un módulo
     */
    public static function crearPermisoCRUD($modulo, $entidad)
    {
        $acciones = [
            'ver' => 'Ver',
            'crear' => 'Crear',
            'editar' => 'Editar',
            'eliminar' => 'Eliminar',
        ];
        
        $permisosCreados = [];
        
        foreach ($acciones as $accion => $displayAccion) {
            $name = "{$accion}.{$entidad}";
            
            // Verificar si ya existe
            if (!self::where('name', $name)->exists()) {
                $permiso = self::create([
                    'name' => $name,
                    'display_name' => "{$displayAccion} " . ucfirst($entidad),
                    'description' => "Permite {$accion} {$entidad} en el sistema",
                    'module' => $modulo,
                ]);
                
                $permisosCreados[] = $permiso;
            }
        }
        
        return $permisosCreados;
    }
}