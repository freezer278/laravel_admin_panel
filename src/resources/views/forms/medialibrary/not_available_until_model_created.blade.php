@php
    /**
     * @var string $collection
     * @var \Vmorozov\LaravelAdminGenerator\App\Utils\FileUploads\Medialibrary\MediaExtractor $mediaExtractor
     */
@endphp

<div class="col-md-12">
    <h3>{{ $mediaExtractor->getMediaCollectionParam($collection, 'name', \Illuminate\Support\Str::title($collection)) }}</h3>

    <h5>{{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.medialibrary.not_available_until_model_created')  }}</h5>
</div>
