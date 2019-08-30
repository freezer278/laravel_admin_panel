<?php

namespace Vmorozov\LaravelAdminGenerator\Tests\CrudController;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Mockery;
use Vmorozov\LaravelAdminGenerator\Tests\TestCase;
use Vmorozov\LaravelAdminGenerator\Tests\TestModel;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @coversDefaultClass \Vmorozov\LaravelAdminGenerator\App\Controllers\CrudController
 */
class ExportRoutesTest extends TestCase
{
    /**
     * @var
     */
    private $modelMock;
    /**
     * @var
     */
    private $queryBuilderMock;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->queryBuilderMock = Mockery::mock(Builder::class);
        $this->queryBuilderMock->shouldReceive('cursor')->andReturn(function () {
            yield new TestModel();
        });

        $this->modelMock = Mockery::mock(TestModel::class);
        $this->modelMock->shouldReceive('__construct');
        $this->modelMock->shouldReceive('setAttribute')->andReturn($this->modelMock);
        $this->modelMock->shouldReceive('newQuery')->andReturn($this->queryBuilderMock);
        $this->modelMock->id = self::MODEL_DEFAULT_ID;
    }

    /**
     * @throws BindingResolutionException
     * @throws Exception
     */
    public function testDownloadExcelRoute()
    {
        $controller = new TestController($this->modelMock);

        $result = $controller->downloadExcel();
        $this->assertNotNull($result);
        $this->assertNotNull($result->getFile());

        //        todo: add here asserts for file contents
    }

    /**
     * @throws BindingResolutionException
     * @throws Exception
     */
    public function testDownloadCsvRoute()
    {
        $controller = new TestController($this->modelMock);

        $result = $controller->downloadCsv();
        $this->assertNotNull($result);
        $this->assertNotNull($result->getFile());

//        todo: add here asserts for file contents
    }
}
