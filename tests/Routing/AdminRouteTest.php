<?php

namespace Vmorozov\LaravelAdminGenerator\Tests\Field;

use Illuminate\Database\Eloquent\Model;
use Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider;
use Vmorozov\LaravelAdminGenerator\App\Controllers\CrudController;
use Vmorozov\LaravelAdminGenerator\App\Utils\AdminRoute;
use Vmorozov\LaravelAdminGenerator\App\Utils\ColumnsExtractor;
use Vmorozov\LaravelAdminGenerator\App\Utils\Field;
use Vmorozov\LaravelAdminGenerator\Tests\TestCase;

/**
 * @coversDefaultClass \Vmorozov\LaravelAdminGenerator\App\Utils\Field
 */
class AdminRouteTest extends TestCase
{
    private $model;
    private $controller;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = $this->getTestDummyModel();

        $this->controller = new class($this->model) extends CrudController {
            protected $url = 'products';
            protected $titlePlural = 'Товары';
            protected $titleSingular = 'Товар';
        };

    }

    public function testResourceMethod()
    {
        AdminRoute::resource(get_class($this->controller), $this->model);
        $this->assertTrue(true);
    }

    public function testHomeMethod()
    {
        AdminRoute::home();
        $this->get('/admin');

        $this->assertTrue(true);
    }


}
