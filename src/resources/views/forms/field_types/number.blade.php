<div class="form-group col-md-12 {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    <label for="{{ $fieldName }}">{{ $field->getLabel() }}</label>

    <input type="number"
           name="{{ $fieldName }}"
           id="{{ $fieldName }}"
           value="{{ $entity->$fieldName or old($fieldName) }}"
           class="form-control"
           {{ $field->required() ? 'required' : '' }}
           min="{{ isset($params[\Vmorozov\LaravelAdminGenerator\App\Utils\Field::PARAM_KEY_MIN]) ? $params[\Vmorozov\LaravelAdminGenerator\App\Utils\Field::PARAM_KEY_MIN] : '' }}"
           max="{{ isset($params[\Vmorozov\LaravelAdminGenerator\App\Utils\Field::PARAM_KEY_MAX]) ? $params[\Vmorozov\LaravelAdminGenerator\App\Utils\Field::PARAM_KEY_MAX] : '' }}"
    >

    @if ($errors->has($fieldName))
        <span class="help-block">
            <strong>{{ $errors->first($fieldName) }}</strong>
        </span>
    @endif
</div>