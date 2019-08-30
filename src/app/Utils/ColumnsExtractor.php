<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;

use Illuminate\Database\Eloquent\Model;
use Vmorozov\LaravelAdminGenerator\App\Utils\Columns\AbstractColumn;
use Vmorozov\LaravelAdminGenerator\App\Utils\Columns\Column;

/**
 * @deprecated
 * Class ColumnsExtractor
 * @package Vmorozov\LaravelAdminGenerator\App\Utils
 */
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
    private $columnParams = [];


    /**
     * ColumnsExtractor constructor.
     * @param Model $model
     * @param array $columnParams
     */
    public function __construct(Model $model, array $columnParams = [])
    {
        $this->model = $model;
        $this->modelClass = get_class($model);

        if ($columnParams !== [])
            $this->setColumnParams($columnParams);
        else
            $this->setColumnParamsFromModel();
    }

    /**
     *
     */
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

    /**
     * @param array $columnParams
     */
    public function setColumnParams(array $columnParams)
    {
        $this->columnParams = array_merge($this->columnParams, $columnParams);
    }

    /**
     * @return string
     */
    public function getModelClass(): string
    {
        return $this->modelClass;
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @param Column[] $columnParams
     * @return array
     */
    public function getActiveListColumns(array $columnParams = []): array
    {
        $activeColumns = [];

        foreach ($this->columnParams as $key => $column) {
            if (isset($column['displayInList']) && $column['displayInList'] == true) {
                $column['name'] = $key;
                $activeColumns[] = AbstractColumn::create($column);
            }
        }

        return $activeColumns;
    }

    /**
     * @param Column[] $columnParams
     * @return array
     */
    public function getActiveAddEditFields(array $columnParams = []): array
    {
        $activeColumns = [];

        foreach ($this->columnParams as $key => $column) {
            if (isset($column['displayInForm']) && $column['displayInForm'] == true) {
                $column['name'] = $key;
                $activeColumns[] = AbstractColumn::create($column);
            }
        }

        return $activeColumns;
    }

    /**
     * @param array $validationRules
     * @return array
     */
    public function getValidationRules(array $validationRules = []): array
    {
        $validationRules = [];

        foreach ($this->columnParams as $key => $column) {
            $validationRules[$key] = '';

            foreach ($column as $paramName => $paramValue) {
                switch ($paramName) {
                    case 'min':
                    case 'max':
                        $validationRules[$key] .= $paramName . ':' . $paramValue . '|';
                        break;
                    case 'required':
                        $validationRules[$key] .= 'required|';
                        break;
                }
            }
        }

        return $validationRules;
    }

    /**
     * @return array
     */
    public function getSearchableColumns(): array
    {
        $searchable = [];

        foreach ($this->columnParams as $key => $column) {
            if (isset($column['searchable']) && $column['searchable'] == true)
                $searchable[] = $key;
        }

        return $searchable;
    }

    /**
     * @return array
     */
    public function getFileUploadColumns(): array
    {
        $results = [];

        foreach ($this->columnParams as $key => $column) {
            if (isset($column[Field::PARAM_KEY_FIELD_TYPE]) && in_array($column[Field::PARAM_KEY_FIELD_TYPE], Field::FILE_UPLOAD_TYPES) == true)
                $results[] = $key;
        }

        return $results;
    }

    /**
     * @param string $column
     * @return array
     */
    public function getColumnParams(string $column): array
    {
        return $this->columnParams[$column] ?? [];
    }
}
