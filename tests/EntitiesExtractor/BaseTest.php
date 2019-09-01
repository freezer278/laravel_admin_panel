<?php

namespace Vmorozov\LaravelAdminGenerator\Tests\EntitiesExtractor;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Mockery;
use Vmorozov\LaravelAdminGenerator\App\Utils\ColumnsExtractor;
use Vmorozov\LaravelAdminGenerator\App\Utils\EntitiesExtractor;
use Vmorozov\LaravelAdminGenerator\Tests\ColumnsExtractor\TestColumnParamsFactory;
use Vmorozov\LaravelAdminGenerator\Tests\TestCase;

class BaseTest extends TestCase
{
    /**
     * @var Model
     */
    private $model;
    /**
     * @var ColumnsExtractor
     */
    private $columnsExtractor;
    /**
     * @var EntitiesExtractor
     */
    private $entitiesExtractor;
    /**
     * @var \Illuminate\Contracts\Pagination\Paginator
     */
    private $testPaginationOutput;
    /**
     * @var array
     */
    private $columnParams;
    /**
     * @var Builder|Mockery\LegacyMockInterface|Mockery\MockInterface
     */
    private $queryBuilderMock;

    /**
     * @throws BindingResolutionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->queryBuilderMock = Mockery::mock(Builder::class);
        $this->model = Mockery::mock(Model::class, [
            'getFillable' => [
                'title',
                'description',
            ]
        ]);

        $this->testPaginationOutput = new Paginator(collect([$this->model, $this->model, $this->model, $this->model]), 15, 1);

        $this->model->shouldReceive('newQuery')->andReturn($this->queryBuilderMock);
        $this->model->shouldReceive('getAttribute');

        $this->queryBuilderMock->shouldReceive('where')->andReturn($this->queryBuilderMock);
        $this->queryBuilderMock->shouldReceive('orderBy')->andReturn($this->queryBuilderMock);
        $this->queryBuilderMock
            ->shouldReceive('paginate')
            ->andReturn($this->testPaginationOutput);
        $this->queryBuilderMock
            ->shouldReceive('findOrFail')
            ->andReturn($this->model);

        $this->columnParams = TestColumnParamsFactory::create();

        $this->columnsExtractor = app()->make(ColumnsExtractor::class);
        $this->entitiesExtractor = new EntitiesExtractor($this->model, $this->columnParams);

    }

    /**
     *
     */
    public function testSimpleGetEntities()
    {
        $this->assertEquals($this->entitiesExtractor->getPaginated(), $this->testPaginationOutput);
    }

    /**
     *
     */
    public function testSearchGetEntities()
    {
        $this->assertEquals($this->entitiesExtractor->getPaginated(['search' => 'search_query', 'page' => 1]), $this->testPaginationOutput);
    }

    /**
     *
     */
    public function testAddOrderByClause()
    {
        $this->entitiesExtractor->addOrderByClause('title', 'asc');

        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testAddWhereClause()
    {
        $this->entitiesExtractor->addWhereClause('title', '=', 'test');

        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testGetEntitiesWithClauses()
    {
        $this->entitiesExtractor->addOrderByClause('title', 'asc');
        $this->entitiesExtractor->addWhereClause('title', '=', 'test');

        $this->assertEquals($this->entitiesExtractor->getPaginated(['search' => 'search_query', 'page' => 1]), $this->testPaginationOutput);
    }


    /**
     *
     */
    public function testGetSingleEntity()
    {
        $this->assertEquals($this->entitiesExtractor->getSingleEntity(123), $this->model);
    }
}
