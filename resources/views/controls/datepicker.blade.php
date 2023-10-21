@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/controls/datepicker.css') }}">
    @endpush
@endonce
<div class="m-datepicker-overlay">
    <div class="m-datepicker">
        <div class="m-datepicker-header">
            <small class="small-year-title fw-600">{{ "SELECT DATE" }}</small>
            <div class="m-date-picker-status-wrapper">
                <p class="m-datepicker-status">
                    <span>{{-- DAY NAME --}}</span>,
                    <span>{{-- MONTH, YEAR --}}</span>
                </p>
                <button>
                    {{-- <img src="icons/edit.png" alt="pencil"> --}}
                    <i class="fas fa-calendar text-white"></i>
                </button>
            </div>
        </div>
        <div class="m-datepicker-body">
        </div>
        <div class="m-datepicker-footer">
            <button class="m-datepicker-cancel text-uppercase">{{ 'Cancel' }}</button>
            <button class="m-datepicker-ok text-uppercase">{{ 'Ok' }}</button>
        </div>
    </div>
</div>
@once
    @push('scripts')
        <script src="{{ asset('js/controls/datepicker.js') }}"></script>
    @endpush    
@endonce