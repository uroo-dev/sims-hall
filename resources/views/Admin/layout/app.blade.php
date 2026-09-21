<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin | SIM Sarpras SMK N 2 Kra')</title>
    @vite(['resources/css/app.css'])
    @stack('styles')
</head>
<body>
    <div class="admin-layout">
        @include('Admin.layout.sidebar')

        <div class="admin-main">
            @include('Admin.layout.header')

            <main>
                @yield('content')
            </main>

            @include('Admin.layout.footer')
        </div>
    </div>

    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>
