<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RelationResolver
{
    const RELATION_MARKERS = ['relation', 'relation_display_attribute'];

    protected $model;
    protected $columnParams;

    public function __construct(Model $model, array $columnParams = [])
    {
        $this->model = $model;

        if ($columnParams !== [])
            $this->columnParams = $columnParams;
        else
            $this->columnParams = $this->model->adminFields;
    }

    protected function getColumnParamsByName(string $column)
    {
        if (!isset($this->columnParams[$column]))
            throw new \Exception('Column '.$column.' not found in model '.get_class($this->model));

        return $this->columnParams[$column];
    }

    protected function getRelatedModelClassName(array $params): string
    {
        return $params['relation_model'];
    }

    public function getRelatedModelDisplayField(array $params): string
    {
        return $params['relation_display_attribute'];
    }

    protected function getRelationMethod(string $column): string
    {
        $params = $this->getColumnParamsByName($column);

        return $params['relation'];
    }

    public function retrieveRelated(string $column)
    {
        $params = $this->getColumnParamsByName($column);

        $relatedColumns = $this->getRelatedModelClassName($params);
        $relatedColumns = (new $relatedColumns());
        $relatedColumns = $relatedColumns->all();

        return $relatedColumns;
    }

    public function setRelated(string $column, array $relatedIds)
    {
        $method = $this->getRelationMethod($column);
        $relation = $this->model->$method();

        if (method_exists($relation, 'sync'))
            $relation->sync($relatedIds);
    }

    public function getRelatedModelsIds(string $column): Collection
    {
        $method = $this->getRelationMethod($column);

        $relationModel = $this->getRelatedModelClassName($this->getColumnParamsByName($column));
        $relationModel = new $relationModel();

        $related = $this->model->$method()->get(['id']);

        return $related->pluck('id');
    }

    public function checkFieldHasRelation()
    {

    }

    public function getAllColumnsWithRelations(): array
    {
        $params = $this->columnParams;
        $result = [];

        foreach (($params ?? []) as $key => $param) {
            $intersects = array_intersect(array_keys($param), self::RELATION_MARKERS);
            if (count($intersects) > 0)
                $result[] = $key;
        }

        return $result;
    }

    public function saveAllRelations(Request $request)
    {
        $relationColumns = $this->getAllColumnsWithRelations();
        $request = $request->only($relationColumns);

        foreach ($request as $key => $item) {
            $this->setRelated($key, (array) $item);
        }
    }

}