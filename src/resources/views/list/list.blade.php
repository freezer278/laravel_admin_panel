@extends(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::layouts.app')

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $controller->titlePlural or '' }}</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered">
                <tr>
                    @foreach($columns as $column)
                        <th>{{ $column['label'] }}</th>
                    @endforeach
                    <th>Controls</th>
                </tr>

                @foreach($entities as $entity)
                    <tr>
                        @foreach($columns as $key => $column)
                            <td>{{ $entity->$key }}</td>
                        @endforeach
                        <td>
                            <a href="" class="btn btn-warning">edit</a>
                            <a href="" class="btn btn-danger">delete</a>
                            {{--Todo: add displaying buttons and their links here--}}
                        </td>
                    </tr>
                @endforeach
            </table>

            {{ $entities->links() }}
        </div>
    </div>
@endsection