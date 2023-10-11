@php
    use App\Http\Extensions\Utils;
@endphp

@extends('layouts.backoffice.master')

@section('title')
    {{ "Attendance" }}
@endsection
 
@section('content')
    <div class="content-wrapper py-3">
 
        @include('layouts.backoffice.sidebar')

        <main class="main-content overflow-y-auto ps-2" data-simplebar>
            
            @include('layouts.backoffice.header')

            <div class="container-fluid p-4">
               
                <div class="row">
                    <div class="col">
                        <div class="card mb-4 table-card">
                            <div class="card-header pb-0">
                                <h6 class="card-title">{{ "Daily Attendance" }}</h6>
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <table class="table table-sm table-fixed table-striped align-middle bg-white daily-attendance">
                                    <thead>
                                        <tr>
                                            <th data-orderable="false" class="text-xs text-uppercase fixed-long-column">{{ "Student" }}</th>
                                            <th data-orderable="false" class="text-xs text-uppercase text-center">{{ "Course" }}</th>
                                            <th data-orderable="false" class="text-xs text-uppercase text-center">{{ "Time In" }}</th>
                                            <th data-orderable="false" class="text-xs text-uppercase text-center">{{ "Time Out" }}</th>
                                            <th data-orderable="false" class="text-xs text-uppercase text-center">{{ "Status" }}</th>
                                            <th data-orderable="false" class="text-xs text-uppercase text-center">{{ "Action" }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($dailyAttendance))
                                             
                                            @foreach ($dailyAttendance as $row)

                                                @php
                                                     
                                                    $names = [ $row->lastname . ",", $row->firstname, $row->middlename];
                                                    $name = implode(" ", $names);
                                                    $timeFormat = 'g:i A';
                                                    $timeIn = Utils::formatTimestamp($row->time_in, $timeFormat);
                                                    $timeOut = !empty($row->time_out) ? Utils::formatTimestamp($row->time_out, $timeFormat) : '';
                                                @endphp
                                                <tr>
                                                    <td class="fixed-long-column ps-4">
                                                        <div class="d-flex px-2 py-1">
                                                            <div class="avatar-profile me-3 rounded-3 overflow-hidden">
                                                                <img src="{{ $row->photo }}" width="36" height="36">
                                                            </div>
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">{{ $name }}</h6>
                                                                <p class="mb-0 text-secondary text-xs">{{ $row->student_no }}</p>
                                                            </div>
                                                        </div> 
                                                    </td>
                                                    <td class="text-center">{{ $row->course }}</td>
                                                    <td class="text-center">{{ $timeIn }}</td>
                                                    <td class="text-center">{{ $timeOut }}</td>
                                                    <td class="text-center">{{ '' }}</td>
                                                    <td class="text-center">{{ '' }}</td>
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