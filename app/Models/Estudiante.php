<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected $table = 'estudiantes';
    
    protected $fillable = [
        'persona_id',
        'grado_id',
        'codigo_estudiante',
        'año_ingreso',
        'condicion'
    ];

    // Relaciones
    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }

    public function tutores()
    {
        return $this->belongsToMany(Tutor::class, 'tutor_estudiante')
                    ->withPivot('relacion_familiar', 'tipo', 'autorizacion_recojo', 'estado')
                    ->withTimestamps();
    }

    // ⭐ RELACIÓN AGREGADA PARA EL CRUD ⭐
    public function tutorEstudiantes()
    {
        return $this->hasMany(TutorEstudiante::class);
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }

    public function comportamientos()
    {
        return $this->hasMany(Comportamiento::class);
    }

    public function reportes()
    {
        return $this->hasMany(Reporte::class);
    }

    // Scopes
    public function scopeActivo($query)
    {
        return $query->whereHas('persona', function($q) {
            $q->where('estado', 'Activo');
        });
    }

    public function scopeRegular($query)
    {
        return $query->where('condicion', 'Regular');
    }

    // Accessors
    public function getNombresAttribute()
    {
        return $this->persona->nombres;
    }

    public function getApellidosAttribute()
    {
        return $this->persona->apellidos;
    }

    public function getNombreCompletoAttribute()
    {
        return $this->persona->nombre_completo;
    }

    public function getEstadoAttribute()
    {
        return $this->persona->estado;
    }

    public function getDniAttribute()
    {
        return $this->persona->dni;
    }

    // Obtener tutor principal (usando la relación belongsToMany)
    public function getTutorPrincipalAttribute()
    {
        return $this->tutores()->wherePivot('tipo', 'Principal')->first();
    }

    // ⭐ MÉTODOS ÚTILES AGREGADOS ⭐
    
    // Obtener el tutor principal usando hasMany
    public function tutorPrincipal()
    {
        return $this->tutorEstudiantes()
                    ->where('tipo', 'Principal')
                    ->where('estado', 'Activo')
                    ->with('tutor.persona')
                    ->first();
    }

    // Obtener todos los tutores activos
    public function tutoresActivos()
    {
        return $this->tutorEstudiantes()
                    ->where('estado', 'Activo')
                    ->with('tutor.persona')
                    ->get();
    }

    // Obtener personas autorizadas para recojo
    public function personasAutorizadasRecojo()
    {
        return $this->tutorEstudiantes()
                    ->where('autorizacion_recojo', true)
                    ->where('estado', 'Activo')
                    ->with('tutor.persona')
                    ->get();
    }

    // Verificar si tiene tutor principal activo
    public function tieneTutorPrincipal()
    {
        return $this->tutorEstudiantes()
                    ->where('tipo', 'Principal')
                    ->where('estado', 'Activo')
                    ->exists();
    }

    // Obtener cantidad de tutores
    public function getCantidadTutoresAttribute()
    {
        return $this->tutorEstudiantes()->where('estado', 'Activo')->count();
    }

    // Obtener cantidad de personas autorizadas para recojo
    public function getCantidadAutorizadosRecojoAttribute()
    {
        return $this->tutorEstudiantes()
                    ->where('autorizacion_recojo', true)
                    ->where('estado', 'Activo')
                    ->count();
    }
}