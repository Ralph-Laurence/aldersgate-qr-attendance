<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Back Office')</title>

    {{-- FRAMEWORK SPECIFIC STYLES --}}
    <link rel="stylesheet" href="{{ asset('css/lib/mdb/mdb.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/fontawesome/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/fontawesome/css/solid.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/lib/fontawesome/css/brands.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/lib/simplebar/simplebar.6.2.5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/flat-colors.css') }}">
    <link rel="stylesheet" href="{{ asset('css/backoffice/backoffice.css') }}">
    <link rel="stylesheet" href="{{ asset('css/backoffice/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/backoffice/overrides/simplebar.css') }}">
    
    {{-- CHILD VIEW STYLES --}}
    @stack('styles')

</head>
<body class=":antialiased">
 
    @yield('content')

    @stack('hidden-layouts')

    {{-- FRAMEWORK SPECIFIC SCRIPTS --}}
    <script src="{{ asset('js/lib/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/lib/momentjs/moment-with-locales.js') }}"></script>
    <script src="{{ asset('js/lib/simplebar/simplebar6.2.5.min.js') }}"></script>
    <script src="{{ asset('js/lib/mdb/mdb.min.js') }}"></script>

    {{-- CHILD VIEW SCRIPTS --}}
    @stack('scripts')

</body>
</html>