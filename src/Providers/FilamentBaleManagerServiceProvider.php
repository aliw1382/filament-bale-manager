<?php

namespace Aliw1382\FilamentBaleManager\Providers;

use Illuminate\Support\ServiceProvider;

class FilamentBaleManagerServiceProvider extends ServiceProvider
{

    public function register() : void
    {

    }

    public function boot() : void
    {
        $this->publishesMigrations( [
            __DIR__ . '/../../database/migrations' => database_path( 'migrations' ),
        ] );
        $this->loadTranslationsFrom(
            __DIR__ . '/../../lang',
            'filament-bale-manager'
        );
        $this->publishes( [

            __DIR__ . '/../../lang' => $this->app->langPath( 'vendor/filament-bale-manager' ),

        ] );
    }

}
