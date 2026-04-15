<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'estado',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // =========================================
    // RELACIONES
    // =========================================

    public function persona()
    {
        return $this->hasOne(Persona::class, 'user_id');
    }

    // =========================================
    // HELPERS (Spatie)
    // =========================================

    public function tieneRol($roleName)
    {
        return $this->hasRole($roleName);
    }

    public function tieneAlgunRol($roles)
    {
        return $this->hasAnyRole($roles);
    }

    public function tieneTodosRoles($roles)
    {
        return $this->hasAllRoles($roles);
    }

    public function tienePermiso($permisoName)
    {
        return $this->hasPermissionTo($permisoName);
    }

    public function isAdmin()
    {
        return $this->hasRole('admin') || $this->hasRole('Administrador');
    }

    public function isDocente()
    {
        return $this->hasRole('docente');
    }

    public function isEstudiante()
    {
        return $this->hasRole('estudiante');
    }

    public function isTutor()
    {
        return $this->hasRole('tutor');
    }

    // =========================================
    // ACCESSORS
    // =========================================

    public function getNombreCompletoAttribute()
    {
        if ($this->persona) {
            return $this->persona->nombres . ' ' . $this->persona->apellidos;
        }
        return $this->name;
    }

    public function getAvatarAttribute()
    {
        if ($this->persona && $this->persona->foto_perfil) {
            return asset('storage/' . $this->persona->foto_perfil);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&size=200&background=random';
    }

    public function getTipoUsuarioAttribute()
    {
        if ($this->isAdmin()) return 'Administrador';
        if ($this->isDocente()) return 'Docente';
        if ($this->isEstudiante()) return 'Estudiante';
        if ($this->isTutor()) return 'Tutor';
        return 'Usuario';
    }

    public function getBadgeEstadoAttribute()
    {
        return ($this->persona && $this->persona->estado === 'Activo') ? 'success' : 'secondary';
    }

    /**
     * Accessor para usar en las vistas (ej. $usuario->esta_activo)
     */
    public function getEstaActivoAttribute()
    {
        return $this->persona && $this->persona->estado === 'Activo';
    }
}