<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;



class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    // =========================================
    // RELACIONES
    // =========================================

    /**
     * Relación con usuarios (N:N)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user')
            ->withTimestamps();
    }

    /**
     * Relación con permisos (N:N)
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role')
            ->withTimestamps();
    }

    // =========================================
    // SCOPES
    // =========================================

    /**
     * Scope para buscar roles
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where('name', 'like', "%{$termino}%")
            ->orWhere('display_name', 'like', "%{$termino}%")
            ->orWhere('description', 'like', "%{$termino}%");
    }

    /**
     * Scope para roles con usuarios
     */
    public function scopeConUsuarios($query)
    {
        return $query->has('users');
    }

    /**
     * Scope para roles sin usuarios
     */
    public function scopeSinUsuarios($query)
    {
        return $query->doesntHave('users');
    }

    // =========================================
    // ACCESSORS
    // =========================================

    /**
     * Obtener cantidad de usuarios
     */
    public function getCantidadUsuariosAttribute()
    {
        return $this->users()->count();
    }

    /**
     * Obtener cantidad de permisos
     */
    public function getCantidadPermisosAttribute()
    {
        return $this->permissions()->count();
    }

    /**
     * Badge de color
     */
    public function getBadgeColorAttribute()
    {
        $colors = [
            'Administrador' => 'danger',
            'Director' => 'warning',
            'Docente' => 'success',
            'Estudiante' => 'primary',
            'Tutor' => 'info',
        ];

        return $colors[$this->display_name] ?? 'secondary';
    }

    /**
     * Icono del rol
     */
    public function getIconoAttribute()
    {
        $icons = [
            'Administrador' => 'fa-user-shield',
            'Director' => 'fa-user-tie',
            'Docente' => 'fa-chalkboard-teacher',
            'Estudiante' => 'fa-user-graduate',
            'Tutor' => 'fa-users',
        ];

        return $icons[$this->display_name] ?? 'fa-user-tag';
    }

    // =========================================
    // MÉTODOS
    // =========================================

    /**
     * Verificar si tiene usuarios asignados
     */
    public function tieneUsuarios()
    {
        return $this->users()->count() > 0;
    }

    /**
     * Verificar si tiene permisos asignados
     */
    public function tienePermisos()
    {
        return $this->permissions()->count() > 0;
    }

    /**
     * Asignar permiso al rol
     */
    public function asignarPermiso($permisoId)
    {
        if (!$this->permissions()->where('permission_id', $permisoId)->exists()) {
            $this->permissions()->attach($permisoId);
        }
    }

    /**
     * Remover permiso del rol
     */
    public function removerPermiso($permisoId)
    {
        $this->permissions()->detach($permisoId);
    }

    /**
     * Sincronizar permisos
     */
    public function sincronizarPermisos($permisosIds)
    {
        $this->permissions()->sync($permisosIds);
    }

    /**
     * Asignar usuario al rol
     */
    public function asignarUsuario($userId)
    {
        if (!$this->users()->where('user_id', $userId)->exists()) {
            $this->users()->attach($userId);
        }
    }

    /**
     * Remover usuario del rol
     */
    public function removerUsuario($userId)
    {
        $this->users()->detach($userId);
    }

    /**
     * Sincronizar usuarios
     */
    public function sincronizarUsuarios($usersIds)
    {
        $this->users()->sync($usersIds);
    }

    /**
     * Verificar si el rol tiene un permiso específico
     */
    public function tienePermiso($permisoName)
    {
        return $this->permissions()->where('name', $permisoName)->exists();
    }

    /**
     * Obtener nombres de permisos
     */
    public function getNombresPermisos()
    {
        return $this->permissions()->pluck('display_name')->toArray();
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
            'con_usuarios' => self::has('users')->count(),
            'sin_usuarios' => self::doesntHave('users')->count(),
            'con_permisos' => self::has('permissions')->count(),
            'sin_permisos' => self::doesntHave('permissions')->count(),
            'total_usuarios_asignados' => DB::table('role_user')->distinct('user_id')->count('user_id'),
            'total_permisos_asignados' => DB::table('permission_role')->distinct('permission_id')->count('permission_id'),
        ];
    }
    /**
     * Buscar rol por nombre
     */
    public static function buscarPorNombre($nombre)
    {
        return self::where('name', $nombre)->first();
    }

    /**
     * Obtener rol por display name
     */
    public static function porDisplayName($displayName)
    {
        return self::where('display_name', $displayName)->first();
    }

    /**
     * Obtener roles con sus permisos agrupados por módulo
     */
    public static function obtenerConPermisosAgrupados()
    {
        return self::with(['permissions' => function ($query) {
            $query->orderBy('module')->orderBy('display_name');
        }])->get();
    }
}
