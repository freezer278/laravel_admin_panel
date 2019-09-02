<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Vmorozov\FileUploads\Uploader;
use Vmorozov\LaravelAdminGenerator\App\Utils\Columns\Column;
use Vmorozov\LaravelAdminGenerator\App\Utils\ColumnsExtractor;
use Vmorozov\LaravelAdminGenerator\App\Utils\Field;

/**
 * Class FilesSaver
 * @package Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads
 */
class FilesSaver
{
    /**
     * @var ColumnsExtractor
     */
    private $columnsExtractor;
    /**
     * @var Request
     */
    private $request;

    /**
     * FilesSaver constructor.
     * @param ColumnsExtractor $columnsExtractor
     * @param Request $request
     */
    public function __construct(ColumnsExtractor $columnsExtractor, Request $request)
    {
        $this->columnsExtractor = $columnsExtractor;
        $this->request = $request;
    }

    /**
     * @param Model $model
     * @param array $columnParams
     */
    public function saveFiles(Model $model, array $columnParams): void
    {
        $modelUpdateParams = [];

        foreach ($this->columnsExtractor->getFileUploadColumnParams($columnParams) as $fileField => $params) {
            $file = $this->request->file($fileField);

            if ($file !== null) {
                $modelUpdateParams[$fileField] = Uploader::uploadFile($file, $params['upload_folder'] ?? '');
                $this->deleteFile($model, $columnParams, $fileField);
            }
        }

        $model->update($modelUpdateParams);
    }

    /**
     * @param Model $model
     * @param array $columnParams
     */
    public function deleteAllModelFiles(Model $model, array $columnParams): void
    {
        foreach ($this->columnsExtractor->getFileUploadColumnParams($columnParams) as $fileField => $params) {
            if($model->$fileField !== null) {
                Uploader::deleteFile($model->$fileField);
            }
        }
    }

    /**
     * @param Model $model
     * @param array $columnParams
     * @param string $field
     */
    public function deleteFile(Model $model, array $columnParams, string $field): void
    {
        $type = $columnParams[$field]['type'] ?? '';

        if ($type === Column::TYPE_FILE_UPLOAD_TO_DB_FIELD) {
            Uploader::deleteFile($model->$field);
            $model->update([$field => null]);
        }
    }
}
