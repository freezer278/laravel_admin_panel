<div class="col-md-12">
    <h3>{{ $collection }}</h3>
    {{--Todo: add here link--}}
    <a href="#">{{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.medialibrary.clear_all') }}</a>

    @foreach($files as $file)
        <div>
            {{ $file->file_name }}
            {{--Todo: add here link to delete file--}}
            <button data-url="">{{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.delete') }}</button>
        </div>
    @endforeach

    {{--Todo: add here correct uploading url--}}
    <button class="btn btn-success" data-url="">{{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.upload') }}</button>

    <input type="hidden" name="media_collections[{{ $collection }}]" value="{{ $files->pluck('id') }}">
</div>