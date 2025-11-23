<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'cursos';
    
    protected $fillable = [
        'nivel_id',
        'nombre',
        'codigo',
        'horas_semanales',
        'area_curricular',
        'estado'
    ];

    protected $casts = [
        'creditos' => 'integer',
        'horas_semanales' => 'integer',
        'nivel_id' => 'integer',
    ];

    // Relación con nivel
    public function nivel()
    {
        return $this->belongsTo(Nivel::class);
    }

    // Relación muchos a muchos con docentes (tabla pivote: docente_curso)
    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'docente_curso')
                    ->withPivot('grado_id', 'gestion_id', 'es_tutor_aula')
                    ->withTimestamps();
    }

    // ⭐ NUEVA RELACIÓN - SOLUCIONA EL ERROR ⭐
    // Relación con asignaciones (1:N) - Tabla: docente_curso
    public function asignaciones()
    {
        return $this->hasMany(DocenteCurso::class, 'curso_id');
    }


    // Relación con horarios
    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    // Relación con matrículas
    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }

    // Relación con asistencias
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }

    // Scope para cursos activos
    public function scopeActivo($query)
    {
        return $query->where('estado', 'Activo');
    }

    // Scope para cursos inactivos
    public function scopeInactivo($query)
    {
        return $query->where('estado', 'Inactivo');
    }

    // Scope por nivel
    public function scopePorNivel($query, $nivelId)
    {
        return $query->where('nivel_id', $nivelId);
    }

    // Accessor para obtener el nombre completo con código
    public function getNombreCompletoAttribute()
    {
        return $this->codigo ? "{$this->codigo} - {$this->nombre}" : $this->nombre;
    }

    // Accessor para obtener el nombre con nivel
    public function getNombreConNivelAttribute()
    {
        $nivel = $this->nivel ? $this->nivel->nombre : 'Sin nivel';
        return "{$this->nombre} ({$nivel})";
    }
}