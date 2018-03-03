<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads\Medialibrary;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Media as BaseMedia;
use Vmorozov\UrlHelpers\SubdomainUrlHelper;

class Media extends BaseMedia
{
    const HOURS_TEMP_FILES_ARE_VALID = 1;
    const TEMP_LOADED_FILES_COLLECTION_NAME = 'temp_loaded_files';


    public function getIsTempAttribute(): bool
    {
        return isset($this->custom_properties['load_confirmed']) && $this->custom_properties['load_confirmed'] || !isset($this->custom_properties['load_confirmed']);
    }

    public function getDeleteUrlAttribute(): string
    {
//        Todo: change this url
        return SubdomainUrlHelper::generateUrl('media_file_delete', ['media' => $this->id]);
    }



    public function scopeTemp($query)
    {
        return $query->where('custom_properties->load_confirmed', false);
    }



    public function attachToModel(Model $model)
    {
        $this->model_type = get_class($model);
        $this->model_id = $model->id;

        if ($this->is_temp && isset($this->custom_properties['load_confirmed']))
            $this->custom_properties['load_confirmed'] = true;

        $this->save();
    }

    public static function attachMultipleToModelByIds(array $ids, Model $model, string $collection = 'default')
    {
        self::whereIn('id', $ids)->update([
            'model_id' => $model->id,
            'model_type' => get_class($model),
            'collection_name' => $collection
        ]);
    }

    public static function syncMultipleWithModelByIds(array $ids, Model $model, array $customProperties = [], string $collection = 'default')
    {
        $toUpdate = [
            'model_id' => $model->id,
            'model_type' => get_class($model),
            'collection_name' => $collection,
        ];

        foreach ($customProperties as $property => $value) {
            $toUpdate['custom_properties->'.$property] = $value;
        }

        $toUpdate['custom_properties->load_confirmed'] = true;

        self::whereIn('id', $ids)->update($toUpdate);

        $toDelete = self::whereNotIn('id', $ids)->where('model_id', $model->id)->get();

        foreach ($toDelete as $item) {
            $item->delete();
        }
    }
}