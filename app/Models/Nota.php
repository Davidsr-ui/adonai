<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Nota extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricula_id',
        'periodo_id',
        'docente_id',
        'nota_practica',
        'nota_teoria',
        'nota_final',
        'tipo_evaluacion',
        'descripcion',
        'observaciones',
        'fecha_evaluacion',
        'visible_tutor',
        'fecha_publicacion',
    ];

    protected $casts = [
        'matricula_id' => 'integer',
        'periodo_id' => 'integer',
        'docente_id' => 'integer',
        'nota_practica' => 'decimal:2',
        'nota_teoria' => 'decimal:2',
        'nota_final' => 'decimal:2',
        'fecha_evaluacion' => 'date',
        'visible_tutor' => 'boolean',
        'fecha_publicacion' => 'datetime',
    ];

    // Relaciones
    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    // Relaciones a través de matrícula
    public function estudiante()
    {
        return $this->hasOneThrough(Estudiante::class, Matricula::class, 'id', 'id', 'matricula_id', 'estudiante_id');
    }

    public function curso()
    {
        return $this->hasOneThrough(Curso::class, Matricula::class, 'id', 'id', 'matricula_id', 'curso_id');
    }

    // Scopes
    public function scopeParciales($query)
    {
        return $query->where('tipo_evaluacion', 'Parcial');
    }

    public function scopeFinales($query)
    {
        return $query->where('tipo_evaluacion', 'Final');
    }

    public function scopePracticas($query)
    {
        return $query->where('tipo_evaluacion', 'Práctica');
    }

    public function scopeOrales($query)
    {
        return $query->where('tipo_evaluacion', 'Oral');
    }

    public function scopeTrabajos($query)
    {
        return $query->where('tipo_evaluacion', 'Trabajo');
    }

    public function scopeVisiblesParaTutores($query)
    {
        return $query->where('visible_tutor', true);
    }

    public function scopePublicadas($query)
    {
        return $query->whereNotNull('fecha_publicacion')
                     ->where('fecha_publicacion', '<=', now());
    }

    public function scopePorMatricula($query, $matriculaId)
    {
        return $query->where('matricula_id', $matriculaId);
    }

    public function scopePorPeriodo($query, $periodoId)
    {
        return $query->where('periodo_id', $periodoId);
    }

    public function scopePorDocente($query, $docenteId)
    {
        return $query->where('docente_id', $docenteId);
    }

    public function scopeAprobadas($query)
    {
        return $query->where('nota_final', '>=', 11);
    }

    public function scopeDesaprobadas($query)
    {
        return $query->where('nota_final', '<', 11);
    }

    // Accessors
    public function getTipoEvaluacionBadgeAttribute()
    {
        $badges = [
            'Parcial' => 'primary',
            'Final' => 'danger',
            'Práctica' => 'success',
            'Oral' => 'info',
            'Trabajo' => 'warning',
        ];
        
        return $badges[$this->tipo_evaluacion] ?? 'secondary';
    }

    public function getEstadoNotaBadgeAttribute()
    {
        if ($this->nota_final >= 14) {
            return 'success'; // Excelente
        } elseif ($this->nota_final >= 11) {
            return 'primary'; // Aprobado
        } else {
            return 'danger'; // Desaprobado
        }
    }

    public function getEstadoNotaTextoAttribute()
    {
        if ($this->nota_final >= 18) {
            return 'Excelente';
        } elseif ($this->nota_final >= 14) {
            return 'Bueno';
        } elseif ($this->nota_final >= 11) {
            return 'Regular';
        } else {
            return 'Desaprobado';
        }
    }

    public function getFechaEvaluacionFormateadaAttribute()
    {
        return $this->fecha_evaluacion ? Carbon::parse($this->fecha_evaluacion)->format('d/m/Y') : 'No registrada';
    }

    public function getFechaPublicacionFormateadaAttribute()
    {
        return $this->fecha_publicacion ? Carbon::parse($this->fecha_publicacion)->format('d/m/Y H:i') : 'No publicada';
    }

    // Métodos útiles
    public function estaAprobada()
    {
        return $this->nota_final >= 11;
    }

    public function estaPublicada()
    {
        return $this->fecha_publicacion && Carbon::parse($this->fecha_publicacion)->isPast();
    }

    public function esVisibleParaTutor()
    {
        return $this->visible_tutor;
    }

    public function publicar()
    {
        $this->fecha_publicacion = now();
        $this->visible_tutor = true;
        $this->save();
    }

    public function despublicar()
    {
        $this->fecha_publicacion = null;
        $this->visible_tutor = false;
        $this->save();
    }

    // Método estático para calcular promedio
    public static function calcularPromedioPorMatricula($matriculaId, $periodoId = null)
    {
        $query = self::where('matricula_id', $matriculaId);
        
        if ($periodoId) {
            $query->where('periodo_id', $periodoId);
        }
        
        return $query->avg('nota_final');
    }

    public static function calcularPromedioPorEstudiante($estudianteId, $periodoId = null)
    {
        $query = self::whereHas('matricula', function($q) use ($estudianteId) {
            $q->where('estudiante_id', $estudianteId);
        });
        
        if ($periodoId) {
            $query->where('periodo_id', $periodoId);
        }
        
        return $query->avg('nota_final');
    }

    // Calcular nota final automáticamente
    public function calcularNotaFinal()
    {
        if ($this->nota_practica !== null && $this->nota_teoria !== null) {
            // Promedio de práctica y teoría
            $this->nota_final = ($this->nota_practica + $this->nota_teoria) / 2;
        } elseif ($this->nota_practica !== null) {
            // Solo práctica
            $this->nota_final = $this->nota_practica;
        } elseif ($this->nota_teoria !== null) {
            // Solo teoría
            $this->nota_final = $this->nota_teoria;
        }
        
        return $this->nota_final;
    }
}