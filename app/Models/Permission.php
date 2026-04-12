<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission; // <--- CAMBIO IMPORTANTE
use Illuminate\Support\Facades\DB;

class Permission extends SpatiePermission // <--- EXTENDEMOS DE SPATIE
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'module',
        'guard_name', // Importante para Spatie
    ];

    // NOTA: Eliminé la función roles() porque Spatie ya la incluye.

    // =========================================
    // SCOPES
    // =========================================

    public function scopeBuscar($query, $termino)
    {
        return $query->where('name', 'like', "%{$termino}%")
                    ->orWhere('display_name', 'like', "%{$termino}%")
                    ->orWhere('description', 'like', "%{$termino}%")
                    ->orWhere('module', 'like', "%{$termino}%");
    }

    public function scopePorModulo($query, $modulo)
    {
        return $query->where('module', $modulo);
    }

    // =========================================
    // ACCESSORS (Visuales)
    // =========================================

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
            'seguridad' => 'dark',
            'sistema' => 'indigo',
        ];
        
        // Buscamos el color, si no existe devolvemos secondary
        // Convertimos a minúscula el modulo por si acaso
        return $colors[strtolower($this->module)] ?? 'secondary';
    }

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
            'seguridad' => 'fa-shield-alt',
        ];
        
        return $icons[strtolower($this->module)] ?? 'fa-key';
    }
}