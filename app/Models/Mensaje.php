<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;

    protected $table = 'mensajes';

    protected $fillable = [
        'remitente_id',
        'estudiante_id',
        'asunto',
        'contenido',
        'prioridad',
        'tipo',
        'archivos'
    ];

    protected $casts = [
        'archivos' => 'array',
    ];

    // =========================================
    // RELACIONES
    // =========================================

    /**
     * Remitente (Usuario que envía)
     */
    public function remitente()
    {
        return $this->belongsTo(User::class, 'remitente_id');
    }

    /**
     * Estudiante sobre el que trata el mensaje
     */
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    /**
     * Destinatarios del mensaje
     */
    public function destinatarios()
    {
        return $this->hasMany(MensajeDestinatario::class);
    }

    /**
     * Usuarios destinatarios (a través de la tabla pivot)
     */
    public function usuariosDestinatarios()
    {
        return $this->belongsToMany(User::class, 'mensaje_destinatarios', 'mensaje_id', 'destinatario_id')
                    ->withPivot('leido', 'fecha_lectura')
                    ->withTimestamps();
    }

    // =========================================
    // SCOPES
    // =========================================

    /**
     * Mensajes enviados por un usuario
     */
    public function scopeEnviadosPor($query, $userId)
    {
        return $query->where('remitente_id', $userId);
    }

    /**
     * Mensajes recibidos por un usuario
     */
    public function scopeRecibidosPor($query, $userId)
    {
        return $query->whereHas('destinatarios', function($q) use ($userId) {
            $q->where('destinatario_id', $userId);
        });
    }

    /**
     * Mensajes no leídos para un usuario
     */
    public function scopeNoLeidosPor($query, $userId)
    {
        return $query->whereHas('destinatarios', function($q) use ($userId) {
            $q->where('destinatario_id', $userId)
              ->where('leido', false);
        });
    }

    /**
     * Mensajes sobre un estudiante
     */
    public function scopeSobreEstudiante($query, $estudianteId)
    {
        return $query->where('estudiante_id', $estudianteId);
    }

    /**
     * Mensajes de alta prioridad
     */
    public function scopeAltaPrioridad($query)
    {
        return $query->whereIn('prioridad', ['Alta', 'Urgente']);
    }

    /**
     * Mensajes individuales
     */
    public function scopeIndividuales($query)
    {
        return $query->where('tipo', 'Individual');
    }

    /**
     * Mensajes grupales
     */
    public function scopeGrupales($query)
    {
        return $query->where('tipo', 'Grupal');
    }

    // =========================================
    // ACCESSORS
    // =========================================

    /**
     * Badge color según prioridad
     */
    public function getBadgePrioridadAttribute()
    {
        return match($this->prioridad) {
            'Urgente' => 'danger',
            'Alta' => 'warning',
            'Normal' => 'info',
            'Baja' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Icono según prioridad
     */
    public function getIconoPrioridadAttribute()
    {
        return match($this->prioridad) {
            'Urgente' => 'fa-exclamation-triangle',
            'Alta' => 'fa-exclamation-circle',
            'Normal' => 'fa-info-circle',
            'Baja' => 'fa-minus-circle',
            default => 'fa-envelope'
        };
    }

    /**
     * Verificar si tiene archivos adjuntos
     */
    public function getTieneArchivosAttribute()
    {
        return !empty($this->archivos);
    }

    /**
     * Cantidad de archivos
     */
    public function getCantidadArchivosAttribute()
    {
        return $this->archivos ? count($this->archivos) : 0;
    }

    // =========================================
    // MÉTODOS
    // =========================================

    /**
     * Verificar si un usuario leyó el mensaje
     */
    public function fueLeido($userId)
    {
        $destinatario = $this->destinatarios()
                             ->where('destinatario_id', $userId)
                             ->first();
        
        return $destinatario ? $destinatario->leido : false;
    }

    /**
     * Marcar como leído para un usuario específico
     */
    public function marcarComoLeido($userId)
    {
        $this->destinatarios()
             ->where('destinatario_id', $userId)
             ->update([
                 'leido' => true,
                 'fecha_lectura' => now()
             ]);
    }

    /**
     * Agregar destinatarios
     */
    public function agregarDestinatarios(array $userIds)
    {
        foreach ($userIds as $userId) {
            MensajeDestinatario::create([
                'mensaje_id' => $this->id,
                'destinatario_id' => $userId,
                'leido' => false
            ]);
        }
    }

    /**
     * Contar destinatarios
     */
    public function cantidadDestinatarios()
    {
        return $this->destinatarios()->count();
    }

    /**
     * Contar destinatarios que leyeron
     */
    public function cantidadLeidos()
    {
        return $this->destinatarios()->where('leido', true)->count();
    }

    /**
     * Porcentaje de lectura
     */
    public function porcentajeLectura()
    {
        $total = $this->cantidadDestinatarios();
        if ($total == 0) return 0;
        
        $leidos = $this->cantidadLeidos();
        return round(($leidos / $total) * 100, 2);
    }
}