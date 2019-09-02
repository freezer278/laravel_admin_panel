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
            if (isset($column['display_in_list']) && $column['display_in_list'] == true) {
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
    public function getCreateFormFields(array $columnParams): array
    {
        $activeColumns = [];

        foreach ($columnParams as $key => $column) {
            if ($this->displayInCreateForm($column)) {
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
    public function getUpdateFormFields(array $columnParams): array
    {
        $activeColumns = [];

        foreach ($columnParams as $key => $column) {
            if ($this->displayInUpdateForm($column)) {
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
    public function getCreateFormValidationRules(array $columnParams): array
    {
        $validationRules = [];

        foreach ($columnParams as $key => $column) {
            if (!$this->displayInCreateForm($column)) {
                continue;
            }

            $validationRules[$key] = $this->getValidationRulesForSingleColumn($column);
        }

        return $validationRules;
    }

    /**
     * @param array $columnParams
     * @return array[]
     */
    public function getUpdateFormValidationRules(array $columnParams): array
    {
        $validationRules = [];

        foreach ($columnParams as $key => $column) {
            if (!$this->displayInUpdateForm($column)) {
                continue;
            }

            $validationRules[$key] = $this->getValidationRulesForSingleColumn($column);
        }

        return $validationRules;
    }

    /**
     * @param array $params
     * @return array[]
     */
    public function getValidationRulesForSingleColumn(array $params): array
    {
        $rules = [];

        foreach ($params as $paramName => $paramValue) {
            switch ($paramName) {
                case 'min':
                case 'max':
                    $rules[] = $paramName . ':' . $paramValue;
                    break;
                case 'required':
                    $rules[] = 'required';
                    break;
            }
        }

        return $rules;
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
     * @param array $columnParams
     * @return string[]
     * @deprecated use getFileUploadColumnParams
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
     * @param array $singleColumnParams
     * @return bool
     */
    private function displayInCreateForm(array $singleColumnParams): bool
    {
        return ($singleColumnParams['display_in_create_form'] ?? false);
    }

    /**
     * @param array $singleColumnParams
     * @return bool
     */
    private function displayInUpdateForm(array $singleColumnParams): bool
    {
        return ($singleColumnParams['display_in_update_form'] ?? false);
    }
}
