<div class="form-group col-md-12 {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    <label for="{{ $fieldName }}">{{ $params['label'] or title_case($fieldName) }}</label>

    <input type="text"
           name="{{ $fieldName }}"
           id="{{ $fieldName }}"
           value="{{ old($fieldName) }}"
           class="form-control"
           {{ $field->required() ? 'required' : '' }}
           {{ isset($params['min']) ? 'minlength="'.$params['min'].'"' : '' }}
           {{ isset($params['max']) ? 'maxlength="'.$params['max'].'"' : '' }}
    >

    @if ($errors->has($fieldName))
        <span class="help-block">
            <strong>{{ $errors->first($fieldName) }}</strong>
        </span>
    @endif
</div>