<?php


namespace Vmorozov\LaravelAdminGenerator\App\Utils\Export;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ModelExport implements FromQuery, ModelExportInterface, WithHeadings, ShouldAutoSize
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
        return $this->model->newQuery()->select($this->model->getFillable());
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return $this->model->getFillable();
    }
}
