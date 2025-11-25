<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Driver de hash por defecto
    |--------------------------------------------------------------------------
    |
    | Puede ser: "bcrypt" o "argon".
    | También puedes cambiarlo con la variable de entorno HASH_DRIVER.
    |
    */

    'driver' => env('HASH_DRIVER', 'bcrypt'),

    /*
    |--------------------------------------------------------------------------
    | Configuración de Bcrypt
    |--------------------------------------------------------------------------
    */

    'bcrypt' => [
        'rounds' => (int) env('BCRYPT_ROUNDS', 12),
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Argon
    |--------------------------------------------------------------------------
    */

    'argon' => [
        'memory'  => 65536,
        'threads' => 1,
        'time'    => 4,
    ],

    /*
    |--------------------------------------------------------------------------
    | Tiempo de expiración de confirmación de contraseña
    |--------------------------------------------------------------------------
    |
    | Segundos que una confirmación de contraseña es considerada válida.
    |
    */

    'password_timeout' => 10800,
];
