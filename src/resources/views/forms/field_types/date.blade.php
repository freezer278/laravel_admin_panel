<div class="form-group col-md-12 {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    <label for="{{ $fieldName }}">{{ $field->getLabel() }}</label>

    <input type="date"
           name="{{ $fieldName }}"
           id="{{ $fieldName }}"
           value="{{ $entity->$fieldName->format('Y-m-d') ?? old($fieldName) }}"
           class="form-control"
           {{ $field->required() ? 'required' : '' }}
           min="{{ isset($params['min']) ? \Carbon\Carbon::parse($params['min'])->format('Y-m-d') : '' }}"
           max="{{ isset($params['max']) ? \Carbon\Carbon::parse($params['max'])->format('Y-m-d') : '' }}"
    >

    @if ($errors->has($fieldName))
        <span class="help-block">
            <strong>{{ $errors->first($fieldName) }}</strong>
        </span>
    @endif
</div>