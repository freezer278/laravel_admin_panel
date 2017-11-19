<?php

namespace Vmorozov\LaravelAdminGenerator\App\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider;
use Vmorozov\LaravelAdminGenerator\App\Utils\ColumnsExtractor;
use Vmorozov\LaravelAdminGenerator\App\Utils\EntitiesExtractor;
use Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager;

abstract class CrudController extends Controller
{
    protected $model;

    protected $url = '';

    protected $titleSingular = '';

    protected $titlePlural = '';

    public function __construct()
    {
        $this->setup();
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $columnsExtractor = new ColumnsExtractor($this->model);
        $entitiesExtractor = new EntitiesExtractor($columnsExtractor);

        $columns = $columnsExtractor->getActiveListColumns();
        $entities = $entitiesExtractor->getEntities();

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
        $columnsExtractor = new ColumnsExtractor($this->model);

        $columns = $columnsExtractor->getActiveListColumns();

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

        $entity = call_user_func($this->model.'::create', $data);

        session()->flash('message', 'Entity created successfully');

        return redirect(UrlManager::listRoute($this->url));
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
        $columnsExtractor = new ColumnsExtractor($this->model);

        $columns = $columnsExtractor->getActiveListColumns();
        $entity = call_user_func($this->model.'::find', $id);

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
        $entity = call_user_func($this->model.'::find', $id);

        if ($entity !== null) {
            throw new ModelNotFoundException();
        }
        else {
            $data = $this->validate($request, $this->getValidationRules());

            $entity->update($data);

            session()->flash('message', 'Entity changed successfully');

            return redirect(UrlManager::listRoute($this->url));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        call_user_func($this->model.'::delete', $id);

        return redirect(UrlManager::listRoute($this->url));
    }
}
