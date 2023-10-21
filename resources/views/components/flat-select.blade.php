@php
    $oldValue = old($as . "-value");
    $oldLabel = $text;

    if (!empty($oldValue))
    {
        $itemLabel = array_search($oldValue, $items);

        if ($itemLabel !== false)
            $oldLabel = $itemLabel;
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

    @if ($withCaption)
        <div class="my-1 px-1">
            <small class="fw-600">{{ $attributes->has('required') ?  "$label *" : $label }}</small>
        </div>
    @endif
    <div class="dropdown">
        <button class="btn dropdown-toggle flat-select-button" type="button" id="{{ $as }}" 
            data-mdb-toggle="dropdown" aria-expanded="false">
                {{ $oldLabel }}
        </button>
        <ul class="dropdown-menu overflow-hidden user-select-none" aria-labelledby="{{ $as }}">
            <div class="h-100 w-100" style="max-height: 192px; overflow-y: auto;" data-simplebar>
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
            </div>
        </ul>
        <input type="text" name="{{ $as . "-value" }}" id="{{ $as . "-value" }}" value="{{ $oldValue }}" class="main-control flat-select-value d-none" {{ $attributes->has('required') ? 'required' : '' }}>
    </div>

    {{-- ERROR LABEL --}}
    <h6 class="px-2 mb-0 text-danger text-xs error-label">
        @if (!$attributes->has('suppress-errors'))
            {{ $errors->first("$as-value") }}
        @endif
    </h6>

</div>