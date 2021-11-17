<?php

namespace Vmorozov\LaravelAdminGenerator\App\Controllers;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider;
use Vmorozov\LaravelAdminGenerator\App\Utils\ColumnsExtractor;
use Vmorozov\LaravelAdminGenerator\App\Utils\EntitiesExtractor;
use Vmorozov\LaravelAdminGenerator\App\Utils\Export\ModelExportFactory;
use Vmorozov\LaravelAdminGenerator\App\Utils\Export\Strategies\CsvExportStrategy;
use Vmorozov\LaravelAdminGenerator\App\Utils\Export\Strategies\ExcelExportStrategy;
use Vmorozov\LaravelAdminGenerator\App\Utils\Export\Strategies\ExportStrategy;
use Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads\FilesSaver;
use Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads\Medialibrary\Media;
use Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads\Medialibrary\MediaExtractor;
use Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads\Medialibrary\MediaSaver;
use Vmorozov\LaravelAdminGenerator\App\Utils\RelationResolver;
use Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager;

abstract class CrudController extends Controller
{
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var ColumnsExtractor
     */
    protected $columnsExtractor;
    /**
     * @var EntitiesExtractor
     */
    protected $entitiesExtractor;
    /**
     * @var string
     */
    protected $model;
    /**
     * @var Model
     */
    protected $modelInstance;
    /**
     * @var string
     */
    protected $url = '';
    /**
     * @var string
     */
    protected $titleSingular = '';
    /**
     * @var string
     */
    protected $titlePlural = '';
    /**
     * @var array
     */
    protected $columnParams = [];
    /**
     * @var array
     */
    protected $listItemButtons = [];
    /**
     * @var bool
     */
    protected $enableCreate = true;
    /**
     * @var bool
     */
    protected $enableEdit = true;
    /**
     * @var bool
     */
    protected $enableDelete = true;
    /**
     * @var bool
     */
    protected $enableSearch = true;
    /**
     * @var bool
     */
    protected $enableExport = true;
    /**
     * @var ModelExportFactory
     */
    private $modelExportFactory;
    /**
     * @var ExportStrategy
     */
    private $excelExportStrategy;
    /**
     * @var ExportStrategy
     */
    private $csvExportStrategy;
    /**
     * @var FilesSaver
     */
    private $fileSaver;

    /**
     * CrudController constructor.
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->request = app()->make(Request::class);
        $this->modelInstance = app()->make($this->model);

        $this->columnsExtractor = app()->make(ColumnsExtractor::class);
        $this->entitiesExtractor = app()->make(EntitiesExtractor::class, ['model' => $this->modelInstance, 'columnParams' => $this->columnParams]);

        $this->modelExportFactory = app()->make(ModelExportFactory::class);
        $this->excelExportStrategy = app()->make(ExcelExportStrategy::class);
        $this->csvExportStrategy = app()->make(CsvExportStrategy::class);

        $this->fileSaver = app()->make(FilesSaver::class);

        $this->setup();
    }

    /**
     *
     */
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

    /**
     * @param string $column
     * @param string $operator
     * @param $value
     */
    protected function addDefaultWhereClause(string $column, string $operator, $value)
    {
        $this->entitiesExtractor->addWhereClause($column, $operator, $value);
    }

    /**
     * @param string $column
     * @param string $direction
     */
    protected function addDefaultOrderByClause(string $column, string $direction)
    {
        $this->entitiesExtractor->addOrderByClause($column, $direction);
    }

    /**
     * Get validation rules for create and edit actions.
     *
     * @return array
     */
    protected function getCreateValidationRules(): array
    {
        return $this->columnsExtractor->getCreateFormValidationRules($this->columnParams);
    }

    /**
     * Get validation rules for create and edit actions.
     *
     * @return array
     */
    protected function getUpdateValidationRules(): array
    {
        return $this->columnsExtractor->getUpdateFormValidationRules($this->columnParams);
    }


    /**
     * @param int $id
     * @return Model
     */
    protected function getEntity(int $id): Model
    {
        return $this->entitiesExtractor->getSingleEntity($id);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $columns = $this->columnsExtractor->getActiveListColumns($this->columnParams);
        $entities = $this->entitiesExtractor->getPaginated($this->request->all());

        return view(AdminGeneratorServiceProvider::VIEWS_NAME . '::list.list')
            ->with([
                'columns' => $columns,
                'entities' => $entities,
                'titleSingular' => $this->titleSingular,
                'titlePlural' => $this->titlePlural,
                'url' => $this->getUrl(),
                'search' => $request->input('search', ''),
                'listItemButtons' => $this->listItemButtons,
                'enableCreate' => $this->enableCreate,
                'enableEdit' => $this->enableEdit,
                'enableDelete' => $this->enableDelete,
                'enableSearch' => $this->enableSearch,
                'enableExport' => $this->enableExport,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $columns = $this->columnsExtractor->getCreateFormFields($this->columnParams);
        $mediaExtractor = new MediaExtractor($this->modelInstance);

        return view(AdminGeneratorServiceProvider::VIEWS_NAME . '::forms.create')
            ->with([
                'entity' => $this->modelInstance,
                'columns' => $columns,
                'titleSingular' => $this->titleSingular,
                'titlePlural' => $this->titlePlural,
                'url' => $this->getUrl(),
                'mediaExtractor' => $mediaExtractor,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     * @throws ValidationException
     */
    public function store()
    {
        $this->validate($this->request, $this->getCreateValidationRules());

        $this->beforeCreate();

        $data = $this->request->all();

        $entity = $this->modelInstance->create($data);

        $relationsResolver = new RelationResolver($entity);
        $relationsResolver->saveAllRelations($this->request);

        $this->fileSaver->saveFiles($entity, $this->columnParams);

        $this->afterCreate($entity);

        session()->flash('message', 'Entity created successfully');

        return redirect(UrlManager::listRoute($this->url));
    }

    /**
     *
     */
    protected function beforeCreate()
    {

    }

    /**
     * @param Model $entity
     */
    protected function afterCreate(Model $entity)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $columns = $this->columnsExtractor->getUpdateFormFields($this->columnParams);
        $entity = $this->getEntity($id);
        $mediaExtractor = new MediaExtractor($entity);

        return view(AdminGeneratorServiceProvider::VIEWS_NAME . '::forms.edit')
            ->with([
                'columns' => $columns,
                'entity' => $entity,
                'titleSingular' => $this->titleSingular,
                'titlePlural' => $this->titlePlural,
                'url' => $this->getUrl(),
                'mediaExtractor' => $mediaExtractor,
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ValidationException
     */
    public function update($id)
    {
        $entity = $this->getEntity($id);
        $this->validate($this->request, $this->getUpdateValidationRules());

        $this->beforeUpdate($entity);

        $data = $this->request->all();
        $entity->update($data);

        $relationsResolver = new RelationResolver($entity);
        $relationsResolver->saveAllRelations($this->request);
        $this->fileSaver->saveFiles($entity, $this->columnParams);

        $this->afterUpdate($entity);

        session()->flash('message', 'Entity changed successfully');

        return redirect(UrlManager::listRoute($this->url));
    }

    /**
     * @param Model $entity
     */
    protected function beforeUpdate(Model $entity)
    {

    }

    /**
     * @param Model $entity
     */
    protected function afterUpdate(Model $entity)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function destroy($id)
    {
        $entity = $this->getEntity($id);

        $this->fileSaver->deleteAllModelFiles($entity, $this->columnParams);
        $entity->delete();

        session()->flash('message', 'Entity deleted successfully');

        return redirect(UrlManager::listRoute($this->url));
    }


    /**
     * @return BinaryFileResponse
     * @throws Exception
     */
    public function downloadExcel()
    {
        return $this->excelExportStrategy->export($this->modelExportFactory->createForModel($this->modelInstance, $this->columnParams));
    }


    /**
     * @return BinaryFileResponse
     * @throws Exception
     */
    public function downloadCsv()
    {
        return $this->csvExportStrategy->export($this->modelExportFactory->createForModel($this->modelInstance, $this->columnParams));
    }

    /**
     * @param $id
     * @param $field
     * @return RedirectResponse|Redirector
     */
    public function deleteFile($id, $field)
    {
        $entity = $this->getEntity($id);
        $this->fileSaver->deleteFile($entity, $this->columnParams, $field);

        return redirect(UrlManager::listRoute($this->url));
    }


    /**
     * @param $id
     * @param string $collection
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function uploadMedialibraryFile($id, $collection = Media::TEMP_LOADED_FILES_COLLECTION_NAME)
    {
        $this->validate($this->request, [
            'file' => 'file'
        ]);

        $model = $this->getEntity($id);

        $model->addMedia($this->request->file('file'))
            ->withCustomProperties(['load_confirmed' => false])
            ->toMediaCollection($collection);

        $media = $model->getMedia($collection)->last();

        return response()->json([
            'id' => $media->getKey(),
            'url' => $media->getUrl(),
            'delete_url' => UrlManager::deleteMedialibraryFileRoute($this->getUrl(), $model->getKey(), $media),
        ]);
    }

    /**
     * @param $id
     * @param Media $media
     * @return JsonResponse
     * @throws Exception
     */
    public function deleteMedialibraryFile($id, Media $media)
    {
        MediaSaver::deleteMedia($media);

        return response()->json();
    }

    /**
     * @param $id
     * @param string $collection
     * @return JsonResponse
     */
    public function clearMedialibraryCollection($id, string $collection)
    {
        $mediaSaver = new MediaSaver($this->getEntity($id));
        $mediaSaver->clearMediaCollection($collection);

        return response()->json();
    }
}
