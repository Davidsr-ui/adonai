<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Comportamiento extends Model
{
    use HasFactory;

    protected $table = 'comportamientos';

    protected $fillable = [
        'estudiante_id',
        'docente_id',
        'fecha',
        'descripcion',
        'tipo',
        'sancion',
        'notificado_tutor',
        'fecha_notificacion',
    ];

    protected $casts = [
        'estudiante_id' => 'integer',
        'docente_id' => 'integer',
        'fecha' => 'date',
        'notificado_tutor' => 'boolean',
        'fecha_notificacion' => 'datetime',
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

    // ✅ AGREGADO: Relación "dummy" para evitar error en vistas antiguas
    // Como la tabla NO tiene curso_id, retorna un valor por defecto
    public function curso()
    {
        // Relación dummy que siempre devuelve un objeto con valores por defecto
        return $this->belongsTo(\App\Models\Curso::class)->withDefault([
            'nombre' => 'Comportamiento General',
            'descripcion' => 'Sin curso específico'
        ]);
    }

    // Scopes
    public function scopePositivos($query)
    {
        return $query->where('tipo', 'Positivo');
    }

    public function scopeNegativos($query)
    {
        return $query->where('tipo', 'Negativo');
    }

    public function scopeNeutros($query)
    {
        return $query->where('tipo', 'Neutro');
    }

    public function scopeNotificados($query)
    {
        return $query->where('notificado_tutor', true);
    }

    public function scopePendientesNotificacion($query)
    {
        return $query->where('notificado_tutor', false);
    }

    public function scopePorEstudiante($query, $estudianteId)
    {
        return $query->where('estudiante_id', $estudianteId);
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

    public function scopeConSancion($query)
    {
        return $query->whereNotNull('sancion');
    }

    // Accessors
    public function getTipoBadgeAttribute()
    {
        $badges = [
            'Positivo' => 'success',
            'Negativo' => 'danger',
            'Neutro' => 'secondary',
        ];
        
        return $badges[$this->tipo] ?? 'secondary';
    }

    public function getTipoIconAttribute()
    {
        $icons = [
            'Positivo' => 'fa-smile',
            'Negativo' => 'fa-frown',
            'Neutro' => 'fa-meh',
        ];
        
        return $icons[$this->tipo] ?? 'fa-meh';
    }

    public function getFechaFormateadaAttribute()
    {
        return Carbon::parse($this->fecha)->format('d/m/Y');
    }

    public function getFechaNotificacionFormateadaAttribute()
    {
        return $this->fecha_notificacion ? Carbon::parse($this->fecha_notificacion)->format('d/m/Y H:i') : 'No notificado';
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
    public function esPositivo()
    {
        return $this->tipo === 'Positivo';
    }

    public function esNegativo()
    {
        return $this->tipo === 'Negativo';
    }

    public function esNeutro()
    {
        return $this->tipo === 'Neutro';
    }

    public function estaNotificado()
    {
        return $this->notificado_tutor;
    }

    public function tieneSancion()
    {
        return !empty($this->sancion);
    }

    public function notificarTutor()
    {
        $this->notificado_tutor = true;
        $this->fecha_notificacion = now();
        $this->save();
    }

    public function cancelarNotificacion()
    {
        $this->notificado_tutor = false;
        $this->fecha_notificacion = null;
        $this->save();
    }

    // Métodos estáticos para estadísticas
    public static function contarPorEstudiante($estudianteId, $tipo = null)
    {
        $query = self::where('estudiante_id', $estudianteId);
        
        if ($tipo) {
            $query->where('tipo', $tipo);
        }
        
        return $query->count();
    }

    public static function obtenerResumenPorEstudiante($estudianteId)
    {
        return [
            'total' => self::where('estudiante_id', $estudianteId)->count(),
            'positivos' => self::where('estudiante_id', $estudianteId)->where('tipo', 'Positivo')->count(),
            'negativos' => self::where('estudiante_id', $estudianteId)->where('tipo', 'Negativo')->count(),
            'neutros' => self::where('estudiante_id', $estudianteId)->where('tipo', 'Neutro')->count(),
            'con_sancion' => self::where('estudiante_id', $estudianteId)->whereNotNull('sancion')->count(),
            'notificados' => self::where('estudiante_id', $estudianteId)->where('notificado_tutor', true)->count(),
        ];
    }

    public static function obtenerUltimosComportamientos($estudianteId, $limite = 10)
    {
        return self::where('estudiante_id', $estudianteId)
                   ->with('docente.persona')
                   ->orderBy('fecha', 'desc')
                   ->limit($limite)
                   ->get();
    }
}