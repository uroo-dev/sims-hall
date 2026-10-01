@extends('Admin.layout.app')

@section('title', 'Dashboard Admin - SMK Negeri 2 Karanganyar')

@section('content')


        <!-- 3 TOP STAT CARDS GRID -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Stat Card 1 -->
            <a href="{{ route('admin.peminjaman.index', ['status' => 'approved_final']) }}" class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 flex items-center justify-between transition-transform duration-200 hover:-translate-y-1 block">
                <div>
                    <h3 id="stat-1-label" class="text-xs md:text-sm font-extrabold text-brand-600 uppercase tracking-wide leading-snug max-w-[140px]">
                        PEMINJAMAN TERVERIFIKASI
                    </h3>
                </div>
                <div id="stat-1-value" class="text-4xl lg:text-5xl font-extrabold text-brand-600 pl-2">
                    {{ number_format($peminjamanTerverifikasiCount) }}
                </div>
            </a>

            <!-- Stat Card 2 -->
            <a href="{{ route('admin.paket.index') }}" class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 flex items-center justify-between transition-transform duration-200 hover:-translate-y-1 block">
                <div>
                    <h3 id="stat-2-label" class="text-xs md:text-sm font-extrabold text-brand-600 uppercase tracking-wide leading-snug max-w-[140px]">
                        JUMLAH PAKET PEMINJAMAN
                    </h3>
                </div>
                <div id="stat-2-value" class="text-4xl lg:text-5xl font-extrabold text-brand-600 pl-2">
                    {{ number_format($paketCount) }}
                </div>
            </a>

            <!-- Stat Card 3 -->
            <a href="{{ route('admin.fasilitas.index') }}" class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 flex items-center justify-between transition-transform duration-200 hover:-translate-y-1 block">
                <div>
                    <h3 id="stat-3-label" class="text-xs md:text-sm font-extrabold text-brand-600 uppercase tracking-wide leading-snug max-w-[140px]">
                        JUMLAH FASILITAS
                    </h3>
                </div>
                <div id="stat-3-value" class="text-4xl lg:text-5xl font-extrabold text-brand-600 pl-2">
                    {{ number_format($facilityCount) }}
                </div>
            </a>

        </section>

        @if (!empty($isSuperAdmin) && !empty($paymentConfig))
            <!-- SUPER ADMIN PAYMENT CONFIGURATION SECTION -->
            <section class="bg-white rounded-2xl p-5 md:p-6 figma-card-shadow border border-slate-100 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-base flex-shrink-0">
                            <i class="fa-solid fa-credit-card"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-sm md:text-base font-extrabold text-slate-800 uppercase tracking-wide">
                                    Konfigurasi Peminjaman &amp; Rekening Sekolah
                                </h2>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $paymentConfig->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                    {{ $paymentConfig->is_active ? 'Sistem Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Khusus Super Admin: Pengaturan rekening transfer tujuan, QRIS, minimal booking, toleransi batal, serta tenggat pembayaran aula.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('admin.payment-configuration.index') }}"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-brand-600 hover:bg-brand-700 active:scale-95 text-white rounded-xl text-xs font-bold shadow-sm transition flex-shrink-0">
                        <i class="fa-solid fa-sliders text-xs"></i>
                        <span>Kelola Konfigurasi</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-1">
                    <!-- Card Rekening Utama -->
                    <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-xl space-y-1">
                        <span class="text-[11px] font-bold text-brand-700 uppercase tracking-wider block flex items-center gap-1.5">
                            <i class="fa-solid fa-building-columns text-[10px]"></i>
                            Rekening Utama
                        </span>
                        <div class="font-extrabold text-slate-800 text-sm">
                            {{ $paymentConfig->bank_utama }}
                        </div>
                        <div class="font-mono text-xs text-slate-600 font-semibold">
                            {{ $paymentConfig->norek_utama }}
                        </div>
                        <div class="text-[11px] text-slate-500 truncate" title="{{ $paymentConfig->atas_nama_utama }}">
                            a.n. {{ $paymentConfig->atas_nama_utama }}
                        </div>
                    </div>

                    <!-- Card Rekening Alternatif -->
                    <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-xl space-y-1">
                        <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider block flex items-center gap-1.5">
                            <i class="fa-solid fa-money-bill-transfer text-[10px]"></i>
                            Rekening Alternatif
                        </span>
                        @if ($paymentConfig->bank_alternatif_1)
                            <div class="font-bold text-slate-800 text-xs">
                                1. {{ $paymentConfig->bank_alternatif_1 }} ({{ $paymentConfig->norek_alternatif_1 }})
                            </div>
                        @else
                            <div class="text-xs text-slate-400 italic">Alternatif 1 belum diatur</div>
                        @endif
                        @if ($paymentConfig->bank_alternatif_2)
                            <div class="font-bold text-slate-800 text-xs">
                                2. {{ $paymentConfig->bank_alternatif_2 }} ({{ $paymentConfig->norek_alternatif_2 }})
                            </div>
                        @else
                            <div class="text-[11px] text-slate-400 italic">Alternatif 2 belum diatur</div>
                        @endif
                    </div>

                    <!-- Card QRIS -->
                    <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-xl space-y-1">
                        <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider block flex items-center gap-1.5">
                            <i class="fa-solid fa-qrcode text-[10px]"></i>
                            Metode QRIS
                        </span>
                        <div class="font-extrabold text-slate-800 text-xs">
                            {{ $paymentConfig->qris_merchant ?: 'QRIS SMKN 2 KRA' }}
                        </div>
                        <div class="text-xs flex items-center gap-1.5">
                            @if ($paymentConfig->qris_image)
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span class="text-emerald-700 font-semibold text-[11px]">Barcode Siap Digunakan</span>
                            @else
                                <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                                <span class="text-slate-400 italic text-[11px]">Barcode belum diunggah</span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Jatuh Tempo (Jam) -->
                    <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-xl space-y-1">
                        <span class="text-[11px] font-bold text-purple-700 uppercase tracking-wider block flex items-center gap-1.5">
                            <i class="fa-solid fa-clock text-[10px]"></i>
                            Jatuh Tempo (Jam)
                        </span>
                        <div class="text-xs text-slate-700 flex items-center justify-between">
                            <span>Batas Bayar DP:</span>
                            <strong class="font-extrabold text-purple-700">{{ $paymentConfig->jatuh_tempo_dp_jam }} Jam</strong>
                        </div>
                        <div class="text-xs text-slate-700 flex items-center justify-between">
                            <span>Batas Pelunasan:</span>
                            <strong class="font-extrabold text-brand-700">{{ $paymentConfig->jatuh_tempo_pelunasan_jam }} Jam</strong>
                        </div>
                        <div class="text-xs text-slate-700 flex items-center justify-between">
                            <span>Min. Booking:</span>
                            <strong class="font-extrabold text-emerald-700">{{ $paymentConfig->minimal_hari_booking ?? 3 }} Hari</strong>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!-- MAIN CONTENT GRID (KALENDER KETERSEDIAAN AULA & PROFIL ADMIN) -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- LEFT COLUMN: CEK KETERSEDIAAN AULA (Span 2 cols) -->
            <div class="lg:col-span-2 bg-white rounded-2xl figma-card-shadow p-6 md:p-7 border border-blue-50/50 flex flex-col justify-between">
                <div>
                    <!-- Header Judul -->
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-[#0070ba] font-bold text-lg md:text-xl tracking-wide uppercase">
                            CEK KETERSEDIAAN AULA
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
                            <a href="{{ route('dashboard', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}"
                               class="w-7 h-7 rounded border border-indigo-200 text-indigo-600 hover:bg-indigo-50 flex items-center justify-center text-xs transition-colors"
                               title="Bulan Sebelumnya">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>

                            <span class="text-sm font-bold text-gray-800 tracking-wide">
                                {{ $calendarDate->translatedFormat('F Y') }}
                            </span>

                            <a href="{{ route('dashboard', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}"
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
                                        <!-- Blue Selection Box Outline -->
                                        <div class="absolute inset-1 border border-blue-500 rounded bg-blue-50/40 pointer-events-none"></div>

                                        <!-- Badge Terpakai -->
                                        <div class="mt-1 relative z-10">
                                            <span class="block truncate text-[10px] text-[#0070ba] font-semibold bg-white/90 border border-blue-200 rounded px-1 shadow-xs">
                                                Terpakai
                                            </span>
                                        </div>

                                        <!-- Hover popup (Event Info) -->
                                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 hidden group-hover:block z-30 w-48 bg-gray-900 text-white text-[11px] rounded-lg p-2.5 shadow-xl">
                                            @foreach ($events as $ev)
                                                <div class="font-bold text-blue-300 truncate">{{ $ev['nama'] }}</div>
                                                <div class="text-gray-300 text-[10px] truncate">{{ $ev['paket'] }}</div>
                                                <div class="text-[9px] text-gray-400">{{ $ev['jam_mulai'] }} - {{ $ev['jam_selesai'] }} WIB</div>
                                                @if (!empty($ev['url']))
                                                    <a href="{{ $ev['url'] }}" class="mt-1.5 inline-flex items-center gap-1 text-[10px] text-brand-400 hover:text-brand-300 underline font-semibold">
                                                        <span>Lihat Peminjaman</span>
                                                        <i class="fa-solid fa-arrow-right text-[8px]"></i>
                                                    </a>
                                                @endif
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
                </div>

                <!-- Footer Keterangan -->
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-gray-500">
                    <span>Total <strong>{{ count($bookedDays) }} hari</strong> terpakai pada bulan ini.</span>
                    <a href="{{ route('admin.peminjaman.index') }}" class="font-semibold text-[#0070ba] hover:underline flex items-center gap-1">
                        <span>Lihat Semua Peminjaman</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- RIGHT COLUMN: PROFILE CARD (Span 1 col) -->
            <div class="lg:col-span-1 bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 flex flex-col justify-between">
                <div>
                    <!-- Card Header -->
                    <div class="flex items-center justify-between mb-6 pb-2 border-b border-slate-100">
                        <div>
                            <h2 class="text-sm font-extrabold text-brand-600 uppercase tracking-wider relative inline-block">
                                PROFIL
                                <div class="h-0.5 w-full bg-brand-600 rounded-full mt-1"></div>
                            </h2>
                        </div>
                        <a href="{{ route('customer.profil') }}" class="px-3.5 py-1 bg-brand-600 hover:bg-brand-700 text-white rounded-full text-[11px] font-semibold flex items-center space-x-1.5 transition-colors shadow-sm">
                            <i class="fa-solid fa-user text-[10px]"></i>
                            <span>Detail</span>
                        </a>
                    </div>

                    <!-- Profile Image Frame (Figma Vector Silhouette Accent) -->
                    <div class="flex flex-col items-center justify-center my-4">
                        <div class="w-48 h-56 rounded-2xl border-2 border-slate-200 bg-slate-50 p-2 flex items-center justify-center shadow-inner overflow-hidden relative">
                            <!-- Dark Silhouette Vector Illustration -->
                            <svg class="w-full h-full text-slate-700 transform scale-105 translate-y-2" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>

                        <!-- Name & Email Subtitle -->
                        <div class="text-center mt-5">
                            <h3 id="profile-card-name" class="text-xl font-extrabold text-slate-900 tracking-tight">
                                {{ auth()->user()->name }}
                            </h3>
                            <p id="profile-card-email" class="text-xs font-semibold text-slate-500 mt-1">
                                {{ auth()->user()->email }}
                            </p>
                            <div class="mt-2 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                <i class="fa-solid fa-shield-halved text-brand-600 text-[10px]"></i>
                                <span>{{ strtoupper(str_replace('_', ' ', auth()->user()->role ?? 'ADMIN')) }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </section>

        <!-- SECTION LAPORAN OPERASIONAL TERBARU -->
        <section class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
            <!-- Card Header -->
            <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
                <div>
                    <h2 id="table-card-title" class="text-sm md:text-base font-extrabold text-brand-600 uppercase tracking-wider">
                        LAPORAN OPERASIONAL TERBARU
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar pengajuan peminjaman aula yang baru masuk dan diproses.</p>
                </div>
                <!-- Selengkapnya Badge Button -->
                <a href="{{ route('admin.peminjaman.index') }}" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-semibold flex items-center space-x-1.5 transition-colors shadow-sm">
                    <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                    <span>Lihat Selengkapnya</span>
                </a>
            </div>

            <!-- Data Table Container -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs md:text-sm">
                    <thead>
                        <tr id="table-headers" class="border-b-2 border-slate-800 text-slate-800 font-extrabold">
                            <th class="py-3 px-3">Kode Order</th>
                            <th class="py-3 px-3">Pemohon / Instansi</th>
                            <th class="py-3 px-3">Paket Peminjaman</th>
                            <th class="py-3 px-3">Jadwal Acara</th>
                            <th class="py-3 px-3 text-center">Status</th>
                            <th class="py-3 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-body" class="divide-y divide-slate-100 text-slate-700 font-medium">
                        @forelse ($recentPeminjamans as $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-3 font-bold text-slate-800">
                                    <a href="{{ route('admin.peminjaman.show', $item) }}" class="text-brand-600 hover:underline">
                                        ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                                    </a>
                                </td>
                                <td class="py-3.5 px-3">
                                    <div class="font-bold text-slate-900">{{ $item->nama }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $item->email_instansi }}</div>
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="font-semibold text-slate-800">{{ $item->nama_paket ?? ($item->paketPeminjaman?->nama_paket ?? 'Paket Kustom') }}</span>
                                    @if ($item->is_custom)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 ml-1">
                                            Custom
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3 text-xs text-slate-600">
                                    {{ $item->tanggal_mulai ? $item->tanggal_mulai->translatedFormat('d M Y, H:i') : '-' }} WIB
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    @if ($item->status === 'approved_final')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="fa-solid fa-circle-check text-[10px]"></i> Disetujui
                                        </span>
                                    @elseif ($item->status === 'rejected')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-red-50 text-red-700 border border-red-200">
                                            <i class="fa-solid fa-circle-xmark text-[10px]"></i> Ditolak
                                        </span>
                                    @elseif ($item->status === 'cancelled')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                            Dibatalkan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            <i class="fa-solid fa-clock text-[10px]"></i> Menunggu
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    <a href="{{ route('admin.peminjaman.show', $item) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                                        <i class="fa-solid fa-eye text-[10px]"></i>
                                        <span>Rincian</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400 italic">
                                    Belum ada data peminjaman operasional.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

@endsection
