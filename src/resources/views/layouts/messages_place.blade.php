<div class="col-md-12 messages">
    @if($errors->any() || Session::has('error'))
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li> {{ $error }}</li>
            @endforeach
            <p>{{ Session::get('error') }}</p>
        </ul>
    @endif
    @if (Session::has('message'))
        <div class="alert alert-info">
            <p>{{ Session::get('message') }}</p>
        </div>
    @endif
</div>