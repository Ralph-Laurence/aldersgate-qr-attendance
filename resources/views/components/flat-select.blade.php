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

<div {{ $attributes->merge(['class' => 'flat-select' ]) }}>

    @if ($withCaption)
        <div class="my-1 px-1">
            <small class="fw-600">{{ $attributes->has('required') ?  "$label *" : $label }}</small>
        </div>
    @endif
    <div class="dropdown">
        <button class="btn dropdown-toggle flat-select-button" type="button" id="{{ $as }}" 
            data-mdb-toggle="dropdown" aria-expanded="false">
                {{ $text }}
        </button>
        <ul class="dropdown-menu overflow-hidden user-select-none" aria-labelledby="{{ $as }}">
            <div class="h-100 w-100" style="max-height: 192px; overflow-y: auto;" data-simplebar>
                @foreach ($items as $item => $value)
                <li>
                    <a class="dropdown-item" data-item-value="{{ $value }}">
                        {{ $item }}
                    </a>
                </li>                
                @endforeach
            </div>
        </ul>
        <input type="text" name="{{ $as . "-value" }}" id="{{ $as . "-value" }}" value="{{ old($as . "-value") }}" class="flat-select-value d-none">
    </div>

    {{-- ERROR LABEL --}}
    <h6 class="px-2 mb-0 text-danger text-xs error-label">
        @if (!$attributes->has('suppress-errors'))
            {{ $errors->first("$as-value") }}
        @endif
    </h6>

</div>