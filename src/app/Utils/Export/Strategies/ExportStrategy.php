<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\Export\Strategies;


interface ExportStrategy
{
    public function __construct(string $modelClass);

    public function export();
}