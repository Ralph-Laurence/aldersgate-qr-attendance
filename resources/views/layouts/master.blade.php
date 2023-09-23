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
    @stack("styles")
    <title>{{ 'Library Attendance' }}</title>
</head>

<body class="antialiased">

    {{-- MAIN WRAPPER --}}
    <div class="wrapper d-flex flex-column w-100 h-100 base-bg-color">

        {{-- MASTER HEADER --}}
        @include('layouts.header')

        {{-- MASTER CONTENT --}}
        @yield('content')

        {{-- STICKY FOOTER --}}
        @if (isset($footerBehaviour) && $footerBehaviour == 'sticky')
            <footer class="bg-light text-center text-lg-start master-footer fixed-bottom opacity-10">
        @else
        {{-- FOOTER THAT SCROLLS ALONG CONTENT --}}
            <footer class="bg-light text-center text-lg-start master-footer">
        @endif
            <!-- Copyright -->
            <div class="text-center p-2" style="background-color: rgba(0, 0, 0, 0.15);">
                {{ '© Aldersgate College Incorporated ' . date('Y') }}
            </div>
            <!-- Copyright -->
        </footer>

    </div>


    <script src="{{ asset('js/lib/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/lib/mdb/mdb.min.js') }}"></script>

    @stack("scripts")
</body>

</html>