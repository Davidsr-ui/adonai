<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Administrador extends Model
{
    use HasFactory;

    /**
     * CRÍTICO: Laravel pluraliza mal "administrador" a "administradors"
     * Por eso debemos especificar el nombre correcto de la tabla
     */
    protected $table = 'administradores';

    protected $fillable = [
        'persona_id',
        'cargo',
        'area',
        'fecha_asignacion',
    ];

    protected $casts = [
        'persona_id' => 'integer',
        'fecha_asignacion' => 'date',
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

    // =========================================
    // SCOPES
    // =========================================

    /**
     * Scope para directores
     */
    public function scopeDirectores($query)
    {
        return $query->where('cargo', 'Director');
    }

    /**
     * Scope para subdirectores
     */
    public function scopeSubdirectores($query)
    {
        return $query->where('cargo', 'Subdirector');
    }

    /**
     * Scope para secretarios
     */
    public function scopeSecretarios($query)
    {
        return $query->where('cargo', 'Secretario');
    }

    /**
     * Scope para administrativos
     */
    public function scopeAdministrativos($query)
    {
        return $query->where('cargo', 'Administrativo');
    }

    /**
     * Scope por cargo
     */
    public function scopePorCargo($query, $cargo)
    {
        return $query->where('cargo', $cargo);
    }

    /**
     * Scope por área
     */
    public function scopePorArea($query, $area)
    {
        return $query->where('area', $area);
    }

    /**
     * Scope para administradores activos
     */
    public function scopeActivos($query)
    {
        return $query->whereHas('persona', function($q) {
            $q->where('estado', 'Activo');
        });
    }

    /**
     * Scope para administradores inactivos
     */
    public function scopeInactivos($query)
    {
        return $query->whereHas('persona', function($q) {
            $q->where('estado', 'Inactivo');
        });
    }

    /**
     * Scope para asignados este año
     */
    public function scopeAsignadosEsteAño($query)
    {
        return $query->whereYear('fecha_asignacion', date('Y'));
    }

    /**
     * Scope para buscar administradores
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->whereHas('persona', function($q) use ($termino) {
            $q->where('nombres', 'like', "%{$termino}%")
              ->orWhere('apellidos', 'like', "%{$termino}%")
              ->orWhere('dni', 'like', "%{$termino}%");
        })->orWhere('cargo', 'like', "%{$termino}%")
          ->orWhere('area', 'like', "%{$termino}%");
    }

    /**
     * Scope para administradores con área
     */
    public function scopeConArea($query)
    {
        return $query->whereNotNull('area');
    }

    /**
     * Scope para administradores sin área
     */
    public function scopeSinArea($query)
    {
        return $query->whereNull('area');
    }

    // =========================================
    // ACCESSORS
    // =========================================

    /**
     * Obtener badge color según cargo
     */
    public function getCargoBadgeAttribute()
    {
        $badges = [
            'Director' => 'danger',
            'Subdirector' => 'warning',
            'Secretario' => 'primary',
            'Administrativo' => 'info',
        ];
        
        return $badges[$this->cargo] ?? 'secondary';
    }

    /**
     * Obtener icono según cargo
     */
    public function getCargoIconAttribute()
    {
        $icons = [
            'Director' => 'fa-user-tie',
            'Subdirector' => 'fa-user-cog',
            'Secretario' => 'fa-user-edit',
            'Administrativo' => 'fa-user-shield',
        ];
        
        return $icons[$this->cargo] ?? 'fa-user';
    }

    /**
     * Obtener nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return $this->persona 
            ? $this->persona->nombres . ' ' . $this->persona->apellidos 
            : 'N/A';
    }

    /**
     * Obtener fecha asignación formateada
     */
    public function getFechaAsignacionFormateadaAttribute()
    {
        return $this->fecha_asignacion 
            ? Carbon::parse($this->fecha_asignacion)->format('d/m/Y') 
            : 'No asignada';
    }

    /**
     * Calcular antigüedad
     */
    public function getAntiguedadAttribute()
    {
        if (!$this->fecha_asignacion) {
            return 'No disponible';
        }

        $fecha = Carbon::parse($this->fecha_asignacion);
        $ahora = Carbon::now();
        
        $años = $fecha->diffInYears($ahora);
        $meses = $fecha->copy()->addYears($años)->diffInMonths($ahora);
        
        if ($años > 0) {
            return $años . ' año' . ($años > 1 ? 's' : '') . 
                   ($meses > 0 ? ' y ' . $meses . ' mes' . ($meses > 1 ? 'es' : '') : '');
        }
        
        return $meses . ' mes' . ($meses > 1 ? 'es' : '');
    }

    /**
     * Obtener estado de la persona
     */
    public function getEstadoPersonaAttribute()
    {
        return $this->persona->estado ?? 'Desconocido';
    }

    /**
     * Obtener badge de estado de persona
     */
    public function getEstadoPersonaBadgeAttribute()
    {
        $estado = $this->estado_persona;
        
        $badges = [
            'Activo' => 'success',
            'Inactivo' => 'secondary',
            'Suspendido' => 'warning',
        ];
        
        return $badges[$estado] ?? 'secondary';
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
     * Obtener DNI
     */
    public function getDniAttribute()
    {
        return $this->persona->dni ?? 'N/A';
    }

    /**
     * Obtener teléfono
     */
    public function getTelefonoAttribute()
    {
        return $this->persona->telefono ?? 'N/A';
    }

    /**
     * Obtener email
     */
    public function getEmailAttribute()
    {
        return $this->persona->user->email ?? 'N/A';
    }

    /**
     * Obtener avatar
     */
    public function getAvatarAttribute()
    {
        if ($this->persona && $this->persona->foto_perfil) {
            return asset('storage/' . $this->persona->foto_perfil);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nombre_completo) . '&size=200&background=random';
    }

    // =========================================
    // MÉTODOS ÚTILES
    // =========================================

    /**
     * Verificar si es director
     */
    public function esDirector()
    {
        return $this->cargo === 'Director';
    }

    /**
     * Verificar si es subdirector
     */
    public function esSubdirector()
    {
        return $this->cargo === 'Subdirector';
    }

    /**
     * Verificar si es secretario
     */
    public function esSecretario()
    {
        return $this->cargo === 'Secretario';
    }

    /**
     * Verificar si es administrativo
     */
    public function esAdministrativo()
    {
        return $this->cargo === 'Administrativo';
    }

    /**
     * Verificar si está activo
     */
    public function estaActivo()
    {
        return $this->persona && $this->persona->estado === 'Activo';
    }

    /**
     * Verificar si tiene área asignada
     */
    public function tieneArea()
    {
        return !empty($this->area);
    }

    /**
     * Cambiar cargo
     */
    public function cambiarCargo($nuevoCargo)
    {
        $this->cargo = $nuevoCargo;
        $this->save();
    }

    /**
     * Asignar área
     */
    public function asignarArea($area)
    {
        $this->area = $area;
        $this->save();
    }

    /**
     * Activar administrador
     */
    public function activar()
    {
        if ($this->persona) {
            $this->persona->estado = 'Activo';
            $this->persona->save();
        }
    }

    /**
     * Desactivar administrador
     */
    public function desactivar()
    {
        if ($this->persona) {
            $this->persona->estado = 'Inactivo';
            $this->persona->save();
        }
    }

    /**
     * Actualizar fecha de asignación
     */
    public function actualizarFechaAsignacion($fecha = null)
    {
        $this->fecha_asignacion = $fecha ?? now();
        $this->save();
    }

    // =========================================
    // MÉTODOS ESTÁTICOS
    // =========================================

    /**
     * Contar por cargo
     */
    public static function contarPorCargo($cargo)
    {
        return self::where('cargo', $cargo)->count();
    }

    /**
     * Obtener estadísticas
     */
    public static function obtenerEstadisticas()
    {
        return [
            'total' => self::count(),
            'directores' => self::where('cargo', 'Director')->count(),
            'subdirectores' => self::where('cargo', 'Subdirector')->count(),
            'secretarios' => self::where('cargo', 'Secretario')->count(),
            'administrativos' => self::where('cargo', 'Administrativo')->count(),
            'activos' => self::whereHas('persona', function($q) {
                $q->where('estado', 'Activo');
            })->count(),
            'inactivos' => self::whereHas('persona', function($q) {
                $q->where('estado', 'Inactivo');
            })->count(),
            'con_area' => self::whereNotNull('area')->count(),
            'sin_area' => self::whereNull('area')->count(),
        ];
    }

    /**
     * Obtener administradores por cargo
     */
    public static function obtenerPorCargo($cargo)
    {
        return self::where('cargo', $cargo)
                   ->with('persona')
                   ->whereHas('persona', function($q) {
                       $q->where('estado', 'Activo');
                   })
                   ->get();
    }

    /**
     * Obtener el director actual
     */
    public static function obtenerDirector()
    {
        return self::where('cargo', 'Director')
                   ->with('persona')
                   ->whereHas('persona', function($q) {
                       $q->where('estado', 'Activo');
                   })
                   ->first();
    }

    /**
     * Obtener áreas disponibles
     */
    public static function obtenerAreasDisponibles()
    {
        return self::whereNotNull('area')
                   ->distinct()
                   ->pluck('area')
                   ->toArray();
    }

    /**
     * Buscar por DNI
     */
    public static function buscarPorDni($dni)
    {
        return self::whereHas('persona', function($q) use ($dni) {
            $q->where('dni', $dni);
        })->first();
    }

    /**
     * Obtener por área
     */
    public static function obtenerPorArea($area)
    {
        return self::where('area', $area)
                   ->with('persona')
                   ->whereHas('persona', function($q) {
                       $q->where('estado', 'Activo');
                   })
                   ->get();
    }

    /**
     * Verificar si existe director activo
     */
    public static function existeDirectorActivo()
    {
        return self::where('cargo', 'Director')
                   ->whereHas('persona', function($q) {
                       $q->where('estado', 'Activo');
                   })
                   ->exists();
    }
}