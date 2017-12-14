<div class="form-group col-md-12 {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    <label for="{{ $fieldName }}">{{ $field->getLabel() }}</label>

    {{--<input type="text"--}}
           {{--name="{{ $fieldName }}"--}}
           {{--id="{{ $fieldName }}"--}}
           {{--value="{{ $entity->$fieldName or old($fieldName) }}"--}}
           {{--class="form-control"--}}
           {{--{{ $field->required() ? 'required' : '' }}--}}
           {{--minlength="{{ isset($params['min']) ? $params['min'] : '' }}"--}}
           {{--maxlength="{{ isset($params['max']) ? $params['max'] : '' }}"--}}
    {{-->--}}

    <textarea name="{{ $fieldName }}"
              id="{{ $fieldName }}"
              class="form-control"
              {{ $field->required() ? 'required' : '' }}
              minlength="{{ isset($params['min']) ? $params['min'] : '' }}"
              maxlength="{{ isset($params['max']) ? $params['max'] : '' }}"
    >{{ $entity->$fieldName or old($fieldName) }}</textarea>

    @if ($errors->has($fieldName))
        <span class="help-block">
            <strong>{{ $errors->first($fieldName) }}</strong>
        </span>
    @endif
</div>