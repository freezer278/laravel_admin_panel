<?php


namespace Vmorozov\LaravelAdminGenerator\App\Utils\Columns;

use Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider as ServiceProvider;

class Number extends AbstractColumn
{
    /**
     * @return string
     */
    protected function getFormFieldViewName(): string
    {
        return ServiceProvider::VIEWS_NAME.'::forms.field_types.number';
    }
}
