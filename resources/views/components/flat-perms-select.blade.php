@once
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/flat-perms-select.css') }}">
@endpush
@endonce
<div {{ $attributes->merge(['class' => "d-flex flex-column " . $stretchWidth ]) }}>
    
    @if (!empty($caption))
    <div class="my-1 px-1">
        <small class="fw-600">{{ $caption }}</small>
    </div>
    @endif
    
    <div class="btn-group mb-1 permission-select flat-controls {{ $stretchWidth }}">
    
        @foreach ($controls as $k => $obj)
            @php
                $isChecked = (old($as) == $k || $level == -1) ? 'checked' : '';
                $isDisabled = $obj['disable'];
            @endphp
            <input {{ $isDisabled }} value="{{ $k }}" type="radio" class="btn-check" name="{{ $obj['name'] }}" id="{{ $obj['id'] }}" autocomplete="off" checked-color="{{ $obj['style'] }}" {{ $isChecked }}/>
            <label class="btn" for="{{ $obj['id'] }}" data-item-value="{{ $k }}">{{ $k }}</label>

            {{-- 
            The ‘checked’ attribute in HTML specifies that an input element should be pre-selected when the page loads. 
            However, if the radio button is not selected when the form is submitted, it will not be included in the HTTP 
            request. This is standard behavior for radio buttons in HTML.    

            The default value must be sent when the radio button is not selected, so, we could consider using a hidden input 
            field in our form. This hidden field can hold the default value, which will be sent with the form data when the 
            form is submitted.
            --}}
            @if ($level == -1)
                <input value="{{ $k }}" type="hidden" name="{{ $obj['name'] }}" class="default-option-value"/>
            @endif
        @endforeach

        <input type="text" 
               name="{{ $as }}" 
               id="{{ $as }}" 
               class="d-none select-value {{ $errors->has('option-' . $as) ? ' has-error' : '' }}" 
               value="{{ $initialValue }}" />
    </div>

    {{-- ERROR LABEL --}}
    <h6 class="px-2 mb-0 text-danger text-xs error-label">{{ $errors->first('option-' . $as) }}</h6>
</div>
@once
    @push('scripts')
    <script type="module" src="{{ asset('js/components/flat-perms-select.js') }}"></script>
    @endpush
@endonce