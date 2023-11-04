{{-- @php
    $active = $current ? 'active' : '';
@endphp --}}
<a  {{ $attributes->merge(['class' => 'flat-records-nav-item btn flat-records-nav-item text-capitalize ' . $current]) }} 
    href="{{ $to }}"
    role="button">
    {{ $text }}
</a>