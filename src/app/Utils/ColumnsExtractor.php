<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;

use Illuminate\Database\Eloquent\Model;

class ColumnsExtractor
{
    /**
     * @var string
     */
    private $modelClass;
    /**
     * @var Model
     */
    private $model;
    /**
     * @var array
     */
    private $columnParams;


    public function __construct(string $modelClass, array $columnParams = [])
    {
        $this->modelClass = $modelClass;
        $this->model = new $this->modelClass;

        if ($columnParams !== [])
            $this->setColumnParams($columnParams);
        else
            $this->setColumnParamsFromModel();
    }

    protected function setColumnParamsFromModel()
    {
        $this->columnParams = $this->model->adminFields;

        if ($this->columnParams === null) {
            $columns = $this->model->getFillable();

            foreach ($columns as $column) {
                $this->columnParams[$column] = [
                    'displayInForm' => true,
                    'displayInList' => true,
                    'searchable' => true,
                ];
            }
        }
    }

    public function setColumnParams(array $columnParams)
    {
        $this->setColumnParamsFromModel();

        $this->columnParams = array_merge($this->columnParams, $columnParams);
    }

    public function getModelClass(): string
    {
        return $this->modelClass;
    }

    public function getActiveListColumns(array $columnParams = []): array
    {
        $activeColumns = [];

        foreach ($this->columnParams as $key => $column) {
            if (isset($column['displayInList']) && $column['displayInList'] == true)
                $activeColumns[] = new Field($this->modelClass, $key, $column);
        }

        return $activeColumns;
    }

    public function getActiveAddEditFields(array $columnParams = []): array
    {
        $activeColumns = [];

        foreach ($this->columnParams as $key => $column) {
            if (isset($column['displayInForm']) && $column['displayInForm'] == true)
                $activeColumns[] = new Field($this->modelClass, $key, $column);
        }

        return $activeColumns;
    }

    public function getValidationRules(array $validationRules = []): array
    {
        $validationRules = [];

        foreach ($this->columnParams as $key => $column) {
            $validationRules[$key] = '';

            foreach ($column as $paramName => $paramValue) {
                switch ($paramName) {
                    case 'min':
                    case 'max':
                        $validationRules[$key] .= $paramName.':'.$paramValue.'|';
                        break;
                    case 'required':
                        $validationRules[$key] .= 'required|';
                        break;
                }
            }
        }

        return $validationRules;
    }

    public function getSearchableColumns(): array
    {
        $searchable = [];

        foreach ($this->columnParams as $key => $column) {
            if (isset($column['searchable']) && $column['searchable'] == true)
                $searchable[] = $key;
        }

        return $searchable;
    }

    public function getFileUploadColumns(): array
    {
        $results = [];

        foreach ($this->columnParams as $key => $column) {
            if (isset($column['type']) && in_array($column['type'], Field::FILE_UPLOAD_TYPES) == true)
                $results[] = $key;
        }

        return $results;
    }

    public function getColumnParams(string $column): array
    {
        return $this->columnParams[$column] ?? [];
    }
}