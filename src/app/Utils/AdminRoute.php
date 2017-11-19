<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;

use Illuminate\Support\Facades\Route;
use Symfony\Component\Debug\Exception\ClassNotFoundException;

class AdminRoute
{
    public static function resource(string $route, string $controller)
    {
//        if (!class_exists($controller)) {
//            throw new ClassNotFoundException('class '.$controller.' was not found!', new \ErrorException());
//        }

        Route::get($route, $controller.'@index');

        Route::get($route.'/create', $controller.'@create');
        Route::post($route.'/create', $controller.'@store');

        Route::get($route.'/{id}', $controller.'@show');
        Route::get($route.'/{id}/delete', $controller.'@destroy');

        Route::get($route.'/{id}/edit', $controller.'@edit');
        Route::post($route.'/{id}/edit', $controller.'@update');
    }
}