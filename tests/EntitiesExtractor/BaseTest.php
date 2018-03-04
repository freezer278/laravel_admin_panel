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

    const PER_PAGE = 25;

    public function setUp()
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
    }

    public function testSimpleGetEntities()
    {
        $this->model->shouldReceive('where')
            ->andReturn($this->model);

        $this->model->shouldReceive('orderBy')
            ->andReturn($this->model);

        $this->model->shouldReceive('paginate')
            ->andReturn(new Paginator(collect([$this->model, $this->model, $this->model, $this->model]), self::PER_PAGE, 1));

        $this->entitiesExtractor->getEntities();
    }

    public function testSearchGetEntities()
    {
        $this->model->shouldReceive('where')
            ->andReturn($this->model);

        $this->model->shouldReceive('orderBy')
            ->andReturn($this->model);

        $this->model->shouldReceive('paginate')
            ->andReturn(new Paginator(collect([$this->model, $this->model, $this->model, $this->model]), self::PER_PAGE, 1));

        $this->entitiesExtractor->getEntities(['search' => 'search_query', 'page' => 1]);
    }

    public function testAddOrderByClause()
    {
        $this->entitiesExtractor->addOrderByClause('title', 'asc');
    }

    public function testAddWhereClause()
    {
        $this->entitiesExtractor->addWhereClause('title', '=', 'test');
    }

    /**
     * @depends testAddOrderByClause
     * @depends testAddWhereClause
     */
    public function testGetEntitiesWithClauses()
    {
        $this->testAddOrderByClause();
        $this->testAddWhereClause();

        $this->model->shouldReceive('where')
            ->andReturn($this->model);

        $this->model->shouldReceive('orderBy')
            ->andReturn($this->model);

        $this->model->shouldReceive('paginate')
            ->andReturn(new Paginator(collect([$this->model, $this->model, $this->model, $this->model]), self::PER_PAGE, 1));

        $this->entitiesExtractor->getEntities(['search' => 'search_query', 'page' => 1]);
    }



    public function testGetSingleEntity()
    {
        $this->model->shouldReceive('find')
            ->andReturn($this->model);

        $this->entitiesExtractor->getSingleEntity(123);
    }

//\Vmorozov\LaravelAdminGenerator\App\Utils\EntitiesExtractor::getEntities
//\Vmorozov\LaravelAdminGenerator\App\Utils\EntitiesExtractor::useQueryClauses
//\Vmorozov\LaravelAdminGenerator\App\Utils\EntitiesExtractor::addWhereClause
//\Vmorozov\LaravelAdminGenerator\App\Utils\EntitiesExtractor::addOrderByClause
}