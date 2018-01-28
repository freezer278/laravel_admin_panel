<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\ModelTraits;


use Carbon\Carbon;

trait AdminPanelTrait
{
    public function getAdminPanelColumns(): array
    {
        return $this->adminColumns ?? $this->fillable ?? [];
    }

    public function fromDateTime($value)
    {
        if (is_string($value))
            $value = Carbon::parse($value);

        $format = $this->getDateFormat();
        $value = $this->asDateTime($value);
        return $value->format($format);
    }
}