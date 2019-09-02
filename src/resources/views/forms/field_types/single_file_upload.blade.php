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

    {{--Todo: add here displaying previous file name with delete by ajax link--}}

    <input type="file"
           name="{{ $fieldName }}"
           id="{{ $fieldName }}"
           class="form-control"
           {{ $field->required() ? 'required' : '' }}
           max="{{ $params[\Vmorozov\LaravelAdminGenerator\App\Utils\Field::PARAM_KEY_MAX] ?? '' }}"
           accept="{{ $params[\Vmorozov\LaravelAdminGenerator\App\Utils\Field::PARAM_ACCEPT_MIME_TYPE] ?? '*/*' }}"
    >

    @if ($errors->has($fieldName))
        <span class="help-block">
            <strong>{{ $errors->first($fieldName) }}</strong>
        </span>
    @endif
</div>
