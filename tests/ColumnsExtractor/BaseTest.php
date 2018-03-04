<?php

namespace Vmorozov\LaravelAdminGenerator\Tests\ColumnsExtractor;

use Illuminate\Database\Eloquent\Model;
use Vmorozov\LaravelAdminGenerator\App\Utils\ColumnsExtractor;
use Vmorozov\LaravelAdminGenerator\Tests\TestCase;

/**
 * @coversDefaultClass \Vmorozov\LaravelAdminGenerator\App\Utils\ColumnsExtractor
 */
class BaseTest extends TestCase
{
    private $model;

    private $columnsExtractor;

    public function setUp()
    {
        parent::setUp();

        $this->model = $this->getTestDummyModel();

        $this->columnsExtractor = new ColumnsExtractor($this->model);
    }


    public function testGetActiveListColumns()
    {
        $columns = $this->columnsExtractor->getActiveListColumns();

//        $this->assertTrue(is_array($columns));
        $this->assertTrue('title' == $columns[0]->getName());
        $this->assertTrue('description' == $columns[1]->getName());
        $this->assertTrue('price' == $columns[2]->getName());
    }


    public function testGetActiveAddEditFields()
    {
        $columns = $this->columnsExtractor->getActiveAddEditFields();

//        $this->assertTrue(is_array($columns));
        $this->assertTrue('title' == $columns[0]->getName());
        $this->assertTrue('description' == $columns[1]->getName());
        $this->assertTrue('price' == $columns[2]->getName());
    }

    public function testGetSearchableColumns()
    {
        $columns = $this->columnsExtractor->getSearchableColumns();

        $this->assertTrue('title' == $columns[0] && count($columns) === 1);
    }

    public function testGetFileUploadColumns()
    {
        $columns = $this->columnsExtractor->getFileUploadColumns();

        $this->assertTrue('file_upload' == $columns[0] && count($columns) === 1);
    }

    public function testGetColumnParams()
    {
        $params = $this->columnsExtractor->getColumnParams('title');

        $this->model->adminFields['title'];

        $this->assertEquals($params ,  $this->model->adminFields['title']);
    }

    public function testGetValidationRules()
    {
        $expectedRules = [
            'title' => 'min:2|max:50|required|',
            'description' => 'min:2|max:5000|',
            'price' => 'min:0|max:100000|',
            'file_upload' => 'min:0|max:100000|',
        ];

        $rules = $this->columnsExtractor->getValidationRules();

        $this->assertEquals($expectedRules ,  $rules);
    }


    public function testSetColumnParams()
    {
        $titleEditParams = [
            'label' => 'Edited Title',
            'displayInForm' => false,
            'displayInList' => false,
            'searchable' => false,
            'min' => 2,
            'max' => 5000,
            'field_type' => 'textarea',
        ];

        $this->columnsExtractor->setColumnParams([
            'title' => $titleEditParams
        ]);

        $this->assertEquals($titleEditParams ,  $this->columnsExtractor->getColumnParams('title'));
    }

    public function testGetModelClass()
    {
        $this->assertEquals($this->columnsExtractor->getModelClass() ,  get_class($this->model));
    }
}