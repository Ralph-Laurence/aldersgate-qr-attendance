@extends('layouts.backoffice.master')

@section('content')
<!-- Button trigger modal -->
<x-flat-time-picker as="myTimePicker"/>
<!-- Modal -->
{{-- @php
                $timeString = "12:00 PM";
                $time = \Carbon\Carbon::parse($timeString);
                echo $time->format('H:i:s a');
                @endphp --}}
@endsection

@push('scripts')
    <script type="module">
        $(() => 
        {
            window.timePicker = new FlatTimePicker('#myTimePicker');
        });
    </script>
@endpush