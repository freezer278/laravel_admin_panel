<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\Export\Strategies;


use Maatwebsite\Excel\Facades\Excel;

class XlsCsvStrategy implements ExportStrategy
{
    private $acceptableFormats = [
        'xlsx', 'xls', 'csv'
    ];

    private $format;
    private $modelClass;

    public function __construct(string $modelClass, string $format = 'xlsx')
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
        ini_set('max_execution_time', '300');


        Excel::create('Test', function($excel) {

            $excel->sheet('Export', function($sheet) {
                $sheet->appendRow($this->getColumns());

                (new $this->modelClass())->select($this->getColumns())->limit(5000)->chunk(1000, function ($models) use (&$sheet) {
                    foreach ($models as &$model) {
                        $sheet->appendRow($model->toArray());
                    }
                    unset($models);
                });
            });


        })->export($this->format);
    }

    protected function getColumns(): array
    {
        return (new $this->modelClass())->getFillable();
    }

    private function checkMemoryLimit(): bool
    {
        $memory_limit = ini_get('memory_limit');

        $val = trim($memory_limit);
        $last = strtolower($val[strlen($val)-1]);
        $val = (int) $val;
        switch($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        $memory_limit = $val;

        return memory_get_usage() < $memory_limit - 10000;
    }
}