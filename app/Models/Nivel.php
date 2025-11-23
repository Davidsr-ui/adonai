<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    protected $table = 'nivels';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'orden',
        'estado'
    ];

    // =========================================
    // RELACIONES
    // =========================================

    /**
     * Relación con grados (1:N)
     * Un nivel puede tener muchos grados
     */
    public function grados()
    {
        return $this->hasMany(Grado::class, 'nivel_id');
    }

    // =========================================
    // SCOPES
    // =========================================

    /**
     * Scope para obtener solo niveles activos
     */
    public function scopeActivo($query)
    {
        return $query->where('estado', 'Activo');
    }

    /**
     * Scope para ordenar por campo orden
     */
    public function scopeOrdenado($query)
    {
        return $query->orderBy('orden', 'asc');
    }

    /**
     * Scope para buscar por nombre
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where('nombre', 'like', "%{$termino}%")
                    ->orWhere('descripcion', 'like', "%{$termino}%");
    }

    // =========================================
    // ACCESSORS
    // =========================================

    /**
     * Obtener badge de estado
     */
    public function getBadgeEstadoAttribute()
    {
        return $this->estado === 'Activo' ? 'success' : 'secondary';
    }

    /**
     * Verificar si está activo
     */
    public function getEstaActivoAttribute()
    {
        return $this->estado === 'Activo';
    }

    /**
     * Obtener cantidad de grados
     */
    public function getCantidadGradosAttribute()
    {
        return $this->grados()->count();
    }
}