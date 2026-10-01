<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN - Dashboard SMKN 2 Karanganyar</title>
    <link rel="icon" type="image/x-icon" href="assets/logosmkk.png">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js untuk grafik batang -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f7ff',
                            100: '#e0effe',
                            500: '#0284c7',
                            600: '#0060ac', // primary dark blue from screenshot
                            700: '#004f8f',
                            800: '#003e73',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }

        /* Custom smooth scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        /* Custom card shadow matching reference */
        .card-shadow {
            box-shadow: 0 4px 15px -2px rgba(0, 0, 0, 0.05), 0 2px 6px -2px rgba(0, 0, 0, 0.03);
        }
    </style>

    @stack('styles')
</head>

<body class="text-gray-800 antialiased min-h-screen bg-[#f1f3f6]">

    <div class="flex min-h-screen relative">

        <!-- MOBILE BACKDROP -->
        <div id="sidebarBackdrop" onclick="toggleSidebar()"
            class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity"></div>

        <!-- SIDEBAR (menu role-aware dari config/menu.php) -->
        @include('Admin.layout.sidebar')

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 lg:ml-[270px] p-3 md:p-6 space-y-5 max-w-[1600px]">
            @include('Admin.layout.header')
            
            @yield('content')

            @include('Admin.layout.footer')
        </main>
    </div>

    <!-- MODAL STACK -->
    @stack('modals')

    @vite(['resources/js/app.js'])

    <script>
        // Sidebar drawer toggle for mobile devices
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');

            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

        // Simulated Logout button handler
        function handleLogout() {
            const confirmLogout = confirm("Apakah Anda yakin ingin keluar dari sistem admin?");
            if (confirmLogout) {
                alert("Anda telah berhasil logout.");
            }
        }

        // Inisialisasi grafik batang
        window.onload = function () {
            const canvas = document.getElementById('aulaChart');

            // Halaman di luar modul aula (mis. PKL & BKK) tidak punya canvas
            // ini. Tanpa guard di bawah, window.onload melempar TypeError dan
            // mematikan script lain yang bergantung padanya.
            if (!canvas) return;

            const ctx = canvas.getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Terjangkau', 'Standar 1', 'Standar 2', 'Standar 3', 'Unggulan'],
                    datasets: [{
                        label: 'Jumlah Peminjaman',
                        data: [9, 10, 8, 16, 18],
                        backgroundColor: '#6ee7b7', // exact light emerald green bar color from screenshot
                        hoverBackgroundColor: '#34d399',
                        borderRadius: 4,
                        barThickness: 28,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return `Total: ${context.raw} Peminjaman`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 10,
                                    family: "'Inter', sans-serif"
                                },
                                color: '#374151'
                            }
                        },
                        y: {
                            min: 0,
                            max: 18,
                            ticks: {
                                stepSize: 2,
                                font: {
                                    size: 10,
                                    family: "'Inter', sans-serif"
                                },
                                color: '#4b5563'
                            },
                            grid: {
                                color: '#f3f4f6'
                            }
                        }
                    }
                }
            });
        };
    </script>

    @stack('scripts')
</body>

</html>