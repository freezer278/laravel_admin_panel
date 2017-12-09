<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;

use Illuminate\Database\Eloquent\Model;

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
    private $clauses = [];


    public function __construct(ColumnsExtractor $columnsExtractor)
    {
        $this->model = $columnsExtractor->getModelClass();
        $this->columnsExtractor = $columnsExtractor;
    }

    public function getEntities(array $params = [], $pageParam = 'page')
    {
        $entities = call_user_func($this->model.'::orderBy', 'id', 'desc');

        if (isset($params['search']) && $params['search'] !== null  && $params['search'] !== '')
            $entities = $this->addSearchQuery($entities, $params['search']);

        $entities = $this->useQueryClauses($entities);

        if (isset($params[$pageParam]))
            unset($params[$pageParam]);

        $entities = $entities->paginate();
        $entities->appends($params);

        return $entities;
    }

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

    protected function useQueryClauses($query)
    {
        $query = $query->where(function ($q) {
            $q->where($this->clauses);
        });

        return $query;
    }

    public function addWhereClause(string $column, string $operator, $value)
    {
        $this->clauses[] = [$column, $operator, $value];
    }

    public function getSingleEntity(int $id)
    {
        $entity = call_user_func($this->model.'::find', $id);

        return $entity;
    }
}