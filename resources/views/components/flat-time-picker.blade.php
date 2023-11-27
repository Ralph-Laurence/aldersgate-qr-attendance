@php
    $hasError   = $errors->has($as) ? ' has-error' : '';

    // default value
    $defaultValue = old($as);

    if ($attributes->has('initial'))
        $defaultValue = $attributes->get('initial');
@endphp

@once 
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/components/flat-input.css') }}">
        <link rel="stylesheet" href="{{ asset('css/components/flat-time-picker.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('js/components/flat-input.js') }}"></script>
        <script src="{{ asset('js/components/flat-time-picker.js') }}"></script>
    @endpush
@endonce

<div class="flat-controls flat-input flat-time-picker dropdown" data-alias="text">

    {{-- OPTIONAL LABEL --}}
    @if ($attributes->has('with-caption'))
        <div class="my-1 px-1">
            <small class="fw-600">{{ $attributes->has('required') ? $fill . ' *' : $fill }}</small>
        </div>
    @endif

    {{-- WARNING LABEL IF DATA IS NOT FOUND --}}
    <div class="my-1 px-1 warning-label-wrapper display-none">
        <small class="fw-600 text-warning">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span class="ms-1 warning-label"></span>
        </small>
    </div>

    {{-- INPUT WRAPPER --}}
    <div class="input-text mb-1 {{ $hasError }}" >
        <input  type="text" 
                name="{{ $as }}" 
                id="{{ $as }}" 
                class="main-control {{ $gravity }} {{ $clamp }} dropdown-toggle"
                maxlength="{{ $limit }}"
                value="{{ $defaultValue }}" 
                aria-autocomplete="none" 
                placeholder="{{ $fill }}"
                {{ $attributes }}
                readonly 
                data-mdb-toggle="dropdown" 
                data-mdb-auto-close="false"/>

        <i class="fa-solid fa-circle-xmark ms-2 input-trailing-icon"></i>

        <div class="flat-timepicker-menu dropdown-menu">
            <div class="timepicker-header d-flex align-items-center flex-row py-2 px-3 border-bottom">
                <h6 class="timepicker-title me-auto mb-0">{{ 'Select Time' }}</h6>
                <button type="button" class="btn-reset">
                    <i class="fas fa-rotate-left"></i>
                </button>
            </div>
            <div class="container-fluid p-3">
                <div class="row mb-2">
                    <div class="col">
                        <button type="button" class="btn-increment-hour btn-spinner">
                            <i class="fas fa-chevron-up"></i>
                        </button>
                    </div>
                    <div class="col-1 px-1"></div>
                    <div class="col">
                        <button type="button" class="btn-increment-minute btn-spinner">
                            <i class="fas fa-chevron-up"></i>
                        </button>
                    </div>
                    <div class="col"></div>
                </div>
                <div class="row">
                    <div class="col flex-center">
                        <div class="flat-controls flat-input-plain" data-alias="text">
                            <div class="input-text-plain mb-1">
                                <input type="text" class="input-hours text-center abs-int ignore-dirty-check" 
                                    name="input-hours" id="input-hours"
                                    maxlength="3" value="{{ $initialHrs }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-1 px-1 flex-center">
                        <h5 class="fw-bold opacity-70 m-0">{{ ':' }}</h5>
                    </div>
                    <div class="col flex-center">
                        <div class="flat-controls flat-input" data-alias="text">
                            <div class="input-text-plain mb-1">
                                <input type="text" class="input-minutes text-center abs-int ignore-dirty-check" 
                                name="input-minutes" id="input-minutes" 
                                maxlength="3" value="{{ $initialMins }}">
                            </div>
                        </div>
                    </div>
                    <div class="col flex-center">
                        <button type="button" class="meridiem-toggle w-100 text-uppercase mb-1" data-time-period="{{ $timePeriod }}"
                            data-meridiem="{{ $initialAmPm }}">
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
                        <button type="button" class="btn-decrement-hour btn-spinner">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="col-1 px-1"></div>
                    <div class="col">
                        <button type="button" class="btn-decrement-minute btn-spinner">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="col"></div>
                </div>
            </div>
            <div class="timepicker-footer border-0 d-flex flex-row align-items-center justify-content-between p-2">
                <x-flat-button as="btn-clear" theme="default" text="Clear" />
                <x-flat-button as="btn-cancel" theme="default" text="Cancel" />
                <x-flat-button as="btn-ok" theme="primary" text="OK" />
            </div>
        </div>
    </div>

    {{-- ERROR LABEL --}}
    <h6 class="px-2 mb-0 text-danger text-xs error-label">{{ $errors->first($as) }}</h6>

    
</div>