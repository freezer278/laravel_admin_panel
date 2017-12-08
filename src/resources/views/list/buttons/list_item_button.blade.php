<a href="{{ $button['url'] }}"
   class="{{ $button['classes'] }}"
   @foreach($button['htmlAttributes'] as $attribute => $value)
       {{ $attribute.'='.$value }}
   @endforeach
>
    {!! $button['text'] !!}
</a>