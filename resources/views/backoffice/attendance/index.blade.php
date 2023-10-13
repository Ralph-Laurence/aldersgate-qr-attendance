@php
    use App\Http\Extensions\Utils;

    $totalMonthlyRecords = (!empty($totalMonthly)) ? $totalMonthly : 0;
@endphp

@extends('layouts.backoffice.master')

@section('title')
    {{ "Attendance" }}
@endsection
 
@section('content')
    <div class="content-wrapper py-3">
 
        @include('layouts.backoffice.sidebar')

        <main class="main-content overflow-y-auto ps-2 no-native-scroll" data-simplebar>
            
            @include('layouts.backoffice.header')

            <div class="container-fluid p-4">
               
                <div class="row">
                    <div class="col-xl-3 mb-4">
                        <div class="card attendance-indicators">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize fw-600 opacity-75">{{ "Daily Attendance" }}</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                {{ $indicatorData['totalRecordsDaily'] }}
                                                <span class="text-success text-sm font-weight-bolder">{{--+55%--}} {{ "-/-" }}</span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape shadow">
                                            <i class="fas fa-calendar-days text-lg" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 mb-4">
                        <div class="card attendance-indicators">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize fw-600 opacity-75">{{ "Today's Students" }}</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                {{ $indicatorData['totalStudentsDaily'] }}
                                                <span class="text-success text-sm font-weight-bolder">{{ "-/-" }}</span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape shadow">
                                            <i class="fas fa-solid fa-user-graduate text-lg" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 mb-4">
                        <div class="card attendance-indicators">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize fw-600 opacity-75">{{ "Currently Timed In" }}</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                {{ $indicatorData['totalTimedInToday'] }}
                                                <span class="text-success text-sm font-weight-bolder">{{ "-/-" }}</span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape shadow">
                                            <i class="fa-solid fa-clock text-lg" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 mb-4">
                        <div class="card attendance-indicators">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize fw-600 opacity-75">{{ "Frequent Course" }}</p>
                                            <h5 class="font-weight-bolder mb-0 course-indicator">

                                                @if (!$indicatorData['frequentCourses']->isEmpty())
                                                    {{ $indicatorData['frequentCourses']->keys()->first() }}
                                                    <span class="text-success text-sm font-weight-bolder">{{ $indicatorData['frequentCourses']->values()->first() }}</span>
                                                @else
                                                    {{ "None" }}
                                                    <span class="text-success text-sm font-weight-bolder">{{ '0' }}</span>
                                                @endif 

                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape shadow">
                                            <i class="fas fa-graduation-cap text-lg" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col">
                        <div class="card mb-4 table-card">
                            <div class="card-header pb-0">
                                <div class="d-flex flex-row align-items-center gap-2 mb-3">
                                    <h6 class="card-title mb-0">{{ "Daily Attendance" }}</h6>
                                    <div class="attendance-calendar text-sm px-3 me-auto">
                                        <i class="fas fa-calendar-days me-2"></i> {{ date('l, F d, Y') }}
                                    </div>
                                    <div class="d-inline-flex align-items-center gap-2">
                                        <div class="search-bar px-2">
                                            <button class="search-button">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <input type="text" name="q" id="search-input" placeholder="Search" autocomplete="off" value="">
                                        </div>
                                        <div class="dropdown">
                                            <div class="search-filter justify-content-center px-3 ripple outlined-on-hover" id="filtersDropdown"
                                            data-mdb-toggle="dropdown" data-mdb-ripple-color="#67748E">
                                                <i class="fas fa-filter text-sm me-2"></i> {{ "Filters" }}
                                            </div>
                                            <ul class="dropdown-menu" aria-labelledby="filtersDropdown">
                                                <li><a class="dropdown-item" href="#">Action</a></li>
                                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                              </ul>
                                        </div> 
                                        <div class="dropdown">
                                            <div class="search-filter justify-content-center px-3 ripple outlined-on-hover" id="filtersDropdown"
                                            data-mdb-toggle="dropdown" data-mdb-ripple-color="#67748E">
                                                <i class="fa-solid fa-arrow-down-a-z me-2"></i> {{ "Sort" }}
                                            </div>
                                            <ul class="dropdown-menu" aria-labelledby="filtersDropdown">
                                                <li><a class="dropdown-item" href="#">Action</a></li>
                                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                              </ul>
                                        </div> 
                                        <div class="dropdown">
                                            <button class="btn shadow-0 btn-sm btn-sort outlined-on-hover"
                                            data-mdb-toggle="dropdown" data-mdb-ripple-color="#67748E">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <table class="table table-sm table-fixed table-striped table-hover align-middle bg-white daily-attendance">
                                    <thead>
                                        <tr>
                                            <th data-orderable="false" class="fixed-long-column-300">{{ "Student" }}</th>
                                            <th data-orderable="false" class="text-center">{{ "Course" }}</th>
                                            <th data-orderable="false" class="text-center">{{ "Time In" }}</th>
                                            <th data-orderable="false" class="text-center">{{ "Time Out" }}</th>
                                            <th data-orderable="false" class="text-center">{{ "Status" }}</th>
                                            <th data-orderable="false" class="text-center">{{ "Action" }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($monthlyAttendance))
                                             
                                            @foreach ($monthlyAttendance as $row)

                                                @php 
                                                    if ($row->attendance_date != Utils::dateToday())
                                                        continue;
                                                @endphp
                                                <tr>
                                                    <td class="fixed-long-column-300 ps-2">
                                                        <div class="d-flex align-items-center px-2 py-1">
                                                            <div class="avatar-profile me-3 rounded-3 overflow-hidden">
                                                                <img src="{{ $row->photo }}" width="36" height="36">
                                                            </div>
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">{{ $row->name }}</h6>
                                                                <p class="mb-0 text-secondary text-xs">{{ $row->student_no }}</p>
                                                            </div>
                                                        </div> 
                                                    </td>
                                                    <td class="text-center opacity-75">{{ $row->course }}</td>
                                                    <td class="text-center opacity-75">{{ $row->time_in }}</td>
                                                    <td class="text-center opacity-75">{{ $row->time_out }}</td>
                                                    <td class="text-center">
                                                        <span class="badge time-status {{ $row->statusBadge }}">{{ $row->status }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="center-flex gap-2 record-actions">
                                                            <button class="btn btn-sm px-2 btn-details">
                                                                <i class="fa-solid fa-circle-info"></i>
                                                            </button>
                                                            <button class="btn btn-sm px-2 btn-edit">
                                                                <i class="fa-solid fa-pen"></i>
                                                            </button>
                                                            <button class="btn btn-sm px-2 btn-delete">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-3 mb-4">
                        <div class="card attendance-indicators">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize fw-600 opacity-75">{{ "This Month" }}</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                {{ $indicatorData['totalRecordsMonthly'] }}
                                                <span class="text-success text-sm font-weight-bolder">+55%</span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape shadow bg-gradient-warning">
                                            <i class="fas fa-calendar text-lg" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 mb-4">
                        <div class="card attendance-indicators">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize fw-600 opacity-75">{{ "Total Students" }}</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                {{ $indicatorData['totalStudentsMonthly'] }}
                                                <span class="text-success text-sm font-weight-bolder">+55%</span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape shadow bg-gradient-warning">
                                            <i class="fas fa-solid fa-user-graduate text-lg" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="col-xl-3 mb-4">
                        <div class="card attendance-indicators">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize fw-600 opacity-75">{{ "Frequent Course" }}</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                BSIT
                                                <span class="text-success text-sm font-weight-bolder">+55%</span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape shadow bg-gradient-warning">
                                            <i class="fa-solid fa-person-arrow-up-from-line text-lg" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 mb-4">
                        <div class="card attendance-indicators">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize fw-600 opacity-75">{{ "Least Course" }}</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                BSIT
                                                <span class="text-success text-sm font-weight-bolder">+55%</span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape shadow bg-gradient-warning">
                                            <i class="fa-solid fa-person-arrow-down-to-line text-lg" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="card mb-4 table-card">
                            <div class="card-header pb-0">
                                {{-- <h6 class="card-title">{{ "Monthly Attendance" }}</h6> --}}
                                <div class="d-flex flex-row align-items-center gap-2 mb-3">
                                    <h6 class="card-title mb-0">{{ "Monthly Attendance" }}</h6>
                                    <div class="attendance-calendar text-sm px-3 me-auto outlined-on-hover">
                                        <i class="fas fa-calendar-week me-2"></i> {{ date('F Y') }}
                                    </div>
                                    <div class="d-inline-flex align-items-center gap-2">
                                        <div class="search-bar px-2">
                                            <button class="search-button">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <input type="text" name="q" id="search-input" placeholder="Search" autocomplete="off" value="">
                                        </div>
                                        <div class="dropdown">
                                            <div class="search-filter justify-content-center px-3 ripple outlined-on-hover" id="filtersDropdown"
                                            data-mdb-toggle="dropdown" data-mdb-ripple-color="#67748E">
                                                <i class="fas fa-filter text-sm me-2"></i> {{ "Filters" }}
                                            </div>
                                            <ul class="dropdown-menu" aria-labelledby="filtersDropdown">
                                                <li><a class="dropdown-item" href="#">Action</a></li>
                                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                              </ul>
                                        </div> 
                                        <div class="dropdown">
                                            <div class="search-filter justify-content-center px-3 ripple outlined-on-hover" id="filtersDropdown"
                                            data-mdb-toggle="dropdown" data-mdb-ripple-color="#67748E">
                                                <i class="fa-solid fa-arrow-down-a-z me-2"></i> {{ "Sort" }}
                                            </div>
                                            <ul class="dropdown-menu" aria-labelledby="filtersDropdown">
                                                <li><a class="dropdown-item" href="#">Action</a></li>
                                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                              </ul>
                                        </div> 
                                        <div class="dropdown">
                                            <button class="btn shadow-0 btn-sm btn-sort outlined-on-hover"
                                            data-mdb-toggle="dropdown" data-mdb-ripple-color="#67748E">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <table class="table table-sm table-fixed table-striped table-hover align-middle bg-white daily-attendance">
                                    <thead>
                                        <tr>
                                            <th data-orderable="false" class="fixed-long-column-300">{{ "Student" }}</th>
                                            <th data-orderable="false" class="text-center">{{ "Course" }}</th>
                                            <th data-orderable="false" class="text-center">{{ "Time In" }}</th>
                                            <th data-orderable="false" class="text-center">{{ "Time Out" }}</th>
                                            <th data-orderable="false" class="text-center">{{ "Date" }}</th>
                                            <th data-orderable="false" class="text-center">{{ "Action" }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($monthlyAttendance))
                                             
                                            @foreach ($monthlyAttendance as $row)
 
                                                <tr>
                                                    <td class="fixed-long-column-300 ps-2">
                                                        <div class="d-flex align-items-center px-2 py-1">
                                                            <div class="avatar-profile me-3 rounded-3 overflow-hidden">
                                                                <img src="{{ $row->photo }}" width="36" height="36">
                                                            </div>
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">{{ $row->name }}</h6>
                                                                <p class="mb-0 text-secondary text-xs">{{ $row->student_no }}</p>
                                                            </div>
                                                        </div> 
                                                    </td>
                                                    <td class="text-center opacity-75">{{ $row->course }}</td>
                                                    <td class="text-center opacity-75">{{ $row->time_in }}</td>
                                                    <td class="text-center opacity-75">{{ $row->time_out }}</td>
                                                    <td class="text-center opacity-75">{{ Utils::dateToString($row->attendance_date, 'M. d, Y') }}</td>
                                                    <td class="text-center">
                                                        <div class="center-flex gap-2 record-actions">
                                                            <button class="btn btn-sm px-2 btn-details">
                                                                <i class="fa-solid fa-circle-info"></i>
                                                            </button>
                                                            <button class="btn btn-sm px-2 btn-edit">
                                                                <i class="fa-solid fa-pen"></i>
                                                            </button>
                                                            <button class="btn btn-sm px-2 btn-delete">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="footer pt-3">
                    <div class="container-fluid">
                        <div class="row align-items-center justify-content-lg-between">
                            <div class="col-lg-6 mb-lg-0 mb-4">
                                <div class="copyright text-sm text-center text-lg-start opacity-75">
                                    {!! "&copy;" !!} {{ date('Y') . ", crafted with "}} <i class="fas fa-heart"></i>  {{"for a smarter library management." }}
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>

            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('extensions/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/backoffice/attendance.js') }}"></script>
@endpush