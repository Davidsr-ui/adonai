<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    /**
     * CRÍTICO: Laravel pluraliza mal "notificacion" a "notificacions"
     * Por eso debemos especificar el nombre correcto de la tabla
     */
    protected $table = 'notificaciones';

    protected $fillable = [
        'user_id',
        'tipo',
        'titulo',
        'descripcion',
        'referencia_id',
        'referencia_tabla',
        'url',
        'leido',
        'fecha_lectura',
    ];

    protected $casts = [
        'leido' => 'boolean',
        'fecha_lectura' => 'datetime',
    ];

    // =========================================
    // RELACIONES
    // =========================================

    /**
     * Relación con Usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // =========================================
    // SCOPES
    // =========================================

    /**
     * Scope para notificaciones no leídas
     */
    public function scopeNoLeidas($query)
    {
        return $query->where('leido', false);
    }

    /**
     * Scope para notificaciones leídas
     */
    public function scopeLeidas($query)
    {
        return $query->where('leido', true);
    }

    /**
     * Scope para notificaciones por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para notificaciones de hoy
     */
    public function scopeDeHoy($query)
    {
        return $query->whereDate('created_at', today());
    }

    // =========================================
    // MÉTODOS
    // =========================================

    /**
     * Marcar como leída
     */
    public function marcarComoLeida()
    {
        $this->leido = true;
        $this->fecha_lectura = now();
        $this->save();
    }

    /**
     * Marcar como no leída
     */
    public function marcarComoNoLeida()
    {
        $this->leido = false;
        $this->fecha_lectura = null;
        $this->save();
    }

    // =========================================
    // ACCESSORS
    // =========================================

    /**
     * Obtener icono según tipo
     */
    public function getIconoAttribute()
    {
        $iconos = [
            'Nota Nueva' => 'fa-file-alt',
            'Asistencia' => 'fa-calendar-check',
            'Comportamiento' => 'fa-star',
            'Mensaje' => 'fa-envelope',
            'Comunicado' => 'fa-bullhorn',
            'Sistema' => 'fa-cog',
        ];

        return $iconos[$this->tipo] ?? 'fa-bell';
    }

    /**
     * Obtener color según tipo
     */
    public function getBadgeColorAttribute()
    {
        $colores = [
            'Nota Nueva' => 'primary',
            'Asistencia' => 'success',
            'Comportamiento' => 'warning',
            'Mensaje' => 'info',
            'Comunicado' => 'danger',
            'Sistema' => 'secondary',
        ];

        return $colores[$this->tipo] ?? 'secondary';
    }

    /**
     * Obtener tiempo transcurrido
     */
    public function getTiempoTranscurridoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}