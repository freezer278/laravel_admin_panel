<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class EntitiesExtractor
 * @package Vmorozov\LaravelAdminGenerator\App\Utils
 */
class EntitiesExtractor
{
    /**
     * @var Model
     */
    protected $model;
    /**
     * @var ColumnsExtractor
     */
    protected $columnsExtractor;
    /**
     * @var array
     */
    private $whereClauses = [];
    /**
     * @var array
     */
    private $orderByClauses = [];
    /**
     * @var int
     */
    protected $perPage = 15;
    /**
     * @var array
     */
    private $columnParams;
    /**
     * @var array
     */
    private $searchableColumns;

    /**
     * EntitiesExtractor constructor.
     * @param Model $model
     * @param array $columnParams
     * @throws BindingResolutionException
     */
    public function __construct(Model $model, array $columnParams)
    {
        $this->model = $model;
        $this->columnsExtractor = app()->make(ColumnsExtractor::class);
        $this->columnParams = $columnParams;
        $this->searchableColumns = $this->columnsExtractor->getSearchableColumnNames($columnParams);
    }

    /**
     * @param int $perPage
     */
    public function setPerPage(int $perPage)
    {
        $this->perPage = $perPage;
    }

    /**
     * @param array $getParams
     * @return Paginator
     */
    public function getPaginated(array $getParams = []): Paginator
    {
        $entities = $this->model->newQuery();

        if (isset($getParams['search']) && $getParams['search'] && $getParams['search'] !== '')
            $entities = $this->useSearchInQuery($entities, $getParams['search']);

        $entities = $this->useQueryClauses($entities);

        if (isset($getParams['page']))
            unset($getParams['page']);

        $entities = $entities->paginate($this->perPage);
        $entities->appends($getParams);

        return $entities;
    }

    /**
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    protected function useSearchInQuery(Builder $query, string $search): Builder
    {
        if (count($this->searchableColumns) > 0) {
            $search = '%'.$search.'%';

            $query = $query->where(function ($q) use ($search) {
                foreach ($this->searchableColumns as $column) {
                    $q->orWhere($column, 'like', $search);
                }
            });
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    protected function useQueryClauses(Builder $query): Builder
    {
        $query = $query->where(function ($q) {
            $q->where($this->whereClauses);
        });

        foreach ($this->orderByClauses as $clause) {
            $query = $query->orderBy($clause['column'], $clause['direction']);
        }

        return $query;
    }

    /**
     * @param string $column
     * @param string $operator
     * @param $value
     */
    public function addWhereClause(string $column, string $operator, $value)
    {
        $this->whereClauses[] = [$column, $operator, $value];
    }

    /**
     * @param string $column
     * @param string $direction
     */
    public function addOrderByClause(string $column, string $direction)
    {
        $this->orderByClauses[] = [
            'column' => $column,
            'direction' => $direction
        ];
    }

    /**
     * @param int $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function getSingleEntity(int $id)
    {
        $entity = $this->model->newQuery()->findOrFail($id);

        return $entity;
    }
}
