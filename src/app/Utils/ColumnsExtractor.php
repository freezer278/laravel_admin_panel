<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;

use Vmorozov\LaravelAdminGenerator\App\Utils\Columns\Column;
use Vmorozov\LaravelAdminGenerator\App\Utils\Columns\Factory\ColumnFactoryInterface;

/**
 * Class ColumnsExtractor
 * @package Vmorozov\LaravelAdminGenerator\App\Utils
 */
class ColumnsExtractor
{
    /**
     * @var ColumnFactoryInterface
     */
    private $columnFactory;

    /**
     * ColumnsExtractor constructor.
     * @param ColumnFactoryInterface $columnFactory
     */
    public function __construct(ColumnFactoryInterface $columnFactory)
    {
        $this->columnFactory = $columnFactory;
    }

    /**
     * @param Column[] $columnParams
     * @return Column[]
     */
    public function getActiveListColumns(array $columnParams): array
    {
        $activeColumns = [];

        foreach ($columnParams as $key => $column) {
            if (isset($column['displayInList']) && $column['displayInList'] == true) {
                $column['name'] = $key;
                $activeColumns[] = $this->columnFactory->create($column);
            }
        }

        return $activeColumns;
    }

    /**
     * @param Column[] $columnParams
     * @return Column[]
     */
    public function getActiveAddEditFields(array $columnParams): array
    {
        $activeColumns = [];

        foreach ($columnParams as $key => $column) {
            if (isset($column['displayInForm']) && $column['displayInForm'] == true) {
                $column['name'] = $key;
                $activeColumns[] = $this->columnFactory->create($column);
            }
        }

        return $activeColumns;
    }

    /**
     * @param array $columnParams
     * @return array[]
     */
    public function getValidationRules(array $columnParams): array
    {
        $validationRules = [];

        foreach ($columnParams as $key => $column) {
            $validationRules[$key] = '';

            foreach ($column as $paramName => $paramValue) {
                switch ($paramName) {
                    case 'min':
                    case 'max':
                        $validationRules[$key] .= $paramName . ':' . $paramValue . '|';
                        break;
                    case 'required':
                        $validationRules[$key] .= 'required|';
                        break;
                }
            }
        }

        return $validationRules;
    }

    /**
     * @param array $columnParams
     * @return string[]
     */
    public function getSearchableColumnNames(array $columnParams): array
    {
        $searchable = [];

        foreach ($columnParams as $key => $column) {
            if (isset($column['searchable']) && $column['searchable'] == true)
                $searchable[] = $key;
        }

        return $searchable;
    }

    /**
     * @deprecated use getFileUploadColumnParams
     * @param array $columnParams
     * @return string[]
     */
    public function getFileUploadColumnNames(array $columnParams): array
    {
        return array_keys($this->getFileUploadColumnParams($columnParams));
    }

    /**
     * @param array $columnParams
     * @return string[]
     */
    public function getFileUploadColumnParams(array $columnParams): array
    {
        $results = [];

        foreach ($columnParams as $key => $column) {
            if (isset($column[Field::PARAM_KEY_FIELD_TYPE]) && in_array($column[Field::PARAM_KEY_FIELD_TYPE], Field::FILE_UPLOAD_TYPES) == true)
                $results[$key] = $column;
        }

        return $results;
    }

    /**
     * @param string $column
     * @return array
     */
    public function getColumnParams(string $column): array
    {
        return $this->columnParams[$column] ?? [];
    }
}
