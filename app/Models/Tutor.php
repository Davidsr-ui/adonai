<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    use HasFactory;

    /**
     * CRÍTICO: Laravel pluraliza mal "tutor" a "tutors"
     * Por eso debemos especificar el nombre correcto de la tabla
     */
    protected $table = 'tutores';
    
    protected $fillable = [
        'persona_id',
        'codigo_tutor',
        'ocupacion'
    ];

    // =========================================
    // RELACIONES
    // =========================================

    /**
     * Relación con Persona (1:1)
     */
    public function persona() 
    {
        return $this->belongsTo(Persona::class);
    }

    /**
     * Relación con Estudiantes (N:N)
     */
    public function estudiantes()
    {
        return $this->belongsToMany(Estudiante::class, 'tutor_estudiante', 'tutor_id', 'estudiante_id')
                    ->withPivot('relacion_familiar', 'tipo', 'autorizacion_recojo', 'estado')
                    ->withTimestamps();
    }

    /**
     * Relación con TutorEstudiante (1:N)
     */
    public function tutorEstudiantes()
    {
        return $this->hasMany(TutorEstudiante::class, 'tutor_id');
    }

    // =========================================
    // SCOPES
    // =========================================

    /**
     * Scope para tutores activos
     */
    public function scopeActivo($query)
    {
        return $query->whereHas('persona', function($q) {
            $q->where('estado', 'Activo');
        });
    }

    /**
     * Alias para scopeActivo (plural)
     */
    public function scopeActivos($query)
    {
        return $this->scopeActivo($query);
    }

    /**
     * Scope para tutores inactivos
     */
    public function scopeInactivos($query)
    {
        return $query->whereHas('persona', function($q) {
            $q->where('estado', 'Inactivo');
        });
    }

    /**
     * Scope para buscar tutores
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->whereHas('persona', function($q) use ($termino) {
            $q->where('nombres', 'like', "%{$termino}%")
              ->orWhere('apellidos', 'like', "%{$termino}%")
              ->orWhere('dni', 'like', "%{$termino}%");
        })->orWhere('codigo_tutor', 'like', "%{$termino}%");
    }

    /**
     * Scope para tutores principales
     */
    public function scopeTutoresPrincipales($query)
    {
        return $query->whereHas('tutorEstudiantes', function($q) {
            $q->where('tipo', 'Principal')
              ->where('estado', 'Activo');
        });
    }

    /**
     * Scope para tutores por ocupación
     */
    public function scopePorOcupacion($query, $ocupacion)
    {
        return $query->where('ocupacion', 'like', "%{$ocupacion}%");
    }

    // =========================================
    // ACCESSORS
    // =========================================

    /**
     * Obtener nombres del tutor
     */
    public function getNombresAttribute()
    {
        return $this->persona->nombres ?? 'N/A';
    }

    /**
     * Obtener apellidos del tutor
     */
    public function getApellidosAttribute()
    {
        return $this->persona->apellidos ?? 'N/A';
    }

    /**
     * Obtener nombre completo del tutor
     */
    public function getNombreCompletoAttribute()
    {
        return $this->persona 
            ? $this->persona->nombres . ' ' . $this->persona->apellidos 
            : 'N/A';
    }

    /**
     * Obtener estado del tutor
     */
    public function getEstadoAttribute()
    {
        return $this->persona->estado ?? 'Desconocido';
    }

    /**
     * Obtener DNI del tutor
     */
    public function getDniAttribute()
    {
        return $this->persona->dni ?? 'N/A';
    }

    /**
     * Obtener teléfono del tutor
     */
    public function getTelefonoAttribute()
    {
        return $this->persona->telefono ?? 'N/A';
    }

    /**
     * Verificar si está activo
     */
    public function getEstaActivoAttribute()
    {
        return $this->persona && $this->persona->estado === 'Activo';
    }

    /**
     * Obtener badge de estado
     */
    public function getBadgeEstadoAttribute()
    {
        return $this->esta_activo ? 'success' : 'danger';
    }

    /**
     * Obtener texto de estado
     */
    public function getTextoEstadoAttribute()
    {
        return $this->esta_activo ? 'Activo' : 'Inactivo';
    }

    /**
     * Contar estudiantes a cargo
     */
    public function getCantidadEstudiantesAttribute()
    {
        return $this->tutorEstudiantes()->where('estado', 'Activo')->count();
    }

    /**
     * Obtener avatar o foto
     */
    public function getAvatarAttribute()
    {
        if ($this->persona && $this->persona->foto_perfil) {
            return asset('storage/' . $this->persona->foto_perfil);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nombre_completo) . '&size=200&background=random';
    }

    /**
     * Obtener dirección
     */
    public function getDireccionAttribute()
    {
        return $this->persona->direccion ?? 'No registrada';
    }

    /**
     * Obtener teléfono emergencia
     */
    public function getTelefonoEmergenciaAttribute()
    {
        return $this->persona->telefono_emergencia ?? 'No registrado';
    }

    /**
     * Obtener género
     */
    public function getGeneroAttribute()
    {
        return $this->persona->genero ?? 'N/A';
    }

    // =========================================
    // MÉTODOS ÚTILES
    // =========================================

    /**
     * Obtener todos los estudiantes activos del tutor
     */
    public function estudiantesActivos()
    {
        return $this->tutorEstudiantes()
                    ->where('estado', 'Activo')
                    ->with('estudiante.persona');
    }

    /**
     * Verificar si es tutor principal de algún estudiante
     */
    public function esTutorPrincipal()
    {
        return $this->tutorEstudiantes()
                    ->where('tipo', 'Principal')
                    ->where('estado', 'Activo')
                    ->exists();
    }

    /**
     * Obtener estudiantes que puede recoger
     */
    public function estudiantesQueRecoger()
    {
        return $this->tutorEstudiantes()
                    ->where('autorizacion_recojo', true)
                    ->where('estado', 'Activo')
                    ->with('estudiante.persona')
                    ->get();
    }

    /**
     * Verificar si puede recoger a un estudiante específico
     */
    public function puedeRecogerEstudiante($estudianteId)
    {
        return $this->tutorEstudiantes()
                    ->where('estudiante_id', $estudianteId)
                    ->where('autorizacion_recojo', true)
                    ->where('estado', 'Activo')
                    ->exists();
    }

    /**
     * Obtener estudiantes por tipo de relación
     */
    public function estudiantesPorRelacion($relacion)
    {
        return $this->tutorEstudiantes()
                    ->where('relacion_familiar', $relacion)
                    ->where('estado', 'Activo')
                    ->with('estudiante.persona')
                    ->get();
    }

    /**
     * Activar tutor
     */
    public function activar()
    {
        if ($this->persona) {
            $this->persona->estado = 'Activo';
            $this->persona->save();
        }
    }

    /**
     * Desactivar tutor
     */
    public function desactivar()
    {
        if ($this->persona) {
            $this->persona->estado = 'Inactivo';
            $this->persona->save();
        }
    }

    /**
     * Verificar si tiene estudiantes asignados
     */
    public function tieneEstudiantes()
    {
        return $this->tutorEstudiantes()->where('estado', 'Activo')->count() > 0;
    }

    /**
     * Obtener tipos de relación con estudiantes
     */
    public function tiposRelacion()
    {
        return $this->tutorEstudiantes()
                    ->where('estado', 'Activo')
                    ->distinct()
                    ->pluck('relacion_familiar')
                    ->toArray();
    }

    // =========================================
    // MÉTODOS ESTÁTICOS
    // =========================================

    /**
     * Obtener estadísticas de tutores
     */
    public static function obtenerEstadisticas()
    {
        return [
            'total' => self::count(),
            'activos' => self::whereHas('persona', function($q) {
                $q->where('estado', 'Activo');
            })->count(),
            'inactivos' => self::whereHas('persona', function($q) {
                $q->where('estado', 'Inactivo');
            })->count(),
            'principales' => self::whereHas('tutorEstudiantes', function($q) {
                $q->where('tipo', 'Principal')->where('estado', 'Activo');
            })->count(),
            'secundarios' => self::whereHas('tutorEstudiantes', function($q) {
                $q->where('tipo', 'Secundario')->where('estado', 'Activo');
            })->count(),
            'con_estudiantes' => self::has('tutorEstudiantes')->count(),
            'sin_estudiantes' => self::doesntHave('tutorEstudiantes')->count(),
        ];
    }

    /**
     * Buscar tutor por DNI
     */
    public static function buscarPorDni($dni)
    {
        return self::whereHas('persona', function($q) use ($dni) {
            $q->where('dni', $dni);
        })->first();
    }

    /**
     * Buscar tutor por código
     */
    public static function buscarPorCodigo($codigo)
    {
        return self::where('codigo_tutor', $codigo)->first();
    }

    /**
     * Obtener tutores sin estudiantes
     */
    public static function sinEstudiantes()
    {
        return self::doesntHave('tutorEstudiantes')
                   ->with('persona')
                   ->get();
    }

    /**
     * Obtener tutores con más estudiantes
     */
    public static function conMasEstudiantes($limit = 10)
    {
        return self::withCount(['tutorEstudiantes' => function($q) {
                    $q->where('estado', 'Activo');
                }])
                ->orderByDesc('tutor_estudiantes_count')
                ->limit($limit)
                ->get();
    }
}