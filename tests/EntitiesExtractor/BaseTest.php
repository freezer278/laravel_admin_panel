<?php

namespace Vmorozov\LaravelAdminGenerator\Tests\EntitiesExtractor;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Mockery;
use Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider;
use Vmorozov\LaravelAdminGenerator\App\Utils\ColumnsExtractor;
use Vmorozov\LaravelAdminGenerator\App\Utils\EntitiesExtractor;
use Vmorozov\LaravelAdminGenerator\App\Utils\Field;
use Vmorozov\LaravelAdminGenerator\Tests\TestCase;

/**
 * @coversDefaultClass \Vmorozov\LaravelAdminGenerator\App\Utils\EntitiesExtractor
 */
class BaseTest extends TestCase
{
    private $model;

    private $columnsExtractor;
    private $entitiesExtractor;

    private $testPaginationOutput;

    const PER_PAGE = 25;

    public function setUp(): void
    {
        $this->model = Mockery::mock(Model::class, [
            'getFillable' => [
                'title',
                'description',
            ]
        ]);

        $this->model
            ->shouldReceive('getAttribute')
            ->shouldReceive('setPerPage');

        $this->columnsExtractor = new ColumnsExtractor($this->model);
        $this->entitiesExtractor = new EntitiesExtractor($this->columnsExtractor, self::PER_PAGE);

        $this->testPaginationOutput = new Paginator(collect([$this->model, $this->model, $this->model, $this->model]), self::PER_PAGE, 1);
    }

    public function testSimpleGetEntities()
    {
        $this->model->shouldReceive('where')
            ->andReturn($this->model);

        $this->model->shouldReceive('orderBy')
            ->andReturn($this->model);

        $this->model->shouldReceive('paginate')
            ->andReturn($this->testPaginationOutput);

        $this->assertEquals($this->entitiesExtractor->getEntities(), $this->testPaginationOutput);
    }

    public function testSearchGetEntities()
    {
        $this->model->shouldReceive('where')
            ->andReturn($this->model);

        $this->model->shouldReceive('orderBy')
            ->andReturn($this->model);

        $this->model->shouldReceive('paginate')
            ->andReturn($this->testPaginationOutput);

        $this->assertEquals($this->entitiesExtractor->getEntities(['search' => 'search_query', 'page' => 1]), $this->testPaginationOutput);
    }

    public function testAddOrderByClause()
    {
        $this->entitiesExtractor->addOrderByClause('title', 'asc');

        $this->assertTrue(true);
    }

    public function testAddWhereClause()
    {
        $this->entitiesExtractor->addWhereClause('title', '=', 'test');

        $this->assertTrue(true);
    }

    public function testGetEntitiesWithClauses()
    {
        $this->model->shouldReceive('where')
            ->andReturn($this->model);

        $this->model->shouldReceive('orderBy')
            ->andReturn($this->model);

        $this->model->shouldReceive('paginate')
            ->andReturn($this->testPaginationOutput);

        $this->entitiesExtractor->addOrderByClause('title', 'asc');
        $this->entitiesExtractor->addWhereClause('title', '=', 'test');

        $this->assertEquals($this->entitiesExtractor->getEntities(['search' => 'search_query', 'page' => 1]), $this->testPaginationOutput);
    }



    public function testGetSingleEntity()
    {
        $this->model->shouldReceive('find')
            ->andReturn($this->model);

        $this->assertEquals($this->entitiesExtractor->getSingleEntity(123), $this->model);
    }
}
