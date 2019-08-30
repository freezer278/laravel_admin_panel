<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\Export;

use Illuminate\Database\Eloquent\Model;

class ModelExportFactory
{
    /**
     * @param Model $model
     * @return ModelExportInterface
     */
    public function createForModel(Model $model): ModelExportInterface
    {
        return new ModelExport($model);
    }
}
