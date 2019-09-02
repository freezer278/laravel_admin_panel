<?php


namespace Vmorozov\LaravelAdminGenerator\App\Utils\Columns;


use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

interface Column
{
    const TYPE_TEXT = 'text';
    const TYPE_NUMBER = 'number';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_EMAIL = 'email';
    const TYPE_SELECT = 'select';
    const TYPE_SELECT_MULTIPLE = 'select_multiple';
    const TYPE_DATE = 'select_date';
    const TYPE_DATE_TIME = 'select_date_time';
    const TYPE_FILE_UPLOAD_TO_DB_FIELD = 'file_upload_to_db_field';
    const TYPE_CKEDITOR = 'ckeditor';

    const DEFAULT_FIELD_TYPE = self::TYPE_TEXT;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @return bool
     */
    public function required(): bool;

    /**
     * @param Model $model
     * @return View
     */
    public function renderFormField(Model $model): View;

    /**
     * @param Model $model
     * @return View
     */
    public function renderListColumn(Model $model): View;
}
