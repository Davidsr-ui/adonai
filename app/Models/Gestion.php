<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    use HasFactory;

    protected $table = 'gestions'; // correcto

    protected $fillable = [
        'año', // puedes mantener la ñ si tu base de datos lo soporta
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'estado'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function periodos()
    {
        return $this->hasMany(Periodo::class);
    }
}
