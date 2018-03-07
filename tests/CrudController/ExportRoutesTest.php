<?php

namespace Vmorozov\LaravelAdminGenerator\Tests\CrudController;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\Paginator;
use Illuminate\View\View;
use Mockery;
use Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads\Medialibrary\Media;
use Vmorozov\LaravelAdminGenerator\Tests\TestCase;
use Vmorozov\LaravelAdminGenerator\Tests\TestModel;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @coversDefaultClass \Vmorozov\LaravelAdminGenerator\App\Controllers\CrudController
 */
class ExportRoutesTest extends TestCase
{
    private $mock;

    public function setUp()
    {
        parent::setUp();

        $this->mock = Mockery::mock(TestModel::class);

        $this->mock->shouldReceive('__construct');
        $this->mock->shouldReceive('where')->andReturn($this->mock);
        $this->mock->shouldReceive('orderBy')->andReturn($this->mock);
        $this->mock->shouldReceive('find')->andReturn($this->mock);
        $this->mock->shouldReceive('select')->andReturn($this->mock);
        $this->mock->shouldReceive('setAttribute')->andReturn($this->mock);
        $this->mock->shouldReceive('getFillable')->andReturn([]);
        $this->mock->shouldReceive('chunk')->andReturn($this->mock);

        $this->mock->id = self::MODEL_DEFAULT_ID;
    }

    public function testDownloadExcelRoute()
    {
        $controller = new TestController($this->mock);

        $controller->downloadExcel();
        $this->assertTrue(true);
    }

    public function testDownloadCsvRoute()
    {
        $controller = new TestController($this->mock);

        $controller->downloadCsv();
        $this->assertTrue(true);
    }
}