<?php


namespace Vmorozov\LaravelAdminGenerator\App\Utils\Columns\Factory;

use Vmorozov\LaravelAdminGenerator\App\Utils\Columns\Column;

interface ColumnFactoryInterface
{

    /**
     * @param array $params
     * @return Column
     */
    public function create(array $params): Column;
}
