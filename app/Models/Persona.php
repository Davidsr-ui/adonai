<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model
{
    use SoftDeletes;

    protected $table = 'personas';
    
    protected $fillable = [
        'user_id',
        'dni',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'genero',
        'direccion',
        'telefono',
        'telefono_emergencia',
        'foto_perfil',
        'estado'
    ];

    protected $dates = ['fecha_nacimiento', 'deleted_at'];

    // =========================================
    // RELACIONES
    // =========================================

    /**
     * Relación con User (N:1)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con Docente (1:1)
     */
    public function docente()
    {
        return $this->hasOne(Docente::class);
    }

    /**
     * Relación con Estudiante (1:1)
     */
    public function estudiante()
    {
        return $this->hasOne(Estudiante::class);
    }

    /**
     * Relación con Tutor (1:1)
     */
    public function tutor()
    {
        return $this->hasOne(Tutor::class);
    }

    /**
     * Relación con Administrador (1:1)
     */
    public function administrador()
    {
        return $this->hasOne(Administrador::class);
    }

    // =========================================
    // SCOPES
    // =========================================

    /**
     * Scope para personas activas
     */
    public function scopeActivo($query)
    {
        return $query->where('estado', 'Activo');
    }

    /**
     * Scope para personas inactivas
     */
    public function scopeInactivo($query)
    {
        return $query->where('estado', 'Inactivo');
    }

    /**
     * Scope para personas disponibles (sin rol asignado)
     */
    public function scopeDisponibles($query)
    {
        return $query->whereDoesntHave('administrador')
                    ->whereDoesntHave('docente')
                    ->whereDoesntHave('estudiante')
                    ->where('estado', 'Activo');
    }

    /**
     * Scope para buscar por DNI, nombres o apellidos
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where('dni', 'like', "%{$termino}%")
                    ->orWhere('nombres', 'like', "%{$termino}%")
                    ->orWhere('apellidos', 'like', "%{$termino}%");
    }

    // =========================================
    // ACCESSORS
    // =========================================

    /**
     * Accessor para nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return $this->apellidos . ' ' . $this->nombres;
    }

    /**
     * Accessor para edad
     */
    public function getEdadAttribute()
    {
        if (!$this->fecha_nacimiento) {
            return null;
        }
        return \Carbon\Carbon::parse($this->fecha_nacimiento)->age;
    }

    /**
     * Accessor para foto de perfil
     */
    public function getFotoPerfilUrlAttribute()
    {
        if ($this->foto_perfil) {
            return asset('storage/' . $this->foto_perfil);
        }
        
        // Foto por defecto según género
        if ($this->genero == 'Masculino') {
            return asset('images/avatar-masculino.png');
        } elseif ($this->genero == 'Femenino') {
            return asset('images/avatar-femenino.png');
        }
        
        return asset('images/avatar-default.png');
    }

    // =========================================
    // MÉTODOS AUXILIARES
    // =========================================

    /**
     * Verificar si es administrador
     */
    public function esAdministrador()
    {
        return $this->administrador !== null;
    }

    /**
     * Verificar si es docente
     */
    public function esDocente()
    {
        return $this->docente !== null;
    }

    /**
     * Verificar si es estudiante
     */
    public function esEstudiante()
    {
        return $this->estudiante !== null;
    }

    /**
     * Verificar si es tutor
     */
    public function esTutor()
    {
        return $this->tutor !== null;
    }

    /**
     * Verificar si está activo
     */
    public function estaActivo()
    {
        return $this->estado === 'Activo';
    }

    /**
     * Verificar si está inactivo
     */
    public function estaInactivo()
    {
        return $this->estado === 'Inactivo';
    }

    /**
     * Obtener el rol principal de la persona
     */
    public function getRolPrincipal()
    {
        if ($this->esAdministrador()) {
            return 'Administrador';
        }
        if ($this->esDocente()) {
            return 'Docente';
        }
        if ($this->esEstudiante()) {
            return 'Estudiante';
        }
        if ($this->esTutor()) {
            return 'Tutor';
        }
        return 'Sin rol';
    }

    /**
     * Obtener todos los roles de la persona
     */
    public function getRoles()
    {
        $roles = [];
        
        if ($this->esAdministrador()) {
            $roles[] = 'Administrador';
        }
        if ($this->esDocente()) {
            $roles[] = 'Docente';
        }
        if ($this->esEstudiante()) {
            $roles[] = 'Estudiante';
        }
        if ($this->esTutor()) {
            $roles[] = 'Tutor';
        }
        
        return $roles;
    }

    /**
     * Verificar si tiene múltiples roles
     */
    public function tieneMultiplesRoles()
    {
        return count($this->getRoles()) > 1;
    }

    /**
     * Verificar si está disponible para asignar un rol
     */
    public function estaDisponible()
    {
        return !$this->esAdministrador() 
            && !$this->esDocente() 
            && !$this->esEstudiante() 
            && $this->estaActivo();
    }

    /**
     * Activar persona
     */
    public function activar()
    {
        $this->estado = 'Activo';
        $this->save();
        return $this;
    }

    /**
     * Desactivar persona
     */
    public function desactivar()
    {
        $this->estado = 'Inactivo';
        $this->save();
        return $this;
    }

    /**
     * Obtener iniciales
     */
    public function getInicialesAttribute()
    {
        $nombres = explode(' ', $this->nombres);
        $apellidos = explode(' ', $this->apellidos);
        
        $inicialNombre = isset($nombres[0]) ? substr($nombres[0], 0, 1) : '';
        $inicialApellido = isset($apellidos[0]) ? substr($apellidos[0], 0, 1) : '';
        
        return strtoupper($inicialNombre . $inicialApellido);
    }

    /**
     * Obtener género formateado
     */
    public function getGeneroFormateadoAttribute()
    {
        return $this->genero ?? 'No especificado';
    }

    /**
     * Obtener badge de estado
     */
    public function getEstadoBadgeAttribute()
    {
        return $this->estado === 'Activo' ? 'success' : 'secondary';
    }

    // =========================================
    // MÉTODOS ESTÁTICOS
    // =========================================

    /**
     * Obtener estadísticas generales
     */
    public static function obtenerEstadisticas()
    {
        return [
            'total' => self::count(),
            'activos' => self::where('estado', 'Activo')->count(),
            'inactivos' => self::where('estado', 'Inactivo')->count(),
            'administradores' => self::whereHas('administrador')->count(),
            'docentes' => self::whereHas('docente')->count(),
            'estudiantes' => self::whereHas('estudiante')->count(),
            'tutores' => self::whereHas('tutor')->count(),
            'disponibles' => self::disponibles()->count(),
            'masculino' => self::where('genero', 'Masculino')->count(),
            'femenino' => self::where('genero', 'Femenino')->count(),
        ];
    }

    /**
     * Buscar persona por DNI
     */
    public static function buscarPorDni($dni)
    {
        return self::where('dni', $dni)->first();
    }

    /**
     * Obtener personas por género
     */
    public static function porGenero($genero)
    {
        return self::where('genero', $genero)->get();
    }
}