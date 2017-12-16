@extends(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::layouts.app')

@section('title', $titlePlural)

@section('content')
    <div class="box">
        <div class="box-header with-border">
            {{--<h3 class="box-title">--}}

            <a href="{{ \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager::createRoute($url) }}" class="btn btn-success col-md-3">{{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.create') }}</a>
            <form action="{{ \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager::listRoute($url) }}" method="GET" class="col-md-6 col-md-offset-3">
                <div class="col-md-10">
                    <input type="search" name="search" placeholder="{{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.search') }}" class="form-control" value="{{ $search }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">{{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.search') }}</button>
                </div>
            </form>

            <div class="pull-left text-right" style="margin-top:1%;">
                <span>{{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.export') }}: </span>
                <div class="dt-buttons btn-group">
                    <a class="btn btn-default buttons-copy buttons-html5 btn-sm"
                       href="{{ \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager::exportXlsRoute($url) }}">
                        <span>Excel</span>
                    </a>
                    <a class="btn btn-default buttons-copy buttons-html5 btn-sm"
                       href="{{ \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager::exportCsvRoute($url) }}">
                        <span>CSV</span>
                    </a>
                </div>
            </div>

        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered">
                <tr>
                    @foreach($columns as $column)
                        <th>{{ $column->getLabel() }}</th>
                    @endforeach
                    <th>{{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.controls') }}</th>
                </tr>

                @foreach($entities as $entity)
                    <tr>
                        @foreach($columns as $column)
                            {!! $column->renderColumn($entity) !!}
                        @endforeach
                        <td>
                            <a href="{{ \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager::editRoute($url, $entity->id) }}" class="btn btn-warning">{{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.edit') }}</a>
                            <a href="{{ \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager::deleteRoute($url, $entity->id) }}" class="btn btn-danger">{{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.delete') }}</a>
                            @foreach($listItemButtons as $button)
                                @include(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::list.buttons.list_item_button')
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </table>

            {{ $entities->links() }}
        </div>
    </div>
@endsection