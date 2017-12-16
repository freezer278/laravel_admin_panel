<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\Export;

use Vmorozov\LaravelAdminGenerator\App\Utils\Export\Strategies\ExportStrategy;

class DataExporter
{
    private $strategy;
    private $modelClass;

    public function __construct(string $modelClass, ExportStrategy $strategy)
    {
        $this->modelClass = $modelClass;
        $this->strategy = $strategy;
    }

    public function export()
    {
        return $this->strategy->export();
    }
}