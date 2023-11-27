@once
    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/flat-button.css') }}">
    @endpush
@endonce

@php
    $buttonAttributes = $attributes->merge([ 'class' => 'btn flat-controls flat-button shadow-0 ' . $alias, 'id' => $alias, 'name' => $alias, 'type' => $action, 'onclick' => $click, 'theme' => $theme ]);
@endphp

@if ($attributes->has('url'))
    <a role="button" {{ $buttonAttributes }} href="{{ $attributes->get('url') }}">
@else
    <button {{ $buttonAttributes }}>
@endif

    @if ($icon)
        <i class="fas {{ $icon }} me-2"></i>        
    @endif

    {{ $text }}

@if ($attributes->has('url'))
    </a>
@else
    </button>
@endif