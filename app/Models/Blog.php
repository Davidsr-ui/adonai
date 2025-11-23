<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'titulo', 'categoria', 'portada', 'fecha',
        'autor', 'descripcion_corta', 'contenido', 'tags'
    ];

    protected $casts = [
        'tags' => 'array',
        'fecha' => 'date',
    ];
}
