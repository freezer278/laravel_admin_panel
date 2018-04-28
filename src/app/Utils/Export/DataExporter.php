<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\Export;

use Illuminate\Database\Eloquent\Model;
use Vmorozov\LaravelAdminGenerator\App\Utils\Export\Strategies\ExportStrategy;

class DataExporter
{
    private $strategy;

    public function __construct(ExportStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function export()
    {
        return $this->strategy->export();
    }
}