<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads\Medialibrary;

use Exception;
use Illuminate\Http\Request;

class MediaSaver extends MediaHandler
{
    /**
     * @var string
     */
    const DEFAULT_COLLECTION_NAME = 'default';


    /**
     * @param Request $request
     */
    public function saveMediaCollection(Request $request)
    {
        // Todo: implement this method
    }

    /**
     * @param Media $media
     * @throws Exception
     */
    public static function deleteMedia(Media $media)
    {
        $media->delete();
    }

    /**
     * @param string $collection
     */
    public function clearMediaCollection(string $collection = self::DEFAULT_COLLECTION_NAME)
    {
        if ($this->hasMediaCollections())
            $this->model->clearMediaCollection($collection);
    }
}
