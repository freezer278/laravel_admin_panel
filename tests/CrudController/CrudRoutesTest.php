<?php

namespace Vmorozov\LaravelAdminGenerator\Tests\CrudController;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\View\View;
use Mockery;
use Spatie\MediaLibrary\Media;
use Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider;
use Vmorozov\LaravelAdminGenerator\App\Controllers\CrudController;
use Vmorozov\LaravelAdminGenerator\App\Utils\AdminRoute;
use Vmorozov\LaravelAdminGenerator\App\Utils\ColumnsExtractor;
use Vmorozov\LaravelAdminGenerator\App\Utils\EntitiesExtractor;
use Vmorozov\LaravelAdminGenerator\App\Utils\Field;
use Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager;
use Vmorozov\LaravelAdminGenerator\Tests\TestCase;
use Vmorozov\LaravelAdminGenerator\Tests\TestModel;

/**
 * @coversDefaultClass \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager
 */
class CrudRoutesTest extends TestCase
{
    private $mock;

    public function setUp()
    {
        parent::setUp();
    }

    public function __construct()
    {
        $this->mock = Mockery::mock(TestModel::class);

        $this->mock->shouldReceive('__construct');
        $this->mock->shouldReceive('where')->andReturn($this->mock);
        $this->mock->shouldReceive('orderBy')->andReturn($this->mock);
        $this->mock->shouldReceive('find')->andReturn($this->mock);
    }

    public function testListRoute()
    {
        $this->mock
            ->shouldReceive('paginate')
            ->andReturn(new Paginator(collect([$this->mock, $this->mock, $this->mock]), 22, 1));

        $this->app->instance(TestModel::class, $this->mock);

        $controller = new TestController($this->mock);

        $this->assertInstanceOf(View::class, $controller->index(request()));
    }


}