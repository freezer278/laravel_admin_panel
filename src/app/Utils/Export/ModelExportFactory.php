<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\Export;

use Illuminate\Database\Eloquent\Model;

class ModelExportFactory
{
    /**
     * @param Model $model
     * @param array $columnParams
     * @return ModelExportInterface
     */
    public function createForModel(Model $model, array $columnParams): ModelExportInterface
    {
        return new ModelExport($model, $columnParams);
    }
}
