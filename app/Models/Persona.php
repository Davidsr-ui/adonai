<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\EmailHelper; // 👈 NUEVO

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
    // RELACIONES (ya las tienes, se mantienen)
    // =========================================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function docente()
    {
        return $this->hasOne(Docente::class);
    }

    public function estudiante()
    {
        return $this->hasOne(Estudiante::class);
    }

    public function tutor()
    {
        return $this->hasOne(Tutor::class);
    }

    public function administrador()
    {
        return $this->hasOne(Administrador::class);
    }

    // =========================================
    // NUEVO MÉTODO PARA CREAR USUARIO
    // =========================================

    /**
     * Crea automáticamente un usuario para esta persona (si no existe)
     * y le asigna el rol especificado.
     *
     * @param string $role Rol a asignar (tutor, docente, etc.)
     * @param string|null $password Contraseña (si no se da, usa el DNI)
     * @return \App\Models\User|null
     */
    public function crearUsuarioSiNoExiste($role = 'tutor', $password = null)
    {
        // Si ya tiene usuario, no crear de nuevo
        if ($this->user_id) {
            return $this->user;
        }

        // Generar email único
        $email = EmailHelper::generateUniqueEmail($this->nombres, $this->apellidos);

        // Contraseña: usar la proporcionada o el DNI
        if (!$password) {
            $password = $this->dni;
        }

        // Crear el usuario
        $user = User::create([
            'name'     => $this->nombres . ' ' . $this->apellidos,
            'email'    => $email,
            'password' => bcrypt($password),
            'estado'   => 'Activo',
        ]);

        // Asignar rol (Spatie Permission)
        if (method_exists($user, 'assignRole')) {
            $user->assignRole($role);
        }

        // Vincular usuario a la persona
        $this->user_id = $user->id;
        $this->save();

        return $user;
    }

    // =========================================
    // SCOPES Y ACCESSORS (todos los que ya tenías, se mantienen)
    // =========================================
    
    public function scopeActivo($query)
    {
        return $query->where('estado', 'Activo');
    }

    public function scopeInactivo($query)
    {
        return $query->where('estado', 'Inactivo');
    }

    public function scopeDisponibles($query)
    {
        return $query->whereDoesntHave('administrador')
                    ->whereDoesntHave('docente')
                    ->whereDoesntHave('estudiante')
                    ->where('estado', 'Activo');
    }

    public function scopeBuscar($query, $termino)
    {
        return $query->where('dni', 'like', "%{$termino}%")
                    ->orWhere('nombres', 'like', "%{$termino}%")
                    ->orWhere('apellidos', 'like', "%{$termino}%");
    }

    public function getNombreCompletoAttribute()
    {
        return $this->apellidos . ' ' . $this->nombres;
    }

    public function getEdadAttribute()
    {
        if (!$this->fecha_nacimiento) {
            return null;
        }
        return \Carbon\Carbon::parse($this->fecha_nacimiento)->age;
    }

    public function getFotoPerfilUrlAttribute()
    {
        if ($this->foto_perfil) {
            return asset('storage/' . $this->foto_perfil);
        }
        
        if ($this->genero == 'Masculino') {
            return asset('images/avatar-masculino.png');
        } elseif ($this->genero == 'Femenino') {
            return asset('images/avatar-femenino.png');
        }
        
        return asset('images/avatar-default.png');
    }

    public function esAdministrador()
    {
        return $this->administrador !== null;
    }

    public function esDocente()
    {
        return $this->docente !== null;
    }

    public function esEstudiante()
    {
        return $this->estudiante !== null;
    }

    public function esTutor()
    {
        return $this->tutor !== null;
    }

    public function estaActivo()
    {
        return $this->estado === 'Activo';
    }

    public function estaInactivo()
    {
        return $this->estado === 'Inactivo';
    }

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

    public function getRoles()
    {
        $roles = [];
        if ($this->esAdministrador()) $roles[] = 'Administrador';
        if ($this->esDocente()) $roles[] = 'Docente';
        if ($this->esEstudiante()) $roles[] = 'Estudiante';
        if ($this->esTutor()) $roles[] = 'Tutor';
        return $roles;
    }

    public function tieneMultiplesRoles()
    {
        return count($this->getRoles()) > 1;
    }

    public function estaDisponible()
    {
        return !$this->esAdministrador() 
            && !$this->esDocente() 
            && !$this->esEstudiante() 
            && $this->estaActivo();
    }

    public function activar()
    {
        $this->estado = 'Activo';
        $this->save();
        return $this;
    }

    public function desactivar()
    {
        $this->estado = 'Inactivo';
        $this->save();
        return $this;
    }

    public function getInicialesAttribute()
    {
        $nombres = explode(' ', $this->nombres);
        $apellidos = explode(' ', $this->apellidos);
        $inicialNombre = isset($nombres[0]) ? substr($nombres[0], 0, 1) : '';
        $inicialApellido = isset($apellidos[0]) ? substr($apellidos[0], 0, 1) : '';
        return strtoupper($inicialNombre . $inicialApellido);
    }

    public function getGeneroFormateadoAttribute()
    {
        return $this->genero ?? 'No especificado';
    }

    public function getEstadoBadgeAttribute()
    {
        return $this->estado === 'Activo' ? 'success' : 'secondary';
    }

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

    public static function buscarPorDni($dni)
    {
        return self::where('dni', $dni)->first();
    }

    public static function porGenero($genero)
    {
        return self::where('genero', $genero)->get();
    }
}