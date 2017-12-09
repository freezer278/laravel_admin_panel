<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;


class UrlManager
{
    public static function dashboardRoute(): string
    {
        return url(AdminRoute::getRoutePrefix().'/dashboard');
    }

    public static function listRoute(string $route): string
    {
        return url(AdminRoute::getRoutePrefix().str_start($route, '/'));
    }

    public static function createRoute(string $route): string
    {
        return url(AdminRoute::getRoutePrefix().str_start($route, '/').'/create');
    }

    public static function editRoute(string $route, int $id): string
    {
        return url(AdminRoute::getRoutePrefix().str_start($route, '/').'/'.$id.'/edit');
    }

    public static function deleteRoute(string $route, int $id): string
    {
        return url(AdminRoute::getRoutePrefix().str_start($route, '/').'/'.$id.'/delete');
    }
}