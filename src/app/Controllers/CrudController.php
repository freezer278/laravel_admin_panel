<?php

namespace Vmorozov\LaravelAdminGenerator\App\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider;

abstract class CrudController extends Controller
{
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entities = [];
        $columns = [];

        return view(AdminGeneratorServiceProvider::VIEWS_NAME.'::list.list')->with(compact('columns', 'entities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
