@extends('layouts.backoffice.master')

@section('title')
    {{ "Dashboard" }}
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/overrides/datatables.css') }}">
@endpush

@section('content')
    <div class="content-wrapper p-3">

        @include('layouts.backoffice.sidebar')

    </div>
@endsection