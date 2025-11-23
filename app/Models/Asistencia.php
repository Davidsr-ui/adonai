<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Asistencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'estudiante_id',
        'curso_id',
        'docente_id',
        'fecha',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'estudiante_id' => 'integer',
        'curso_id' => 'integer',
        'docente_id' => 'integer',
        'fecha' => 'date',
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

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    // Scopes
    public function scopePresente($query)
    {
        return $query->where('estado', 'Presente');
    }

    public function scopeAusente($query)
    {
        return $query->where('estado', 'Ausente');
    }

    public function scopeTardanza($query)
    {
        return $query->where('estado', 'Tardanza');
    }

    public function scopeJustificado($query)
    {
        return $query->where('estado', 'Justificado');
    }

    public function scopePorEstudiante($query, $estudianteId)
    {
        return $query->where('estudiante_id', $estudianteId);
    }

    public function scopePorCurso($query, $cursoId)
    {
        return $query->where('curso_id', $cursoId);
    }

    public function scopePorDocente($query, $docenteId)
    {
        return $query->where('docente_id', $docenteId);
    }

    public function scopePorFecha($query, $fecha)
    {
        return $query->whereDate('fecha', $fecha);
    }

    public function scopePorRangoFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
    }

    public function scopeDelMes($query, $mes, $año)
    {
        return $query->whereMonth('fecha', $mes)
                     ->whereYear('fecha', $año);
    }

    // Accessors
    public function getEstadoBadgeAttribute()
    {
        $badges = [
            'Presente' => 'success',
            'Ausente' => 'danger',
            'Tardanza' => 'warning',
            'Justificado' => 'info',
        ];
        
        return $badges[$this->estado] ?? 'secondary';
    }

    public function getFechaFormateadaAttribute()
    {
        return Carbon::parse($this->fecha)->format('d/m/Y');
    }

    public function getDiaSemanaAttribute()
    {
        $dias = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo',
        ];
        
        return $dias[Carbon::parse($this->fecha)->format('l')] ?? '';
    }

    // Métodos útiles
    public function esPresente()
    {
        return $this->estado === 'Presente';
    }

    public function esAusente()
    {
        return $this->estado === 'Ausente';
    }

    public function esTardanza()
    {
        return $this->estado === 'Tardanza';
    }

    public function esJustificado()
    {
        return $this->estado === 'Justificado';
    }

    // Método estático para calcular porcentaje de asistencia
    public static function calcularPorcentajeAsistencia($estudianteId, $cursoId = null, $fechaInicio = null, $fechaFin = null)
    {
        $query = self::where('estudiante_id', $estudianteId);
        
        if ($cursoId) {
            $query->where('curso_id', $cursoId);
        }
        
        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        }
        
        $total = $query->count();
        
        if ($total == 0) {
            return 0;
        }
        
        $presentes = $query->where('estado', 'Presente')->count();
        
        return round(($presentes / $total) * 100, 2);
    }
}