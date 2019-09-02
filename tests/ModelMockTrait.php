<?php


namespace Vmorozov\LaravelAdminGenerator\Tests;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Mockery;

trait ModelMockTrait
{
    /**
     * @var Model | Mockery\MockInterface
     */
    private $modelMock;
    /**
     * @var Builder | Mockery\MockInterface
     */
    private $queryBuilderMock;
    /**
     * @var \Illuminate\Contracts\Pagination\Paginator
     */
    private $paginatedResponse;

    /**
     *
     */
    protected function setUpModelMock(): void
    {
        $this->modelMock = Mockery::mock(TestModel::class);
        $this->queryBuilderMock = Mockery::mock(Builder::class);
        $this->paginatedResponse = new Paginator(collect([$this->modelMock, $this->modelMock, $this->modelMock]), 15, 1);

        $this->modelMock->shouldReceive('__construct');
        $this->modelMock->shouldReceive('newQuery')->andReturn($this->queryBuilderMock);
        $this->modelMock->shouldReceive('getAttribute');
        $this->modelMock->shouldReceive('setAttribute');
        $this->modelMock->shouldReceive('create')->andReturn($this->modelMock);
        $this->modelMock->shouldReceive('update');

        $this->queryBuilderMock->shouldReceive('where')->andReturn($this->queryBuilderMock);
        $this->queryBuilderMock->shouldReceive('orderBy')->andReturn($this->queryBuilderMock);
        $this->queryBuilderMock
            ->shouldReceive('paginate')
            ->andReturn($this->paginatedResponse);
        $this->queryBuilderMock
            ->shouldReceive('findOrFail')
            ->andReturn($this->modelMock);

        app()->instance(TestModel::class, $this->modelMock);
        app()->instance(Builder::class, $this->queryBuilderMock);
    }
}
