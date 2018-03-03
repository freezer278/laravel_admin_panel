<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;


use Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads\Medialibrary\Media;

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


    public static function uploadMedialibraryFileRoute(string $route): string
    {
        return url(self::getUrlPrefix().str_start($route, '/').'/upload_medialibrary_file');
    }

    public static function deleteMedialibraryFileRoute(string $route, Media $media): string
    {
        return url(self::getUrlPrefix().str_start($route, '/').'/delete_medialibrary_file/'.$media->id);
    }

    public static function clearMedialibraryCollectionRoute(string $route, string $collection): string
    {
        return url(self::getUrlPrefix().str_start($route, '/').'/clear_medialibrary_collection/'.$collection);
    }
}