@php
    $hasError   = $errors->has($as) ? ' has-error' : '';

    // default value
    $defaultValue = old($as);

    if ($attributes->has('initial'))
        $defaultValue = $attributes->get('initial');
@endphp

@once 
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/components/flat-input.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('js/components/flat-input.js') }}"></script>
        {{-- <script src="{{ asset('js/components/flat-input-suggest.js') }}"></script> --}}
    @endpush
@endonce

<div {{ $attributes->merge(['class' => 'flat-controls flat-input dropdown']) }} data-alias="text">

    {{-- OPTIONAL LABEL --}}
    @if ($attributes->has('with-caption'))
        <div class="my-1 px-1">
            <small class="fw-600">{{ $attributes->has('required') ? $fill . ' *' : $fill }}</small>
        </div>
    @endif

    {{-- WARNING LABEL IF DATA IS NOT FOUND --}}
    <div class="my-1 px-1 warning-label-wrapper display-none">
        <small class="fw-600 text-warning">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span class="ms-1 warning-label"></span>
        </small>
    </div>

    {{-- INPUT WRAPPER --}}
    <div class="input-text mb-1 {{ $hasError }} dropdown-toggle" data-mdb-toggle="dropdown" data-mdb-auto-close="inside">
        <input  type="text" 
                name="{{ $as }}" 
                id="{{ $as }}" 
                class="main-control {{ $gravity }} {{ $clamp }}"
                maxlength="{{ $limit }}"
                value="{{ $defaultValue }}" 
                aria-autocomplete="none" 
                placeholder="{{ $fill }}"
                {{ $attributes }} />

        <i class="fa-solid fa-circle-xmark ms-2 input-trailing-icon"></i>

    </div>

    {{-- ERROR LABEL --}}
    <h6 class="px-2 mb-0 text-danger text-xs error-label">{{ $errors->first($as) }}</h6>

    <ul class="dropdown-menu">
        <div class="w-100 menu-scrollview" style="max-height: 192px; overflow-y: auto;" data-simplebar>
                
        </div>
        {{-- <li><a class="dropdown-item" href="#">Action</a></li>
        <li><a class="dropdown-item" href="#">Another action</a></li>
        <li><a class="dropdown-item" href="#">Something else here</a></li> --}}
    </ul>
</div>