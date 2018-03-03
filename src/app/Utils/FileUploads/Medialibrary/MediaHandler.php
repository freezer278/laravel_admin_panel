<?php

namespace Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads\Medialibrary;


use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class MediaHandler
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function hasMediaCollections(): bool
    {
        return $this->model instanceof HasMedia;
    }
}