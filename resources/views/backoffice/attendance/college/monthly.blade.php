@extends('layouts.backoffice.master')

@section('title')
{{ "Attendance" }}
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/backoffice/overrides/datatables.css') }}">
@endpush
 
@section('content')

@once
    @include('modals.message-box')
    @include('controls.datepicker')
    @include('modals.toast')
@endonce

{{-- BEGIN STUDENT FORM MODAL --}}
<x-modal-form-md id="crudFormModal" title="Attendance Form" method="POST" action="" title-create="Create attendance" title-edit="Edit attendance">
    <x-slot name="formInner">
        <div class="container-fluid mb-3">
            <div class="d-none">
                <textarea id="form-action-store">{{-- $formActions['storeStudent'] --}}</textarea>
                <textarea id="form-action-update">{{-- $formActions['updateStudent'] --}}</textarea>
            </div>
            <div class="mb-2">
                <i class="fas fa-info-circle me-1"></i>
                <small>{{ "Please fill out the fields marked with an asterisk (*) as they are required." }}</small>
            </div>
            <div class="row">
                <div class="col">
                    {{-- <x-flat-input as="{{ 'input-student-no' }}" fill="{{ 'Student No.' }}"  required with-caption />
                    <x-flat-input as="{{ 'input-fname' }}"      fill="{{ 'Firstname' }}"    required with-caption />
                    <x-flat-input as="{{ 'input-mname' }}"      fill="{{ 'Middlename' }}"   required with-caption />
                    <x-flat-input as="{{ 'input-lname' }}"      fill="{{ 'Lastname' }}"     required with-caption /> --}}
                </div>
                <div class="col">

                    {{-- <div class="d-flex justify-content-between">
                        <x-flat-select as="{{ 'input-course' }}"     caption="Course"     {{ --:items="$coursesList"-- }} use-caption required />
                        <x-flat-select as="{{ 'input-year-level' }}" caption="Year Level" {{ --:items="$yearLevels" -- }} use-caption required />
                    </div>

                    <x-flat-input  as="{{ 'input-email' }}"     fill="{{ 'Email' }}"        with-caption required />
                    <x-flat-input  as="{{ 'input-contact' }}"   fill="{{ 'Contact No.' }}"  with-caption />
                    <x-flat-input  as="{{ 'input-birthday' }}"  fill="{{ 'Birthday' }}"     with-caption readonly/> --}}
                </div>
            </div>
            {{-- WILL BE USED TO TRACK FORM ACTIONS SUCH AS EDIT CREATE --}}
            <input type="text" name="form-action" id="form-action" class="d-none" value="{{ $errors->any() ? old('form-action', '0') : '0'  }}">
            
            {{-- WILL BE USED DURING UPDATE --}}
            <input type="text" name="student-key" id="student-key" class="d-none" value="{{ old('student-key') }}">
        </div>
    </x-slot>
</x-modal-form-md>
{{-- END STUDENT FORM MODAL --}}

<div class="content-wrapper py-3">

    @include('layouts.backoffice.sidebar')

    <main class="main-content overflow-y-auto ps-2 no-native-scroll" data-simplebar>

        @include('layouts.backoffice.header')

        <div class="container-fluid p-4">

            <div class="row">
                <div class="col mb-4 align-items-center d-flex">
                    <x-flat-pager-length class="pagination-length-control"/>
                </div>
                <div class="col">
                    <x-flat-worksheet-tabs leading-label="Show" trailing-label="records">
                        <x-slot name="navItems">
                            <x-flat-worksheet-tabs-item to="{{ $worksheetTabRoutes['today'] }}"   text="Today"/>
                            <x-flat-worksheet-tabs-item to="{{ $worksheetTabRoutes['weekly'] }}" text="This Week"/>
                            <x-flat-worksheet-tabs-item text="This Month" current/>
                            <x-flat-worksheet-tabs-item to="{{ $worksheetTabRoutes['alltime'] }}" text="All Time"/>
                        </x-slot>
                    </x-flat-worksheet-tabs> 
                </div>
                <div class="col mb-4 align-items-center d-flex justify-content-end px-3 gap-2">
                    <x-flat-button as="btn-browse-record" theme="default" text="Browse" icon="fa-folder" url="{{ $backPage }}"/>
                    <x-flat-button as="btn-add-record" theme="primary" text="Add" icon="fa-plus"/>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card mb-4 table-card">
                        <div class="card-header pb-0">
                            
                            <div class="d-flex flex-row align-items-center gap-2 mb-3">
                                <h6 class="card-title mb-0">{{ "College Attendance" }}</h6>
                                <div class="attendance-calendar text-sm px-3 me-auto">
                                    <i class="fas fa-calendar-days me-2"></i> {{ "$totalRecords Records" }}
                                </div>
                                <div class="d-inline-flex align-items-center gap-2">
                                    <div class="search-bar px-2">
                                        <button class="search-button">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <input type="text" name="q" id="search-input" placeholder="Search"
                                            autocomplete="off" value="" maxlength="32">
                                    </div>
                                    @php
                                        $filterItems = [
                                            'Action' => 'Action',
                                            'Another action' => '#',
                                            'Something else here' => '#',
                                        ];
                                    @endphp
                                    <x-flat-select as="{{ 'search-filter' }}" :items="$filterItems" text="Filter" drop-arrow="none" use-icon="fa-filter"/>
                                    <x-flat-select as="{{ 'sort-filter' }}"   text="Sort"   drop-arrow="none" use-icon="fa-arrow-down-a-z"/>
                                    <x-flat-select as="{{ 'sort-filter' }}"   no-text       drop-arrow="none" use-icon="fa-ellipsis-vertical"/>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="text-center month-indicator d-flex align-items-center justify-content-center rounded-2 gap-2 my-2 p-2 flat-color-control mx-3">
                                <i class="fa-solid fa-caret-right opacity-75 small"></i>
                                <p class="m-0 small opacity-85">{{ 'This Month' }}</p>
                                <p class="m-0 small opacity-85">
                                    <i>{{ '( '. $monthHint['monthStart'] . " --to-- " . $monthHint['monthEnd'] . ' )' }}</i>
                                </p>
                                <i class="fa-solid fa-caret-left opacity-75 small"></i>
                            </div>
                            <table
                                class="table table-sm table-fixed table-striped table-hover align-middle bg-white attendance-table">
                                <thead>
                                    <tr>
                                        <th data-orderable="false" class="text-xs text-uppercase text-center fixed-short-column-60 px-1">{{ "No." }}</th>
                                        <th data-orderable="false" class="text-xs text-uppercase">{{ "Student" }}</th>
                                        <th data-orderable="false" class="text-xs text-uppercase text-center fixed-short-column-60 px-1">{{ "Grade"}}</th>
                                        <th data-orderable="false" class="text-xs text-uppercase text-center fixed-medium-column-120">{{ "Time In"}}</th>
                                        <th data-orderable="false" class="text-xs text-uppercase text-center fixed-medium-column-120">{{"Time Out" }}</th>
                                        <th data-orderable="false" class="text-xs text-uppercase text-center fixed-medium-column-120">{{"Duration" }}</th>
                                        {{-- <th data-orderable="false" class="text-xs text-uppercase text-center fixed-short-column-80 px-1">{{ "Status"}}</th> --}}
                                        <th data-orderable="false" class="text-xs text-uppercase text-center fixed-medium-column-120">{{ "Date"}}</th>
                                        <th data-orderable="false" class="text-xs text-uppercase text-center fixed-medium-column-140">{{"Action" }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if (!empty($attendanceDataset))

                                        @foreach ($attendanceDataset as $row)

                                        {{-- @php
                                            $dataTarget = json_encode([
                                                'name' => $row->name,
                                                'key'  => $row->id
                                            ]);
                                        @endphp --}}
                                        <tr>
                                            <td class="fixed-short-column-60 opacity-55 text-center text-truncate px-1">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column justify-content-center text-truncate px-3">
                                                    <h6 class="mb-0 text-sm text-truncate">{{ $row->name }}</h6>
                                                    <p class="mb-0 text-secondary text-xs">{{ $row->student_no }}</p>
                                                </div>
                                            </td>
                                            <td class="text-center opacity-75 fixed-short-column-60 px-1">
                                                <span class="fw-600">{{ $row->grade_level }}</span>
                                            </td>
                                            </td>
                                            <td class="text-center opacity-75 text-truncate">{{ $row->time_in }}</td>
                                            <td class="text-center opacity-75 text-truncate">{{ $row->time_out }}</td>
                                            <td class="text-center opacity-85 text-truncate fixed-medium-column-120">{{ $row->duration }}</td>
                                            <td class="text-center fixed-medium-column-120 px-1">
                                                {{ $row->created_at }}
                                            </td>
                                            <td class="text-center fixed-medium-column-140">
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
                                                    <textarea class="data-target d-none">{{-- $dataTarget --}}</textarea>
                                                    <textarea class="row-data d-none">{{-- $row->rowData --}}</textarea>
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

            @include('layouts.backoffice.footer')

        </div>
    </main>
</div>
<div class="d-none actions">
    <textarea id="flash-message">
        @if (Session::has('flash-message'))
            {{ Session::get('flash-message') }}
        @endif
    </textarea>
    <form action="{{-- $formActions['deleteStudent'] --}}" method="post" id="deleteform">
        @csrf
        <input type="text" name="student-key" id="student-key">
    </form>
</div>

@endsection

@push('scripts')
<script src="{{ asset('extensions/datatables/datatables.min.js') }}"></script>
<script type="module" src="{{ asset('js/backoffice/attendance/common.js') }}"></script>
{{-- <script type="module" src="{{ asset('js/backoffice/students/college-students.js') }}"></script> --}}
<script src="{{ asset('js/utils.js') }}"></script>
@endpush