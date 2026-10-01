<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SMK Negeri 2 Karanganyar - Sekolah Pusat Keunggulan')</title>
    <meta name="description" content="@yield('description', 'SMK Negeri 2 Karanganyar adalah Sekolah Menengah Kejuruan favorit di Kabupaten Karanganyar yang berpendidikan karakter, berwawasan, disiplin, tanggung jawab, dan bermoral baik.')">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Awesome Icons CDN --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Google Fonts: Plus Jakarta Sans & Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            // Flat palette (halaman publik)
                            blue: '#0066C4',
                            darkBlue: '#0052A3',
                            lightBlue: '#E6F0FA',
                            red: '#FF4D4D',
                            yellow: '#FFC107',
                            green: '#28A745',
                            accent: '#0284C7',
                            // Numeric scale (halaman admin)
                            50: '#f0f7ff',
                            100: '#e0effe',
                            500: '#0284c7',
                            600: '#0060ac',
                            700: '#004f8f',
                            800: '#003e73',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom decorative dot pattern background */
        .dot-pattern {
            background-image: radial-gradient(#0066C4 1.5px, transparent 1.5px);
            background-size: 12px 12px;
        }

        /* Smooth shadow presets */
        .card-shadow {
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.08);
        }

        .floating-button-shadow {
            box-shadow: 0 8px 25px rgba(0, 102, 196, 0.3);
        }

        /* Profil */
        .text-justify-custom {
            text-align: justify;
            text-justify: inter-word;
        }

        /* Layanan Peminjaman - kalender */
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.5rem;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .calendar-day:hover {
            background-color: #f1f5f9;
        }

        .calendar-day.active {
            background-color: #0066C4;
            color: white;
            font-weight: 700;
        }

        .calendar-day.disabled {
            color: #cbd5e1;
            cursor: not-allowed;
        }
    </style>

    @stack('head')
</head>

<body
    class="bg-[#F8FAFC] text-slate-800 font-sans antialiased overflow-x-hidden selection:bg-brand-blue selection:text-white">
