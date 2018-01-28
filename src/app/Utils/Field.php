<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;

use Illuminate\Database\Eloquent\Model;
use Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider;

class Field
{
    const DEFAULT_TYPE = 'text';

    protected $modelClass;

    protected $relationTypes = ['select', 'select_multiple'];

    protected $fieldName = '';

    protected $params = [];

    protected $availableTypes = [];

    protected $viewParams;

    protected $viewType;

    protected function getAvailableTypes(): array
    {
        return [
            'text' => [
                'column' => AdminGeneratorServiceProvider::VIEWS_NAME.'::list.column_types.text',
                'field' => AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.field_types.text',
            ],
            'number' => [
                'column' => AdminGeneratorServiceProvider::VIEWS_NAME.'::list.column_types.text',
                'field' => AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.field_types.number',
            ],
            'textarea' => [
                'column' => AdminGeneratorServiceProvider::VIEWS_NAME.'::list.column_types.text',
                'field' => AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.field_types.textarea',
            ],


            'select' => [
                'column' => AdminGeneratorServiceProvider::VIEWS_NAME.'::list.column_types.text',
                'field' => AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.field_types.select',
            ],
            'select_multiple' => [
                'column' => AdminGeneratorServiceProvider::VIEWS_NAME.'::list.column_types.text',
                'field' => AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.field_types.select_multiple',
            ],


            'date' => [
                'column' => AdminGeneratorServiceProvider::VIEWS_NAME.'::list.column_types.text',
                'field' => AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.field_types.date',
            ],

            'date_time' => [
                'column' => AdminGeneratorServiceProvider::VIEWS_NAME.'::list.column_types.text',
                'field' => AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.field_types.date_time',
            ],

//            'file' => [
//                'column' => '',
//                'field' => '',
//            ],
//        Todo: add all other needed types here
        ];
    }

    public function __construct(string $modelClass, string $fieldName, array $params)
    {
        $this->modelClass = $modelClass;

        $this->availableTypes = $this->getAvailableTypes();
        $this->fieldName = $fieldName;
        $this->params = $params;

        $this->viewType = isset($this->params['field_type']) ? $this->params['field_type'] : self::DEFAULT_TYPE;
        $this->viewParams = $this->availableTypes[$this->viewType] ?? $this->availableTypes[self::DEFAULT_TYPE];
    }

    public function renderField(Model $model = null)
    {
        $model = $model ?? new $this->modelClass();

        $viewName = $this->viewParams['field'];

        if (in_array($this->viewType, $this->relationTypes)) {
            $relationResolver = new RelationResolver($model);

            $relatedIds = $relationResolver->getRelatedModelsIds($this->fieldName);
            $relationModels = $relationResolver->retrieveRelated($this->fieldName);
            $relationModelFieldName = $relationResolver->getRelatedModelDisplayField($this->params);

        }

        return view($viewName)->with([
            'params' => $this->params,
            'fieldName' => $this->fieldName,
            'entity' => $model,
            'field' => $this,
            'relationModels' => $relationModels ?? collect([]),
            'relatedIds' => $relatedIds ?? collect([]),
            'relationModelFieldName' => $relationModelFieldName ?? '',
        ]);
    }

    public function renderColumn(Model $model)
    {
        $viewName = $this->viewParams['column'];

        return view($viewName)->with([
            'params' => $this->params,
            'fieldName' => $this->fieldName,
            'entity' => $model,
            'field' => $this,
        ]);
    }

    public function required(): bool
    {
        return isset($this->params['required']) && $this->params['required'] === true;
    }

    public function getLabel(): string
    {
        return isset($this->params['label']) ? $this->params['label'] : title_case($this->fieldName);
    }
}