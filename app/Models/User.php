<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // <--- 1. IMPORTAMOS EL TRAIT

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasRoles; // <--- 2. ACTIVAMOS EL TRAIT (Esto crea la relación roles() automáticamente)

    protected $fillable = [
        'name',
        'email',
        'password',
        'estado', // <--- CORRECCIÓN: Agregado para permitir guardar el estado
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    // NOTA: Eliminé public function roles() porque 'use HasRoles' ya la crea.
    // Si la dejas manual, choca con la librería.

    public function persona()
    {
        return $this->hasOne(Persona::class, 'user_id');
    }

    // =========================================
    // CUSTOM HELPERS (Adaptados a Spatie)
    // =========================================

    // Mantenemos tus nombres de funciones para que no rompas tu código,
    // pero por dentro usamos la lógica segura de Spatie.

    public function tieneRol($roleName)
    {
        return $this->hasRole($roleName); // Spatie native
    }

    // =========================================
    // MÉTODOS RESCATADOS (Para que tu Middleware funcione)
    // =========================================

    /**
     * Verificar si tiene alguno de los roles (Wrapper para Spatie)
     * Esto arregla el error en CheckRole.php
     */
    public function tieneAlgunRol($roles)
    {
        // Spatie es muy inteligente y acepta array o string aquí automáticamente
        return $this->hasAnyRole($roles);
    }

    /**
     * Verificar si tiene todos los roles (Por si acaso lo usas en otro lado)
     */
    public function tieneTodosRoles($roles)
    {
        return $this->hasAllRoles($roles);
    }

    public function tienePermiso($permisoName)
    {
        return $this->hasPermissionTo($permisoName); // Spatie native
    }

    public function isAdmin()
    {
        // Verifica si tiene rol admin (mayúscula o minúscula)
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
    // ACCESSORS VISUALES
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

        return 'Usuario'; // Default
    }

    public function getBadgeEstadoAttribute()
    {
        // Asumiendo que persona->estado existe
        return ($this->persona && $this->persona->estado === 'Activo') ? 'success' : 'secondary';
    }
}