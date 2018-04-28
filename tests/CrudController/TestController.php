<?php
namespace Vmorozov\LaravelAdminGenerator\Tests\CrudController;

use Illuminate\Database\Eloquent\Model;
use Vmorozov\LaravelAdminGenerator\App\Controllers\CrudController;
use Vmorozov\LaravelAdminGenerator\Tests\TestModel;

class TestController extends CrudController
{
    protected $model = TestModel::class;
    protected $url = 'products';
    protected $titlePlural = 'Products';
    protected $titleSingular = 'Product';

    public function __construct(TestModel $model = null)
    {
        parent::__construct($model);
    }
}