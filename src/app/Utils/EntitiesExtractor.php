<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;

use Illuminate\Database\Eloquent\Model;

class EntitiesExtractor
{
    /**
     * @var Model
     */
    private $model;

    /**
     * @var array
     */
    private $columnsExtractor;


    public function __construct(ColumnsExtractor $columnsExtractor)
    {
        $this->model = $columnsExtractor->getModelClass();
        $this->columnsExtractor = $columnsExtractor;
    }

    public function getEntities(array $params = [])
    {
        $entities = call_user_func($this->model.'::orderBy', 'id', 'desc');

        if (isset($params['search']) && $params['search'] !== null  && $params['search'] !== '')
            $entities = $this->addSearchQuery($entities, $params['search']);

        if (isset($params['page']))
            unset($params['page']);

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

    public function getSingleEntity(int $id)
    {
        $entity = call_user_func($this->model.'::find', $id);

        return $entity;
    }
}