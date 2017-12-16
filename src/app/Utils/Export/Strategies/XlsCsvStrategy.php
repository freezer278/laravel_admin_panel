<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\Export\Strategies;

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

class XlsCsvStrategy implements ExportStrategy
{
    private $acceptableFormats = [
        Type::XLSX, Type::CSV, Type::ODS
    ];

    private $format;
    private $modelClass;

    public function __construct(string $modelClass, string $format = Type::XLSX)
    {
        $this->modelClass = $modelClass;

        if (in_array($format, $this->acceptableFormats))
            $this->format = $format;
        else
            throw new \Exception('XlsCsvStrategy error: illegal export format: '.$format);
    }

    public function export()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(0);

        $writer = WriterFactory::create($this->format);
        $writer->openToBrowser('Export.'.$this->format); // stream data directly to the browser

        (new $this->modelClass())->select($this->getColumns())->chunk(500, function ($models) use (&$writer) {
            $writer->addRows($models->toArray());
            unset($models);
        });

        $writer->close();
    }

    protected function getColumns(): array
    {
        return (new $this->modelClass())->getFillable();
    }
}