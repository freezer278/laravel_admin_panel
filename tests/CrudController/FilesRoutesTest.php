<?php

namespace Vmorozov\LaravelAdminGenerator\Tests\CrudController;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Mockery;
use Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads\Medialibrary\Media;
use Vmorozov\LaravelAdminGenerator\Tests\ModelMockTrait;
use Vmorozov\LaravelAdminGenerator\Tests\TestCase;
use Vmorozov\LaravelAdminGenerator\Tests\TestModel;

/**
 * @coversDefaultClass \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager
 */
class FilesRoutesTest extends TestCase
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
    public function testDeleteFileRoute()
    {
        $controller = new TestController();

        $this->assertInstanceOf(RedirectResponse::class, $controller->deleteFile(12, 'file_upload'));
    }

    /**
     * @throws BindingResolutionException
     * @throws Exception
     */
    public function testDeleteMedialibraryFile()
    {
        $mediaMock = $this->getMockBuilder(Media::class)
            ->setMethods(['update', 'find'])
            ->setConstructorArgs([['id' => 12]])
            ->getMock();
        $mediaMock->id = 12;

        $controller = new TestController();

        $this->assertInstanceOf(JsonResponse::class, $controller->deleteMedialibraryFile(12, $mediaMock));
    }

    /**
     * @throws BindingResolutionException
     */
    public function testClearMedialibraryCollection()
    {
        $controller = new TestController();

        $this->assertInstanceOf(JsonResponse::class, $controller->clearMedialibraryCollection(12, 'collection'));
    }

    /**
     * @throws BindingResolutionException
     * @throws ValidationException
     */
    public function testUploadMedialibraryFile()
    {
        $mediaMock = Mockery::mock(\Spatie\MediaLibrary\Models\Media::class);
        $mediaMock->shouldReceive('getAttribute')->with('id')->andReturn(self::MEDIA_DEFAULT_ID);
        $mediaMock->shouldReceive('getKey')->andReturn(self::MEDIA_DEFAULT_ID);
        $mediaMock->shouldReceive('getAttribute')->with('disk')->andReturn('public');
        $mediaMock->shouldReceive('getUrl')->andReturn('url');
        $mediaMock->shouldReceive('withCustomProperties')->andReturn($mediaMock);
        $mediaMock->shouldReceive('toMediaCollection')->andReturn(collect([$mediaMock]));

        $this->modelMock->shouldReceive('find')->andReturn($this->modelMock);
        $this->modelMock->shouldReceive('addMedia')->andReturn($mediaMock);
        $this->modelMock->shouldReceive('withCustomProperties')->andReturn($this->modelMock);
        $this->modelMock->shouldReceive('toMediaCollection')->andReturn($this->modelMock);
        $this->modelMock->shouldReceive('getMedia')->andReturn(collect([$mediaMock]));
        $this->modelMock->shouldReceive('last')->andReturn($mediaMock);
        $this->modelMock->shouldReceive('getAttribute')->with('id')->andReturn(self::MODEL_DEFAULT_ID);
        $this->modelMock->shouldReceive('getKey')->andReturn(self::MEDIA_DEFAULT_ID);
        $this->modelMock->shouldReceive('getUrl')->andReturn('url');

        $this->app->instance(TestModel::class, $this->modelMock);

        $controller = new TestController();

        $request = (new Request());
        $request->merge(['file' => UploadedFile::fake()->image('avatar.jpg')]);

        $response = $controller->uploadMedialibraryFile(self::MODEL_DEFAULT_ID, 'collection', $request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertArrayHasKey('id', $response->getData(true));
        $this->assertArrayHasKey('url', $response->getData(true));
        $this->assertArrayHasKey('delete_url', $response->getData(true));
    }
}
