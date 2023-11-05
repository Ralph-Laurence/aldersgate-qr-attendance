<a  {{ $attributes->merge(['class' => 'flat-worksheet-tabs-item btn text-capitalize ' . $current]) }} 
    href="{{ $to }}"
    role="button">
    {{ $text }}
</a>