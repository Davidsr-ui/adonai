<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate; // <--- 1. IMPORTANTE: Agregamos esta librería

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // A. Configuración para el Hosting (Mantenemos lo que tenías)
        // Esto asegura que en internet cargue con candadito seguro (HTTPS)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // B. Configuración para el Menú Lateral (LO NUEVO)
        // Esto le dice al sistema: "Si es Admin, dale permiso a TODO automáticamente"
        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });
    }
}