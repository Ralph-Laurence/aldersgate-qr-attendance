@extends('layouts.backoffice.master')

@section('title')
{{ "Students" }}
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/backoffice/overrides/datatables.css') }}">
    <style>
        .btn-add-student {
            background-color: var(--flat-color-primary) !important;
        }
        .btn-add-student:hover {

            background-color: var(--flat-color-primary-600) !important;
        }
    </style>
@endpush
 
@section('content')

@once
    @include('modals.message-box')
    @include('controls.datepicker')
    @include('modals.toast')
@endonce

{{-- BEGIN STUDENT FORM MODAL --}}
<x-modal-form-md id="studentFormModal" title="Student Form" method="POST" action="">
    <x-slot name="formInner">
        <div class="container-fluid mb-3">
            <div class="d-none">
                <textarea id="form-action-container-store">{{ $formActions['storeStudent'] }}</textarea>
                <textarea id="form-action-container-update">{{ $formActions['updateStudent'] }}</textarea>
            </div>
            <div class="mb-2">
                <i class="fas fa-info-circle me-1"></i>
                <small>{{ "Please fill out the fields marked with an asterisk (*) as they are required." }}</small>
            </div>
            <div class="row">
                <div class="col">
                    <x-flat-input as="{{ 'input-student-no' }}" fill="{{ 'Student No.' }}"  required with-caption />
                    <x-flat-input as="{{ 'input-fname' }}"      fill="{{ 'Firstname' }}"    required with-caption />
                    <x-flat-input as="{{ 'input-mname' }}"      fill="{{ 'Middlename' }}"   required with-caption />
                    <x-flat-input as="{{ 'input-lname' }}"      fill="{{ 'Lastname' }}"     required with-caption />
                </div>
                <div class="col">

                    <div class="d-flex justify-content-between">
                        <x-flat-select as="{{ 'input-course' }}"     caption="Course"     :items="$coursesList" use-caption required />
                        <x-flat-select as="{{ 'input-year-level' }}" caption="Year Level" :items="$yearLevels"  use-caption required />
                    </div>

                    <x-flat-input  as="{{ 'input-email' }}"     fill="{{ 'Email' }}"        with-caption required />
                    <x-flat-input  as="{{ 'input-contact' }}"   fill="{{ 'Contact No.' }}"  with-caption />
                    <x-flat-input  as="{{ 'input-birthday' }}"  fill="{{ 'Birthday' }}"     with-caption readonly/>
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
                    <x-flat-records-nav leading-label="Show" trailing-label="students">
                        <x-slot name="navItems">
                            <x-flat-records-nav-item to="{{ $recordNavItemRoutes['elementary'] }}" text="Elementary"/>
                            <x-flat-records-nav-item to="http://" text="Juniors"/>
                            <x-flat-records-nav-item text="Seniors"/>
                            <x-flat-records-nav-item text="College" current/>
                        </x-slot>
                    </x-flat-records-nav> 
                </div>
                <div class="col mb-4 align-items-center d-flex justify-content-end px-3">
                    <x-flat-button as="btn-add-student" theme="primary" text="Add" icon="fa-user-graduate"/>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card mb-4 table-card">
                        <div class="card-header pb-0">
                            
                            <div class="d-flex flex-row align-items-center gap-2 mb-3">
                                <h6 class="card-title mb-0">{{ "College Students" }}</h6>
                                <div class="attendance-calendar text-sm px-3 me-auto">
                                    <i class="fas fa-user-graduate me-2"></i> {{ "$totalRecords Total Records" }}
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
                            <table
                                class="table table-sm table-fixed table-striped table-hover align-middle bg-white students-table">
                                <thead>
                                    <tr>
                                        <th data-orderable="false" class="text-xs text-uppercase fixed-long-column-300">
                                            {{ "Student" }}
                                        </th>
                                        <th data-orderable="false" 
                                            class="text-xs text-uppercase text-center fixed-medium-column-120">{{ "Year"}}</th>
                                        <th data-orderable="false"
                                            class="text-xs text-uppercase text-center fixed-medium-column-120">{{"Course" }}</th>
                                        <th data-orderable="false" class="text-xs text-uppercase text-center">{{ "Email"}}</th>
                                        <th data-orderable="false" class="text-xs text-uppercase text-center">{{"Contact#" }}</th>
                                        <th data-orderable="false" class="text-xs text-uppercase text-center">{{"Action" }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if (!empty($studentsDataset))

                                        @foreach ($studentsDataset as $row)

                                        @php
                                            $dataTarget = json_encode([
                                                'name' => $row->name,
                                                'key'  => $row->id
                                            ]);
                                        @endphp

                                        <tr>
                                            <td class="fixed-long-column-300 ps-2">
                                                <div class="d-flex align-items-center px-2 py-1">
                                                    <div class="avatar-profile me-3 rounded-3 overflow-hidden">
                                                        <img src="{{ $row->photo }}" width="36" height="36" loading="lazy" />
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center text-truncate">
                                                        <h6 class="mb-0 text-sm text-truncate">{{ $row->name }}</h6>
                                                        <p class="mb-0 text-secondary text-xs">{{ $row->student_no }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center opacity-75 fixed-medium-column-120">
                                                <span class="fw-600">{{ $row->year }}</span>
                                                <sup class="opacity-65">{{ $row->year_ordinal }}</sup>
                                            </td>
                                            <td class="text-center opacity-75 fixed-medium-column-120">{{ $row->course }}
                                            </td>
                                            <td class="text-center opacity-75 text-truncate">{{ $row->email }}</td>
                                            <td class="text-center opacity-75">{{ $row->contact }}</td>
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
                                                    <textarea class="data-target d-none">{{ $dataTarget }}</textarea>
                                                    <textarea class="row-data d-none">{{ $row->rowData }}</textarea>
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
    <form action="{{ $formActions['deleteStudent'] }}" method="post" id="deleteform">
        @csrf
        <input type="text" name="student-key" id="student-key">
    </form>
</div>

@endsection

@push('scripts')
<script src="{{ asset('extensions/datatables/datatables.min.js') }}"></script>
<script type="module" src="{{ asset('js/backoffice/students/common.js') }}"></script>
<script type="module" src="{{ asset('js/backoffice/students/college-students.js') }}"></script>
<script src="{{ asset('js/utils.js') }}"></script>
@endpush