<?php

namespace Vmorozov\LaravelAdminGenerator\Tests\ColumnsExtractor;

use Illuminate\Database\Eloquent\Model;
use Vmorozov\LaravelAdminGenerator\App\Utils\ColumnsExtractor;
use Vmorozov\LaravelAdminGenerator\Tests\TestCase;

/**
 * @coversDefaultClass \Vmorozov\LaravelAdminGenerator\App\Utils\ColumnsExtractor
 */
class ModelFromControllerParamsTest extends TestCase
{
    private $model;

    private $columnsExtractor;

    public function setUp()
    {
        parent::setUp();

        $this->model = new class extends Model {
            protected $fillable = [
                'title',
                'description',
                'price',
            ];
        };

        $this->columnsExtractor = new ColumnsExtractor(get_class($this->model), [
            'title' => [
                'displayInForm' => true,
                'displayInList' => true,
                'searchable' => true,
            ],
            'description' => [
                'displayInForm' => true,
                'displayInList' => true,
                'searchable' => true,
            ],
            'price' => [
                'displayInForm' => true,
                'displayInList' => true,
                'searchable' => true,
            ],
        ]);
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

        $this->assertTrue(count($columns) === 3);
    }

    public function testGetFileUploadColumns()
    {
        $columns = $this->columnsExtractor->getFileUploadColumns();

        $this->assertTrue(count($columns) === 0);
    }

    public function testGetColumnParams()
    {
        $params = $this->columnsExtractor->getColumnParams('title');

        $expectedParams = [
            'displayInForm' => true,
            'displayInList' => true,
            'searchable' => true,
        ];

        $this->model->adminFields['title'];

        $this->assertTrue($params === $expectedParams);
    }

    public function testGetValidationRules()
    {
        $expectedRules = [
            'title' => '',
            'description' => '',
            'price' => '',
        ];

        $rules = $this->columnsExtractor->getValidationRules();

        $this->assertTrue($expectedRules === $rules);
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

        $this->assertTrue($titleEditParams === $this->columnsExtractor->getColumnParams('title'));
    }

    public function testGetModelClass()
    {
        $this->assertTrue($this->columnsExtractor->getModelClass() === get_class($this->model));
    }
}