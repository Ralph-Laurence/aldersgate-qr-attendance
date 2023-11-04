@php
    $input_value_name   = $as . "-value";           // name, id, class of this value holder
    $oldValue           = old($input_value_name);   // get the last value after submit and redirect back()
    $oldLabel           = $text;
    
    if (!empty($oldValue))
    { 
        $itemLabel = array_search($oldValue, $items);
    
        if ($itemLabel !== false)
            $oldLabel = $itemLabel;
    }

    $required       = $attributes->has('required') ? 'required' : '';
    $useDropArrow   = ($attributes->has('drop-arrow') && $attributes->get('drop-arrow') == 'none') ? 'drop-arrow-none' : '';
    $icon           = $attributes->has('use-icon') ? $attributes->get('use-icon') : '';
    $iconMargin     = 'me-1';
    $hasText        = true;

    if ($attributes->has('no-text'))
    {
        $oldLabel   = '';
        $iconMargin = '';
        $hasText    = false;
    }
@endphp

@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/components/flat-select.css') }}">
    @endpush
    @push('scripts')
        <script src="{{ asset('js/components/flat-select.js') }}" type="module"></script>
        <script type="module">
            import { FlatSelect } from "{{ asset('js/components/flat-select.js') }}";
            window.FlatSelect = FlatSelect;
        </script>
    @endpush
@endonce

<div {{ $attributes->merge(['class' => 'flat-controls flat-select' ]) }} data-alias="select">

    @if ($useCaption)
        <div class="my-1 px-1">
            <small class="fw-600">{{ $attributes->has('required') ?  "$caption *" : $caption }}</small>
        </div>
    @endif
    <div class="dropdown">
        <button class="btn dropdown-toggle flat-select-button {{ $useDropArrow }} {{ $hasText !== true ? 'no-text' : '' }}" type="button" id="{{ $as }}" 
            data-mdb-toggle="dropdown" aria-expanded="false">
                @if (!empty($icon))
                    <i class="fas {{ $icon }} text-sm {{ $iconMargin }}"></i>
                @endif
                <span class="button-text">{{ $oldLabel }}</span>
        </button>
        <ul class="dropdown-menu overflow-hidden user-select-none" aria-labelledby="{{ $as }}">
            <div class="h-100 w-100" style="max-height: 192px; overflow-y: auto;" data-simplebar>
                
                @if (!is_null($items))
                
                    @foreach ($items as $item => $value)
                    <li> 
                        @if ( !empty($oldValue) && $oldValue == $value )
                            <a class="dropdown-item active" data-item-value="{{ $value }}">
                                {{ $item }}
                            </a>
                        @else
                            <a class="dropdown-item" data-item-value="{{ $value }}">
                            {{ $item }}
                        </a>
                        @endif
                    </li>                
                    @endforeach 

                @endif
                
            </div>
        </ul>
        <input type="text" name="{{ $input_value_name }}" id="{{ $input_value_name }}" value="{{ $oldValue }}" class="main-control flat-select-value d-none {{ $errors->has($input_value_name) ? ' has-error' : '' }}" {{ $required }}>
    </div>

    {{-- ERROR LABEL --}}
    <h6 class="px-2 mb-0 text-danger text-xs error-label">
        @if (!$attributes->has('suppress-errors'))
            {{ $errors->first( $input_value_name ) }}
        @endif
    </h6>

</div>