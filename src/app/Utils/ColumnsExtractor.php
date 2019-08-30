<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils;

use Vmorozov\LaravelAdminGenerator\App\Utils\Columns\AbstractColumn;
use Vmorozov\LaravelAdminGenerator\App\Utils\Columns\Column;

/**
 * Class ColumnsExtractor
 * @package Vmorozov\LaravelAdminGenerator\App\Utils
 */
class ColumnsExtractor
{
    /**
     * @param Column[] $columnParams
     * @return array
     */
    public function getActiveListColumns(array $columnParams = []): array
    {
        $activeColumns = [];

        foreach ($columnParams as $key => $column) {
            if (isset($column['displayInList']) && $column['displayInList'] == true) {
                $column['name'] = $key;
                $activeColumns[] = AbstractColumn::create($column);
            }
        }

        return $activeColumns;
    }

    /**
     * @param Column[] $columnParams
     * @return array
     */
    public function getActiveAddEditFields(array $columnParams = []): array
    {
        $activeColumns = [];

        foreach ($columnParams as $key => $column) {
            if (isset($column['displayInForm']) && $column['displayInForm'] == true) {
                $column['name'] = $key;
                $activeColumns[] = AbstractColumn::create($column);
            }
        }

        return $activeColumns;
    }

    /**
     * @param array $columnParams
     * @return array
     */
    public function getValidationRules(array $columnParams = []): array
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
     * @return array
     */
    public function getSearchableColumns(array $columnParams = []): array
    {
        $searchable = [];

        foreach ($columnParams as $key => $column) {
            if (isset($column['searchable']) && $column['searchable'] == true)
                $searchable[] = $key;
        }

        return $searchable;
    }

    /**
     * @param array $columnParams
     * @return array
     */
    public function getFileUploadColumns(array $columnParams = []): array
    {
        $results = [];

        foreach ($columnParams as $key => $column) {
            if (isset($column[Field::PARAM_KEY_FIELD_TYPE]) && in_array($column[Field::PARAM_KEY_FIELD_TYPE], Field::FILE_UPLOAD_TYPES) == true)
                $results[] = $key;
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
