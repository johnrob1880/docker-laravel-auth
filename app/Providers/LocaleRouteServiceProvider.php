<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LocaleRouteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('locale_route', 'App\Helpers\LocaleRoute' );
    }
}
