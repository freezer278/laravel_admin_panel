<?php


namespace Vmorozov\LaravelAdminGenerator\App\Utils\Columns;


use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider as ServiceProvider;

abstract class AbstractColumn implements Column
{
    /**
     * @var array
     */
    protected $params;

    /**
     * AbstractColumn constructor.
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * @param array $params
     * @return Column
     */
    public static function create(array $params): Column
    {
        switch ($params['field_type'] ?? Column::TYPE_TEXT) {
            case Column::TYPE_NUMBER:
                return new Number($params);
            case Column::TYPE_TEXTAREA:
                return new Textarea($params);
            case Column::TYPE_EMAIL:
                return new Email($params);
            case Column::TYPE_SELECT:
                return new Select($params);
            case Column::TYPE_SELECT_MULTIPLE:
                return new SelectMultiple($params);
            case Column::TYPE_DATE:
                return new Date($params);
            case Column::TYPE_DATE_TIME:
                return new DateTime($params);
            case Column::TYPE_FILE_UPLOAD_TO_DB_FIELD:
                return new FileUploadToDb($params);
            case Column::TYPE_CKEDITOR:
                return new Ckeditor($params);
            case Column::TYPE_TEXT:
            default:
                return new Text($params);
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->params['name'];
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->params['label'];
    }

    /**
     * @return bool
     */
    public function required(): bool
    {
        return $this->params['required'] ?? false;
    }

    /**
     * @param Model $model
     * @return View
     */
    public function renderFormField(Model $model): View
    {
        return view($this->getFormFieldViewName())->with([
            'params' => $this->params,
            'fieldName' => $this->getName(),
            'entity' => $model,
            'field' => $this,
            'relationModels' => collect([]),
            'relatedIds' => collect([]),
            'relationModelFieldName' => '',
        ]);
    }

    /**
     * @return string
     */
    protected function getFormFieldViewName(): string
    {
        return ServiceProvider::VIEWS_NAME.'::forms.field_types.text';
    }

    /**
     * @param Model $model
     * @return View
     */
    public function renderListColumn(Model $model): View
    {
        return view($this->getListColumnViewName())->with([
            'params' => $this->params,
            'fieldName' => $this->getName(),
            'entity' => $model,
            'field' => $this,
        ]);
    }

    /**
     * @return string
     */
    protected function getListColumnViewName(): string
    {
        return ServiceProvider::VIEWS_NAME.'::list.column_types.text';
    }
}
