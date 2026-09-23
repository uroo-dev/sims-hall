<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin - SMK Negeri 2 Karanganyar')</title>

    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
    @stack('styles')
</head>
<body class="min-h-screen text-slate-800 flex flex-col bg-[#f3f5f8]">

    @php
        $usePklSidebar = request()->routeIs('dashboard.pkl', 'pklbkk.*');
    @endphp

    <div class="flex h-screen w-full overflow-hidden">
        @hasSection('sidebar')
            @yield('sidebar')
        @else
            @include($usePklSidebar ? 'Admin.layout.sidebar-pklbkk' : 'Admin.layout.sidebar')
        @endif

        @yield('content')
    </div>

    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>
