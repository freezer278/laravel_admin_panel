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

    <select name="{{ str_finish($fieldName, '[]') }}"
            id="{{ $fieldName }}"
            class="form-control"
            {{ $field->required() ? 'required' : '' }}
            multiple
    >
        @foreach($relationModels as $relationModel)
            <option value="{{ $relationModel->getKey() }}"
                    {{ $relatedIds->has($relationModel->getKey()) !== false ? 'selected' : '' }}
            >
                {{ $relationModel->$relationModelFieldName }}
            </option>
        @endforeach
    </select>

    @if ($errors->has($fieldName))
        <span class="help-block">
            <strong>{{ $errors->first($fieldName) }}</strong>
        </span>
    @endif
</div>
