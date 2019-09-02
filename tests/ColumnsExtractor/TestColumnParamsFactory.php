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
                'display_in_create_form' => true,
                'display_in_update_form' => true,
                'display_in_list' => true,
                'searchable' => true,
                'min' => 2,
                'max' => 50,
                'required' => true,

            ],
            'description' => [
                'label' => 'Description',
                'display_in_create_form' => true,
                'display_in_update_form' => true,
                'display_in_list' => true,
                'searchable' => false,
                'min' => 2,
                'max' => 5000,
                'field_type' => 'textarea',

            ],
            'price' => [
                'label' => 'Price',
                'display_in_create_form' => true,
                'display_in_update_form' => true,
                'display_in_list' => true,
                'min' => 0,
                'max' => 100000,
                'field_type' => 'number',
            ],
            'file_upload' => [
                'label' => 'file_upload',
                'display_in_create_form' => true,
                'display_in_update_form' => true,
                'display_in_list' => true,
                'min' => 0,
                'max' => 100000,
                'field_type' => 'file_upload_to_db_field',
            ],
        ];
    }
}
