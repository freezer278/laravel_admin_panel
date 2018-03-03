<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads\Medialibrary;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Media;

class MediaSaver extends MediaHandler
{
    const DEFAULT_COLLECTION_NAME = 'default';


    public function saveMediaCollection(Request $request)
    {
        // Todo: implement this method
    }

    public function deleteMedia(Media $media)
    {
        $media->delete();
    }

    public function clearMediaCollection(Model $model, string $collection = self::DEFAULT_COLLECTION_NAME)
    {
        $model->clearMediaCollection($collection);
    }
}