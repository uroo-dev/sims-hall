@extends('Admin.layout.app')

@section('title', 'Dashboard - SIMS Aula SMK N 2 Karanganyar')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- ROW 1: STATISTIC CARDS (3 COLS) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Card 1: Peminjaman Terverifikasi Milik User -->
        <div class="bg-white rounded-2xl figma-card-shadow p-6 flex items-center justify-between border border-blue-50/50 hover:border-blue-200 transition-all group">
            <div class="space-y-1">
                <span class="block text-[#0070ba] font-bold text-xs md:text-sm tracking-wider uppercase leading-snug max-w-[130px]">
                    PEMINJAMAN TERVERIFIKASI
                </span>
                <span class="text-xs text-gray-400 font-normal">Disetujui sekolah</span>
            </div>
            <div class="text-[#0070ba] text-5xl md:text-6xl font-black tracking-tight group-hover:scale-105 transition-transform">
                {{ $peminjamanTerverifikasi }}
            </div>
        </div>

        <!-- Card 2: Peminjaman Tertolak Milik User -->
        <div class="bg-white rounded-2xl figma-card-shadow p-6 flex items-center justify-between border border-blue-50/50 hover:border-blue-200 transition-all group">
            <div class="space-y-1">
                <span class="block text-[#0070ba] font-bold text-xs md:text-sm tracking-wider uppercase leading-snug max-w-[130px]">
                    PEMINJAMAN TERTOLAK
                </span>
                <span class="text-xs text-gray-400 font-normal">Tidak disetujui</span>
            </div>
            <div class="text-[#0070ba] text-5xl md:text-6xl font-black tracking-tight group-hover:scale-105 transition-transform">
                {{ $peminjamanTertolak }}
            </div>
        </div>

        <!-- Card 3: Jumlah Paket Peminjaman -->
        <div class="bg-white rounded-2xl figma-card-shadow p-6 flex items-center justify-between border border-blue-50/50 hover:border-blue-200 transition-all group">
            <div class="space-y-1">
                <span class="block text-[#0070ba] font-bold text-xs md:text-sm tracking-wider uppercase leading-snug max-w-[140px]">
                    JUMLAH PAKET PEMINJAMAN
                </span>
                <span class="text-xs text-gray-400 font-normal">Paket aula tersedia</span>
            </div>
            <div class="text-[#0070ba] text-5xl md:text-6xl font-black tracking-tight group-hover:scale-105 transition-transform">
                {{ $jumlahPaket }}
            </div>
        </div>

    </div>

    <!-- ROW 2: CEK KETERSEDIAAN AULA & PROFIL USER -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Kiri: Cek Ketersediaan Aula (Kalender Interaktif) -->
        <div class="lg:col-span-8 bg-white rounded-2xl figma-card-shadow p-6 md:p-7 border border-blue-50/50">
            <!-- Header Judul -->
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-[#0070ba] font-bold text-lg md:text-xl tracking-wide uppercase">
                    CEK KETERSEDIAAN
                </h2>
                <div class="flex items-center gap-3 text-xs text-gray-500">
                    <span class="inline-flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded border border-blue-500 bg-blue-50 inline-block"></span>
                        Terpakai
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded border border-gray-200 bg-white inline-block"></span>
                        Tersedia
                    </span>
                </div>
            </div>

            <!-- Navigasi Kalender (Bulan & Tahun) -->
            @php
                $prevMonth = $calendarDate->copy()->subMonth();
                $nextMonth = $calendarDate->copy()->addMonth();

                $startOfMonth = $calendarDate->copy()->startOfMonth();
                $endOfMonth = $calendarDate->copy()->endOfMonth();

                $startDayOfWeek = $startOfMonth->dayOfWeek;
                $daysInMonth = $calendarDate->daysInMonth;
                $prevMonthDays = $prevMonth->daysInMonth;
            @endphp

            <div class="border border-blue-100 rounded-xl overflow-hidden bg-white shadow-sm">
                <!-- Top Nav Bar -->
                <div class="bg-gray-50/70 border-b border-blue-100 px-4 py-2.5 flex items-center justify-between">
                    <a href="{{ route('customer.dashboard', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}"
                       class="w-7 h-7 rounded border border-indigo-200 text-indigo-600 hover:bg-indigo-50 flex items-center justify-center text-xs transition-colors"
                       title="Bulan Sebelumnya">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>

                    <span class="text-sm font-bold text-gray-800 tracking-wide">
                        {{ $calendarDate->translatedFormat('F Y') }}
                    </span>

                    <a href="{{ route('customer.dashboard', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}"
                       class="w-7 h-7 rounded border border-indigo-200 text-indigo-600 hover:bg-indigo-50 flex items-center justify-center text-xs transition-colors"
                       title="Bulan Berikutnya">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>

                <!-- Grid Days of Week -->
                <div class="grid grid-cols-7 border-b border-gray-200 bg-gray-50/40 text-center text-xs font-semibold text-gray-600 py-2">
                    <div>Min</div>
                    <div>Sen</div>
                    <div>Sel</div>
                    <div>Rab</div>
                    <div>Kam</div>
                    <div>Jum</div>
                    <div>Sab</div>
                </div>

                <!-- Grid Dates -->
                <div class="grid grid-cols-7 text-xs">
                    {{-- 1. Padding hari bulan sebelumnya --}}
                    @for ($i = $startDayOfWeek - 1; $i >= 0; $i--)
                        <div class="h-16 md:h-20 p-1.5 border-b border-r border-gray-100 text-gray-300 bg-gray-50/20 select-none">
                            <span>{{ $prevMonthDays - $i }}</span>
                        </div>
                    @endfor

                    {{-- 2. Hari bulan aktif --}}
                    @for ($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $isBooked = isset($bookedDays[$day]);
                            $events = $bookedDays[$day] ?? [];
                        @endphp

                        <div class="h-16 md:h-20 p-1.5 border-b border-r border-gray-100 relative group transition-colors {{ $isBooked ? 'bg-blue-50/30' : 'hover:bg-gray-50/80' }}">
                            <!-- Date Number -->
                            <div class="flex items-center justify-between">
                                <span class="font-medium {{ $isBooked ? 'text-[#0070ba] font-bold' : 'text-gray-700' }}">
                                    {{ $day }}
                                </span>
                            </div>

                            @if ($isBooked)
                                <!-- Blue Selection Box Outline matching screenshot -->
                                <div class="absolute inset-1 border border-blue-500 rounded bg-blue-50/40 pointer-events-none"></div>

                                <!-- Tooltip Event Info on Hover -->
                                <div class="mt-1 relative z-10">
                                    <span class="block truncate text-[10px] text-[#0070ba] font-semibold bg-white/90 border border-blue-200 rounded px-1 shadow-xs">
                                        Terpakai
                                    </span>
                                </div>

                                <!-- Hover popup -->
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 hidden group-hover:block z-30 w-44 bg-gray-900 text-white text-[11px] rounded-lg p-2 shadow-xl">
                                    @foreach ($events as $ev)
                                        <div class="font-bold text-blue-300">{{ $ev['nama'] }}</div>
                                        <div class="text-gray-300 text-[10px]">{{ $ev['paket'] }}</div>
                                        <div class="text-[9px] text-gray-400">{{ $ev['jam_mulai'] }} - {{ $ev['jam_selesai'] }} WIB</div>
                                    @endforeach
                                    <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                                </div>
                            @endif
                        </div>
                    @endfor

                    {{-- 3. Padding hari bulan berikutnya --}}
                    @php
                        $totalCells = $startDayOfWeek + $daysInMonth;
                        $remaining = 7 - ($totalCells % 7);
                        if ($remaining === 7) $remaining = 0;
                    @endphp
                    @for ($j = 1; $j <= $remaining; $j++)
                        <div class="h-16 md:h-20 p-1.5 border-b border-r border-gray-100 text-gray-300 bg-gray-50/20 select-none">
                            <span>{{ $j }}</span>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Footer Keterangan -->
            <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                <span>Ingin meminjam aula pada tanggal kosong?</span>
                <a href="{{ route('customer.paket') }}" class="font-semibold text-[#0070ba] hover:underline flex items-center gap-1">
                    Lihat Pilihan Paket <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Kanan: Profil User -->
        <div class="lg:col-span-4 bg-white rounded-2xl figma-card-shadow p-6 md:p-7 border border-blue-50/50 flex flex-col justify-between">
            <div>
                <!-- Header Profil dengan Underline Biru & Tombol Selengkapnya -->
                <div class="flex items-center justify-between pb-3 mb-5 border-b border-gray-100">
                    <div class="relative">
                        <span class="text-[#0070ba] font-bold text-base tracking-wide">
                            PROFIL
                        </span>
                        <div class="w-16 h-0.5 bg-[#0070ba] mt-1"></div>
                    </div>

                    <a href="{{ route('customer.profil') }}"
                       class="bg-[#0070ba] hover:bg-blue-700 text-white text-[11px] font-semibold px-3 py-1.5 rounded-full shadow-xs flex items-center gap-1.5 transition-colors">
                        <i class="fa-regular fa-eye text-xs"></i>
                        <span>Selengkapnya</span>
                    </a>
                </div>

                <!-- Avatar Profil Pengguna -->
                <div class="w-full aspect-[4/5] max-h-[300px] border border-gray-200 rounded-lg overflow-hidden flex items-center justify-center bg-[#fdfdfd] relative shadow-inner">
                    <img src="{{ asset('assets/default-avatar.png') }}"
                        alt="Foto Profil {{ $user?->name ?? 'Pengguna' }}"
                        class="w-full h-full object-cover"
                        onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($user?->name ?? 'Pengguna') }}&background=0070ba&color=fff&size=300';">
                </div>

                <!-- Info User (Nama & Email Instansi) -->
                <div class="mt-5 text-left space-y-1">
                    <h3 class="text-xl font-bold text-gray-900 tracking-tight">
                        {{ $user?->name ?? 'Pengguna' }}
                    </h3>
                    <p class="text-sm font-medium text-gray-500">
                        {{ $user?->email ?? '-' }}
                    </p>
                </div>
            </div>

            <!-- Role Badge Footer -->
            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-xs">
                <span class="text-gray-400">Status Akun:</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-[#0070ba] border border-blue-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#0070ba]"></span>
                    Peminjam / Organisasi
                </span>
            </div>
        </div>

    </div>

</div>
@endsection
