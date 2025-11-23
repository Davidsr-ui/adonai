<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horarios';
    
    protected $fillable = [
        'gestion_id',
        'curso_id',
        'grado_id',
        'docente_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'aula'
    ];

    // ✅ CASTS AGREGADOS: Asegurar que las horas se manejen correctamente
    protected $casts = [
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
    ];

    // =========================================
    // RELACIONES
    // =========================================

    /**
     * Relación con gestión (N:1)
     */
    public function gestion()
    {
        return $this->belongsTo(Gestion::class);
    }

    /**
     * Relación con curso (N:1)
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * Relación con grado (N:1)
     */
    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }

    /**
     * Relación con docente (N:1)
     */
    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    // =========================================
    // SCOPES
    // =========================================

    /**
     * Scope para ordenar por día de la semana y hora
     */
    public function scopeOrdenado($query)
    {
        return $query->orderByRaw("FIELD(dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado')")
                     ->orderBy('hora_inicio', 'asc');
    }

    /**
     * Scope para filtrar por gestión
     */
    public function scopePorGestion($query, $gestionId)
    {
        return $query->where('gestion_id', $gestionId);
    }

    /**
     * Scope para filtrar por grado
     */
    public function scopePorGrado($query, $gradoId)
    {
        return $query->where('grado_id', $gradoId);
    }

    /**
     * Scope para filtrar por docente
     */
    public function scopePorDocente($query, $docenteId)
    {
        return $query->where('docente_id', $docenteId);
    }

    /**
     * Scope para filtrar por día
     */
    public function scopePorDia($query, $dia)
    {
        return $query->where('dia_semana', $dia);
    }

    // =========================================
    // ACCESSORS
    // =========================================

    /**
     * Obtener el nombre completo del docente
     */
    public function getNombreDocenteAttribute()
    {
        if ($this->docente && $this->docente->persona) {
            return $this->docente->persona->apellidos . ' ' . $this->docente->persona->nombres;
        }
        return 'Sin asignar';
    }

    /**
     * Obtener información completa del horario
     */
    public function getDescripcionCompletaAttribute()
    {
        return sprintf(
            '%s - %s (%s - %s) - %s',
            $this->dia_semana,
            $this->curso->nombre ?? '',
            $this->hora_inicio,
            $this->hora_fin,
            $this->aula ?? 'Sin aula'
        );
    }

    /**
     * Verificar si hay conflicto de horario
     */
    public function tieneConflicto($gestion_id, $grado_id, $dia_semana, $hora_inicio, $hora_fin, $excluir_id = null)
    {
        $query = self::where('gestion_id', $gestion_id)
            ->where('grado_id', $grado_id)
            ->where('dia_semana', $dia_semana)
            ->where(function ($q) use ($hora_inicio, $hora_fin) {
                $q->whereBetween('hora_inicio', [$hora_inicio, $hora_fin])
                  ->orWhereBetween('hora_fin', [$hora_inicio, $hora_fin])
                  ->orWhere(function ($q2) use ($hora_inicio, $hora_fin) {
                      $q2->where('hora_inicio', '<=', $hora_inicio)
                         ->where('hora_fin', '>=', $hora_fin);
                  });
            });

        if ($excluir_id) {
            $query->where('id', '!=', $excluir_id);
        }

        return $query->exists();
    }
}