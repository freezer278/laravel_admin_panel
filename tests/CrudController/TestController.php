<?php
namespace Vmorozov\LaravelAdminGenerator\Tests\CrudController;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Vmorozov\LaravelAdminGenerator\App\Controllers\CrudController;
use Vmorozov\LaravelAdminGenerator\Tests\ColumnsExtractor\TestColumnParamsFactory;
use Vmorozov\LaravelAdminGenerator\Tests\TestModel;

class TestController extends CrudController
{
    /**
     * @var string
     */
    protected $model = TestModel::class;
    /**
     * @var string
     */
    protected $url = 'products';
    /**
     * @var string
     */
    protected $titlePlural = 'Products';
    /**
     * @var string
     */
    protected $titleSingular = 'Product';

    /**
     * TestController constructor.
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->columnParams = TestColumnParamsFactory::create();
        parent::__construct();
    }
}