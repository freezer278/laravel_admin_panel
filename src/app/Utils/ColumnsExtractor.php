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
            $this->columnParams = $columnParams;
    }

    public function getModelClass(): string
    {
        return $this->modelClass;
    }

    public function getActiveListColumns(array $columnParams = []): array
    {
        $columns = $this->model->adminFields;
        $activeColumns = [];

        foreach ($columns as $key => $column) {
            if (isset($column['displayInList']) && $column['displayInList'] == true)
                $activeColumns[$key] = $column;
        }

        return $activeColumns;
    }

    public function getActiveAddEditFields(array $columnParams = []): array
    {
        $columns = $this->model->adminFields;
        $activeColumns = [];

        foreach ($columns as $key => $column) {
            if (isset($column['displayInForm']) && $column['displayInForm'] == true)
                $activeColumns[$key] = $column;
        }

        return $activeColumns;
    }

    public function getValidationRules(array $validationRules = []): array
    {
        $columns = $this->model->adminFields;
        $validationRules = [];

        foreach ($columns as $key => $column) {
            $validationRules[$key] = '';

            foreach ($column as $paramName => $paramValue) {
                switch ($paramName) {
                    case 'min':
                    case 'max':
                        $validationRules[$key] .= $paramName.':'.$paramValue.'|';
                        break;
                }
            }
        }

        return $validationRules;
    }

    public function getSearchableColumns(): array
    {
        $columns = $this->model->adminFields;
        $searchable = [];

        foreach ($columns as $key => $column) {
            if (isset($column['searchable']) && $column['searchable'] == true)
                $searchable[] = $key;
        }

        return $searchable;
    }
}