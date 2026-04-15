<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Str;

class EmailHelper
{
    /**
     * Genera un email único para el sistema interno.
     * Formato: nombre.apellido@adonai.edu.pe
     * Si ya existe, añade un número incremental.
     */
    public static function generateUniqueEmail($nombres, $apellidos, $dominio = 'adonai.edu.pe')
    {
        // Normalizar: eliminar tildes, espacios, convertir a minúsculas
        $base = Str::slug($nombres . '.' . $apellidos, '.');
        $base = preg_replace('/[^a-z0-9.]/', '', $base);
        $base = trim($base, '.');

        $email = $base . '@' . $dominio;
        $original = $email;
        $counter = 1;

        while (User::where('email', $email)->exists()) {
            $email = str_replace('@', $counter . '@', $original);
            $counter++;
        }

        return $email;
    }
}