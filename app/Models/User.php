<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // =========================================
    // RELACIONES
    // =========================================

    /**
     * Relación con roles (N:N)
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')->withTimestamps();
    }

    /**
     * Relación con persona (1:1)
     * NOTA: La relación correcta es hasOne porque user_id está en la tabla personas
     */
    public function persona()
    {
        return $this->hasOne(Persona::class, 'user_id');
    }

    // =========================================
    // SCOPES (Filtros reutilizables)
    // =========================================

    /**
     * Scope para buscar usuarios
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where('name', 'like', "%{$termino}%")
            ->orWhere('email', 'like', "%{$termino}%")
            ->orWhereHas('persona', function ($q) use ($termino) {
                $q->where('nombres', 'like', "%{$termino}%")
                    ->orWhere('apellidos', 'like', "%{$termino}%")
                    ->orWhere('dni', 'like', "%{$termino}%");
            });
    }

    /**
     * Scope para usuarios con persona
     */
    public function scopeConPersona($query)
    {
        return $query->has('persona');
    }

    /**
     * Scope para usuarios sin persona
     */
    public function scopeSinPersona($query)
    {
        return $query->doesntHave('persona');
    }

    /**
     * Scope para usuarios por rol
     */
    public function scopePorRol($query, $roleName)
    {
        return $query->whereHas('roles', function ($q) use ($roleName) {
            $q->where('name', $roleName);
        });
    }

    /**
     * Scope para usuarios verificados
     */
    public function scopeVerificados($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope para usuarios no verificados
     */
    public function scopeNoVerificados($query)
    {
        return $query->whereNull('email_verified_at');
    }

    // =========================================
    // ACCESSORS (Atributos computados)
    // =========================================

    /**
     * Obtener nombre completo de la persona
     */
    public function getNombreCompletoAttribute()
    {
        if ($this->persona) {
            return $this->persona->nombres . ' ' . $this->persona->apellidos;
        }
        return $this->name;
    }

    /**
     * Obtener avatar o foto de perfil
     */
    public function getAvatarAttribute()
    {
        if ($this->persona && $this->persona->foto_perfil) {
            return asset('storage/' . $this->persona->foto_perfil);
        }
        // Generar avatar por iniciales usando ui-avatars.com
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&size=200&background=random';
    }

    /**
     * Obtener DNI de la persona
     */
    public function getDniAttribute()
    {
        return $this->persona ? $this->persona->dni : 'N/A';
    }

    /**
     * Verificar si está activo (según estado de persona)
     */
    public function getEstaActivoAttribute()
    {
        return $this->persona ? ($this->persona->estado === 'Activo') : true;
    }

    /**
     * Obtener badge de estado
     */
    public function getBadgeEstadoAttribute()
    {
        return $this->esta_activo ? 'success' : 'danger';
    }

    /**
     * Obtener texto de estado
     */
    public function getTextoEstadoAttribute()
    {
        return $this->esta_activo ? 'Activo' : 'Inactivo';
    }

    /**
     * Obtener nombres de roles
     */
    public function getNombresRolesAttribute()
    {
        return $this->roles->pluck('display_name')->toArray();
    }

    /**
     * Obtener primer rol (rol principal)
     */
    public function getRolPrincipalAttribute()
    {
        return $this->roles->first();
    }

    /**
     * Verificar si está verificado
     */
    public function getEstaVerificadoAttribute()
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Obtener tipo de usuario
     */
    public function getTipoUsuarioAttribute()
    {
        // Verificar SOLO por rol (más simple y eficiente)
        // Administrador: acepta AMBOS roles (Administrador y admin)
        if ($this->tieneRol('Administrador') || $this->tieneRol('admin')) return 'Administrador';
        if ($this->tieneRol('docente')) return 'Docente';
        if ($this->tieneRol('estudiante')) return 'Estudiante';
        if ($this->tieneRol('tutor')) return 'Tutor';

        return 'Usuario';
    }

    /**
     * Obtener icono según tipo
     */
    public function getIconoTipoAttribute()
    {
        $tipo = $this->tipo_usuario;
        $iconos = [
            'Administrador' => 'fa-user-shield',
            'Docente' => 'fa-chalkboard-teacher',
            'Estudiante' => 'fa-user-graduate',
            'Tutor' => 'fa-users',
        ];
        return $iconos[$tipo] ?? 'fa-user';
    }

    /**
     * Obtener color badge según tipo
     */
    public function getBadgeTipoAttribute()
    {
        $tipo = $this->tipo_usuario;
        $badges = [
            'Administrador' => 'danger',
            'Docente' => 'success',
            'Estudiante' => 'primary',
            'Tutor' => 'info',
        ];
        return $badges[$tipo] ?? 'secondary';
    }

    // =========================================
    // MÉTODOS DE ROLES Y PERMISOS (ESPAÑOL)
    // =========================================

    /**
     * Verificar si tiene un rol
     */
    public function tieneRol($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Verificar si tiene alguno de los roles
     */
    public function tieneAlgunRol($roles)
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    /**
     * Verificar si tiene todos los roles
     */
    public function tieneTodosRoles($roles)
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }
        foreach ($roles as $role) {
            if (!$this->tieneRol($role)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Verificar si tiene un permiso (a través de roles)
     */
    public function tienePermiso($permisoName)
    {
        return $this->roles()
            ->whereHas('permissions', function ($q) use ($permisoName) {
                $q->where('name', $permisoName);
            })->exists();
    }

    /**
     * Obtener todos los permisos del usuario
     */
    public function getPermisosAttribute()
    {
        return $this->roles->pluck('permissions')->flatten()->unique('id');
    }

    /**
     * Asignar rol
     */
    public function asignarRol($roleId)
    {
        if (!$this->roles()->where('role_id', $roleId)->exists()) {
            $this->roles()->attach($roleId);
        }
    }

    /**
     * Remover rol
     */
    public function removerRol($roleId)
    {
        $this->roles()->detach($roleId);
    }

    /**
     * Sincronizar roles
     */
    public function sincronizarRoles($rolesIds)
    {
        $this->roles()->sync($rolesIds);
    }

    // =========================================
    // MÉTODOS DE ROLES Y PERMISOS (INGLÉS - ALIAS)
    // =========================================

    /**
     * Verificar si tiene un rol (alias en inglés)
     */
    public function hasRole($roleName)
    {
        return $this->tieneRol($roleName);
    }

    /**
     * Verificar si tiene alguno de los roles (alias en inglés)
     */
    public function hasAnyRole($roles)
    {
        return $this->tieneAlgunRol($roles);
    }

    /**
     * Verificar si tiene todos los roles (alias en inglés)
     */
    public function hasAllRoles($roles)
    {
        return $this->tieneTodosRoles($roles);
    }

    /**
     * Verificar si tiene un permiso (alias en inglés)
     */
    public function hasPermission($permissionName)
    {
        return $this->tienePermiso($permissionName);
    }

    /**
     * Verificar si tiene alguno de los permisos
     */
    public function hasAnyPermission($permissions)
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        foreach ($permissions as $permission) {
            if ($this->tienePermiso($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Verificar si tiene todos los permisos
     */
    public function hasAllPermissions($permissions)
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        foreach ($permissions as $permission) {
            if (!$this->tienePermiso($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Obtener todos los permisos del usuario (a través de sus roles)
     */
    public function getAllPermissions()
    {
        return Permission::whereHas('roles', function ($query) {
            $query->whereIn('roles.id', $this->roles->pluck('id'));
        })->get();
    }

    /**
     * Verificar si es administrador
     */
    public function isAdmin()
    {
        return $this->tieneRol('Administrador') || $this->tieneRol('admin');
    }

    /**
     * Verificar si es docente
     */
    public function isDocente()
    {
        return $this->tieneRol('docente');
    }

    /**
     * Verificar si es tutor
     */
    public function isTutor()
    {
        return $this->tieneRol('tutor');
    }

    /**
     * Verificar si es estudiante
     */
    public function isEstudiante()
    {
        return $this->tieneRol('estudiante');
    }

    /**
     * Obtener nombres de roles (alias en inglés)
     */
    public function getRoleNames()
    {
        return $this->nombres_roles;
    }

    /**
     * Obtener primer rol (alias en inglés)
     */
    public function getPrimaryRole()
    {
        return $this->rol_principal;
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
            'con_persona' => self::has('persona')->count(),
            'sin_persona' => self::doesntHave('persona')->count(),
            'verificados' => self::whereNotNull('email_verified_at')->count(),
            'no_verificados' => self::whereNull('email_verified_at')->count(),
            'activos' => self::whereHas('persona', function ($q) {
                $q->where('estado', 'Activo');
            })->count(),
            'inactivos' => self::whereHas('persona', function ($q) {
                $q->where('estado', 'Inactivo');
            })->count(),
            'por_rol' => self::with('roles')
                ->get()
                ->groupBy(function ($user) {
                    return $user->rol_principal ? $user->rol_principal->display_name : 'Sin Rol';
                })
                ->map(function ($users) {
                    return $users->count();
                })
                ->toArray(),
        ];
    }


    /**
     * Buscar usuario por email
     */
    public static function buscarPorEmail($email)
    {
        return self::where('email', $email)->first();
    }

    /**
     * Buscar usuario por DNI (a través de persona)
     */
    public static function buscarPorDni($dni)
    {
        return self::whereHas('persona', function ($q) use ($dni) {
            $q->where('dni', $dni);
        })->first();
    }
}