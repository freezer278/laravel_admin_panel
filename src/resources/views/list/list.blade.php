@extends(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::layouts.app')

@section('title', $titlePlural)

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{--Todo: add here search bar--}}
                <a href="{{ \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager::createRoute($url) }}" class="btn btn-success">{{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.create') }}</a>
                {{--{{ $titlePlural or '' }}--}}
            </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered">
                <tr>
                    @foreach($columns as $column)
                        <th>{{ $column['label'] }}</th>
                    @endforeach
                    <th>{{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.controls') }}</th>
                </tr>

                @foreach($entities as $entity)
                    <tr>
                        @foreach($columns as $key => $column)
                            <td>{{ $entity->$key }}</td>
                        @endforeach
                        <td>
                            <a href="{{ \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager::editRoute($url, $entity->id) }}" class="btn btn-warning">{{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.edit') }}</a>
                            <a href="{{ \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager::deleteRoute($url, $entity->id) }}" class="btn btn-danger">{{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.delete') }}</a>
                            {{--Todo: add displaying buttons and their links here--}}
                        </td>
                    </tr>
                @endforeach
            </table>

            {{ $entities->links() }}
        </div>
    </div>
@endsection