@extends('layouts.backoffice.master')

@section('title')
{{ "Users" }}
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
<x-modal-form-md id="crudFormModal" title="User Form" method="POST" action="" title-create="Add new user"
    title-edit="Edit user">
    <x-slot name="formInner">
        <div class="container-fluid mb-3">
            <div class="d-none">
                <textarea id="form-action-store">{{ $formActions['storeUser'] }}</textarea>
                <textarea id="form-action-update">{{ $formActions['updateUser'] }}</textarea>
            </div>
            <div class="mb-2">
                <i class="fas fa-info-circle me-1"></i>
                <small>{{ "Please fill out the fields marked with an asterisk (*) as they are required." }}</small>
            </div>
            <div class="row">
                <div class="col-5">
                    <x-flat-input as="input-fname" fill="Firstname"  with-caption   required />
                    <x-flat-input as="input-mname" fill="Middlename" with-caption />
                    <x-flat-input as="input-lname" fill="Lastname"   with-caption   required />
                    <x-flat-input as="input-uname" fill="Username"   with-caption   required />
                    <x-flat-input as="input-email" fill="Email"      with-caption   required />
                </div>
                <div class="col-7 border-start">
                    <div class="small my-1 fw-600 text-flat-primary-dark">
                        <i class="fas fa-lock me-1"></i>
                        {{ "User Account Control" }}
                    </div>
                    @push('styles')
                        <style>
                            .popover {
                                width: 300px !important;
                            }
                        </style>
                    @endpush
                    @push('scripts')
                        <script>
                            $(() => 
                            {
                                const exampleEl = document.getElementById('uac-popover');
                                const options = 
                                {
                                  html: true,
                                  title: 'Permission Levels',
                                  content: 
                                    `<div class="container-fluid">
                                        <div class="row mb-1">
                                            <div class="col-4 px-1">
                                                <div class="perm-legend perm-legend-full small">
                                                    <i class="fas fa-crown me-1"></i>Full
                                                </div>
                                            </div>
                                            <div class="col small">Full access to every feature.</div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-4 px-1">
                                                <div class="perm-legend perm-legend-modify small">
                                                    <i class="fas fa-gear me-1"></i>Modify
                                                </div>
                                            </div>
                                            <div class="col small">Users would be able to read, create, update, and delete data.</div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-4 px-1">
                                                <div class="perm-legend perm-legend-write small">
                                                    <i class="fas fa-pen me-1"></i>Write
                                                </div>
                                            </div>
                                            <div class="col small">Users with write access would be able to create and update data, but not delete it.</div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-4 px-1">
                                                <div class="perm-legend perm-legend-read small">
                                                    <i class="fas fa-bookmark me-1"></i>Read
                                                </div>
                                            </div>
                                            <div class="col small">Users with read-only access would be able to read data, but not create, update, or delete it.</div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-4 px-1">
                                                <div class="perm-legend perm-legend-denied small">
                                                    <i class="fas fa-ban me-1"></i>Denied
                                                </div>
                                            </div>
                                            <div class="col small">Users with no access would not be able to perform any operations.</div>
                                        </div>
                                    </div>`,
                                  trigger: 'hover'
                                }
                                window.popover = new mdb.Popover(exampleEl, options);
                            });
                        </script>
                    @endpush
                    <div class="small opacity-75 uac-tip-text">
                        <span class="me-4">
                            {{ "User Account Control is a security feature that protects the system from unauthorized changes. Make sure to assign the appropriate permissions." }}
                        </span> 
                        <a role="button" id="uac-popover" class="underline">
                            <i class="fas fa-info-circle me-1"></i>
                            <i>{{ 'Learn more' }}</i>
                        </a>
                    </div>
                    <div class="perms-selector d-flex flex-column gap-1">
                        <x-flat-perms-select as="perm-students"   caption="Manage students"   stretch-width="true" level="2"/>
                        <x-flat-perms-select as="perm-attendance" caption="Manage attendance" stretch-width="true" level="2"/>    
                        <x-flat-perms-select as="perm-users"      caption="Manage users"      stretch-width="true" level="2"/>
                        <x-flat-perms-select as="perm-advanced"   caption="Advanced settings" stretch-width="true" level="-1"/>    
                    </div>
                </div>
            </div>
            {{-- WILL BE USED TO TRACK FORM ACTIONS SUCH AS EDIT CREATE --}}
            <input type="text" name="form-action" id="form-action" class="d-none"
                value="{{ $errors->any() ? old('form-action', '0') : '0'  }}">

            {{-- WILL BE USED DURING UPDATE --}}
            <input type="text" name="user-key" id="user-key" class="d-none" value="{{ old('user-key') }}">
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
                    <x-flat-pager-length class="pagination-length-control" />
                </div>
                <div class="col">
                    <x-flat-worksheet-tabs leading-label="Show" trailing-label="users">
                        <x-slot name="navItems">
                            <x-flat-worksheet-tabs-item to="{{ $worksheetTabRoutes['librarians'] }}" text="Librarians" />
                            <x-flat-worksheet-tabs-item text="Moderators" current />
                            <x-flat-worksheet-tabs-item to="{{ $worksheetTabRoutes['master'] }}"  text="Master" />
                        </x-slot>
                    </x-flat-worksheet-tabs>
                </div>
                <div class="col mb-4 align-items-center d-flex justify-content-end px-3">
                    <x-flat-button as="btn-add-record" theme="primary" text="Add" icon="fa-plus"/>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card mb-4 table-card">
                        <div class="card-header pb-0">

                            <div class="d-flex flex-row align-items-center gap-2 mb-3">
                                <h6 class="card-title mb-0">{{ "Moderators" }}</h6>
                                <div class="attendance-calendar text-sm px-3 me-auto">
                                    <i class="fas fa-user-shield me-2"></i> {{ "$totalRecords Total Records" }}
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
                                    <x-flat-select as="{{ 'search-filter' }}" :items="$filterItems" text="Filter"
                                        drop-arrow="none" use-icon="fa-filter" />
                                    <x-flat-select as="{{ 'sort-filter' }}" text="Sort" drop-arrow="none"
                                        use-icon="fa-arrow-down-a-z" />
                                    <x-flat-select as="{{ 'sort-filter' }}" no-text drop-arrow="none"
                                        use-icon="fa-ellipsis-vertical" />

                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <table
                                class="table table-sm table-fixed table-striped table-hover align-middle bg-white users-table">
                                <thead>
                                    <tr>
                                        <th data-orderable="false" class="text-xs text-uppercase fixed-long-column-300">
                                            {{ "Name" }}
                                        </th>
                                        <th data-orderable="false" class="text-xs text-uppercase text-center">{{
                                            "Username"}}</th>
                                        <th data-orderable="false"
                                            class="text-xs text-uppercase text-center fixed-medium-column-200">{{"Email"
                                            }}</th>
                                        <th data-orderable="false"
                                            class="text-xs text-uppercase text-center fixed-medium-column-120">{{
                                            "Access"}}</th>
                                        <th data-orderable="false"
                                            class="text-xs text-uppercase text-center fixed-medium-column-120">
                                            {{"Status" }}</th>
                                        <th data-orderable="false" class="text-xs text-uppercase text-center">{{"Action"
                                            }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @if (!empty($usersDataset))

                                    @php
                                        $tdLatestMarker = Session::has('withRecent') ? 'td-latest' : '';
                                    @endphp
                                    @foreach ($usersDataset as $row)

                                    @php
                                        $dataTarget = json_encode([
                                            'name' => $row->name,
                                            'key' => $row->id
                                        ]);

                                        if (!$loop->first)
                                            $tdLatestMarker = '';
                                    @endphp

                                    <tr>
                                        <td class="fixed-long-column-300 ps-2 {{ $tdLatestMarker }}">
                                            <div class="d-flex align-items-center px-2 py-1">
                                                <div class="avatar-profile me-3 rounded-3 overflow-hidden">
                                                    <img src="{{ $row->photo }}" width="36" height="36"
                                                        loading="lazy" />
                                                </div>
                                                <div class="d-flex flex-column justify-content-center text-truncate">
                                                    <h6 class="mb-0 text-sm text-truncate">{{ $row->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center opacity-75 fixed-medium-column-200">
                                            {{ $row->username }}
                                        </td>
                                        <td class="text-center opacity-75 fixed-medium-column-200">{{ $row->email }}
                                        </td>
                                        <td class="text-center opacity-75 text-truncate">
                                            <span class="badge {{ $row->permBadge['type'] }}">
                                                <i class="fa-solid {{ $row->permBadge['icon'] }} me-1"></i>
                                                {{ $row->permBadge['label'] }}
                                            </span>
                                        </td>
                                        <td class="text-center user-select-none">
                                            <span class="badge {{ $row->statusBadge['type'] }}">
                                                <i class="fa-solid {{ $row->statusBadge['icon'] }} me-1"></i>
                                                {{ $row->statusBadge['label'] }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="center-flex gap-2 record-actions">
                                                @include('backoffice.users.shared.record-action-buttons', ['statusAction' => $row->statusAction])
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
@include('backoffice.users.shared.hidden-actions')

@endsection

@push('scripts')
<script src="{{ asset('extensions/datatables/datatables.min.js') }}"></script>
<script type="module" src="{{ asset('js/backoffice/users/common.js') }}"></script>
<script type="module" src="{{ asset('js/backoffice/users/master.js') }}"></script>
<script src="{{ asset('js/utils.js') }}"></script>
@endpush