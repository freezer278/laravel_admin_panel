<?php


namespace Vmorozov\LaravelAdminGenerator\App\Utils\Columns;

use Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider as ServiceProvider;

class FileUploadToDb extends AbstractColumn
{
    /**
     * @return string
     */
    protected function getFormFieldViewName(): string
    {
        return ServiceProvider::VIEWS_NAME.'::forms.field_types.single_file_upload';
    }
}
