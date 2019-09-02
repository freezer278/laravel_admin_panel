<?php


namespace Vmorozov\LaravelAdminGenerator\App\Utils\Export;

use Illuminate\Database\Eloquent\Model;
use Iterator;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromIterator;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ModelExport implements FromIterator, ModelExportInterface, WithHeadings, ShouldAutoSize
{
    use Exportable;

    /**
     * @var Model
     */
    private $model;
    /**
     * @var array
     */
    private $columnParams;

    /**
     * ModelExport constructor.
     * @param Model $model
     * @param array $columnParams
     */
    public function __construct(Model $model, array $columnParams)
    {
        $this->model = $model;
        $this->columnParams = array_filter($columnParams, function (array $params) {
            return $params['displayInList'] ?? false;
        });
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $headings = array_map(function (array $params) {
            return $params['label'];
        }, $this->columnParams);

        if (!isset($headings['id'])) {
            array_unshift($headings, 'id');
        }

        return $headings;
    }

    /**
     * @return Iterator
     */
    public function iterator(): Iterator
    {
        foreach ($this->model->newQuery()->cursor() as $model) {
            yield $this->createExportDataFromModel($model);
        }
    }

    /**
     * @param Model $model
     * @return array
     */
    private function createExportDataFromModel(Model $model): array
    {
        $res = [
            $model->getKey(),
        ];

        foreach ($this->columnParams as $column => $params) {
            $res[$column] = $model->$column;
        }

        return $res;
    }
}
