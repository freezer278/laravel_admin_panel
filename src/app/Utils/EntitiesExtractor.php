<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;

use Illuminate\Database\Eloquent\Model;

/**
 * @deprecated
 * Class EntitiesExtractor
 * @package Vmorozov\LaravelAdminGenerator\App\Utils
 */
class EntitiesExtractor
{
    /**
     * @var string
     */
    protected $modelClass;
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
    private $clauses = [];

    /**
     * @var array
     */
    private $orderByClauses = [];


    /**
     * EntitiesExtractor constructor.
     * @param ColumnsExtractor $columnsExtractor
     * @param int $perPage
     */
    public function __construct(ColumnsExtractor $columnsExtractor, int $perPage = 0)
    {
        $this->modelClass = $columnsExtractor->getModelClass();
        $this->model = $columnsExtractor->getModel();
        $this->columnsExtractor = $columnsExtractor;

        $this->setPerPage($perPage);
    }

    /**
     * @param int $perPage
     */
    public function setPerPage(int $perPage)
    {
        if ($perPage > 0)
            $this->model->setPerPage($perPage);
    }

    /**
     * @param array $params
     * @param string $pageParam
     * @return Model|mixed
     */
    public function getEntities(array $params = [], $pageParam = 'page')
    {
        $entities = $this->model;

        if (isset($params['search']) && $params['search'] !== null  && $params['search'] !== '')
            $entities = $this->addSearchQuery($entities, $params['search']);

        $entities = $this->useQueryClauses($entities);

        if (isset($params[$pageParam]))
            unset($params[$pageParam]);

        $entities = $entities->paginate();
        $entities->appends($params);

        return $entities;
    }
    /**
     * @codeCoverageIgnore
     */
    protected function addSearchQuery($query, string $search)
    {
        $searchableColumns = $this->columnsExtractor->getSearchableColumns();

        if (count($searchableColumns) > 0) {
            $search = '%'.$search.'%';


            $query = $query->where(function ($q) use ($search, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $q->orWhere($column, 'like', $search);
                }
            });
        }

        return $query;
    }

    /**
     * @codeCoverageIgnore
     */
    protected function useQueryClauses($query)
    {
        $query = $query->where(function ($q) {
            $q->where($this->clauses);
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
        $this->clauses[] = [$column, $operator, $value];
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
     */
    public function getSingleEntity(int $id)
    {
        $entity = $this->model->find($id);

        return $entity;
    }
}
