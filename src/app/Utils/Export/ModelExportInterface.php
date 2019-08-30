<?php


namespace Vmorozov\LaravelAdminGenerator\App\Utils\Export;

use Illuminate\Http\Response;
use Iterator;
use Maatwebsite\Excel\Exceptions\NoFilenameGivenException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

interface ModelExportInterface
{
    /**
     * @param string      $fileName
     * @param string|null $writerType
     * @param array       $headers
     *
     * @return Response|BinaryFileResponse
     * @throws NoFilenameGivenException
     */
    public function download(string $fileName, string $writerType = null, array $headers = null);

    /**
     * @return Iterator
     */
    public function iterator(): Iterator;
}
