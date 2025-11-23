<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocenteCurso extends Model
{
    use HasFactory;

    protected $table = 'docente_curso';

    protected $fillable = [
        'docente_id',
        'curso_id',
        'grado_id',
        'gestion_id',
        'es_tutor_aula',
    ];

    protected $casts = [
        'docente_id' => 'integer',
        'curso_id' => 'integer',
        'grado_id' => 'integer',
        'gestion_id' => 'integer',
        'es_tutor_aula' => 'boolean',
    ];

    // Relaciones
    public function docente()
    {
        return $this->belongsTo(Docente::class);
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

    // Scopes
    public function scopePorDocente($query, $docenteId)
    {
        return $query->where('docente_id', $docenteId);
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

    public function scopeTutores($query)
    {
        return $query->where('es_tutor_aula', true);
    }

    public function scopeNoTutores($query)
    {
        return $query->where('es_tutor_aula', false);
    }

    // Accessors
    public function getDescripcionCompletaAttribute()
    {
        $docente = $this->docente ? $this->docente->persona->nombres . ' ' . $this->docente->persona->apellidos : 'N/A';
        $curso = $this->curso ? $this->curso->nombre : 'N/A';
        $grado = $this->grado ? $this->grado->nombre_completo : 'N/A';
        $gestion = $this->gestion ? $this->gestion->nombre : 'N/A';
        
        return "{$docente} - {$curso} - {$grado} ({$gestion})";
    }

    // Métodos útiles
    public function esTutorAula()
    {
        return $this->es_tutor_aula;
    }
}