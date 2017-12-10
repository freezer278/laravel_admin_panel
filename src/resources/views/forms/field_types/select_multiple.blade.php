<div class="form-group col-md-12 {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    <label for="{{ $fieldName }}">{{ $field->getLabel() }}</label>

    <select name="{{ str_finish($fieldName, '[]') }}"
            id="{{ $fieldName }}"
            class="form-control"
            {{ $field->required() ? 'required' : '' }}
            multiple
    >
        @foreach($relationModels as $relationModel)
            {{--Todo: add displaying selected entities--}}
            <option value="{{ $relationModel->getKey() }}">
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