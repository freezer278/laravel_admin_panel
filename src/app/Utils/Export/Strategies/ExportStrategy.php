<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\Export\Strategies;


use Illuminate\Database\Eloquent\Model;

interface ExportStrategy
{
    public function __construct(Model $model);

    public function export();
}