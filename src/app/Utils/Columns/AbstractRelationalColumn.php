<?php


namespace Vmorozov\LaravelAdminGenerator\App\Utils\Columns;


use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Vmorozov\LaravelAdminGenerator\App\Utils\RelationResolver;

class AbstractRelationalColumn extends AbstractColumn
{
    /**
     * @param Model $model
     * @return View
     */
    public function renderFormField(Model $model): View
    {
        // todo: fix relation resolver usage to use dependency injection
        $relationResolver = new RelationResolver($model);

        $relatedIds = $relationResolver->getRelatedModelsIds($this->getName());
        $relationModels = $relationResolver->retrieveRelated($this->getName());
        $relationModelFieldName = $relationResolver->getRelatedModelDisplayField($this->params);

        return parent::renderFormField($model)->with([
            'relatedIds' => $relatedIds,
            'relationModels' => $relationModels,
            'relationModelFieldName' => $relationModelFieldName,
        ]);
    }
}
