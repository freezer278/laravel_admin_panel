@php
    /**
     * @var \Vmorozov\LaravelAdminGenerator\App\Utils\Columns\Column $field
     * @var string $fieldName
     * @var array $params
     * @var \Illuminate\Database\Eloquent\Model $entity
     * @var \Illuminate\Support\Collection $errors
     */
@endphp

<div class="form-group col-md-12 {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    <label for="{{ $fieldName }}">{{ $field->getLabel() }}</label>

    <textarea name="{{ $fieldName }}"
              id="{{ $fieldName }}"
              class="form-control"
              {{ $field->required() ? 'required' : '' }}
              minlength="{{ isset($params[\Vmorozov\LaravelAdminGenerator\App\Utils\Field::PARAM_KEY_MIN]) ? $params[\Vmorozov\LaravelAdminGenerator\App\Utils\Field::PARAM_KEY_MIN] : '' }}"
              maxlength="{{ isset($params[\Vmorozov\LaravelAdminGenerator\App\Utils\Field::PARAM_KEY_MAX]) ? $params[\Vmorozov\LaravelAdminGenerator\App\Utils\Field::PARAM_KEY_MAX] : '' }}"
    >{{ old($fieldName) ?? $entity->$fieldName }}</textarea>

    @if ($errors->has($fieldName))
        <span class="help-block">
            <strong>{{ $errors->first($fieldName) }}</strong>
        </span>
    @endif
</div>
