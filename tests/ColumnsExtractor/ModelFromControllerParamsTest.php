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

    public function setUp(): void
    {
        parent::setUp();

        $this->model = new class extends Model {
            protected $fillable = [
                'title',
                'description',
                'price',
            ];
        };

        $this->columnsExtractor = new ColumnsExtractor($this->model, [
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
        $this->assertEquals('title' ,  $columns[0]->getName());
        $this->assertEquals('description' ,  $columns[1]->getName());
        $this->assertEquals('price' ,  $columns[2]->getName());
    }


    public function testGetActiveAddEditFields()
    {
        $columns = $this->columnsExtractor->getActiveAddEditFields();

//        $this->assertTrue(is_array($columns));
        $this->assertEquals('title' ,  $columns[0]->getName());
        $this->assertEquals('description' ,  $columns[1]->getName());
        $this->assertEquals('price' ,  $columns[2]->getName());
    }

    public function testGetSearchableColumns()
    {
        $columns = $this->columnsExtractor->getSearchableColumns();

        $this->assertEquals(count($columns) ,  3);
    }

    public function testGetFileUploadColumns()
    {
        $columns = $this->columnsExtractor->getFileUploadColumns();

        $this->assertEquals(count($columns) ,  0);
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

        $this->assertEquals($params ,  $expectedParams);
    }

    public function testGetValidationRules()
    {
        $expectedRules = [
            'title' => '',
            'description' => '',
            'price' => '',
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
