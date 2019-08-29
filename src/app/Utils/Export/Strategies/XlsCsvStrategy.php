<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\Export\Strategies;

use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Exception;
use Illuminate\Database\Eloquent\Model;

class XlsCsvStrategy implements ExportStrategy
{
    /**
     * @var array
     */
    private $acceptableFormats = [
        Type::XLSX, Type::CSV, Type::ODS
    ];
    /**
     * @var string
     */
    private $format;
    /**
     * @var Model
     */
    private $model;

    /**
     * XlsCsvStrategy constructor.
     * @param Model $model
     * @param string $format
     * @throws Exception
     */
    public function __construct(Model $model, string $format = Type::XLSX)
    {
        $this->model = $model;

        if (in_array($format, $this->acceptableFormats))
            $this->format = $format;
        else
            throw new Exception('XlsCsvStrategy error: illegal export format: '.$format);
    }

    /**
     * @throws IOException
     * @throws UnsupportedTypeException
     */
    public function export()
    {
        ini_set('memory_limit', config('export.memory_limit', '512M'));
        set_time_limit(0);

        $writer = WriterFactory::create($this->format);
        $writer->openToBrowser('Export.'.$this->format); // stream data directly to the browser

        $this->model->select($this->getColumns())->chunk(config('export.single_chunk_size', 100), function ($models) use (&$writer) {
            $writer->addRows($models->toArray());
            unset($models);
        });

        $writer->close();
    }

    /**
     * @return array
     */
    protected function getColumns(): array
    {
        return $this->model->getFillable();
    }
}
