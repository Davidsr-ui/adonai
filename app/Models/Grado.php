<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    use HasFactory;

    protected $table = 'grados';
    
    protected $fillable = [
        'nivel_id',
        'turno_id',
        'nombre',
        'seccion',
        'capacidad_maxima',
        'estado'
    ];

    protected $casts = [
        'nivel_id' => 'integer',
        'turno_id' => 'integer',
        'capacidad_maxima' => 'integer',
    ];

    // Relaciones
    public function nivel()
    {
        return $this->belongsTo(Nivel::class);
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class);
    }

    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class);
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    public function docenteCursos()
    {
        return $this->hasMany(DocenteCurso::class);
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }

    // Scopes
    public function scopeActivo($query)
    {
        return $query->where('estado', 'Activo');
    }

    public function scopeInactivo($query)
    {
        return $query->where('estado', 'Inactivo');
    }

    public function scopePorNivel($query, $nivelId)
    {
        return $query->where('nivel_id', $nivelId);
    }

    public function scopePorTurno($query, $turnoId)
    {
        return $query->where('turno_id', $turnoId);
    }

    // Accessors
    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ($this->seccion ? ' - ' . $this->seccion : '');
    }

    public function getCapacidadDisponibleAttribute()
    {
        return $this->capacidad_maxima - $this->estudiantes()->count();
    }

    public function getEstaLlenoAttribute()
    {
        return $this->estudiantes()->count() >= $this->capacidad_maxima;
    }

    // Métodos adicionales útiles
    public function getNombreConNivelAttribute()
    {
        $nivel = $this->nivel ? $this->nivel->nombre : 'Sin nivel';
        return "{$nivel} - {$this->nombre_completo}";
    }

    public function tieneCapacidadDisponible()
    {
        return !$this->esta_lleno;
    }

    public function getPorcentajeOcupacionAttribute()
    {
        if ($this->capacidad_maxima == 0) return 0;
        $estudiantesMatriculados = $this->estudiantes()->count();
        return round(($estudiantesMatriculados / $this->capacidad_maxima) * 100, 2);
    }

    public function getVacantesDisponiblesAttribute()
    {
        return max(0, $this->capacidad_disponible);
    }
}