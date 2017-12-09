<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;

use Illuminate\Support\Facades\Route;
use Symfony\Component\Debug\Exception\ClassNotFoundException;
use Vmorozov\LaravelAdminGenerator\App\Controllers\AdminHomeController;
use Vmorozov\LaravelAdminGenerator\App\Controllers\CrudController;

class AdminRoute
{
    public static function getRoutePrefix(): string
    {
        return config('laravel_admin.route_prefix', 'admin');
    }

    public static function resource(string $controller)
    {
//        if (!class_exists($controller)) {
//            throw new ClassNotFoundException('class '.$controller.' was not found!', new \ErrorException());
//        }

        $controllerInstance = new $controller();
        $route = $controllerInstance->getUrl();

        Route::get($route, $controller.'@index');

        Route::get($route.'/create', $controller.'@create');
        Route::post($route.'/create', $controller.'@store');

        Route::get($route.'/{id}', $controller.'@show');
        Route::get($route.'/{id}/delete', $controller.'@destroy');

        Route::get($route.'/{id}/edit', $controller.'@edit');
        Route::post($route.'/{id}/edit', $controller.'@update');
    }

    public static function home(string $controller = AdminHomeController::class)
    {
        Route::get('/dashboard', $controller.'@showDashboard');
    }
}