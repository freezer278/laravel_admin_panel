@extends(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::layouts.app')

@section('styles')

@endsection

@section('title', 'Edit ' . $titleSingular)

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <!-- Default box -->
            <a href="{{ \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager::listRoute($url) }}">
                <i class="fa fa-angle-double-left"></i> {{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.back_to_list') }} <span>{{ $titlePlural }}</span>
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
                        <h3 class="box-title">{{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.edit_title') }} {{ $titleSingular }}</h3>
                    </div>
                    <div class="box-body row">
                        @foreach($columns as $key => $column)
                            <div class="form-group col-md-12 {{ $errors->has($key) ? 'has-error' : '' }}">
                                <label for="{{ $key }}">{{ $column['label'] }}</label>

                                <input type="text" name="{{ $key }}" id="{{ $key }}" value="{{ $entity->$key or old($key) }}" class="form-control">
                                @if ($errors->has($key))
                                    <span class="help-block">
                                        <strong>{{ $errors->first($key) }}</strong>
                                    </span>
                                @endif
                            </div>
                        @endforeach
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

                            <button class="btn btn-success" type="submit">{{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.save') }}</button>

                            <a href="{{ \Vmorozov\LaravelAdminGenerator\App\Utils\UrlManager::listRoute($url) }}" class="btn btn-default"><span class="fa fa-ban"></span> {{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.cancel') }}</a>
                        </div>
                    </div><!-- /.box-footer-->

                </div><!-- /.box -->
            </form>
        </div>
    </div>
@endsection

@section('scripts')

@endsection