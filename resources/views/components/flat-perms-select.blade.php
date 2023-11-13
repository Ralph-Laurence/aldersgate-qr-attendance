@once
@push('styles')
    <style>
        .permission-select label 
        {
            height: 25px;
            padding: 2px 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: capitalize;
            background-color: #ebedef;
            color: #40464f;
        } 

        .permission-select .btn-check:checked[checked-color='success'] + label {
            background-color: var(--flat-color-primary-dark);
        }
 
        .permission-select .btn-check:checked[checked-color='danger']  + label {
            background-color: var(--flat-color-danger);
        }

        .permission-select .btn-check:checked[checked-color='warning'] + label {
            background-color: var(--flat-color-warning);
        }

        .permission-select .btn-check:checked[checked-color='primary'] + label {
            background-color: #2c58a0;
        }

        .permission-select .btn-check:checked[checked-color='success'] + label::before { content: '\f521'; }
        .permission-select .btn-check:checked[checked-color='danger']  + label::before { content: '\f05e'; }
        .permission-select .btn-check:checked[checked-color='warning'] + label::before { content: '\f304'; }
        .permission-select .btn-check:checked[checked-color='primary'] + label::before { content: '\f02e'; }
        .permission-select .btn-check:checked + label { color: white; }
        .permission-select .btn-check:checked + label::before 
        {
            font-family: var(--fas-font);
            margin-right: 4px;
            font-size: 10px;
        }

    </style>
@endpush
@endonce
<div {{ $attributes->merge(['class' => "d-flex flex-column " . $stretchWidth ]) }}>
    @if (!empty($caption))
    <div class="my-1 px-1">
        <small class="fw-600">{{ $caption }}</small>
    </div>
    @endif
    <div class="btn-group flat-controls permission-select {{ $stretchWidth }}">
    
        <input type="radio" class="btn-check" name="option-{{ $as }}" id="option-{{ $as }}-full" autocomplete="off" checked-color="success" {{ $FLAG_ATTR_FULL }} {{ $FLAG_EXCEPT_FULL }}/>
        <label class="btn" for="option-{{ $as }}-full" data-item-value="4">Full</label>
    
        <input type="radio" class="btn-check" name="option-{{ $as }}" id="option-{{ $as }}-modify" autocomplete="off" checked-color="warning" {{ $FLAG_ATTR_MODIFY }} {{ $FLAG_EXCEPT_MODIFY }}/>
        <label class="btn" for="option-{{ $as }}-modify" data-item-value="3">Modify</label>
    
        <input type="radio" class="btn-check" name="option-{{ $as }}" id="option-{{ $as }}-write" autocomplete="off" checked-color="warning" {{ $FLAG_ATTR_WRITE }}/>
        <label class="btn" for="option-{{ $as }}-write" data-item-value="2">Write</label>
        
        <input type="radio" class="btn-check" name="option-{{ $as }}" id="option-{{ $as }}-read" autocomplete="off" checked-color="primary" {{ $FLAG_ATTR_READ }} />
        <label class="btn" for="option-{{ $as }}-read" data-item-value="1">Read</label>
        
        <input type="radio" class="btn-check" name="option-{{ $as }}" id="option-{{ $as }}-deny" autocomplete="off" checked-color="danger" {{ $FLAG_ATTR_DENY }}/>
        <label class="btn" for="option-{{ $as }}-deny" data-item-value="0">Deny</label>
    
        <input type="text" name="{{ $as }}" id="{{ $as }}" class="d-none select-value {{ $as }}" value="{{ old($as) }}" data-default="{{ $dataDefault }}">
    </div>
</div>
@once
    @push('scripts')
    <script type="module" src="{{ asset('js/components/flat-perms-select.js') }}"></script>
    @endpush
@endonce