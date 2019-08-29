<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;


use Spatie\MediaLibrary\Models\Media;

class UrlManager
{
    /**
     * @return string
     */
    protected static function getUrlPrefix()
    {
        $route = request()->route();

        return $route !== null ? $route->getPrefix() : 'admin';
    }


    /**
     * @return string
     */
    public static function dashboardRoute(): string
    {
        return url(self::getUrlPrefix().'/dashboard');
    }

    /**
     * @param string $route
     * @return string
     */
    public static function listRoute(string $route): string
    {
        return url(self::getUrlPrefix().str_start($route, '/'));
    }

    /**
     * @param string $route
     * @return string
     */
    public static function createRoute(string $route): string
    {
        return url(self::getUrlPrefix().str_start($route, '/').'/create');
    }

    /**
     * @param string $route
     * @param int $id
     * @return string
     */
    public static function editRoute(string $route, int $id): string
    {
        return url(self::getUrlPrefix().str_start($route, '/').'/'.$id.'/edit');
    }

    /**
     * @param string $route
     * @param int $id
     * @return string
     */
    public static function deleteRoute(string $route, int $id): string
    {
        return url(self::getUrlPrefix().str_start($route, '/').'/'.$id.'/delete');
    }


    /**
     * @param string $route
     * @return string
     */
    public static function exportXlsRoute(string $route): string
    {
        return url(self::getUrlPrefix().str_start($route, '/').'/export/xls');
    }

    /**
     * @param string $route
     * @return string
     */
    public static function exportCsvRoute(string $route): string
    {
        return url(self::getUrlPrefix().str_start($route, '/').'/export/csv');
    }


    /**
     * @param string $route
     * @param int $id
     * @param string $collection
     * @return string
     */
    public static function uploadMedialibraryFileRoute(string $route, int $id, string $collection): string
    {
        return url(self::getUrlPrefix().str_start($route, '/').'/'.$id.'/upload_medialibrary_file/'.$collection);
    }

    /**
     * @param string $route
     * @param int $id
     * @param Media $media
     * @return string
     */
    public static function deleteMedialibraryFileRoute(string $route, int $id, Media $media): string
    {
        return url(self::getUrlPrefix().str_start($route, '/').'/'.$id.'/delete_medialibrary_file/'.$media->id);
    }

    /**
     * @param string $route
     * @param int $id
     * @param string $collection
     * @return string
     */
    public static function clearMedialibraryCollectionRoute(string $route, int $id, string $collection): string
    {
        return url(self::getUrlPrefix().str_start($route, '/').'/'.$id.'/clear_medialibrary_collection/'.$collection);
    }
}
