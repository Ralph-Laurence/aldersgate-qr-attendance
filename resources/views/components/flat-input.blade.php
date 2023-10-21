@php
    $inputName  = !empty($as)     ? $as     : '';
    $inputHint  = !empty($fill)   ? $fill   : '';
    $maxLength  = !empty($limit)  ? $limit  : 32;

    $isRequired = $attributes->has('required') ? 'required' : '';
@endphp

@once 
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/components/flat-input.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('js/components/flat-input.js') }}"></script>
    @endpush
@endonce

<div {{ $attributes->merge(['class' => 'flat-controls flat-input']) }} data-alias="text">

    {{-- OPTIONAL LABEL --}}
    @if ($attributes->has('with-caption'))
        <div class="my-1 px-1">
            <small class="fw-600">{{ $attributes->has('required') ? $inputHint . ' *' : $inputHint }}</small>
        </div>
    @endif

    {{-- INPUT WRAPPER --}}
    <div class="input-text mb-1 {{ $errors->has($inputName) ? ' has-error' : '' }}">

        <input  type="text" 
                name="{{ $inputName }}" 
                id="{{ $inputName }}" 
                class="main-control"
                maxlength="{{ $maxLength }}"
                value="{{ old($inputName) }}" 
                aria-autocomplete="none" 
                placeholder="{{ $inputHint }}"
                {{ $attributes }} />

        <i class="fa-solid fa-circle-xmark ms-2 input-trailing-icon"></i>

    </div>

    {{-- ERROR LABEL --}}
    <h6 class="px-2 mb-0 text-danger text-xs error-label">{{ $errors->first($inputName) }}</h6>

</div>