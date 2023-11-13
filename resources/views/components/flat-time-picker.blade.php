@php
    use Illuminate\Support\Str;
    // $timeString = "12:00 PM";
    // $time = \Carbon\Carbon::parse($timeString);
    // echo $time->format('H:i:s a');
    $carbon         = \Carbon\Carbon::now();
    $initialHrs     = $carbon->format('h');
    $initialMins    = $carbon->format('i');
    $initialAmPm    = $carbon->format('a');
    $elementId      = $attributes->has('as') ? $attributes->get('as') : 'flat-time-picker-' . Str::random(4);
@endphp 
@once
    @push('styles')
    <style>
        .flat-time-picker-modal .modal-dialog 
        {
            width: 320px !important;
            max-width: 320px !important;
        }

        .flat-time-picker-modal .btn-reset
        {
            background: none;
            outline: none;
            border: none;
            color: var(--text-color-500);
        }

        .flat-time-picker-modal .btn-reset:hover
        {
            color: var(--text-color-800);
        }

        .flat-time-picker-modal .btn-spinner
        {
            border: none;
            border-radius: 2rem;
            width: 100%;
            outline: none;
            background: none;
            color: var(--text-color-400);
            box-shadow: inset 0 0 0 1px #eee;
            opacity: 0.5;
        }

        .flat-time-picker-modal .btn-spinner:hover
        {
            background-color: var(--flat-color-control);
            color: var(--text-color-700);
            border: none;
            opacity: 1;
        }

        .flat-time-picker-modal .input-text
        {
            padding-left: 14px !important;
            padding-right: 14px !important;
        }

        .flat-time-picker-modal .meridiem-toggle 
        {
            border: none;
            outline: none;
            border-radius: 2rem;
            font-size: 14px;
            transition: background-color 0.25s ease-in-out, color 0.25s ease-in-out;
        }
        
        .meridiem-toggle[data-meridiem="am"]
        {
            background-color: #2b62b4;
            color: white;
        }

        .meridiem-toggle[data-meridiem="pm"]
        {
            background-color: #2E3963;
            color: #F5C87B;
        }

        .meridiem-toggle[data-meridiem="am"]::before 
        {
            font-family: var(--fas-font);
            content: '\f185';
            padding-right: 4px;
            font-size: 13px;
            color: #F5C87B;
        }

        .meridiem-toggle[data-meridiem="pm"]::after
        {
            font-family: var(--fas-font);
            content: '\f186';
            padding-left: 4px;
            font-size: 13px;
            color: #F5C87B;
        }
    </style>
    @endpush
@endonce
<x-flat-input as="{{ $elementId }}" fill="{{ 'Select Time' }}" data-mdb-toggle="modal" data-mdb-target="#{{ $elementId }}-modal"/>
<div class="modal fade flat-time-picker-modal {{ $elementId }}-modal" id="{{ $elementId }}-modal" tabindex="-1" aria-labelledby="{{ $elementId }}Label"
    aria-hidden="true" data-mdb-backdrop="static" data-mdb-keyboard="false">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title" id="{{ $elementId }}Label">{{ 'Select Time' }}</h6>
                <button type="button" class="btn-reset">
                    <i class="fas fa-rotate-left"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col">
                            <button class="btn-increment-hour btn-spinner">
                                <i class="fas fa-chevron-up"></i>
                            </button>
                        </div>
                        <div class="col-1 px-1"></div>
                        <div class="col">
                            <button class="btn-increment-minute btn-spinner">
                                <i class="fas fa-chevron-up"></i>
                            </button>
                        </div>
                        <div class="col"></div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <x-flat-input as="{{ 'input-hours' }}" gravity="center" limit="3" clamp="abs-int" initial="{{ $initialHrs }}"/>
                        </div>
                        <div class="col-1 px-1 flex-center">
                            <h5 class="fw-bold opacity-70 m-0">{{ ':' }}</h5>
                        </div>
                        <div class="col">
                            <x-flat-input as="{{ 'input-minutes' }}" gravity="center" limit="3" clamp="abs-int" initial="{{ $initialMins }}"/>
                        </div>
                        <div class="col flex-center">
                            <button class="meridiem-toggle w-100 text-uppercase" data-meridiem="{{ $initialAmPm }}">{{ $initialAmPm }}</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col text-center">
                            <small class="text-xs opacity-75 hour-label">{{ 'Hour' }}</small>
                        </div>
                        <div class="col-1 px-1"></div>
                        <div class="col text-center">
                            <small class="text-xs opacity-75 minute-label">{{ 'Minute' }}</small>
                        </div>
                        <div class="col"></div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button class="btn-decrement-hour btn-spinner">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div class="col-1 px-1"></div>
                        <div class="col">
                            <button class="btn-decrement-minute btn-spinner">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div class="col"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 d-flex flex-row align-items-center justify-content-between">
                <x-flat-button as="btn-clear"  theme="default" text="Clear"  data-mdb-dismiss="modal" />
                <x-flat-button as="btn-cancel" theme="default" text="Cancel" data-mdb-dismiss="modal" />
                <x-flat-button as="btn-ok"     theme="primary" text="OK"     data-mdb-dismiss="modal" />
            </div>
        </div>
    </div>
</div>

@once
    @push('scripts')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('js/components/flat-time-picker.js') }}"></script>
    @endpush    
@endonce