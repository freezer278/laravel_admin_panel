<?php


namespace Vmorozov\LaravelAdminGenerator\App\Utils\Export\Strategies;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Vmorozov\LaravelAdminGenerator\App\Utils\Export\ModelExportInterface;

class CsvExportStrategy implements ExportStrategy
{
    /**
     * @param ModelExportInterface $modelExport
     * @return BinaryFileResponse
     */
    public function export(ModelExportInterface $modelExport): BinaryFileResponse
    {
        return $modelExport->download('export.csv');
    }
}
