<a href="{{ str_replace('{id}', $entity->getKey(), $button['url']) }}"
   class="{{ $button['classes'] }}"
@foreach($button['htmlAttributes'] as $attribute => $value)
    {{ $attribute.'='.$value }}
        @endforeach
>
    {!! $button['text'] !!}
</a>