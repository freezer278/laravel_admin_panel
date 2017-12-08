<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;


class UrlManager
{
    public static function dashboardRoute(string $prefix = ''): string
    {
        return url($prefix.'/dashboard');
    }

    public static function listRoute(string $route): string
    {
        return url($route);
    }

    public static function createRoute(string $route): string
    {
        return url($route.'/create');
    }

    public static function editRoute(string $route, int $id): string
    {
        return url($route.'/'.$id.'/edit');
    }

    public static function deleteRoute(string $route, int $id): string
    {
        return url($route.'/'.$id.'/delete');
    }
}