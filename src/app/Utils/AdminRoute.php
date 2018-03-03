<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;

use Illuminate\Support\Facades\Route;
use Symfony\Component\Debug\Exception\ClassNotFoundException;
use Vmorozov\LaravelAdminGenerator\App\Controllers\AdminHomeController;
use Vmorozov\LaravelAdminGenerator\App\Controllers\Auth\AdminAuthController;

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

        Route::get($route.'/export/xls', $controller.'@downloadExcel');
        Route::get($route.'/export/csv', $controller.'@downloadCsv');

        Route::get($route.'/{id}', $controller.'@show');
        Route::get($route.'/{id}/delete', $controller.'@destroy');

        Route::get($route.'/{id}/edit', $controller.'@edit');
        Route::post($route.'/{id}/edit', $controller.'@update');

        Route::get($route.'/{id}/delete_file/{field}', $controller.'@deleteFile');

        Route::post($route.'/{id}/upload_medialibrary_file/{collection}', $controller.'@uploadMedialibraryFile');
        Route::get($route.'/{id}/delete_medialibrary_file/{media}', $controller.'@deleteMedialibraryFile');
        Route::get($route.'/{id}/clear_medialibrary_collection/{collection}', $controller.'@clearMedialibraryCollection');
    }

    public static function home(string $controller = AdminHomeController::class)
    {
        Route::get('/', function () {
            return redirect()->route('admin_dashboard');
        });

        Route::get('/dashboard', $controller.'@showDashboard')->name('admin_dashboard');
    }

    public static function auth(string $controller = AdminAuthController::class)
    {
        Route::get('/login', $controller.'@showLoginForm')->name('admin_login');
        Route::post('/login', $controller.'@login')->name('post_admin_login');
    }
}