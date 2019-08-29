<?php

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the \Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider.
| Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Vmorozov\LaravelAdminGenerator\App\Utils\AdminRoute;

$routePrefix = AdminRoute::getRoutePrefix();

Route::group(['prefix' => $routePrefix, 'middleware' => ['web']], function () {

    Route::group(['middleware' => 'auth:web'], function () {
        AdminRoute::home();
    });

    AdminRoute::auth();
});
