@extends('layouts.qr-scanner-parent')

@push('stylesheets')
<link rel="stylesheet" href="{{ asset('css/base.css') }}">
<link rel="stylesheet" href="{{ asset('css/controls.css') }}">
<link rel="stylesheet" href="{{ asset('css/scanner-page.css') }}">
@endpush

@section('content')
<div class=":relative :flex :items-top :justify-center :min-h-screen :bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
 
    <div class=":max-w-6xl :mx-auto sm:px-6 lg:px-8">
        <div class=":flex :justify-center :pt-8 sm:justify-start :items-center sm:pt-0">
             
            <div class="header-banner d-flex flex-row">
                <div class="banner-brand-logo">
                    <img src="{{ asset('img/aldersgate.svg') }}" width="70" height="70">
                </div>
                <div class="banner-brand-label mx-3">
                    <div class="fs-2">{{ "Aldersgate College Inc." }}</div>
                    <div class="fs-6" style="font-size: 15px;  color: #3D5656;">
                        {{ "QR Code Library Attendance Monitoring System" }}
                    </div>
                </div>
            </div>
            
            <div class="nav-items ms-auto d-none d-xl-flex flex-row gap-1">
                
                <button type="button" class="btn btn-link px-3 navbar-item-link-button url d-flex align-items-center gap-2">
                    <img src="{{ asset('img/icn-info.png') }}" width="20" height="20">
                    <span>{{ 'About' }}</span>
                </button>

                <button type="button" class="btn btn-link px-3 navbar-item-link-button url d-flex align-items-center gap-2">
                    <img src="{{ asset('img/icn-support.png') }}" width="20" height="20">
                    <span>{{ 'Support' }}</span>
                </button>
                
                <button type="button" class="btn btn-link px-3 navbar-item-link-button url d-flex align-items-center gap-2">
                    <img src="{{ asset('img/icn-options.png') }}" width="20" height="20">
                    <span>{{ 'Options' }}</span>
                </button>

                <button class="btn flat-btn flat-btn-primary">
                    <i class="fas fa-qrcode me-1"></i>
                    {{ "Scan QR" }}
                </button>

            </div>
        </div>

        <div class=":mt-8 bg-white dark:bg-gray-800 :overflow-hidden :shadow sm:rounded-lg">
            <div class=":grid :grid-cols-1 md:grid-cols-1 ~large-grid-cols-2">
                <div class=":p-6">

                    <div class=":flex :items-center">
                        <img src="{{ asset('img/icn-cam.png') }}" width="32" height="32">
                        <div
                            class=":ml-4 :text-lg :leading-7 :font-semibold border-bottom border-2 underline :text-gray-900">
                            {{ "Scanner" }}
                        </div>
                    </div>

                    <div class="ms-1 text-start"> 
                        <div class="mt-2 mb-1 pt-1 :border-t :border-gray-200 :text-gray-600 dark:text-gray-400 :text-sm scanner-tips">
                            <i class="fas fa-info-circle me-1"></i>
                            {{ "Please select a camera below. Click on \"" }}<i class="fas fa-rotate px-1"></i>{{ "\" to refresh if it's not detected." }}
                        </div> 
                    </div>

                    <div class="camera-control-ribbon d-flex ms-1 mb-2 py-1">

                        <select class="camera-selector display:none">
                            <option selected disabled>{{ 'Select Camera' }}</option>
                        </select>

                        <button class="btn flat-btn flat-btn-default btn-refresh-cam-list py-1 px-3 ms-2 me-auto disabled"
                            data-mdb-toggle="tooltip" title="Refresh camera list">
                            <i class="fas fa-rotate"></i>
                        </button>

                        <button class="btn py-1 px-2 mx-2 flat-btn flat-btn-warning d-none btn-open-cam">
                            <i class="fas fa-circle-play me-1"></i>{{ "Open"}}
                        </button>

                        <button class="btn py-1 px-2 mx-2 flat-btn flat-btn-danger d-none btn-stop-cam">
                            <i class="fa-solid fa-circle-stop me-1"></i>{{ "Stop"}}
                        </button>

                    </div>

                    <!-- CAMERA PREVIEW SURFACE -->
                    <div class="video-wrapper d-flex ps-1 pe-2 position-relative">
                        <video class="w-100 h-100 rounded-4" autoplay id="camera-view"></video>
                        <div
                            class="webcam-overlay position-absolute start-0 end-0 top-0 bottom-0 w-100 h-100 px-4 center-flex">
                            <div class="w-100 h-100 webcam-overlay-crosshair center-flex d-none">
                                <img src="{{ asset('img/crosshair.svg') }}" width="200" height="200">
                            </div>
                            <div class="w-100 h-100 webcam-overlay-perms-warn center-flex">
                                <div class="text-center">
                                    <div class="font-accent mb-2">
                                        <i class="fas fa-info-circle text-primary"></i>
                                        <span class=":text-gray-800">
                                            {{ "Please ensure that you have allowed your browser to access the camera before using." }}
                                        </span>
                                    </div>
                                    <small class="text-center :text-gray-600">
                                        {{ "If you're unsure how to do this, please check your browser settings." }}
                                    </small>
                                </div>
                            </div>
                            <div class="w-100 h-100 webcam-overlay-spinner center-flex d-none">
                                <img class="round-spinner" src="{{ asset('img/floater.svg') }}" width="32" height="32">
                                <h6 class="mx-2 mb-0 :text-gray-600 z-100 prep-text">{{ "Preparing the scanner..."}}</h6>
                            </div>
                        </div>
                    </div> 
                </div>

                <div class=":p-6 :border-t :border-gray-200 dark:border-gray-700 md:border-t-0 md:border-l">
                  
                    <div class=":flex :items-center">
                        <img src="{{ asset('img/icn-attendance.png') }}" width="32" height="32">
                        <div class=":ml-4 :text-lg :leading-7 :font-semibold border-bottom border-2 underline :text-gray-900 me-auto">
                            {{ "Attendance" }}
                        </div>
                        <div class="time-label d-flex align-items-center gap-3 text-base-primary-dark pt-1 rounded-5">
                            <div class="bg-control date-label :text-gray-700">{{ date('l, M. d, Y') }}</div>
                            <p class="time-label font-condensed-bold m-0">
                                
                                <i class="fas fa-clock me-1"></i>
                                <span class="hour-minute-label">{{ date('g:i') }}</span>
                                <span class="seconds-label :text-sm :text-gray-500">{{ date(':s') }}</span>
                                <span class="day-night-label">{{ date('A') }}</span>
                            </p>
                        </div> 
                    </div>
                    <div class="d-flex flex-row justify-content-around align-items-center mt-2 mb-1 pt-1 :border-t :border-gray-200">
                        <div class="d-flex align-items-center me-auto gap-2">
                            <span class=":text-sm :text-gray-600 dark:text-gray-400">{{ "Total students" }}</span>
                            <span class="badge badge-success total-students">{{ $totalStudents }}</span>
                        </div>
                        <div class="d-flex align-items-center mx-auto gap-2">
                            <span class=":text-sm :text-gray-600 dark:text-gray-400">{{ "Total records" }}</span>
                            <span class="badge badge-success total-records">{{ $totalRecords }}</span>
                        </div>
                        <div class="ms-auto">
                            <a class="badge badge-warning btn-manage-attendance" target="_blank" rel="noopener noreferrer" href="{{ url('/') }}">
                                <i class="fas fa-wrench me-1"></i>
                                <span>{{ "Manage" }}</span>
                            </a>
                        </div>
                    </div>
                    <div class="attendance-scrollview-root"> 
                        <div class=":text-gray-600 dark:text-gray-400 :text-sm overflow-hidden attendance-scrollview">
                            <div data-simplebar class="attendance-table-wrapper overflow-y-auto">
                                <table class="table table-sm table-striped align-middle attendance-table">
                                    <thead style="z-index: 1000; position: sticky; top: 0;">
                                        <tr>
                                            <th data-orderable="false" style="max-width: 200px;">{{"Name"}}</th>
                                            <th data-orderable="false" class="td-20">{{ "Time in"}}</th>
                                            <th data-orderable="false" class="td-20">{{ "Time out"}}</th>
                                            <th data-orderable="false" class="td-20">{{ "Duration" }}</th>
                                            <th class="d-none"></th>{{-- This will hold row timestamps; useful for sorting--}}
                                        </tr>
                                    </thead>
                                    <tbody class="attendance-sheet">

                                        @if (!empty($attendanceData))
                                            @foreach ($attendanceData as $data)

                                            @php 
                                                $timeInStyle = "";
                                                $timeOutStyle = "";

                                                // If there is a time in data, and no timeout yet,
                                                // we assume that this data is Time In
                                                if (!empty($data->time_in) && empty($data->time_out))
                                                    $timeInStyle = "time-in";
                                                
                                                // If there is a time out data ...
                                                if (!empty($data->time_out))
                                                    $timeOutStyle = "time-out";

                                            @endphp

                                            @if (!empty($data->time_out))
                                                {{--We expect this row as TimedOut, so we no longer need to highlight this and/or
                                                    do some procesing like moving on topmost table --}}
                                                <tr>
                                            @else
                                                {{--All rows with no TimeOut values yet, must have these classes and attributes.
                                                    These rows will be given a class called 'timed-in-rows' which will be used
                                                    to identify the rows that needs to be mapped. The entries of the row Map
                                                    will be supplied onload at document.ready() where the script looks for all
                                                    rows with class 'timed-in-rows'.
                                                 --}}
                                                <tr class="row-index-{{ $data->student_no }} timed-in-rows" data-student-no="{{ $data->student_no }}" data-row-idx="{{ $loop->index }}">
                                            @endif
                                                <td class="td-name-details">
                                                    <div class="d-flex align-items-center w-100">
                                                        <img src="{{ $data->photo }}" alt=""
                                                            style="width: 45px; height: 45px" class="rounded-circle" />
                                                        <div class="ms-3 w-100 text-truncate">
                                                            <p class="fw-bold mb-1 text-truncate">{{ $data->name }}</p>
                                                            <p class="text-muted mb-0 text-truncate">{{ $data->student_no }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="td-20"> 
                                                    <span class="attendance-time-label time-in-label {{ $timeInStyle }}">{{ $data->time_in }}</span>
                                                </td>
                                                <td class="td-20">
                                                    <span class="attendance-time-label time-out-label {{ $timeOutStyle }}">{{ $data->time_out }}</span>
                                                    <input type="hidden" class="timeout-val">
                                                </td>
                                                <td class="td-20"> 
                                                    <span class="text-primary-color font-condensed-bold duration duration-label">
                                                        @if (!empty($data->stay_duration))
                                                            {{ $data->stay_duration }} 
                                                        @endif
                                                    </span>
                                                </td>
                                                <td class="d-none row-timestamp">{{ $data->row_stamp }}</td>
                                            </tr>

                                            @endforeach
                                        @endif 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class=":flex :justify-center :mt-4 sm:items-center sm:justify-between">
            <div class=":text-center :text-sm :text-gray-500 sm:text-left">
                <div class=":flex :items-center">
                   
                    <a href="https://aldersgate.edu.ph/about/" class=":ml-1 :text-gray-500 url">
                        &copy; {{ date('Y') . " Aldersgate College Incorporated" }}
                    </a>
                    
                </div>
            </div>

            <div class=":ml-4 :text-center :text-sm :text-gray-500 sm:text-right sm:ml-0">
                Build v{{ Illuminate\Foundation\Application::VERSION }}
            </div>
        </div>
    </div>
</div>

<!-- MODALS -->
@include('modals.error-dialog')
@include('modals.warn-dialog')

@push('scripts')
<script>
    const SCAN_POST_URL = "{{ route('qr-scan-result') }}";
</script>
<script src="{{ asset('js/controls/combobox.js') }}"></script>
<script src="{{ asset('js/controls/qrcodescanner.js') }}"></script>
<script src="{{ asset('js/qr-scanner/index.js') }}"></script>
@endpush
@endsection