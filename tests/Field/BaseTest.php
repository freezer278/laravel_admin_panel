<?php

namespace Vmorozov\LaravelAdminGenerator\Tests\Field;

use Illuminate\Database\Eloquent\Model;
use Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider;
use Vmorozov\LaravelAdminGenerator\App\Utils\ColumnsExtractor;
use Vmorozov\LaravelAdminGenerator\App\Utils\Field;
use Vmorozov\LaravelAdminGenerator\Tests\TestCase;

/**
 * @coversDefaultClass \Vmorozov\LaravelAdminGenerator\App\Utils\Field
 */
class BaseTest extends TestCase
{
    private $model;

    private $columnsExtractor;

    public function setUp()
    {
        parent::setUp();

        $this->model = $this->getTestDummyModel();

        $this->model->id = 11;
        $this->model->title = 'title value';
        $this->model->description = 'description value';
        $this->model->price = 'price value';

        $this->columnsExtractor = new ColumnsExtractor($this->model);
    }

    protected function createField(string $fieldName = 'title')
    {
        return new Field(get_class($this->model), $fieldName, $this->model->adminFields[$fieldName]);
    }

    public function testRequired()
    {
        $field = $this->createField('title');

        $this->assertTrue($field->required());
    }

    public function testGetLabel()
    {
        $field = $this->createField('title');

        $this->assertEquals($field->getLabel(), 'Title');
    }

    public function testRenderColumn()
    {
        $field = $this->createField('title');

        $this->assertEquals($field->renderColumn($this->model)->getName(), AdminGeneratorServiceProvider::VIEWS_NAME.'::list.column_types.text');
    }


    public function testRenderTextField()
    {
        $field = $this->createField('title');

        $this->assertEquals($field->renderField($this->model)->getName(), AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.field_types.text');
    }


    public function testRenderTextareaField()
    {
        $field = $this->createField('description');

        $this->assertEquals($field->renderField($this->model)->getName(), AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.field_types.textarea');
    }
}