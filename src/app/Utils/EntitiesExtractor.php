<?php

namespace Vmorozov\LaravelAdminGenerator\Utils;

use Illuminate\Database\Eloquent\Model;

class EntitiesExtractor
{
    /**
     * @var Model
     */
    private $modelClass;

    /**
     * @var array
     */
    private $columnParams;


    public function __construct(string $modelClass, ColumnsExtractor $columnParams)
    {
        $this->modelClass = $modelClass;
        $this->columnParams = $columnParams;
    }

    public function getEntities(array $params = [])
    {

    }
}