<?php

namespace Vmorozov\LaravelAdminGenerator\Tests\ColumnsExtractor;

use Vmorozov\LaravelAdminGenerator\App\Utils\ColumnsExtractor;
use Vmorozov\LaravelAdminGenerator\Tests\TestCase;

class ColumnsExtractorTest extends TestCase
{
    /**
     * @var ColumnsExtractor
     */
    private $columnsExtractor;
    /**
     * @var array
     */
    private $columnParams;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->columnParams = TestColumnParamsFactory::create();
        $this->columnsExtractor = $this->app->make(ColumnsExtractor::class);
    }


    /**
     *
     */
    public function testGetActiveListColumns()
    {
        $columns = $this->columnsExtractor->getActiveListColumns($this->columnParams);

        $this->assertEquals(count($this->columnParams), count($columns));
        $this->assertTrue('title' == $columns[0]->getName());
        $this->assertTrue('description' == $columns[1]->getName());
        $this->assertTrue('price' == $columns[2]->getName());
    }


    /**
     *
     */
    public function testGetActiveAddEditFields()
    {
        $columns = $this->columnsExtractor->getActiveAddEditFields($this->columnParams);

//        $this->assertTrue(is_array($columns));
        $this->assertTrue('title' == $columns[0]->getName());
        $this->assertTrue('description' == $columns[1]->getName());
        $this->assertTrue('price' == $columns[2]->getName());
    }

    /**
     *
     */
    public function testGetSearchableColumnNames()
    {
        $columns = $this->columnsExtractor->getSearchableColumnNames($this->columnParams);

        $this->assertTrue('title' == $columns[0] && count($columns) === 1);
    }

    /**
     *
     */
    public function testGetFileUploadColumnNames()
    {
        $columns = $this->columnsExtractor->getFileUploadColumnNames($this->columnParams);

        $this->assertTrue('file_upload' == $columns[0] && count($columns) === 1);
    }

    /**
     *
     */
    public function testGetValidationRules()
    {
        $expectedRules = [
            'title' => 'min:2|max:50|required|',
            'description' => 'min:2|max:5000|',
            'price' => 'min:0|max:100000|',
            'file_upload' => 'min:0|max:100000|',
        ];

        $rules = $this->columnsExtractor->getValidationRules($this->columnParams);

        $this->assertEquals($expectedRules ,  $rules);
    }
}
