<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\Debug\Exception\ClassNotFoundException;
use Vmorozov\LaravelAdminGenerator\App\Controllers\AdminHomeController;
use Vmorozov\LaravelAdminGenerator\App\Controllers\Auth\AdminAuthController;

class AdminRoute
{
    /**
     * @return string
     */
    public static function getRoutePrefix(): string
    {
        return config('laravel_admin.route_prefix', 'admin');
    }

    /**
     * @param string $controller
     * @param Model|null $model
     */
    public static function resource(string $controller, Model $model = null)
    {
//        if (!class_exists($controller)) {
//            throw new ClassNotFoundException('class '.$controller.' was not found!', new \ErrorException());
//        }

        $controllerInstance = new $controller($model);
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

    /**
     * @param string $controller
     */
    public static function home(string $controller = AdminHomeController::class)
    {
        Route::get('/dashboard', $controller.'@showDashboard')->name('admin_dashboard');
        Route::redirect('/', self::getRoutePrefix() . '/dashboard');
    }

    /**
     * @param string $controller
     */
    public static function auth(string $controller = AdminAuthController::class)
    {
        Route::get('/login', $controller.'@showLoginForm')->name('admin_login');
        Route::post('/login', $controller.'@login')->name('post_admin_login');
        Route::post('/logout', $controller.'@logout')->name('admin_logout');
    }
}
