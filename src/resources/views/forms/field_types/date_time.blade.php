<div class="form-group col-md-12 {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    <label for="{{ $fieldName }}">{{ $field->getLabel() }}</label>

    <input type="datetime-local"
           name="{{ $fieldName }}"
           id="{{ $fieldName }}"
           value="{{ $entity->$fieldName->format('Y-m-d\TH:i') ?? old($fieldName) }}"
           class="form-control"
           {{ $field->required() ? 'required' : '' }}
           min="{{ isset($params[\Vmorozov\LaravelAdminGenerator\App\Utils\Field::PARAM_KEY_MIN]) ? \Carbon\Carbon::parse($params[\Vmorozov\LaravelAdminGenerator\App\Utils\Field::PARAM_KEY_MIN])->format('Y-m-d\TH:i') : '' }}"
           max="{{ isset($params[\Vmorozov\LaravelAdminGenerator\App\Utils\Field::PARAM_KEY_MAX]) ? \Carbon\Carbon::parse($params[\Vmorozov\LaravelAdminGenerator\App\Utils\Field::PARAM_KEY_MAX])->format('Y-m-d\TH:i') : '' }}"
    >

    @if ($errors->has($fieldName))
        <span class="help-block">
            <strong>{{ $errors->first($fieldName) }}</strong>
        </span>
    @endif
</div>