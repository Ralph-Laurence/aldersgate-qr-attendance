@php
    use App\Http\Extensions\Utils;
@endphp

@extends('layouts.backoffice.master')

@section('title')
    {{ "Students" }}
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/overrides/datatables.css') }}">
@endpush
 
@section('content')

    @include('modals.message-box')
    @include('modals.backoffice.add-edit-student')

    <div class="content-wrapper py-3">
 
        @include('layouts.backoffice.sidebar')

        <main class="main-content overflow-y-auto ps-2 no-native-scroll" data-simplebar>
            
            @include('layouts.backoffice.header')

            <div class="container-fluid p-4">
               
                <div class="row">
                    <div class="col mb-4 align-items-center d-flex">
                        <div class="pagination-length-control d-inline-flex gap-2 bg-white rounded-8 px-3 py-2 text-sm">
                            {{ "Show" }}
                                <div class="dropdown z-100">
                                    <button class="btn btn-page-length" data-mdb-toggle="dropdown" id="pageLengthMenuButton"></button>
                                    <ul class="dropdown-menu user-select-none" aria-labelledby="pageLengthMenuButton">
                                        {{-- Append items here --}}
                                    </ul>
                                </div>
                            {{ "entries" }}
                        </div>
                    </div>
                    <div class="col mb-4 align-items-center d-flex justify-content-end px-3">
                        <button class="btn btn-gradient-primary btn-add-student">
                            <i class="fas fa-user-graduate me-2"></i>
                            {{ "Add" }}
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="card mb-4 table-card">
                            <div class="card-header pb-0">
                                {{-- <h6 class="card-title">{{ "All Students" }}</h6> --}}
                                <div class="d-flex flex-row align-items-center gap-2 mb-3">
                                    <h6 class="card-title mb-0">{{ "All Students" }}</h6>
                                    <div class="attendance-calendar text-sm px-3 me-auto">
                                        <i class="fas fa-user-graduate me-2"></i> {{ "$totalRecords Total Records" }}
                                    </div>
                                    <div class="d-inline-flex align-items-center gap-2">
                                        <div class="search-bar px-2">
                                            <button class="search-button">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <input type="text" name="q" id="search-input" placeholder="Search" autocomplete="off" value="" maxlength="32">
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
                                <table class="table table-sm table-fixed table-striped table-hover align-middle bg-white students-table">
                                    <thead>
                                        <tr>
                                            <th data-orderable="false" class="text-xs text-uppercase fixed-long-column-300">{{ "Student" }}</th>
                                            <th data-orderable="false" class="text-xs text-uppercase text-center fixed-medium-column-120">{{ "Year" }}</th>
                                            <th data-orderable="false" class="text-xs text-uppercase text-center fixed-medium-column-120">{{ "Course" }}</th>
                                            <th data-orderable="false" class="text-xs text-uppercase text-center">{{ "Email" }}</th>
                                            <th data-orderable="false" class="text-xs text-uppercase text-center">{{ "Contact#" }}</th>
                                            <th data-orderable="false" class="text-xs text-uppercase text-center">{{ "Action" }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        @if (!empty($studentsDataset))
                                             
                                            @foreach ($studentsDataset as $row)
 
                                                <tr>
                                                    <td class="fixed-long-column-300 ps-2">
                                                        <div class="d-flex align-items-center px-2 py-1">
                                                            <div class="avatar-profile me-3 rounded-3 overflow-hidden">
                                                                <img src="{{ $row->photo }}" width="36" height="36">
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
                                                    <td class="text-center opacity-75 fixed-medium-column-120">{{ $row->course }}</td>
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
    <div class="lightbox-content-add-student">
        APPEND THIS
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('extensions/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/backoffice/common.js') }}"></script>
    <script src="{{ asset('js/backoffice/students.js') }}" type="module"></script>
@endpush