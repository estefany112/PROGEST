<?php

namespace App\Providers;

use App\Models\Cotizacion;
use App\Policies\CotizacionPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;

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
        Blade::component('components.app-layout', 'app-layout');
        
        // Registrar políticas
        Gate::policy(Cotizacion::class, CotizacionPolicy::class);
    }
}
