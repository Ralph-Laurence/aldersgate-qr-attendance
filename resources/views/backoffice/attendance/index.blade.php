@extends('layouts.backoffice.master')

@section('title')
    {{ "Attendance" }}
@endsection

@section('content')
    <div class="content-wrapper py-3">
 
        @include('layouts.backoffice.sidebar')

        <main class="main-content overflow-y-auto ps-2" data-simplebar>
            
            @include('layouts.backoffice.header')
            
            {{-- <div class="container-fluid ms-2 py-4">
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6 class="card-title">{{ "Attendance Sheet" }}</h6>
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="container-fluid p-4">
                <div class="row">
                    <div class="col">
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6 class="card-title">{{ "Daily Attendance" }}</h6>
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <table class="table table-sm align-middle bg-white">
                                    <thead>
                                        <tr>
                                            <th class="text-xs text-uppercase">{{ "Student" }}</th>
                                            <th class="text-xs text-uppercase">H2</th>
                                            <th class="text-xs text-uppercase">H3</th>
                                            <th class="text-xs text-uppercase">H4</th>
                                            <th class="text-xs text-uppercase">H5</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        {{-- FOOTER: © 2023, crafted with dedication for smarter library management. --}}
    </div>
@endsection