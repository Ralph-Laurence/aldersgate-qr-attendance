@once
    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/flat-button.css') }}">
    @endpush
@endonce

<button {{ $attributes->merge([ 'class' => 'btn flat-controls flat-button shadow-0 ' . $alias ]) }} 
    id      ="{{ $alias }}" 
    name    ="{{ $alias }}"
    type    ="{{ $action }}"
    onclick ="{{ $click }}"
    theme   ="{{ $theme }}">

    @if ($icon)
        <i class="fas {{ $icon }} me-2"></i>        
    @endif

    {{ $text }}
</button>