<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads\Medialibrary;

use Illuminate\Http\Request;

class MediaSaver extends MediaHandler
{
    const DEFAULT_COLLECTION_NAME = 'default';


    public function saveMediaCollection(Request $request)
    {
        // Todo: implement this method
    }

    public static function deleteMedia(Media $media)
    {
        $media->delete();
    }

    public function clearMediaCollection(string $collection = self::DEFAULT_COLLECTION_NAME)
    {
        if ($this->hasMediaCollections())
            $this->model->clearMediaCollection($collection);
    }
}