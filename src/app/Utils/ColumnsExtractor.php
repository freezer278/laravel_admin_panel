<?php

namespace Vmorozov\LaravelAdminGenerator\Utils;

use Illuminate\Database\Eloquent\Model;

class ColumnsExtractor
{
    /**
     * @var Model
     */
    private $modelClass;
    /**
     * @var array
     */
    private $columnParams;


    public function __construct(string $modelClass, array $columnParams = [])
    {
        $this->modelClass = $modelClass;

        if ($columnParams !== [])
            $this->columnParams = $columnParams;
    }

    public function getActiveColumns()
    {

    }
}