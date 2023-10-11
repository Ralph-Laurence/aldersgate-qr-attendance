@php
use \App\Http\Extensions\RouteNames as route; 
 
//
// These are sidebar route markers having routenames as keys. The values are initially
// empty and we will set atleast one of them as "Active" when each of the keys matched 
// the current route. Thus, a link item will be active
//
$routeMarkers = 
[
    route::DASHBOARD    => '',
    route::ATTENDANCE   => '',
    route::STUDENTS     => '',
    route::USERS        => '',
    route::SETTINGS     => ''
];

// Get the current route name
$currentRoute = \Request::route()->getName();

// Process each route markers. Set each of them as "active"
// if it matches the current route
foreach ($routeMarkers as $key => $value) 
{
    if ($key == $currentRoute)
        $routeMarkers[$key] = 'active';
}

@endphp
<div class="sidebar ms-3 pb-3 -border-radius-xl">

    {{-- SIDEBAR BRAND HEADER --}}
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <img src="{{ asset('img/aldersgate.svg') }}" alt="logo" width="32" height="32">
            <span class="ms-1 brand-text">{{ "Library Attendance" }}</span>
        </div>
    </div>

    <hr class="separator mt-0">

    {{-- SIDEBAR LINKS --}}
    <div class="sidebar-links no-native-scroll" data-simplebar>

        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="{{ route(route::DASHBOARD) }}" class="nav-link {{ $routeMarkers[route::DASHBOARD] }}">
                    <div class="icon me-2 shadow center-flex">
                        <i class="fa-solid fa-square-poll-vertical"></i>
                    </div>
                    <span class="nav-link-text ms-1">{{ "Dashboard" }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route(route::ATTENDANCE) }}" class="nav-link {{ $routeMarkers[route::ATTENDANCE] }}">
                    <div class="icon me-2 shadow center-flex">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <span class="nav-link-text ms-1">{{ "Attendance" }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route(route::STUDENTS) }}" class="nav-link {{ $routeMarkers[route::STUDENTS] }}">
                    <div class="icon me-2 shadow center-flex">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                    <span class="nav-link-text ms-1">{{ "Students" }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link">
                    <div class="icon me-2 shadow center-flex">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <span class="nav-link-text ms-1">{{ "Librarians" }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link">
                    <div class="icon me-2 shadow center-flex">
                        <i class="fa-solid fa-user-secret"></i>
                    </div>
                    <span class="nav-link-text ms-1">{{ "Administrators" }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link">
                    <div class="icon me-2 shadow center-flex">
                        <i class="fa-solid fa-circle-user"></i>
                    </div>
                    <span class="nav-link-text ms-1">{{ "My Profile" }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link">
                    <div class="icon me-2 shadow center-flex">
                        <i class="fa-solid fa-wrench"></i>
                    </div>
                    <span class="nav-link-text ms-1">{{ "Settings" }}</span>
                </a>
            </li>
        </ul>
    </div>
</div>