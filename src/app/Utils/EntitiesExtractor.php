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
        $entities = call_user_func($this->model.'::paginate');

        return $entities;
    }
}