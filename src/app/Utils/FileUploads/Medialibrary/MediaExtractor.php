<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads\Medialibrary;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class MediaExtractor extends MediaHandler
{
    private $params = [];

    public function __construct(Model $model)
    {
        parent::__construct($model);

        $this->params = $this->model->mediaCollections;
    }

    public function getAllMediaCollections(): Collection
    {
        $mediaCollections = collect([]);

        if ($this->hasMediaCollections()) {
            $collections = $this->params;

            foreach ($collections as $collection => $params) {
                $mediaCollections->put($collection, $this->model->getMedia($collection));
            }
        }

        return $mediaCollections;
    }

    public function getMediaCollectionParam(string $collection, string $param, string $defaultValue = ''): string
    {
        return $this->params[$collection][$param] ?? $defaultValue;
    }
}