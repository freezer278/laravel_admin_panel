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
use Vmorozov\LaravelAdminGenerator\Tests\ModelMockTrait;
use Vmorozov\LaravelAdminGenerator\Tests\TestCase;

class BaseTest extends TestCase
{
    use ModelMockTrait;

    /**
     * @var EntitiesExtractor
     */
    private $entitiesExtractor;
    /**
     * @var array
     */
    private $columnParams;

    /**
     * @throws BindingResolutionException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->setUpModelMock();

        $this->columnParams = TestColumnParamsFactory::create();

        $this->entitiesExtractor = new EntitiesExtractor($this->modelMock, $this->columnParams);

    }

    /**
     *
     */
    public function testSimpleGetEntities()
    {
        $this->assertEquals($this->entitiesExtractor->getPaginated(), $this->paginatedResponse);
    }

    /**
     *
     */
    public function testSearchGetEntities()
    {
        $this->assertEquals($this->entitiesExtractor->getPaginated(['search' => 'search_query', 'page' => 1]), $this->paginatedResponse);
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

        $this->assertEquals($this->entitiesExtractor->getPaginated(['search' => 'search_query', 'page' => 1]), $this->paginatedResponse);
    }


    /**
     *
     */
    public function testGetSingleEntity()
    {
        $this->assertEquals($this->entitiesExtractor->getSingleEntity(123), $this->modelMock);
    }
}
