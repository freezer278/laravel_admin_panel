<?php

namespace Vmorozov\LaravelAdminGenerator\App\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider;
use Vmorozov\LaravelAdminGenerator\App\Utils\ColumnsExtractor;
use Vmorozov\LaravelAdminGenerator\App\Utils\EntitiesExtractor;
use Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class CrudController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $columnsExtractor;
    protected $entitiesExtractor;

    protected $model;

    protected $url = '';

    protected $titleSingular = '';

    protected $titlePlural = '';

    protected $columnParams = [];

    public function __construct()
    {
        $this->setup();

        $this->columnsExtractor = new ColumnsExtractor($this->model, $this->columnParams);
        $this->entitiesExtractor = new EntitiesExtractor($this->columnsExtractor);
    }

    protected function setup()
    {

    }

    /**
     * Get validation rules for create and edit actions.
     *
     * @return array
     */
    protected abstract function getValidationRules(): array;


    protected function getEntity(int $id): Model
    {
        $entity = $this->entitiesExtractor->getSingleEntity($id);

        if ($entity === null) {
            throw new ModelNotFoundException();
        }

        return $entity;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $columns = $this->columnsExtractor->getActiveListColumns();
        $entities = $this->entitiesExtractor->getEntities();

        $title = $this->titlePlural;
        $url = $this->url;

        return view(AdminGeneratorServiceProvider::VIEWS_NAME.'::list.list')->with(compact('columns', 'entities', 'title', 'url'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $columns = $this->columnsExtractor->getActiveAddEditFields();

        $title = $this->titlePlural;
        $url = $this->url;

        return view(AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.create')->with(compact('columns', 'title', 'url'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, $this->getValidationRules());

        $this->beforeCreate();
        $entity = call_user_func($this->model.'::create', $data);
        $this->afterCreate();

        session()->flash('message', 'Entity created successfully');

        return redirect(UrlManager::listRoute($this->url));
    }

    protected function beforeCreate()
    {

    }

    protected function afterCreate()
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $columns = $this->columnsExtractor->getActiveAddEditFields();
        $entity = $this->getEntity($id);

        $title = $this->titlePlural;
        $url = $this->url;

        return view(AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.edit')->with(compact('columns', 'entity', 'title', 'url'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $entity = $this->getEntity($id);

        $data = $this->validate($request, $this->getValidationRules());

        $this->beforeUpdate();
        $entity->update($data);
        $this->afterUpdate();

        session()->flash('message', 'Entity changed successfully');

        return redirect(UrlManager::listRoute($this->url));
    }

    protected function beforeUpdate()
    {

    }

    protected function afterUpdate()
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $entity = $this->getEntity($id);
        $entity->delete();

        session()->flash('message', 'Entity deleted successfully');

        return redirect(UrlManager::listRoute($this->url));
    }
}
