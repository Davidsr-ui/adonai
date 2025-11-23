<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MensajeDestinatario extends Model
{
    use HasFactory;

    protected $table = 'mensaje_destinatarios';

    protected $fillable = [
        'mensaje_id',
        'destinatario_id',
        'leido',
        'fecha_lectura'
    ];

    protected $casts = [
        'leido' => 'boolean',
        'fecha_lectura' => 'datetime',
    ];

    // =========================================
    // RELACIONES
    // =========================================

    /**
     * Mensaje al que pertenece
     */
    public function mensaje()
    {
        return $this->belongsTo(Mensaje::class);
    }

    /**
     * Usuario destinatario
     */
    public function destinatario()
    {
        return $this->belongsTo(User::class, 'destinatario_id');
    }

    // =========================================
    // SCOPES
    // =========================================

    /**
     * Destinatarios de un mensaje específico
     */
    public function scopeDeMensaje($query, $mensajeId)
    {
        return $query->where('mensaje_id', $mensajeId);
    }

    /**
     * Mensajes para un usuario específico
     */
    public function scopeParaUsuario($query, $userId)
    {
        return $query->where('destinatario_id', $userId);
    }

    /**
     * Mensajes no leídos
     */
    public function scopeNoLeidos($query)
    {
        return $query->where('leido', false);
    }

    /**
     * Mensajes leídos
     */
    public function scopeLeidos($query)
    {
        return $query->where('leido', true);
    }

    // =========================================
    // MÉTODOS
    // =========================================

    /**
     * Marcar como leído
     */
    public function marcarComoLeido()
    {
        $this->leido = true;
        $this->fecha_lectura = now();
        $this->save();
    }

    /**
     * Marcar como no leído
     */
    public function marcarComoNoLeido()
    {
        $this->leido = false;
        $this->fecha_lectura = null;
        $this->save();
    }
}