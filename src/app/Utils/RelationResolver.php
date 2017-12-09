<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;

use Illuminate\Database\Eloquent\Model;

class RelationResolver
{
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

    public function getRelatedModelDisplayField(array $params = []): string
    {
        return $params['relation_display_attribute'];
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

    }

}