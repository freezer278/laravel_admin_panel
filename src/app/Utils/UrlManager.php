<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;


class UrlManager
{
    protected static function getUrlPrefix()
    {
        return request()->route()->getPrefix();
    }

    public static function dashboardRoute(): string
    {
        return url(self::getUrlPrefix().'/dashboard');
    }

    public static function listRoute(string $route): string
    {
        return url(self::getUrlPrefix().str_start($route, '/'));
    }

    public static function createRoute(string $route): string
    {
        return url(self::getUrlPrefix().str_start($route, '/').'/create');
    }

    public static function editRoute(string $route, int $id): string
    {
        return url(self::getUrlPrefix().str_start($route, '/').'/'.$id.'/edit');
    }

    public static function deleteRoute(string $route, int $id): string
    {
        return url(self::getUrlPrefix().str_start($route, '/').'/'.$id.'/delete');
    }

    public static function exportXlsRoute(string $route): string
    {
        return url(self::getUrlPrefix().str_start($route, '/').'/export/xls');
    }

    public static function exportCsvRoute(string $route): string
    {
        return url(self::getUrlPrefix().str_start($route, '/').'/export/csv');
    }
}