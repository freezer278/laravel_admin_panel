<?php


namespace Vmorozov\LaravelAdminGenerator\App\Utils\Export;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;

class ModelExport implements FromQuery, ModelExportInterface
{
    use Exportable;

    /**
     * @var Model
     */
    private $model;

    /**
     * ModelExport constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        return $this->model->newQuery();
    }
}
