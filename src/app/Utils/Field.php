<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;

use Illuminate\Database\Eloquent\Model;
use Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider;

/**
 * @deprecated
 * Class Field
 * @package Vmorozov\LaravelAdminGenerator\App\Utils
 */
class Field
{

    const FIELD_TYPE_TEXT = 'text';
    const FIELD_TYPE_NUMBER = 'number';
    const FIELD_TYPE_TEXTAREA = 'textarea';
    const FIELD_TYPE_EMAIL = 'email';
    const FIELD_TYPE_SELECT = 'select';
    const FIELD_TYPE_SELECT_MULTIPLE = 'select_multiple';
    const FIELD_TYPE_DATE = 'select_date';
    const FIELD_TYPE_DATE_TIME = 'select_date_time';
    const FIELD_TYPE_FILE_UPLOAD_TO_DB_FIELD = 'file_upload_to_db_field';
    const FIELD_TYPE_CKEDITOR = 'ckeditor';

    const PARAM_KEY_LABEL = 'label';
    const PARAM_KEY_FIELD_TYPE = 'field_type';
    const PARAM_KEY_REQUIRED = 'required';
    const PARAM_KEY_DISPLAY_IN_FORM = 'displayInForm';
    const PARAM_KEY_DISPLAY_IN_LIST = 'display_in_list';
    const PARAM_KEY_MIN = 'min';
    const PARAM_KEY_MAX = 'max';
    const PARAM_ACCEPT_MIME_TYPE = 'accept_mime_type';
    const PARAM_RELATION = 'relation';
    const PARAM_RELATION_MODEL = 'relation_model';
    const PARAM_RELATION_RELATION_DISPLAY_ATTRIBUTE = 'relation_display_attribute';

    const DEFAULT_TYPE = self::FIELD_TYPE_TEXT;
    const RELATIONAL_TYPES = [self::FIELD_TYPE_SELECT, self::FIELD_TYPE_SELECT_MULTIPLE];
    const FILE_UPLOAD_TYPES = [self::FIELD_TYPE_FILE_UPLOAD_TO_DB_FIELD];

    protected $modelClass;

    protected $fieldName = '';

    protected $params = [];

    protected $availableTypes = [];

    protected $viewParams;

    protected $viewType;

    protected function getAvailableTypes(): array
    {
        return [
            self::FIELD_TYPE_TEXT => [
                'column' => AdminGeneratorServiceProvider::VIEWS_NAME.'::list.column_types.text',
                'field' => AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.field_types.text',
            ],
            self::FIELD_TYPE_NUMBER => [
                'column' => AdminGeneratorServiceProvider::VIEWS_NAME.'::list.column_types.text',
                'field' => AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.field_types.number',
            ],
            self::FIELD_TYPE_TEXTAREA => [
                'column' => AdminGeneratorServiceProvider::VIEWS_NAME.'::list.column_types.text',
                'field' => AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.field_types.textarea',
            ],
            self::FIELD_TYPE_EMAIL => [
                'column' => AdminGeneratorServiceProvider::VIEWS_NAME.'::list.column_types.text',
                'field' => AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.field_types.email',
            ],

            self::FIELD_TYPE_SELECT => [
                'column' => AdminGeneratorServiceProvider::VIEWS_NAME.'::list.column_types.text',
                'field' => AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.field_types.select',
            ],
            self::FIELD_TYPE_SELECT_MULTIPLE => [
                'column' => AdminGeneratorServiceProvider::VIEWS_NAME.'::list.column_types.text',
                'field' => AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.field_types.select_multiple',
            ],


            self::FIELD_TYPE_DATE => [
                'column' => AdminGeneratorServiceProvider::VIEWS_NAME.'::list.column_types.text',
                'field' => AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.field_types.date',
            ],

            self::FIELD_TYPE_DATE_TIME => [
                'column' => AdminGeneratorServiceProvider::VIEWS_NAME.'::list.column_types.text',
                'field' => AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.field_types.date_time',
            ],


            self::FIELD_TYPE_FILE_UPLOAD_TO_DB_FIELD => [
                'column' => AdminGeneratorServiceProvider::VIEWS_NAME.'::list.column_types.text',
                'field' => AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.field_types.single_file_upload',
            ],

            self::FIELD_TYPE_CKEDITOR => [
                'column' => AdminGeneratorServiceProvider::VIEWS_NAME.'::list.column_types.text',
                'field' => AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.field_types.ckeditor',
            ],
//            'file' => [
//                'column' => '',
//                'field' => '',
//            ],
        ];
    }

    public function __construct(string $modelClass, string $fieldName, array $params)
    {
        $this->modelClass = $modelClass;

        $this->availableTypes = $this->getAvailableTypes();
        $this->fieldName = $fieldName;
        $this->params = $params;

        $this->viewType = isset($this->params[self::PARAM_KEY_FIELD_TYPE]) ? $this->params[self::PARAM_KEY_FIELD_TYPE] : self::DEFAULT_TYPE;

        $this->viewParams = $this->availableTypes[$this->viewType] ?? $this->availableTypes[self::DEFAULT_TYPE];
    }

    public function renderField(Model $model = null)
    {
        $model = $model ?? new $this->modelClass();

        $viewName = $this->viewParams['field'];

        if (in_array($this->viewType, self::RELATIONAL_TYPES)) {
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
        return isset($this->params[self::PARAM_KEY_LABEL]) ? $this->params[self::PARAM_KEY_LABEL] : title_case($this->fieldName);
    }

    public function getName(): string
    {
        return $this->fieldName;
    }
}
