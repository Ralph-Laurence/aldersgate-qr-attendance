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
    <link rel="stylesheet" href="{{ asset('css/lib/fontawesome/css/brands.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/jquery-ui/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/jquery-ui/jquery-ui.structure.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/jquery-ui/jquery-ui.theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/simplebar/simplebar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/backoffice.css') }}">
    
    {{-- CHILD VIEW STYLES --}}
    @stack('styles')

</head>
<body class=":antialiased">

    @yield('content')

    {{-- FRAMEWORK SPECIFIC SCRIPTS --}}
    <script src="{{ asset('js/lib/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/lib/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/lib/momentjs/moment-with-locales.js') }}"></script>
    <script src="{{ asset('js/lib/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('js/lib/mdb/mdb.min.js') }}"></script>

    {{-- CHILD VIEW SCRIPTS --}}
    @stack('scripts')

</body>
</html>