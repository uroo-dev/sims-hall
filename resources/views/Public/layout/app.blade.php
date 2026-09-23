<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIM Sarpras SMK N 2 Kra')</title>

    <!-- Google Fonts: Poppins, Inter, Montserrat, Nunito, Public Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@600;700&family=Nunito:wght@500;700&family=Public+Sans:wght@600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
    @stack('styles')
</head>
<body>
    <header>
        @include('Public.layout.header')
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        @include('Public.layout.footer')
    </footer>

    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>