@php
    $inputName  = !empty($as)     ? $as     : '';
    $inputHint  = !empty($fill)   ? $fill   : '';
    $maxLength  = !empty($limit)  ? $limit  : 32;

    // Limit input data to specific type
    $acceptData = $attributes->has('clamp')   ? $attributes->get('clamp') : '';

    $isReadOnly = $attributes->has('locked')   ? 'readonly' : '';
    $isRequired = $attributes->has('required') ? 'required' : '';
    $gravity    = '';
    
    if ($attributes->has('gravity'))
    {
        switch ($attributes->get('gravity')) 
        {
            case 'start':
                $gravity = 'text-start';
                break;
            
            case 'center':
                $gravity = 'text-center';
                break;

            case 'end':
                $gravity = 'text-end';
                break;
        }
    }

    // default value
    $defaultValue = old($inputName);

    if ($attributes->has('initial'))
        $defaultValue = $attributes->get('initial');
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
                class="main-control {{ $gravity }} {{ $acceptData }}"
                maxlength="{{ $maxLength }}"
                value="{{ $defaultValue }}" 
                aria-autocomplete="none" 
                {{ $isReadOnly }}
                placeholder="{{ $inputHint }}"
                {{ $attributes }} />

        <i class="fa-solid fa-circle-xmark ms-2 input-trailing-icon"></i>

    </div>

    {{-- ERROR LABEL --}}
    <h6 class="px-2 mb-0 text-danger text-xs error-label">{{ $errors->first($inputName) }}</h6>

</div>