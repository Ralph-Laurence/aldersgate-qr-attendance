@php
    use App\Http\Extensions\Utils;
@endphp

@extends('layouts.backoffice.master')

@section('title')
    {{ "Students" }}
@endsection
 
@section('content')
    <div class="content-wrapper py-3">
 
        @include('layouts.backoffice.sidebar')

        <main class="main-content overflow-y-auto ps-2 no-native-scroll" data-simplebar>
            
            @include('layouts.backoffice.header')

            <div class="container-fluid p-4">
               
                <div class="row">
                    <div class="col">
                        <div class="card mb-4 table-card">
                            <div class="card-header pb-0">
                                <h6 class="card-title">{{ "All Students" }}</h6>
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <table class="table table-sm table-fixed table-striped align-middle bg-white students-table">
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
    <script src="{{ asset('js/backoffice/students.js') }}"></script>
@endpush