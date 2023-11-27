@extends('layouts.backoffice.master')

@section('content')
<x-flat-date-picker as="input-date"/>
@endsection

@push('scripts')
{{-- <script type="module" src="{{ asset('js/components/flat-date-picker.js') }}"></script> --}}
<script type="module">
import { FlatDatePicker } from "{{ asset('js/components/flat-date-picker.js') }}"
window.datePicker = new FlatDatePicker("#input-date")
</script>
@endpush