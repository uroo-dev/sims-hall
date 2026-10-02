@extends('Admin.layout.app')

@section('title', 'Dashboard Kepala Sekolah - SIMS Sarpras Aula')
@section('page_title', 'Dashboard Kepala Sekolah')

@section('content')
<div class="space-y-6">

    <!-- ALERT FLASH NOTIFIKASI -->
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-check"></i>
                </div>
                <span class="text-xs md:text-sm font-medium">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    @endif

    <!-- BANNER SELAMAT DATANG KEPALA SEKOLAH -->
    <div class="rounded-3xl bg-white p-6 md:p-8 border border-slate-100 figma-card-shadow">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-xs font-semibold text-brand-700 border border-blue-100">
                    <i class="fa-solid fa-crown text-amber-500"></i>
                    <span>Portal Otorisasi Pimpinan</span>
                </div>
                <h2 class="text-xl md:text-3xl font-black text-slate-900 tracking-tight leading-tight">
                    Selamat Datang, {{ auth()->user()->name ?? 'Bapak/Ibu Kepala Sekolah' }}
                </h2>
                <p class="text-slate-500 text-xs md:text-sm leading-relaxed">
                    Sebagai pimpinan, Anda memiliki wewenang utama dalam memberikan <strong>Persetujuan Final</strong> peminjaman aula SMK Negeri 2 Karanganyar setelah permohonan dan pembayaran diverifikasi oleh tim sarpras.
                </p>
            </div>

            <div class="flex items-center gap-3 flex-shrink-0">
                <a href="{{ route('kepala-sekolah.peminjaman.index', ['status' => 'approved_1']) }}"
                    class="px-5 py-3 rounded-2xl bg-amber-500 hover:bg-amber-600 text-slate-900 font-extrabold text-xs md:text-sm shadow-sm transition transform active:scale-95 flex items-center gap-2">
                    <i class="fa-solid fa-signature"></i>
                    <span>Tinjau Persetujuan ({{ $stats['pending_final'] }})</span>
                </a>
            </div>
        </div>
    </div>

    <!-- KARTU METRIK STATISTIK -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
        <!-- 1. Menunggu Persetujuan Final -->
        <div class="bg-white rounded-2xl p-5 border {{ $stats['pending_final'] > 0 ? 'border-amber-300 ring-2 ring-amber-100' : 'border-slate-100' }} figma-card-shadow flex items-center gap-4 transition hover:shadow-md">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl flex-shrink-0 border border-amber-100">
                <i class="fa-solid fa-file-signature"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Perlu Persetujuan</p>
                <div class="flex items-center gap-2">
                    <h4 class="text-xl md:text-2xl font-black text-slate-900">{{ number_format($stats['pending_final']) }}</h4>
                    @if ($stats['pending_final'] > 0)
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping"></span>
                    @endif
                </div>
            </div>
        </div>

        <!-- 2. Disetujui Final -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 figma-card-shadow flex items-center gap-4 transition hover:shadow-md">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl flex-shrink-0 border border-emerald-100">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Disetujui Final</p>
                <h4 class="text-xl md:text-2xl font-black text-emerald-600">{{ number_format($stats['approved_final']) }}</h4>
            </div>
        </div>

        <!-- 3. Permohonan Ditolak -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 figma-card-shadow flex items-center gap-4 transition hover:shadow-md">
            <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-xl flex-shrink-0 border border-red-100">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Ditolak</p>
                <h4 class="text-xl md:text-2xl font-black text-red-600">{{ number_format($stats['rejected']) }}</h4>
            </div>
        </div>

        <!-- 4. Total Keseluruhan Pengajuan -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 figma-card-shadow flex items-center gap-4 transition hover:shadow-md">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-brand-600 flex items-center justify-center text-xl flex-shrink-0 border border-blue-100">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Peminjaman</p>
                <h4 class="text-xl md:text-2xl font-black text-slate-800">{{ number_format($stats['total']) }}</h4>
            </div>
        </div>
    </div>

    <!-- SEKSI 1: PERMOHONAN MENUNGGU PERSETUJUAN FINAL DARI KEPALA SEKOLAH -->
    <div class="bg-white rounded-3xl p-5 md:p-6 border border-slate-100 figma-card-shadow space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    <h3 class="font-extrabold text-slate-900 text-base md:text-lg tracking-tight">
                        Menunggu Persetujuan Final Anda
                    </h3>
                </div>
                <p class="text-slate-500 text-xs mt-0.5">
                    Permohonan ini telah diverifikasi valid oleh admin sarpras dan siap untuk disahkan oleh Kepala Sekolah.
                </p>
            </div>
            <a href="{{ route('kepala-sekolah.peminjaman.index', ['status' => 'approved_1']) }}"
                class="text-xs font-bold text-brand-600 hover:text-brand-800 transition flex items-center gap-1.5 self-start sm:self-auto">
                <span>Lihat Semua Permohonan</span>
                <i class="fa-solid fa-arrow-right text-[11px]"></i>
            </a>
        </div>

        @if ($pendingPeminjamans->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs md:text-sm">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-600 border-b border-slate-200/80">
                            <th class="py-3 px-3.5 font-bold uppercase text-[11px]">No / ID</th>
                            <th class="py-3 px-3.5 font-bold uppercase text-[11px]">Pemohon & Instansi</th>
                            <th class="py-3 px-3.5 font-bold uppercase text-[11px]">Paket Aula</th>
                            <th class="py-3 px-3.5 font-bold uppercase text-[11px]">Tanggal Pemakaian</th>
                            <th class="py-3 px-3.5 font-bold uppercase text-[11px]">Verifikasi Sarpras</th>
                            <th class="py-3 px-3.5 font-bold uppercase text-[11px] text-center">Aksi Keputusan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($pendingPeminjamans as $index => $item)
                            <tr class="hover:bg-amber-50/30 transition group">
                                <td class="py-3.5 px-3.5 font-mono font-bold text-slate-800">
                                    #{{ $item->id }}
                                </td>
                                <td class="py-3.5 px-3.5">
                                    <div class="font-bold text-slate-900">{{ $item->nama }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $item->email_instansi }}</div>
                                </td>
                                <td class="py-3.5 px-3.5">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-brand-700 border border-blue-100">
                                        {{ $item->paketPeminjaman?->nama_paket ?: 'Paket Standar' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3.5">
                                    <div class="font-bold text-slate-800">
                                        {{ $item->tanggal_mulai ? $item->tanggal_mulai->translatedFormat('d M Y') : '-' }}
                                    </div>
                                    <div class="text-[11px] text-slate-500">
                                        {{ $item->tanggal_mulai ? $item->tanggal_mulai->format('H:i') : '' }} - {{ $item->tanggal_selesai ? $item->tanggal_selesai->format('H:i') : '' }} WIB
                                    </div>
                                </td>
                                <td class="py-3.5 px-3.5">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Lolos Sarpras
                                    </span>
                                </td>
                                <td class="py-3.5 px-3.5 text-center">
                                    <a href="{{ route('kepala-sekolah.peminjaman.show', $item->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-900 font-extrabold text-xs transition shadow-2xs">
                                        <i class="fa-solid fa-signature"></i>
                                        <span>Tinjau & Putuskan</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 rounded-2xl bg-slate-50 border border-dashed border-slate-200 text-center space-y-2">
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mx-auto border border-emerald-100">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h4 class="font-bold text-slate-800 text-sm">Tidak Ada Permohonan Tertunda</h4>
                <p class="text-slate-500 text-xs max-w-md mx-auto">
                    Semua permohonan peminjaman aula yang masuk telah Anda tinjau dan putuskan. Terima kasih.
                </p>
            </div>
        @endif
    </div>

    <!-- SEKSI 2: JADWAL AGENDA AULA MENDATANG (SUDAH DISETUJUI FINAL) -->
    <div class="bg-white rounded-3xl p-5 md:p-6 border border-slate-100 figma-card-shadow space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="font-extrabold text-slate-900 text-base md:text-lg tracking-tight">
                    Agenda Resmi Peminjaman Aula Mendatang
                </h3>
                <p class="text-slate-500 text-xs mt-0.5">
                    Daftar peminjaman aula yang telah resmi Anda setujui dan dijadwalkan terlaksana.
                </p>
            </div>
            <a href="{{ route('kepala-sekolah.peminjaman.index', ['status' => 'approved_final']) }}"
                class="text-xs font-bold text-emerald-600 hover:text-emerald-800 transition flex items-center gap-1.5">
                <span>Lihat Semua Agenda</span>
                <i class="fa-solid fa-arrow-right text-[11px]"></i>
            </a>
        </div>

        @if ($upcomingAgendas->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($upcomingAgendas as $agenda)
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3 hover:border-emerald-300 transition">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-900 font-mono">#{{ $agenda->id }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                Resmi Terjadwal
                            </span>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm line-clamp-1">{{ $agenda->nama }}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $agenda->paketPeminjaman?->nama_paket ?: 'Paket Standar' }}</p>
                        </div>
                        <div class="pt-2 border-t border-slate-200/60 text-xs text-slate-600 space-y-1">
                            <div class="flex items-center gap-1.5">
                                <i class="fa-regular fa-calendar text-brand-600 text-[11px]"></i>
                                <span class="font-semibold">{{ $agenda->tanggal_mulai ? $agenda->tanggal_mulai->translatedFormat('d F Y') : '-' }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-slate-500 text-[11px]">
                                <i class="fa-regular fa-clock text-[10px]"></i>
                                <span>{{ $agenda->tanggal_mulai ? $agenda->tanggal_mulai->format('H:i') : '' }} - {{ $agenda->tanggal_selesai ? $agenda->tanggal_selesai->format('H:i') : '' }} WIB</span>
                            </div>
                        </div>
                        <a href="{{ route('kepala-sekolah.peminjaman.show', $agenda->id) }}"
                            class="block w-full py-2 text-center rounded-xl bg-white hover:bg-slate-100 border border-slate-200 text-xs font-bold text-slate-700 transition">
                            Lihat Detail Peminjaman
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-6 text-center text-slate-400 text-xs space-y-1">
                <i class="fa-regular fa-calendar text-2xl block mb-1"></i>
                <p>Belum ada agenda peminjaman aula mendatang yang disetujui.</p>
            </div>
        @endif
    </div>

</div>
@endsection
