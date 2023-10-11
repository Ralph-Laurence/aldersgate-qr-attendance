@php
use \App\Http\Extensions\RouteNames as route;

// Get the current route and split them (called as url segments)
// We will use this for creating breadcrumbs
$routeSegments = \Request::segments();

@endphp
<nav class="navbar navbar-main navbar-expand-lg pt-0 px-0 mx-4 shadow-0 -border-radius-xl">
    <div class="container-fluid pb-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 px-0 me-sm-6 me-5">

            @foreach ($routeSegments as $segment)
                {{-- $url = url(implode('/', array_slice($routeSegments, 0, array_search($segment, $routeSegments) + 1))); --}}
                @if ($segment === end($routeSegments))
                    <li class="breadcrumb-item text-sm active">{{ ucfirst($segment) }}</li>
                @else
                    <li class="breadcrumb-item text-sm opacity-50">{{ ucfirst($segment) }}</li>
                @endif                    
            @endforeach
            </ol>
            {{-- THE PAGE TITLE WILL COME FROM THE URL's END SEGMENT --}}
            <h6 class="mb-0 page-title-segment text-capitalize">{{ end($routeSegments) }}</h6>
        </nav>
        {{-- <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            right content
        </div> --}}
    </div>
</nav>