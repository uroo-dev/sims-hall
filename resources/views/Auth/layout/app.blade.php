<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal SIMS - SMK NEGERI 2 KARANGANYAR')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logosmkk.png') }}">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Subtle floating animation for badge and icons */
        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-10px) rotate(2deg);
            }
        }

        @keyframes waveMove {
            0% {
                transform: translateX(0) translateZ(0) scaleY(1);
            }
            50% {
                transform: translateX(-25px) translateZ(0) scaleY(1.05);
            }
            100% {
                transform: translateX(0) translateZ(0) scaleY(1);
            }
        }

        .animate-float {
            animation: float 5s ease-in-out infinite;
        }

        .animate-wave-slow {
            animation: waveMove 8s ease-in-out infinite;
        }

        .animate-wave-fast {
            animation: waveMove 5s ease-in-out infinite;
        }

        /* Grid dot pattern background */
        .bg-grid-pattern {
            background-image: radial-gradient(rgba(255, 255, 255, 0.2) 1.5px, transparent 1.5px);
            background-size: 16px 16px;
        }

        /* School watermark silhouette simulation */
        .school-watermark {
            background-image: linear-gradient(to bottom, rgba(2, 84, 168, 0.75), rgba(2, 60, 125, 0.95)),
                url('https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
        }

        /* Curved transition divider */
        .wave-divider {
            filter: drop-shadow(6px 0px 10px rgba(0, 0, 0, 0.15));
        }
    </style>

    @stack('styles')
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center p-3 sm:p-6 md:p-10 select-none">

    @yield('content')

    <script>
        const yearEl = document.getElementById('year');
        if (yearEl) {
            yearEl.textContent = new Date().getFullYear();
        }
    </script>

    @stack('scripts')
</body>

</html>