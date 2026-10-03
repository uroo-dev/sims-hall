<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Akses Ditolak — {{ config('app.name', 'SMK Negeri 2 Karanganyar') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logosmkk.png') }}">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            blue: '#0066C4',
                            darkBlue: '#004385'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-white text-slate-900 font-sans antialiased min-h-screen flex flex-col justify-between selection:bg-slate-900 selection:text-white">

    <!-- HEADER MINIMALIS -->
    <header class="w-full border-b border-slate-100 py-6">
        <div class="max-w-4xl mx-auto px-6 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <img src="{{ asset('assets/logosmkk.png') }}" alt="Logo SMKN 2 Karanganyar" class="w-8 h-8 object-contain">
                <span class="font-extrabold text-sm tracking-tight text-slate-900">SMKN 2 KARANGANYAR</span>
            </a>
            <a href="{{ url('/') }}" class="text-xs font-medium text-slate-500 hover:text-slate-900 transition-colors">
                Kembali ke Beranda &rarr;
            </a>
        </div>
    </header>

    <!-- MAIN EDITORIAL CONTENT (TANPA KOTAK / CARD) -->
    <main class="flex-1 flex items-center py-16">
        <div class="max-w-4xl mx-auto px-6 w-full">
            
            <div class="max-w-xl space-y-8">
                
                <!-- Status Code Indicator -->
                <div class="flex items-center gap-3 text-xs font-mono font-semibold text-rose-600 tracking-wider uppercase">
                    <span class="inline-block w-2 h-2 rounded-full bg-rose-600"></span>
                    <span>Error Code 403 / Access Forbidden</span>
                </div>

                <!-- Main Heading & Body -->
                <div class="space-y-4">
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-none">
                        Akses Ditolak.
                    </h1>
                    <p class="text-slate-600 text-base sm:text-lg leading-relaxed font-normal">
                        {{ !empty($exception) && $exception->getMessage() ? $exception->getMessage() : 'Maaf, direktori atau halaman ini berada di area terbatas. Anda membutuhkan izin khusus atau akun terverifikasi untuk melihat konten di halaman ini.' }}
                    </p>
                </div>

                <!-- Clean Text Action -->
                <div class="pt-4 flex items-center gap-4 flex-wrap">
                    <a href="{{ url('/') }}" class="inline-block bg-slate-900 hover:bg-brand-blue text-white text-xs font-semibold px-6 py-3 rounded-lg transition-colors">
                        Kembali ke Beranda
                    </a>
                    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                        &larr; Halaman Sebelumnya
                    </a>
                </div>

            </div>

        </div>
    </main>

    <!-- FOOTER TEXT ONLY -->
    <footer class="py-8 border-t border-slate-100">
        <div class="max-w-4xl mx-auto px-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-xs text-slate-400">
            <p>&copy; {{ date('Y') }} SMK Negeri 2 Karanganyar.</p>
            <p>Sistem Keamanan &amp; Infrastruktur Jaringan</p>
        </div>
    </footer>

</body>

</html>
