<?php

namespace App\Filament;

use Filament\FilamentServiceProvider as ServiceProvider;
use Filament\Resources\PageResource;
use Filament\Resources\PageResource\Pages;
use Filament\Facades\Filament;

class FilamentServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Otros registros de servicios si es necesario
    }

    public function boot()
    {
        parent::boot();

        // Deshabilitar la navegación
        Filament::serving(function () {
            Filament::disableNavigation();
        });
    }
}
