<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Vmorozov\FileUploads\Uploader;
use Vmorozov\LaravelAdminGenerator\App\Utils\ColumnsExtractor;

/**
 * Class FilesSaver
 * @package Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads
 */
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
                $this->deleteFile($fileField);
            }
        }

        $this->model->update($modelUpdateParams);
    }

    public function deleteAllModelFiles()
    {
        foreach ($this->fileFields as $fileField => $params) {
            Uploader::deleteFile($this->model->$fileField);
        }
    }

    public function deleteFile(string $field)
    {
        $fieldType = $this->fileFields[$field]['type'] ?? '';

        if ($fieldType === 'file_upload_to_db_field') {
            Uploader::deleteFile($this->model->$field);
            $this->model->update([$field => null]);
        }
    }
}