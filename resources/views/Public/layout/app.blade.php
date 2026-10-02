<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIM Sarpras SMK N 2 Kra')</title>
    <meta name="description" content="@yield('description', 'Produk unggulan dan layanan SMK Negeri 2 Karanganyar.')">

    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
    @stack('styles')
</head>
<body class="bg-[#F8FAFC] text-slate-800 font-jakarta antialiased overflow-x-hidden selection:bg-brand-blue selection:text-white">

    @include('Public.layout.header')

    <main>
        @yield('content')
    </main>

    @include('Public.layout.footer')

    @include('Public.layout.widget')

    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>
