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

use Vmorozov\LaravelAdminGenerator\App\Utils\AdminRoute;

$routePrefix = AdminRoute::getRoutePrefix();

Route::group(['prefix' => $routePrefix], function () {

    AdminRoute::home();

    //    Todo: add your admin panel routes here


});