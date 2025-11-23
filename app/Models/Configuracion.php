<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    /**
     * CRÍTICO: Laravel pluraliza mal "configuracion" a "configuracions"
     * La tabla es singular, no plural
     */
    protected $table = 'configuracion';

    protected $fillable = [
        'nombre',
        'descripcion',
        'direccion',
        'telefono',
        'divisa',
        'email',
        'web',
        'logo',
        'clave',
        'valor',
        'tipo',
        'categoria',
        'editable',
    ];

    protected $casts = [
        'editable' => 'boolean',
    ];

    // =========================================
    // SCOPES
    // =========================================

    /**
     * Scope para configuraciones editables
     */
    public function scopeEditables($query)
    {
        return $query->where('editable', true);
    }

    /**
     * Scope por categoría
     */
    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    /**
     * Scope por clave
     */
    public function scopePorClave($query, $clave)
    {
        return $query->where('clave', $clave);
    }

    // =========================================
    // MÉTODOS ESTÁTICOS
    // =========================================

    /**
     * Obtener valor de configuración por clave
     */
    public static function obtenerValor($clave, $default = null)
    {
        $config = self::where('clave', $clave)->first();
        
        if (!$config) {
            return $default;
        }

        // Convertir según tipo
        switch ($config->tipo) {
            case 'Numero':
                return (int) $config->valor;
            case 'Boolean':
                return filter_var($config->valor, FILTER_VALIDATE_BOOLEAN);
            case 'JSON':
                return json_decode($config->valor, true);
            case 'Fecha':
                return \Carbon\Carbon::parse($config->valor);
            default:
                return $config->valor;
        }
    }

    /**
     * Establecer valor de configuración
     */
    public static function establecerValor($clave, $valor)
    {
        $config = self::where('clave', $clave)->first();

        if (!$config) {
            return false;
        }

        if (!$config->editable) {
            return false;
        }

        // Convertir según tipo
        switch ($config->tipo) {
            case 'JSON':
                $config->valor = json_encode($valor);
                break;
            case 'Fecha':
                $config->valor = \Carbon\Carbon::parse($valor)->toDateString();
                break;
            default:
                $config->valor = $valor;
        }

        return $config->save();
    }

    /**
     * Obtener configuración global del sistema
     */
    public static function obtenerConfiguracionGlobal()
    {
        $config = self::where('clave', 'GLOBAL_SETTINGS')->first();

        if (!$config) {
            return [
                'nombre' => config('app.name'),
                'direccion' => '',
                'telefono' => '',
                'email' => '',
                'web' => '',
                'logo' => '',
                'divisa' => 'PEN',
            ];
        }

        return [
            'nombre' => $config->nombre,
            'descripcion' => $config->descripcion,
            'direccion' => $config->direccion,
            'telefono' => $config->telefono,
            'email' => $config->email,
            'web' => $config->web,
            'logo' => $config->logo,
            'divisa' => $config->divisa,
        ];
    }

    // =========================================
    // ACCESSORS
    // =========================================

    /**
     * Obtener badge color según categoría
     */
    public function getBadgeCategoriaAttribute()
    {
        $badges = [
            'Academico' => 'primary',
            'Calificacion' => 'success',
            'General' => 'info',
            'Seguridad' => 'danger',
            'Notificaciones' => 'warning',
        ];

        return $badges[$this->categoria] ?? 'secondary';
    }

    /**
     * Obtener icono según categoría
     */
    public function getIconoCategoriaAttribute()
    {
        $iconos = [
            'Academico' => 'fa-graduation-cap',
            'Calificacion' => 'fa-star',
            'General' => 'fa-cog',
            'Seguridad' => 'fa-shield-alt',
            'Notificaciones' => 'fa-bell',
        ];

        return $iconos[$this->categoria] ?? 'fa-cog';
    }

    /**
     * Obtener valor formateado
     */
    public function getValorFormateadoAttribute()
    {
        switch ($this->tipo) {
            case 'Boolean':
                return $this->valor ? 'Sí' : 'No';
            case 'JSON':
                return json_encode(json_decode($this->valor), JSON_PRETTY_PRINT);
            case 'Fecha':
                return \Carbon\Carbon::parse($this->valor)->format('d/m/Y');
            default:
                return $this->valor;
        }
    }
}