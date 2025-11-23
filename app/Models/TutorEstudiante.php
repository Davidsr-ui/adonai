<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorEstudiante extends Model
{
    use HasFactory;

    protected $table = 'tutor_estudiante';

    protected $fillable = [
        'tutor_id',
        'estudiante_id',
        'relacion_familiar',
        'tipo',
        'autorizacion_recojo',
        'estado',
    ];

    protected $casts = [
        'tutor_id' => 'integer',
        'estudiante_id' => 'integer',
        'autorizacion_recojo' => 'boolean',
    ];

    // Relaciones
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('estado', 'Activo');
    }

    public function scopeInactivos($query)
    {
        return $query->where('estado', 'Inactivo');
    }

    public function scopePrincipales($query)
    {
        return $query->where('tipo', 'Principal');
    }

    public function scopeSecundarios($query)
    {
        return $query->where('tipo', 'Secundario');
    }

    public function scopePorTutor($query, $tutorId)
    {
        return $query->where('tutor_id', $tutorId);
    }

    public function scopePorEstudiante($query, $estudianteId)
    {
        return $query->where('estudiante_id', $estudianteId);
    }

    public function scopeConAutorizacionRecojo($query)
    {
        return $query->where('autorizacion_recojo', true);
    }

    // Accessors
    public function getDescripcionCompletaAttribute()
    {
        $tutor = $this->tutor ? $this->tutor->persona->nombres . ' ' . $this->tutor->persona->apellidos : 'N/A';
        $estudiante = $this->estudiante ? $this->estudiante->persona->nombres . ' ' . $this->estudiante->persona->apellidos : 'N/A';
        
        return "{$tutor} ({$this->relacion_familiar}) - {$estudiante}";
    }

    public function getEstadoBadgeAttribute()
    {
        return $this->estado === 'Activo' ? 'success' : 'secondary';
    }

    public function getTipoBadgeAttribute()
    {
        return $this->tipo === 'Principal' ? 'primary' : 'info';
    }

    // Métodos útiles
    public function esPrincipal()
    {
        return $this->tipo === 'Principal';
    }

    public function tieneAutorizacionRecojo()
    {
        return $this->autorizacion_recojo;
    }

    public function esActivo()
    {
        return $this->estado === 'Activo';
    }
}