<div class="form-group col-md-12 {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    <label for="{{ $fieldName }}">{{ $field->getLabel() }}</label>

    {{--Todo: add here displaying previous file name--}}

    <input type="file"
           name="{{ $fieldName }}"
           id="{{ $fieldName }}"
           class="form-control"
           {{ $field->required() ? 'required' : '' }}
           max="{{ $params['max'] ?? '' }}"
           accept="{{ $params['accept_mime_type'] ?? '*/*' }}"
    >

    @if ($errors->has($fieldName))
        <span class="help-block">
            <strong>{{ $errors->first($fieldName) }}</strong>
        </span>
    @endif
</div>