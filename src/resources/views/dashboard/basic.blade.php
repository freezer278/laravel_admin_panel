@extends(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::layouts.app')

@section('styles')

@endsection

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <div class="box-title">{{ __(\Vmorozov\LaravelAdminGenerator\AdminGeneratorServiceProvider::VIEWS_NAME.'::base.dashboard_title') }}</div>

        </div>
        <!-- /.box-header -->
        <div class="box-body">
            You are logged in!
        </div>
    </div>
@endsection

@section('scripts')

@endsection