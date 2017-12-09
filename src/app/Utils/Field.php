<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;


use Illuminate\Database\Eloquent\Model;
use Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider;

class Field
{
    const DEFAULT_TYPE = 'text';

    protected $relationTypes = ['select'];

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
            'select' => [
                'column' => AdminGeneratorServiceProvider::VIEWS_NAME.'::list.column_types.text',
                'field' => AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.field_types.select',
            ],
//            'file' => [
//                'column' => '',
//                'field' => '',
//            ],
//        Todo: add all other needed types here
        ];
    }

    public function __construct(string $fieldName, array $params)
    {
        $this->availableTypes = $this->getAvailableTypes();
        $this->fieldName = $fieldName;
        $this->params = $params;

        $this->viewType = isset($this->params['field_type']) ? $this->params['field_type'] : self::DEFAULT_TYPE;
        $this->viewParams = $this->availableTypes[$this->viewType] ?: $this->availableTypes[self::DEFAULT_TYPE];
    }

    public function renderField(Model $model = null)
    {
        $viewName = $this->viewParams['field'];
        $relationModels = collect([]);
        $relationModelFieldName = isset($this->viewParams['relation_display_attribute']) ? $this->viewParams['relation_display_attribute'] : '';

        if (in_array($this->viewType, $this->relationTypes)) {
            $relationModels = (new $this->viewParams['relation_model']())->all();
        }

        return view($viewName)->with([
            'params' => $this->params,
            'fieldName' => $this->fieldName,
            'entity' => $model,
            'field' => $this,
            'relationModels' => $relationModels,
            'relationModelFieldName' => $relationModelFieldName,
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