<?php


namespace Vmorozov\LaravelAdminGenerator\App\Utils\Columns\Factory;


use Vmorozov\LaravelAdminGenerator\App\Utils\Columns\Ckeditor;
use Vmorozov\LaravelAdminGenerator\App\Utils\Columns\Column;
use Vmorozov\LaravelAdminGenerator\App\Utils\Columns\Date;
use Vmorozov\LaravelAdminGenerator\App\Utils\Columns\DateTime;
use Vmorozov\LaravelAdminGenerator\App\Utils\Columns\Email;
use Vmorozov\LaravelAdminGenerator\App\Utils\Columns\FileUploadToDb;
use Vmorozov\LaravelAdminGenerator\App\Utils\Columns\Number;
use Vmorozov\LaravelAdminGenerator\App\Utils\Columns\Select;
use Vmorozov\LaravelAdminGenerator\App\Utils\Columns\SelectMultiple;
use Vmorozov\LaravelAdminGenerator\App\Utils\Columns\Text;
use Vmorozov\LaravelAdminGenerator\App\Utils\Columns\Textarea;

class ColumnFactory implements ColumnFactoryInterface
{
    /**
     * @param array $params
     * @return Column
     */
    public function create(array $params): Column
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
}
