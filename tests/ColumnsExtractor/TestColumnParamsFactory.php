<?php


namespace Vmorozov\LaravelAdminGenerator\Tests\ColumnsExtractor;

/**
 * Class TestColumnParamsFactory
 * @package Vmorozov\LaravelAdminGenerator\Tests\ColumnsExtractor
 */
class TestColumnParamsFactory
{
    /**
     * @return array
     */
    public static function create(): array
    {
        return [
            'title' => [
                'label' => 'Title',
                'displayInForm' => true,
                'displayInList' => true,
                'searchable' => true,
                'min' => 2,
                'max' => 50,
                'required' => true,

            ],
            'description' => [
                'label' => 'Description',
                'displayInForm' => true,
                'displayInList' => true,
                'searchable' => false,
                'min' => 2,
                'max' => 5000,
                'field_type' => 'textarea',

            ],
            'price' => [
                'label' => 'Price',
                'displayInForm' => true,
                'displayInList' => true,
                'min' => 0,
                'max' => 100000,
                'field_type' => 'number',
            ],
            'file_upload' => [
                'label' => 'file_upload',
                'displayInForm' => true,
                'displayInList' => true,
                'min' => 0,
                'max' => 100000,
                'field_type' => 'file_upload_to_db_field',
            ],
        ];
    }
}