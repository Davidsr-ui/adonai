<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reporte extends Model
{
    use HasFactory;

    protected $fillable = [
        'estudiante_id',
        'docente_id',
        'periodo_id',
        'gestion_id',
        'tipo',
        'promedio_general',
        'porcentaje_asistencia',
        'comentario_final',
        'archivo_pdf',
        'visible_tutor',
        'fecha_generacion',
        'fecha_publicacion',
    ];

    protected $casts = [
        'estudiante_id' => 'integer',
        'docente_id' => 'integer',
        'periodo_id' => 'integer',
        'gestion_id' => 'integer',
        'promedio_general' => 'decimal:2',
        'porcentaje_asistencia' => 'decimal:2',
        'visible_tutor' => 'boolean',
        'fecha_generacion' => 'datetime',
        'fecha_publicacion' => 'datetime',
    ];

    // Relaciones
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }

    public function gestion()
    {
        return $this->belongsTo(Gestion::class);
    }

    // ✅ AGREGADO: Relación "dummy" para evitar error en vistas del tutor
    // Como la tabla NO tiene curso_id, obtenemos el curso del primer curso del docente
    public function curso()
    {
        // Retornar el primer curso del docente que generó el reporte
        // Esto es una solución temporal para vistas que esperan ->curso
        return $this->hasOneThrough(
            \App\Models\Curso::class,
            \App\Models\DocenteCurso::class,
            'docente_id', // FK en docente_curso
            'id', // PK en cursos
            'docente_id', // Local key en reportes
            'curso_id' // FK en docente_curso
        )->withDefault([
            'nombre' => 'Reporte General',
            'descripcion' => 'Sin curso específico'
        ]);
    }

    // Scopes
    public function scopeBimestrales($query)
    {
        return $query->where('tipo', 'Bimestral');
    }

    public function scopeTrimestrales($query)
    {
        return $query->where('tipo', 'Trimestral');
    }

    public function scopeAnuales($query)
    {
        return $query->where('tipo', 'Anual');
    }

    public function scopeVisiblesParaTutores($query)
    {
        return $query->where('visible_tutor', true);
    }

    public function scopePublicados($query)
    {
        return $query->whereNotNull('fecha_publicacion')
                     ->where('fecha_publicacion', '<=', now());
    }

    public function scopePorEstudiante($query, $estudianteId)
    {
        return $query->where('estudiante_id', $estudianteId);
    }

    public function scopePorDocente($query, $docenteId)
    {
        return $query->where('docente_id', $docenteId);
    }

    public function scopePorPeriodo($query, $periodoId)
    {
        return $query->where('periodo_id', $periodoId);
    }

    public function scopePorGestion($query, $gestionId)
    {
        return $query->where('gestion_id', $gestionId);
    }

    public function scopeConPdf($query)
    {
        return $query->whereNotNull('archivo_pdf');
    }

    public function scopeAprobados($query)
    {
        return $query->where('promedio_general', '>=', 11);
    }

    public function scopeDesaprobados($query)
    {
        return $query->where('promedio_general', '<', 11);
    }

    // Accessors
    public function getTipoBadgeAttribute()
    {
        $badges = [
            'Bimestral' => 'primary',
            'Trimestral' => 'success',
            'Anual' => 'danger',
        ];
        
        return $badges[$this->tipo] ?? 'secondary';
    }

    public function getEstadoPromedioBadgeAttribute()
    {
        if ($this->promedio_general >= 14) {
            return 'success';
        } elseif ($this->promedio_general >= 11) {
            return 'primary';
        } else {
            return 'danger';
        }
    }

    public function getEstadoPromedioTextoAttribute()
    {
        if ($this->promedio_general >= 18) {
            return 'Excelente';
        } elseif ($this->promedio_general >= 14) {
            return 'Bueno';
        } elseif ($this->promedio_general >= 11) {
            return 'Regular';
        } else {
            return 'Desaprobado';
        }
    }

    public function getEstadoAsistenciaBadgeAttribute()
    {
        if ($this->porcentaje_asistencia >= 90) {
            return 'success';
        } elseif ($this->porcentaje_asistencia >= 75) {
            return 'primary';
        } elseif ($this->porcentaje_asistencia >= 60) {
            return 'warning';
        } else {
            return 'danger';
        }
    }

    public function getFechaGeneracionFormateadaAttribute()
    {
        return $this->fecha_generacion ? Carbon::parse($this->fecha_generacion)->format('d/m/Y H:i') : 'No generado';
    }

    public function getFechaPublicacionFormateadaAttribute()
    {
        return $this->fecha_publicacion ? Carbon::parse($this->fecha_publicacion)->format('d/m/Y H:i') : 'No publicado';
    }

    // Métodos útiles
    public function estaAprobado()
    {
        return $this->promedio_general >= 11;
    }

    public function estaPublicado()
    {
        return $this->fecha_publicacion && Carbon::parse($this->fecha_publicacion)->isPast();
    }

    public function esVisibleParaTutor()
    {
        return $this->visible_tutor;
    }

    public function tienePdf()
    {
        return !empty($this->archivo_pdf) && file_exists(storage_path('app/public/' . $this->archivo_pdf));
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

    public function generarReporte()
    {
        $this->fecha_generacion = now();
        $this->save();
    }

    // Método para calcular datos automáticamente
    public function calcularDatos()
    {
        // Calcular promedio general
        $notas = \App\Models\Nota::whereHas('matricula', function($query) {
            $query->where('estudiante_id', $this->estudiante_id);
        })
        ->where('periodo_id', $this->periodo_id)
        ->get();

        if ($notas->count() > 0) {
            $this->promedio_general = $notas->avg('nota_final');
        }

        // Calcular porcentaje de asistencia
        $asistencias = \App\Models\Asistencia::where('estudiante_id', $this->estudiante_id)
            ->whereHas('curso.matriculas', function($query) {
                $query->where('estudiante_id', $this->estudiante_id);
            })
            ->get();

        if ($asistencias->count() > 0) {
            $presentes = $asistencias->where('estado', 'Presente')->count();
            $this->porcentaje_asistencia = ($presentes / $asistencias->count()) * 100;
        }

        $this->save();
    }

    // Método estático para obtener reportes por gestión
    public static function obtenerPorGestion($gestionId)
    {
        return self::where('gestion_id', $gestionId)
                   ->with(['estudiante.persona', 'periodo'])
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    // Método estático para obtener último reporte de un estudiante
    public static function obtenerUltimoReporte($estudianteId)
    {
        return self::where('estudiante_id', $estudianteId)
                   ->orderBy('fecha_generacion', 'desc')
                   ->first();
    }
}