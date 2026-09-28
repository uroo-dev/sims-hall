<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Portal - SMK NEGERI 2 KARANGANYAR</title>
    <link rel="icon" type="image/x-icon" href="assets/logosmkk.png">
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

            0%,
            100% {
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
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center p-3 sm:p-6 md:p-10 select-none">

    <!-- Main Card Container -->
    <div
        class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[600px] relative">

      @yield('content')
    </div>
    </div>

    <script>
        // Set dynamic current year
        document.getElementById('year').textContent = new Date().getFullYear();

        // Toggle Password Visibility Function
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleCheckbox = document.getElementById('togglePassword');

            if (toggleCheckbox.checked) {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }

        // Handle Login Submission
        function handleLogin(event) {
            event.preventDefault();

            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const alertBox = document.getElementById('alertBox');
            const alertMessage = document.getElementById('alertMessage');
            const submitBtn = document.getElementById('submitBtn');
            const btnSpinner = document.getElementById('btnSpinner');

            // Hide previous alerts
            alertBox.classList.add('hidden');

            if (!usernameInput.value.trim() || !passwordInput.value.trim()) {
                alertMessage.textContent = 'Username dan Password harus diisi!';
                alertBox.classList.remove('hidden');
                return;
            }

            // Simulate loading state
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
            btnSpinner.classList.remove('hidden');

            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-80', 'cursor-not-allowed');
                btnSpinner.classList.add('hidden');

                // Success simulation message
                alertBox.className = 'mb-4 p-3 rounded-xl text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-2 animate-fade-in';
                alertMessage.textContent = `Selamat Datang, ${usernameInput.value}! Mengalihkan ke dashboard...`;
                alertBox.classList.remove('hidden');

                // Clear input after success simulation
                setTimeout(() => {
                    usernameInput.value = '';
                    passwordInput.value = '';
                    document.getElementById('togglePassword').checked = false;
                    passwordInput.type = 'password';
                }, 2000);

            }, 1200);
        }

        // Interactive Button Feedback
        document.getElementById('backBtn').addEventListener('click', () => {
            const alertBox = document.getElementById('alertBox');
            const alertMessage = document.getElementById('alertMessage');
            alertBox.className = 'mb-4 p-3 rounded-xl text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200 flex items-center gap-2';
            alertMessage.textContent = 'Kembali ke Halaman Utama Sekolah.';
            alertBox.classList.remove('hidden');
        });

        document.getElementById('registerLink').addEventListener('click', (e) => {
            e.preventDefault();
            const alertBox = document.getElementById('alertBox');
            const alertMessage = document.getElementById('alertMessage');
            alertBox.className = 'mb-4 p-3 rounded-xl text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-2';
            alertMessage.textContent = 'Halaman pendaftaran akun sedang dibuka...';
            alertBox.classList.remove('hidden');
        });
    </script>
</body>

</html>