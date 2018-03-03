<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads\Medialibrary;

use Illuminate\Support\Collection;

class MediaExtractor extends MediaHandler
{
    public function getAllMediaCollections(): Collection
    {
        $mediaCollections = collect([]);

        if ($this->hasMediaCollections()) {
            $collections = $this->model->mediaCollections;

            foreach ($collections as $collection => $params) {
                $mediaCollections->put($collection, $this->model->getMedia($collection));
            }
        }

        return $mediaCollections;
    }
}