<?php

namespace Vmorozov\LaravelAdminGenerator\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CrudController extends Controller
{
    private $url = '';

    private $titleSingular = '';

    private $titlePlural = '';


//    public function __construct(string $url = '', string $titleSingular = '', string $titlePlural = '')
    public function __construct()
    {
//        $this->url = $url;
//        $this->titleSingular = $titleSingular;
//        $this->titlePlural = $titlePlural;

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

        return view('laravel_admin_generator::list.list')->with(compact('columns', 'entities'));
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
