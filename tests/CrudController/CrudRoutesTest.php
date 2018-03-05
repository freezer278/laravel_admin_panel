<?php

namespace Vmorozov\LaravelAdminGenerator\Tests\CrudController;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\View\View;
use Mockery;
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

    public function testShowCreateRoute()
    {
        $this->mock
            ->shouldReceive('getAttribute');

        $this->app->instance(TestModel::class, $this->mock);

        $controller = new TestController($this->mock);

        $this->assertInstanceOf(View::class, $controller->create(request()));
    }

    public function testShowEditRoute()
    {
        $this->mock
            ->shouldReceive('getAttribute');

        $this->app->instance(TestModel::class, $this->mock);

        $controller = new TestController($this->mock);

        $this->assertInstanceOf(View::class, $controller->edit(12));
    }

    public function testDeleteRoute()
    {
        $this->mock = $this->getMockBuilder(TestModel::class)
            ->setMethods(['update', 'find'])
            ->setConstructorArgs([['id' => 12]])
            ->getMock();

        $this->mock->expects($this->once())
            ->method('find')
            ->willReturn($this->mock);

        $this->mock->id = 12;
        $this->mock->file_upload = 'test';


        $this->app->instance(TestModel::class, $this->mock);

        $controller = new TestController($this->mock);

        $this->assertInstanceOf(RedirectResponse::class, $controller->destroy(12));
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testStoreRoute()
    {
        $this->mock = $this->createMock(TestModel::class);
        $request = Mockery::mock(Request::class);

        $request->shouldReceive('all')
            ->andReturn([
                'title' => 'title',
                'description' => 'description',
                'price' => 'price',
            ]);

        $request->shouldReceive('only')
            ->andReturn([]);

        $request->shouldReceive('file')
            ->andReturn(null);

        $controller = new TestController($this->mock);

        $this->assertInstanceOf(RedirectResponse::class, $controller->store($request));
    }

    public function testUpdateRoute()
    {
        $this->mock = $this->getMockBuilder(TestModel::class)
            ->setMethods(['update', 'find'])
            ->setConstructorArgs([['id' => 12]])
            ->getMock();

        $this->mock->expects($this->once())
            ->method('find')
            ->willReturn($this->mock);

        $this->mock->id = 12;

        $request = Mockery::mock(Request::class);

        $request->shouldReceive('all')
            ->andReturn([
                'title' => 'title',
                'description' => 'description',
                'price' => 'price',
            ]);

        $request->shouldReceive('only')
            ->andReturn([]);

        $request->shouldReceive('file')
            ->andReturn(null);

        $controller = new TestController($this->mock);

        $this->assertInstanceOf(RedirectResponse::class, $controller->update($request, $this->mock->id));
    }

}