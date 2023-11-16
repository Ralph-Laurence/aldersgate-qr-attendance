@php
    use Illuminate\Support\Str;
    use Carbon\Carbon;
    // $timeString = "12:00 PM";
    // $time = \Carbon\Carbon::parse($timeString);
    // echo $time->format('H:i:s a');
    $carbon         = Carbon::now();
    $initialHrs     = $carbon->format('h');
    $initialMins    = $carbon->format('i');
    $initialAmPm    = $carbon->format('a');
    $elementId      = $attributes->has('as') ? $attributes->get('as') : 'flat-time-picker-' . Str::random(4);

    $getCurrentTimePeriod = function() use($carbon)
    {
        $hour = $carbon->format('H');

        if ($hour >= 5 && $hour < 12)
            return "morning";

        else if ($hour >= 12 && $hour < 17)
            return "noon";

        else
            return "night";
    };

    $timePeriod = $getCurrentTimePeriod();
@endphp 

@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/components/flat-time-picker.css') }}" />
    @endpush
@endonce
<x-flat-input as="{{ $elementId }}" fill="{{ 'Select Time' }}" data-mdb-toggle="modal" data-mdb-target="#{{ $elementId }}-modal" locked/>

<div class="modal fade flat-time-picker-modal {{ $elementId }}-modal" id="{{ $elementId }}-modal" tabindex="-1" aria-labelledby="{{ $elementId }}Label"
    aria-hidden="true" data-mdb-backdrop="static" data-mdb-keyboard="false" data-output-control="{{ $elementId }}">

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
                        <div class="col flex-center">
                            <x-flat-input as="{{ 'input-hours' }}" gravity="center" limit="3" clamp="abs-int" initial="{{ $initialHrs }}"/>
                        </div>
                        <div class="col-1 px-1 flex-center">
                            <h5 class="fw-bold opacity-70 m-0">{{ ':' }}</h5>
                        </div>
                        <div class="col flex-center">
                            <x-flat-input as="{{ 'input-minutes' }}" gravity="center" limit="3" clamp="abs-int" initial="{{ $initialMins }}"/>
                        </div>
                        <div class="col flex-center">
                            <button class="meridiem-toggle w-100 text-uppercase mb-1" data-time-period="{{ $timePeriod }}" data-meridiem="{{ $initialAmPm }}">
                                <span class="meridiem-label">{{ $initialAmPm }}</span>
                            </button>
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