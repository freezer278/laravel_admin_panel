<div class="col-md-12">
    <h3>{{ $collection }}</h3>
    <a href="{{ \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager::clearMedialibraryCollectionRoute($url, $collection) }}">
        {{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.medialibrary.clear_all') }}
    </a>

    @foreach($files as $file)
        <div>
            {{ $file->file_name }}
            <button data-url="{{ \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager::deleteMedialibraryFileRoute($url, $file) }}">
                {{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.delete') }}
            </button>
        </div>
    @endforeach

    <button class="btn btn-success" data-url="{{ \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager::uploadMedialibraryFileRoute($url) }}">
        {{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.upload') }}
    </button>

    <input type="hidden" name="media_collections[{{ $collection }}]" value="{{ $files->pluck('id') }}">
</div>