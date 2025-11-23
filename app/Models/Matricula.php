<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $fillable = [
        'estudiante_id',
        'curso_id',
        'grado_id',
        'gestion_id',
        'estado',
    ];

    protected $casts = [
        'estudiante_id' => 'integer',
        'curso_id' => 'integer',
        'grado_id' => 'integer',
        'gestion_id' => 'integer',
    ];

    // Relaciones
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }

    public function gestion()
    {
        return $this->belongsTo(Gestion::class);
    }

    // Relación con notas
    public function notas()
    {
        return $this->hasMany(Nota::class);
    }

    // Scopes
    public function scopeMatriculados($query)
    {
        return $query->where('estado', 'Matriculado');
    }

    public function scopeRetirados($query)
    {
        return $query->where('estado', 'Retirado');
    }

    public function scopeAprobados($query)
    {
        return $query->where('estado', 'Aprobado');
    }

    public function scopeDesaprobados($query)
    {
        return $query->where('estado', 'Desaprobado');
    }

    public function scopePorEstudiante($query, $estudianteId)
    {
        return $query->where('estudiante_id', $estudianteId);
    }

    public function scopePorCurso($query, $cursoId)
    {
        return $query->where('curso_id', $cursoId);
    }

    public function scopePorGrado($query, $gradoId)
    {
        return $query->where('grado_id', $gradoId);
    }

    public function scopePorGestion($query, $gestionId)
    {
        return $query->where('gestion_id', $gestionId);
    }

    // Accessors
    public function getDescripcionCompletaAttribute()
    {
        $estudiante = $this->estudiante ? $this->estudiante->persona->nombres . ' ' . $this->estudiante->persona->apellidos : 'N/A';
        $curso = $this->curso ? $this->curso->nombre : 'N/A';
        $grado = $this->grado ? $this->grado->nombre_completo : 'N/A';
        $gestion = $this->gestion ? $this->gestion->nombre : 'N/A';
        
        return "{$estudiante} - {$curso} - {$grado} ({$gestion})";
    }

    public function getEstadoBadgeAttribute()
    {
        $badges = [
            'Matriculado' => 'primary',
            'Retirado' => 'warning',
            'Aprobado' => 'success',
            'Desaprobado' => 'danger',
        ];
        
        return $badges[$this->estado] ?? 'secondary';
    }

    // Métodos útiles
    public function estaMatriculado()
    {
        return $this->estado === 'Matriculado';
    }

    public function estaAprobado()
    {
        return $this->estado === 'Aprobado';
    }

    public function calcularPromedio()
    {
        return $this->notas()->avg('nota_final');
    }
}