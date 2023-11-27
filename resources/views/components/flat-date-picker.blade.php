@php
$hasError = $errors->has($as) ? ' has-error' : '';
@endphp

@once
@push('styles')
<link rel="stylesheet" href="{{ asset('css/components/flat-input.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/flat-date-picker.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/components/flat-input.js') }}"></script>
@endpush
@endonce

<div {{ $attributes->merge(['class' => 'flat-controls flat-input flat-date-picker dropdown']) }} data-alias="text">

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
    <div class="input-text mb-1 {{ $hasError }} date-picker-toggle dropdown-toggle">

        <input type="text" name="{{ $as }}" id="{{ $as }}" class="main-control {{ $gravity }} {{ $clamp }}"
            maxlength="{{ $limit }}" value="{{ $defaultValue }}" aria-autocomplete="none" placeholder="{{ $fill }}"
            readonly {{$attributes }} />

        <i class="fa-solid fa-circle-xmark ms-2 input-trailing-icon"></i>

    </div>

    {{-- ERROR LABEL --}}
    <h6 class="px-2 mb-0 text-danger text-xs error-label">{{ $errors->first($as) }}</h6>

    <div class="dropdown-menu date-picker-dropmenu">

        {{--BEGIN HEADER--}}
        <div class="date-header d-flex flex-column py-1">

            {{--YEAR DROPDOWN MENU CONTAINER --}}
            <div class="flex-center year-header p-1">
                <div class="dropdown year-dropdown">
                    <button class="btn dropdown-toggle plain-dropdown-dropdown year-dropdown-toggle" 
                    type="button" data-mdb-toggle="dropdown" aria-expanded="false">{{--Year--}}</button>
                    <ul class="dropdown-menu overflow-hidden">
                        <div class="w-100 menu-scrollview" style="max-height: 192px; overflow-y: auto;" data-simplebar>
                
                        </div>
                    </ul>
                </div>
            </div>

            {{--MONTH DROPDOWN MENU CONTAINER--}}
            <div class="flex-center d-flex align-items-center justify-content-between month-wrapper py-0 px-2">
                
                {{-- SCROLL PREVIOUS MONTH --}}
                <button class="btn btn-month-spinner month-spinner-prev">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>

                <div class="dropdown month-dropdown">
                    <button class="btn dropdown-toggle plain-dropdown-dropdown month-dropdown-toggle" 
                    type="button" data-mdb-toggle="dropdown" aria-expanded="false">{{--Month--}}</button>
                    <ul class="dropdown-menu overflow-hidden">
                        <div class="w-100 menu-scrollview" style="max-height: 192px; overflow-y: auto;" data-simplebar>
                
                        </div>
                    </ul>
                </div>

                {{-- SCROLL NEXT MONTH --}}
                <button class="btn btn-month-spinner month-spinner-next">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>

            </div>

            {{--DAY NAME LABEL--}}
            <small class="flex-center day-wrapper day-label p-0">Saturday, Dec 14</small>
        </div>
        {{--END HEADER--}}

        {{--BEGIN TABLE--}}
        <div class="days-table-wrapper w-100">
            <table class="w-100 days-table" id="days">
                <thead>
                    <tr class="day-names-header" id="day-names"></tr>
                </thead>
                <tbody class="day-values"></tbody>
            </table>
        </div>
        {{--END TABLE--}}

        <div class="date-picker-footer d-flex align-items-center justify-content-between p-1">
            <button class="btn btn-link btn-clear">{{ 'Clear' }}</button>
            <button class="btn btn-link btn-cancel">{{ 'Cancel' }}</button>
            <button class="btn btn-link btn-ok">{{ 'OK' }}</button>
        </div>
    </div>

</div>