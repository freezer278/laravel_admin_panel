<?php

namespace Vmorozov\LaravelAdminGenerator\Tests\Field;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Media;
use Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider;
use Vmorozov\LaravelAdminGenerator\App\Controllers\CrudController;
use Vmorozov\LaravelAdminGenerator\App\Utils\AdminRoute;
use Vmorozov\LaravelAdminGenerator\App\Utils\ColumnsExtractor;
use Vmorozov\LaravelAdminGenerator\App\Utils\Field;
use Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager;
use Vmorozov\LaravelAdminGenerator\Tests\TestCase;

/**
 * @coversDefaultClass \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager
 */
class UrlManagerTest extends TestCase
{
    const LOCAL_URL = 'http://localhost/admin';

    private $url = 'products';
    private $id = 123;
    private $collection = 'collection';

    public function setUp()
    {
        parent::setUp();
    }

    private function getBaseUrl(): string
    {
        return self::LOCAL_URL.'/'.$this->url;
    }

    public function testExportXlsRoute()
    {
        $route = UrlManager::exportXlsRoute($this->url);

        $this->assertEquals($route, $this->getBaseUrl().'/export/xls');
    }

    public function testExportCsvRoute()
    {
        $route = UrlManager::exportCsvRoute($this->url);

        $this->assertEquals($route, $this->getBaseUrl().'/export/csv');
    }

    public function testDashboardRoute()
    {
        $route = UrlManager::dashboardRoute();

        $this->assertEquals($route, self::LOCAL_URL.'/dashboard');
    }

    public function testListRoute()
    {
        $route = UrlManager::listRoute($this->url);

        $this->assertEquals($route, $this->getBaseUrl());
    }

    public function testCreateRoute()
    {
        $route = UrlManager::createRoute($this->url);

        $this->assertEquals($route, $this->getBaseUrl().'/create');
    }

    public function testEditRoute()
    {
        $route = UrlManager::editRoute($this->url, $this->id);

        $this->assertEquals($route, $this->getBaseUrl().'/'.$this->id.'/edit');
    }

    public function testDeleteRoute()
    {
        $route = UrlManager::deleteRoute($this->url, $this->id);

        $this->assertEquals($route, $this->getBaseUrl().'/'.$this->id.'/delete');
    }

    public function testClearMedialibraryCollectionRoute()
    {
        $route = UrlManager::clearMedialibraryCollectionRoute($this->url, $this->id, $this->collection);

        $this->assertEquals($route, $this->getBaseUrl().'/'.$this->id.'/clear_medialibrary_collection/'.$this->collection);
    }

    public function testDeleteMedialibraryFileRoute()
    {
        $route = UrlManager::deleteMedialibraryFileRoute($this->url, $this->id, new Media(['id' => $this->id]));

        $this->assertEquals($route, $this->getBaseUrl().'/'.$this->id.'/delete_medialibrary_file/'.$this->id);
    }

    public function testUploadMedialibraryFileRoute()
    {
        $route = UrlManager::uploadMedialibraryFileRoute($this->url, $this->id, $this->collection);

        $this->assertEquals($route, $this->getBaseUrl().'/'.$this->id.'/upload_medialibrary_file/'.$this->collection);
    }
}