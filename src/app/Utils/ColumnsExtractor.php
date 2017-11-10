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

    public function getActiveListColumns(array $columnParams = [])
    {
        $columns = $this->model->adminFields;
        $activeColumns = [];

        foreach ($columns as $key => $column) {
            if ($column['displayInList'] == true)
                $activeColumns[$key] = $column;
        }

        return $activeColumns;
    }

    public function getActiveAddEditFields(array $columnParams = [])
    {
        $columns = $this->model->adminFields;
        $activeColumns = [];

        foreach ($columns as $key => $column) {
            if ($column['displayInForm'] == true)
                $activeColumns[$key] = $column;
        }

        return $activeColumns;
    }
}