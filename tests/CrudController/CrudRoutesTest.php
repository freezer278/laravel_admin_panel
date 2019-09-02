<?php

namespace Vmorozov\LaravelAdminGenerator\Tests\CrudController;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Mockery;
use ReflectionException;
use Vmorozov\LaravelAdminGenerator\Tests\ModelMockTrait;
use Vmorozov\LaravelAdminGenerator\Tests\TestCase;
use Vmorozov\LaravelAdminGenerator\Tests\TestModel;

/**
 * @coversDefaultClass \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager
 */
class CrudRoutesTest extends TestCase
{
    use ModelMockTrait;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpModelMock();
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
     * @throws ValidationException
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
        $request->shouldReceive('only')->andReturn([]);
        $request->shouldReceive('file')->andReturn(null);
        $this->app->instance(Request::class, $request);

//        $this->modelMock->shouldReceive('save')->once();

        $controller = new TestController();
        $this->assertInstanceOf(RedirectResponse::class, $controller->store());
    }

    /**
     * @throws BindingResolutionException
     * @throws ValidationException
     */
    public function testUpdateRoute()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('all')
            ->andReturn([
                'title' => 'title',
                'description' => 'description',
                'price' => 'price',
            ]);
        $request->shouldReceive('only')->andReturn([]);
        $request->shouldReceive('file')->andReturn(null);
        $this->app->instance(Request::class, $request);

        $controller = new TestController();
        $this->assertInstanceOf(RedirectResponse::class, $controller->update(12));
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
