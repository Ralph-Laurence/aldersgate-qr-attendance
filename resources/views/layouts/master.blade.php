<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/master.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/controls.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/lib/mdb/mdb.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/fontawesome/css/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/lib/fontawesome/css/brands.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/lib/fontawesome/css/solid.min.css') }}" />
    @yield("styles")
    <title>{{ 'Library Attendance' }}</title>
</head>

<body class="antialiased">
    <div class="wrapper d-flex flex-column w-100 h-100 base-bg-color">
        @include('layouts.header')
        <div class="master-content">
            @yield('content')
            @if (empty($footerBehaviour))
                @include('layouts.footer')
            @endif

        </div>
        @if (isset($footerBehaviour) && $footerBehaviour == 'floating')
            @include('layouts.footer-floating')
        @endif
    </div>
    <script src="{{ asset('js/lib/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/lib/mdb/mdb.min.js') }}"></script>

    @yield("scripts")
</body>

</html>
