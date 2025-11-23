<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    protected $table = 'periodos';
    
    protected $fillable = [
        'gestion_id',
        'nombre',
        'numero',
        'fecha_inicio',
        'fecha_fin',
        'estado'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function gestion()
    {
        return $this->belongsTo(Gestion::class);
    }
}