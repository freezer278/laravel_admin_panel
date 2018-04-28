<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\Export\Strategies;

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Illuminate\Database\Eloquent\Model;

class XlsCsvStrategy implements ExportStrategy
{
    const MEMORY_LIMIT = '512M';

    private $acceptableFormats = [
        Type::XLSX, Type::CSV, Type::ODS
    ];
    private $format;
    private $model;

    public function __construct(Model $model, string $format = Type::XLSX)
    {
        $this->model = $model;

        if (in_array($format, $this->acceptableFormats))
            $this->format = $format;
        else
            throw new \Exception('XlsCsvStrategy error: illegal export format: '.$format);
    }

    public function export()
    {
        ini_set('memory_limit', self::MEMORY_LIMIT);
        set_time_limit(0);

        $writer = WriterFactory::create($this->format);
        $writer->openToBrowser('Export.'.$this->format); // stream data directly to the browser

        $this->model->select($this->getColumns())->chunk(500, function ($models) use (&$writer) {
            $writer->addRows($models->toArray());
            unset($models);
        });

        $writer->close();
    }

    protected function getColumns(): array
    {
        return $this->model->getFillable();
    }
}