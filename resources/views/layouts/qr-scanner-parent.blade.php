<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ "Aldersgate Library Attendance" }}</title>
    <link rel="shortcut icon" href="{{ asset('img/aldersgate.svg') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('css/lib/simplebar/simplebar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/normalize-css-mod/normalize-mod.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/lib/jquery-ui/jquery-ui.min.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/lib/jquery-ui/jquery-ui.structure.min.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/lib/jquery-ui/jquery-ui.theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lib/fontawesome/css/fontawesome.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/lib/fontawesome/css/solid.min.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/lib/fontawesome/css/fontawesome.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/lib/mdb/mdb.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('extensions/datatables/datatables.min.css') }}"> --}}
    @stack('stylesheets')
</head>

<body class=":antialiased">
    
    <!-- MAIN CONTENT -->
    @yield('content')

    <script src="{{ asset('js/lib/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/lib/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/lib/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('js/lib/momentjs/moment-with-locales.js') }}"></script>
    <script src="{{ asset('js/lib/instascan/instascan.min.js') }}"></script>
    <script src="{{ asset('js/lib/mdb/mdb.min.js') }}"></script>
    <script src="{{ asset('extensions/datatables/datatables.min.js') }}"></script>
    @stack('scripts')
</body>

</html>