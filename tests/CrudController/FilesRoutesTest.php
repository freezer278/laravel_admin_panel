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
 * @coversDefaultClass \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager
 */
class FilesRoutesTest extends TestCase
{
    private $mock;

    public function setUp()
    {
        parent::setUp();

        $this->mock = Mockery::mock(TestModel::class);

        $this->mock->shouldReceive('__construct');
        $this->mock->shouldReceive('where')->andReturn($this->mock);
        $this->mock->shouldReceive('orderBy')->andReturn($this->mock);
    }

    public function testDeleteFileRoute()
    {

        $this->mock->shouldReceive('find')->andReturn($this->mock);

        $this->app->instance(TestModel::class, $this->mock);

        $controller = new TestController($this->mock);

        $this->assertInstanceOf(RedirectResponse::class, $controller->deleteFile(12, 'file_upload'));
    }

    public function testDeleteMedialibraryFile()
    {
        $this->mock->shouldReceive('find')->andReturn($this->mock);


        $mediaMock = $this->getMockBuilder(Media::class)
            ->setMethods(['update', 'find'])
            ->setConstructorArgs([['id' => 12]])
            ->getMock();

        $mediaMock->id = 12;

        $this->app->instance(TestModel::class, $this->mock);

        $controller = new TestController($this->mock);

        $this->assertInstanceOf(JsonResponse::class, $controller->deleteMedialibraryFile(12, $mediaMock));
    }

    public function testClearMedialibraryCollection()
    {
        $this->mock->shouldReceive('find')->andReturn($this->mock);

        $this->app->instance(TestModel::class, $this->mock);

        $controller = new TestController($this->mock);

        $this->assertInstanceOf(JsonResponse::class, $controller->clearMedialibraryCollection(12, 'collection'));
    }

    public function testUploadMedialibraryFile()
    {
        $mediaMock = Mockery::mock(\Spatie\MediaLibrary\Media::class);
        $mediaMock->shouldReceive('getAttribute')->with('id')->andReturn(self::MEDIA_DEFAULT_ID);
        $mediaMock->shouldReceive('getAttribute')->with('disk')->andReturn('public');
        $mediaMock->shouldReceive('getUrl')->andReturn('url');
        $mediaMock->shouldReceive('withCustomProperties')->andReturn($mediaMock);
        $mediaMock->shouldReceive('toMediaCollection')->andReturn(collect([$mediaMock]));

        $this->mock->shouldReceive('find')->andReturn($this->mock);
        $this->mock->shouldReceive('addMedia')->andReturn($mediaMock);
        $this->mock->shouldReceive('withCustomProperties')->andReturn($this->mock);
        $this->mock->shouldReceive('toMediaCollection')->andReturn($this->mock);
        $this->mock->shouldReceive('getMedia')->andReturn(collect([$mediaMock]));
        $this->mock->shouldReceive('last')->andReturn($mediaMock);
        $this->mock->shouldReceive('getAttribute')->with('id')->andReturn(self::MODEL_DEFAULT_ID);
        $this->mock->shouldReceive('getUrl')->andReturn('url');

        $this->app->instance(TestModel::class, $this->mock);

        $controller = new TestController($this->mock);

        $request = (new Request());
        $request->merge(['file' => UploadedFile::fake()->image('avatar.jpg')]);

        $response = $controller->uploadMedialibraryFile(self::MODEL_DEFAULT_ID, 'collection', $request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertArrayHasKey('id', $response->getData(true));
        $this->assertArrayHasKey('url', $response->getData(true));
        $this->assertArrayHasKey('delete_url', $response->getData(true));
    }
}