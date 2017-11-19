@extends(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::layouts.app')

@section('styles')

@endsection

@section('title', 'Edit ' . $title)

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <!-- Default box -->
            <a href="{{ \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager::listRoute($url) }}">
                <i class="fa fa-angle-double-left"></i> Back to list <span>{{ $title or '' }}</span>
            </a>

            <br>
            <br>


            <form id="entity_form"
                  method="POST"
                  action="{{ \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager::editRoute($url, $entity->id) }}"
                  accept-charset="UTF-8"
                  enctype="multipart/form-data">

                {{ csrf_field() }}
                <div class="box">

                    <div class="box-header with-border">
                        <h3 class="box-title">Edit {{ $title or '' }}</h3>
                    </div>
                    <div class="box-body row">

                    </div><!-- /.box-body -->
                    <div class="box-footer">

                        <div id="saveActions" class="form-group">

                            {{--<input type="hidden" name="save_action" value="save_and_back">--}}

                            {{--<div class="btn-group">--}}

                            {{--<button type="submit" class="btn btn-success">--}}
                            {{--<span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;--}}
                            {{--<span data-value="save_and_back">Сохранить и выйти</span>--}}
                            {{--</button>--}}

                            {{--<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aira-expanded="false">--}}
                            {{--<span class="caret"></span>--}}
                            {{--<span class="sr-only">Toggle Save Dropdown</span>--}}
                            {{--</button>--}}

                            {{--<ul class="dropdown-menu">--}}
                            {{--<li><a href="javascript:void(0);" data-value="save_and_edit">Сохранить и продолжить редактирование</a></li>--}}
                            {{--<li><a href="javascript:void(0);" data-value="save_and_new">Сохранить и создать</a></li>--}}
                            {{--</ul>--}}

                            {{--</div>--}}

                            <button type="submit" form="#entity_form">Submit</button>

                            <a href="{{ \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager::listRoute($url) }}" class="btn btn-default"><span class="fa fa-ban"></span> Cancel</a>
                        </div>
                    </div><!-- /.box-footer-->

                </div><!-- /.box -->
            </form>
        </div>
    </div>
@endsection

@section('scripts')

@endsection