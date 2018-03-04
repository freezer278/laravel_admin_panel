<?php

namespace Vmorozov\LaravelAdminGenerator\App\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider;
use Vmorozov\LaravelAdminGenerator\App\Utils\ColumnsExtractor;
use Vmorozov\LaravelAdminGenerator\App\Utils\EntitiesExtractor;
use Vmorozov\LaravelAdminGenerator\App\Utils\Export\DataExporter;
use Vmorozov\LaravelAdminGenerator\App\Utils\Export\Strategies\XlsCsvStrategy;
use Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads\FilesSaver;
use Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads\Medialibrary\Media;
use Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads\Medialibrary\MediaExtractor;
use Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads\Medialibrary\MediaSaver;
use Vmorozov\LaravelAdminGenerator\App\Utils\RelationResolver;
use Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager;

abstract class CrudController extends Controller
{
    protected $columnsExtractor;
    protected $entitiesExtractor;

    protected $model;

    protected $url = '';

    protected $titleSingular = '';

    protected $titlePlural = '';

    protected $columnParams = [];

    protected $listItemButtons = [];

    public function __construct()
    {
        $this->columnsExtractor = new ColumnsExtractor($this->model, $this->columnParams);
        $this->entitiesExtractor = new EntitiesExtractor($this->columnsExtractor);

        $this->setup();
    }

    protected function setup()
    {

    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Add button to the list with given params.
     * To put entity id to the url you can simply set '{id}' instead of it and entity id will be automatically set there.
     *
     * @param string $url
     * @param string $text
     * @param string $classes
     * @param array $htmlAttributes
     */
    protected function addListItemButton(string $url, string $text, string $classes = '', array $htmlAttributes = [])
    {
        if ($classes === '')
            $classes = 'btn btn-default';

        $this->listItemButtons[] = [
            'url' => url($url),
            'text' => $text,
            'classes' => $classes,
            'htmlAttributes' => $htmlAttributes,
        ];
    }

    protected function addDefaultWhereClause(string $column, string $operator, $value)
    {
        $this->entitiesExtractor->addWhereClause($column, $operator, $value);
    }

    protected function addDefaultOrderByClause(string $column, string $direction)
    {
        $this->entitiesExtractor->addOrderByClause($column, $direction);
    }

    /**
     * Get validation rules for create and edit actions.
     *
     * @return array
     */
    protected function getValidationRules(): array
    {
        return $this->columnsExtractor->getValidationRules();
    }


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
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $requestParams = $request->all();

        $columns = $this->columnsExtractor->getActiveListColumns();
        $entities = $this->entitiesExtractor->getEntities($requestParams);

        $titleSingular = $this->titleSingular;
        $titlePlural = $this->titlePlural;
        $url = $this->getUrl();

        $search = (isset($requestParams['search']) ? $requestParams['search'] : '');
        $listItemButtons = $this->listItemButtons;

        return view(AdminGeneratorServiceProvider::VIEWS_NAME.'::list.list')
            ->with(compact('columns', 'entities', 'titleSingular', 'titlePlural', 'url', 'search', 'listItemButtons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $columns = $this->columnsExtractor->getActiveAddEditFields();

        $titleSingular = $this->titleSingular;
        $titlePlural = $this->titlePlural;
        $url = $this->getUrl();

        $mediaExtractor = new MediaExtractor(new $this->model());

        return view(AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.create')
            ->with(compact('columns', 'titleSingular', 'titlePlural', 'url', 'mediaExtractor'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->getValidationRules());

        $data = $request->all();

        $this->beforeCreate();

        $entity = (new $this->model($data));
        $entity->save();

        $relationsResolver = new RelationResolver($entity);
        $relationsResolver->saveAllRelations($request);

        $filesSaver = new FilesSaver($entity, $this->columnsExtractor, $request);
        $filesSaver->saveFiles();

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

        $titleSingular = $this->titleSingular;
        $titlePlural = $this->titlePlural;
        $url = $this->getUrl();

        $mediaExtractor = new MediaExtractor($entity);

        return view(AdminGeneratorServiceProvider::VIEWS_NAME.'::forms.edit')
            ->with(compact('columns', 'entity', 'titleSingular', 'titlePlural', 'url', 'mediaExtractor'));
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

        $this->validate($request, $this->getValidationRules());

        $data = $request->all();

        $this->beforeUpdate();

        $entity->update($data);

        $relationsResolver = new RelationResolver($entity);
        $relationsResolver->saveAllRelations($request);

        $filesSaver = new FilesSaver($entity, $this->columnsExtractor, $request);
        $filesSaver->saveFiles();

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

        $filesSaver = new FilesSaver($entity, $this->columnsExtractor, request());
        $filesSaver->deleteAllModelFiles();

        $entity->delete();

        session()->flash('message', 'Entity deleted successfully');

        return redirect(UrlManager::listRoute($this->url));
    }


    public function downloadExcel()
    {
        $exporter = new DataExporter($this->model, new XlsCsvStrategy($this->model));

        return $exporter->export();
    }


    public function downloadCsv()
    {
        $exporter = new DataExporter($this->model, new XlsCsvStrategy($this->model, 'csv'));

        return $exporter->export();
    }

    public function deleteFile($id, $field)
    {
        $entity = $this->getEntity($id);

        $filesSaver = new FilesSaver($entity, $this->columnsExtractor, request());

        $filesSaver->deleteFile($field);
    }


    public function uploadMedialibraryFile($id, $collection = Media::TEMP_LOADED_FILES_COLLECTION_NAME, Request $request)
    {
        $this->validate($request, [
            'file' => 'file'
        ]);

        $model = $this->getEntity($id);

        $model->addMedia($request->file('file'))
            ->withCustomProperties(['load_confirmed' => false])
            ->toMediaCollection($collection);

        $media = $model->getMedia($collection)->last();

        return response()->json([
            'id' => $media->id,
            'url' => $media->getUrl(),
            'delete_url' => UrlManager::deleteMedialibraryFileRoute($this->getUrl(), $model->id, $media),
        ]);
    }

    public function deleteMedialibraryFile($id, Media $media)
    {
        MediaSaver::deleteMedia($media);

        return response()->json();
    }

    public function clearMedialibraryCollection($id, string $collection)
    {
        $mediaSaver = new MediaSaver($this->getEntity($id));
        $mediaSaver->clearMediaCollection($collection);

        return response()->json();
    }
}
