@php
    use Illuminate\Support\Str;

    $buttonText = !empty($text) ? $text : 'Combo Box';
    $controlId  = !empty($id)   ? $id   : 'combo-box-' . Str::random(10);
    $listItems  = !empty($data) ? $data : [];
@endphp

@once
@push('styles')
    <style>
        .combobox-button 
        {
            background-color: var(--flat-control-bg) !important;
            padding: 6px 16px;
            border-radius: 2rem !important;
            font-weight: 600;
            color: var(--text-color-700) !important;
            box-shadow: none !important;
            font-size: 0.8125rem;
            text-align: center;
        }

        .combobox-button::after {
            border: none;
            font-family: var(--fas-font);
            content: '\f078';
            padding-left: 6px;
            position: relative;
            bottom: -0.186rem;
        }

        .combobox-button:hover,
        .combobox-button:active,
        .combobox-button:focus {
            background-color: #DBDBDE !important;
            color: var(--text-color-800) !important;
        }
    </style>
@endpush
@endonce

<div class="dropdown">
    <button class="btn btn-primary dropdown-toggle combobox-button" type="button" id="{{ $controlId }}" data-mdb-toggle="dropdown"
        aria-expanded="false">
        {{ $buttonText }}
    </button>
    <ul class="dropdown-menu overflow-hidden user-select-none" aria-labelledby="{{ $controlId }}">
        <div class="h-100 w-100" style="max-height: 192px; overflow-y: auto;" data-simplebar>
            @foreach ($listItems as $label => $value)
            <li>
                <a class="dropdown-item" data-value="{{ $value }}">
                    {{ $label }}
                </a>
            </li>                
            @endforeach
        </div>
    </ul>
    <input type="hidden" name="{{ $controlId . "-value" }}" id="{{ $controlId . "-value" }}">
</div>
