@php
use \App\Http\Extensions\Routes; 

$routeItems = 
[
    ['wildcard' => 'backoffice.dashboard',      'icon' => 'fa-square-poll-vertical',    'text' => 'Dashboard',      'target' => route(Routes::DASHBOARD['index'])],
    ['wildcard' => 'backoffice.attendance',     'icon' => 'fa-calendar-check',          'text' => 'Attendance',     'target' => route(Routes::ATTENDANCE['index'])],
    ['wildcard' => 'backoffice.students',       'icon' => 'fa-user-graduate',           'text' => 'Students',       'target' => route(Routes::ELEM_STUDENT['index'])],
    ['wildcard' => 'backoffice.users',          'icon' => 'fa-user-shield',             'text' => 'Users',          'target' => route(Routes::MASTER_USERS['index'])],
    ['wildcard' => 'backoffice.myprofile',      'icon' => 'fa-circle-user',             'text' => 'My Profile',     'target' => ''],
    ['wildcard' => 'backoffice.settings',       'icon' => 'fa-wrench',                  'text' => 'Settings',       'target' => ''],
];

@endphp
<div class="sidebar ms-3 pb-3 -border-radius-xl">

    {{-- SIDEBAR BRAND HEADER --}}
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <img src="{{ asset('img/sidebar_logo.png') }}" alt="logo" width="32" height="32" loading="lazy">
            <span class="ms-1 brand-text">{{ "Library Attendance" }}</span>
        </div>
    </div>

    <hr class="separator mt-0">

    {{-- SIDEBAR LINKS --}}
    <div class="sidebar-links no-native-scroll" data-simplebar>

        <ul class="navbar-nav">

            @foreach ($routeItems as $obj)

                @php
                    $wildcard = $obj['wildcard'];
                    $isActive = Request::routeIs("$wildcard.*") ? 'active' : '';
                @endphp
                <li class="nav-item {{ $isActive }}">
                    <a href="{{ $obj['target'] }}" class="nav-link">
                        <div class="icon me-2 shadow center-flex">
                            <i class="fa-solid {{ $obj['icon'] }}"></i>
                        </div>
                        <span class="nav-link-text ms-1">{{ $obj['text'] }}</span>
                    </a>
                </li>
            @endforeach
            
        </ul>
    </div>

    {{-- COLLAPSE CONTROL --}}
    <div class="sidebar-collapse px-3">
        <button class="btn btn-dark btn-collapse-sidebar">
            <i class="fa-solid fa-outdent"></i>
        </button>
    </div>
</div>

{{-- FLOATING SIDEBAR SHOW BUTTON --}}
<div class="floating-sidebar-show">
    <button class="btn btn-show-sidebar">
        <i class="fas fa-indent"></i>
    </button>
</div>

@push('scripts')
    <script>
        $(function() 
        {
            var hideButton = $('.btn-collapse-sidebar');
            var showButton = $('.floating-sidebar-show');

            $(document).on('click', '.btn-collapse-sidebar', function()
            {
                hideSidebar(hideButton, showButton);
            })
            .on('click', '.btn-show-sidebar', function()
            {
                showSidebar(hideButton, showButton);
            });
        });

        function hideSidebar(hideButton, showButton) 
        {
            hideButton.hide();

            $('.sidebar').animate({
                minWidth: '0px', 
                width: '0px', 
                left: '-250px' 
            }, 100, function() { 
                showButton.fadeIn('fast');
            });
        }

        function showSidebar(hideButton, showButton) 
        {
            showButton.hide();

            $('.sidebar').animate({
                minWidth: '250px', 
                width: '250px', 
                left: '0px' 
            }, 100, function() {
                hideButton.fadeIn('fast');
            });
        }
    </script>
@endpush