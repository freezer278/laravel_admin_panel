<div class="form-group col-md-12 {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    <label for="{{ $fieldName }}">{{ $field->getLabel() }}</label>

    <input type="datetime-local"
           name="{{ $fieldName }}"
           id="{{ $fieldName }}"
           value="{{ $entity->$fieldName->format('Y-m-d\TH:i') ?? old($fieldName) }}"
           class="form-control"
           {{ $field->required() ? 'required' : '' }}
           min="{{ isset($params['min']) ? \Carbon\Carbon::parse($params['min'])->format('Y-m-d\TH:i') : '' }}"
           max="{{ isset($params['max']) ? \Carbon\Carbon::parse($params['max'])->format('Y-m-d\TH:i') : '' }}"
    >

    @if ($errors->has($fieldName))
        <span class="help-block">
            <strong>{{ $errors->first($fieldName) }}</strong>
        </span>
    @endif
</div>