<?php

namespace Vmorozov\LaravelAdminGenerator;

use Illuminate\Support\ServiceProvider;

class AdminGeneratorServiceProvider extends ServiceProvider
{
    const VIEWS_NAME = 'laravel_admin_generator';

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadViewsFrom(resource_path('views/vendor/vmorozov/laravel_admin_generator'), self::VIEWS_NAME);

        $this->loadTranslationsFrom(resource_path('lang/vendor/vmorozov/laravel_admin_generator'), self::VIEWS_NAME);

        if (file_exists(base_path('/routes/admin.php')))
            $this->loadRoutesFrom(base_path('/routes/admin.php'));
        else
            $this->loadRoutesFrom(__DIR__.'/routes/admin.php');


//        $this->publishFiles();

        $this->publishes([__DIR__.'/config' => config_path()], 'config');
        $this->publishes([__DIR__.'/resources/views' => resource_path('views/vendor/vmorozov/laravel_admin_generator')], 'views');
        $this->publishes([__DIR__.'/resources/lang' => resource_path('lang/vendor/vmorozov/laravel_admin_generator')], 'lang');
        $this->publishes([__DIR__.'/routes' => base_path('/routes')], 'routes');

        $this->publishes([base_path('vendor/almasaeed2010/adminlte/dist') => public_path('adminlte')], 'adminlte');
        $this->publishes([base_path('vendor/almasaeed2010/adminlte/bower_components') => public_path('adminlte/bower_components')], 'adminlte');

        if ($this->app->runningInConsole()) {
            $this->commands([]);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }

    private function publishFiles()
    {
        // publish views
        $this->publishes([__DIR__.'../resources/views' => resource_path('views/vendor/vmorozov/laravel_admin_generator')], 'views');
    }

}