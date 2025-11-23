<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taller extends Model
{
    use HasFactory;

    protected $table = 'talleres';

    protected $fillable = [
        'nombre',
        'descripcion',
        'instructor',

        // NUEVOS CAMPOS DE DURACIÓN
        'duracion_inicio',
        'duracion_fin',

        // NUEVOS CAMPOS DE HORARIO
        'horario_inicio',
        'horario_fin',

        // EXTRA
        'categoria',
        'costo',
        'cupos_maximos',
        'imagen',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'costo' => 'decimal:2',

        // CAST PARA FECHAS
        'duracion_inicio' => 'date',
        'duracion_fin' => 'date',

        // CAST PARA HORAS
        'horario_inicio' => 'datetime:H:i',
        'horario_fin' => 'datetime:H:i',
    ];
}
