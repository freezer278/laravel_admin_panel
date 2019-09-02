<?php


namespace Vmorozov\LaravelAdminGenerator\App\Utils\Columns;

use Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider as ServiceProvider;

class SelectMultiple extends AbstractRelationalColumn
{
    /**
     * @return string
     */
    protected function getFormFieldViewName(): string
    {
        return ServiceProvider::VIEWS_NAME.'::forms.field_types.select_multiple';
    }
}
