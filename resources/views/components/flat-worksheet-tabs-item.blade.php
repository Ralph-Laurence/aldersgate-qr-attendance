<a  {{ $attributes->merge(['class' => 'flat-worksheet-tabs-item btn text-capitalize ' . $current]) }} 
    
    @if (!empty($to))
        href="{{ $to }}"
    @endif
    
    role="button">
    {{ $text }}
</a>