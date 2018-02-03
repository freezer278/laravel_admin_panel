<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Vmorozov\FileUploads\Uploader;
use Vmorozov\LaravelAdminGenerator\App\Utils\ColumnsExtractor;

class FilesSaver
{
    private $model;
    private $columnsExtractor;
    private $request;

    private $fileFields = [];

    public function __construct(Model $model, ColumnsExtractor $columnsExtractor, Request $request)
    {
        $this->model = $model;
        $this->columnsExtractor = $columnsExtractor;
        $this->request = $request;

        $fileFields = $this->columnsExtractor->getFileUploadColumns();

        foreach ($fileFields as $fileField) {
            $this->fileFields[$fileField] = $this->columnsExtractor->getColumnParams($fileField);
        }
    }

    public function saveFiles()
    {
        $modelUpdateParams = [];

        foreach ($this->fileFields as $fileField => $params) {
            $file = $this->request->file($fileField);

            if ($file !== null) {
                $modelUpdateParams[$fileField] = Uploader::uploadFile($file, $params['upload_folder'] ?? '');
            }
        }

        $this->model->update($modelUpdateParams);
    }
}