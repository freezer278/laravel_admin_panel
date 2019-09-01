<?php

namespace Vmorozov\LaravelAdminGenerator\Tests\CrudController;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\View\View;
use Mockery;
use ReflectionException;
use Vmorozov\LaravelAdminGenerator\Tests\TestCase;
use Vmorozov\LaravelAdminGenerator\Tests\TestModel;

/**
 * @coversDefaultClass \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager
 */
class CrudRoutesTest extends TestCase
{
    /**
     * @var
     */
    private $modelMock;
    /**
     * @var Mockery\LegacyMockInterface|Mockery\MockInterface|TestModel
     */
    private $queryBuilderMock;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->modelMock = Mockery::mock(TestModel::class);
        $this->queryBuilderMock = Mockery::mock(Builder::class);

        $this->modelMock->shouldReceive('__construct');
        $this->modelMock->shouldReceive('newQuery')->andReturn($this->queryBuilderMock);
        $this->modelMock->shouldReceive('getAttribute');
        $this->modelMock->shouldReceive('setAttribute');
        $this->modelMock->shouldReceive('create')->andReturn($this->modelMock);;

        $this->queryBuilderMock->shouldReceive('where')->andReturn($this->queryBuilderMock);
        $this->queryBuilderMock->shouldReceive('orderBy')->andReturn($this->queryBuilderMock);
        $this->queryBuilderMock
            ->shouldReceive('paginate')
            ->andReturn(new Paginator(collect([$this->modelMock, $this->modelMock, $this->modelMock]), 15, 1));
        $this->queryBuilderMock
            ->shouldReceive('findOrFail')
            ->andReturn($this->modelMock);

        $this->app->instance(TestModel::class, $this->modelMock);
        $this->app->instance(Builder::class, $this->queryBuilderMock);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testListRoute()
    {
        $controller = new TestController();
        $this->assertInstanceOf(View::class, $controller->index(request()));
    }

    /**
     * @throws BindingResolutionException
     */
    public function testShowCreateRoute()
    {
        $this->app->instance(TestModel::class, $this->modelMock);

        $controller = new TestController();

        $this->assertInstanceOf(View::class, $controller->create(request()));
    }

    /**
     * @throws BindingResolutionException
     */
    public function testShowEditRoute()
    {
        $this->modelMock->shouldReceive('find')->andReturn($this->modelMock);

        $this->app->instance(TestModel::class, $this->modelMock);

        $controller = new TestController();

        $this->assertInstanceOf(View::class, $controller->edit(12));
    }

    /**
     * @throws BindingResolutionException
     */
    public function testDeleteRoute()
    {
        $this->modelMock->shouldReceive('delete')->once();

        $this->modelMock->id = 12;
        $this->modelMock->file_upload = 'test';


        $this->app->instance(TestModel::class, $this->modelMock);

        $controller = new TestController();

        $this->assertInstanceOf(RedirectResponse::class, $controller->destroy(12));
    }

    /**
     *
     */
    public function testStoreRoute()
    {
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

//        $this->modelMock->shouldReceive('save')->once();

        $controller = new TestController();
        $this->assertInstanceOf(RedirectResponse::class, $controller->store($request));
    }

    /**
     * @throws BindingResolutionException
     */
    public function testUpdateRoute()
    {
        $this->modelMock->shouldReceive('update')->once();
        $this->modelMock->id = 12;

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

        $controller = new TestController();
        $this->assertInstanceOf(RedirectResponse::class, $controller->update($request, 12));
    }

    /**
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    public function testListItemButtonsNoExceptions()
    {
        $this->app->instance(TestModel::class, $this->modelMock);

        $controller = new TestController();

        $this->invokeMethod($controller, 'addListItemButton', ['url', 'text']);

        $this->assertCount(1, $this->getPrivatePropertyValue($controller, 'listItemButtons'));

        $this->assertInstanceOf(View::class, $controller->index(request()));
    }

    /**
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    public function testWhereClausesAddingNoExceptions()
    {
        $controller = new TestController();

        $this->invokeMethod($controller, 'addDefaultWhereClause', ['title', '=', 'test']);

        $this->assertInstanceOf(View::class, $controller->index(request()));
    }

    /**
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    public function testOrderByClausesAddingNoExceptions()
    {
        $this->app->instance(TestModel::class, $this->modelMock);

        $controller = new TestController();

        $this->invokeMethod($controller, 'addDefaultOrderByClause', ['title', 'desc']);

        $this->assertInstanceOf(View::class, $controller->index(request()));
    }
}
