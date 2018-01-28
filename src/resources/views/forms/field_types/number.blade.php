<div class="form-group col-md-12 {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    <label for="{{ $fieldName }}">{{ $field->getLabel() }}</label>

    <input type="number"
           name="{{ $fieldName }}"
           id="{{ $fieldName }}"
           value="{{ $entity->$fieldName or old($fieldName) }}"
           class="form-control"
           {{ $field->required() ? 'required' : '' }}
           min="{{ isset($params['min']) ? $params['min'] : '' }}"
           max="{{ isset($params['max']) ? $params['max'] : '' }}"
    >

    @if ($errors->has($fieldName))
        <span class="help-block">
            <strong>{{ $errors->first($fieldName) }}</strong>
        </span>
    @endif
</div>