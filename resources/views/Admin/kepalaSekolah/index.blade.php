@extends('Admin.layout.app')

@section('title', 'Daftar Persetujuan Peminjaman - Kepala Sekolah')
@section('page_title', 'Persetujuan Peminjaman Aula')

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

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <span class="text-xs md:text-sm font-medium">{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    @endif

    <!-- FILTER TAB STATUS -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1">
        <a href="{{ route('kepala-sekolah.peminjaman.index') }}"
            class="px-4 py-2.5 rounded-2xl text-xs md:text-sm font-bold transition flex items-center gap-2 whitespace-nowrap {{ !request('status') ? 'bg-brand-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            <span>Semua Pengajuan</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ !request('status') ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $stats['all'] }}</span>
        </a>

        <a href="{{ route('kepala-sekolah.peminjaman.index', ['status' => 'approved_1']) }}"
            class="px-4 py-2.5 rounded-2xl text-xs md:text-sm font-bold transition flex items-center gap-2 whitespace-nowrap {{ request('status') === 'approved_1' ? 'bg-amber-500 text-slate-900 shadow-sm' : 'bg-white text-amber-700 border border-amber-200 hover:bg-amber-50' }}">
            <i class="fa-solid fa-hourglass-half text-xs"></i>
            <span>Menunggu Persetujuan Anda</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'approved_1' ? 'bg-slate-900 text-amber-300' : 'bg-amber-100 text-amber-900' }}">{{ $stats['pending_final'] }}</span>
        </a>

        <a href="{{ route('kepala-sekolah.peminjaman.index', ['status' => 'approved_final']) }}"
            class="px-4 py-2.5 rounded-2xl text-xs md:text-sm font-bold transition flex items-center gap-2 whitespace-nowrap {{ request('status') === 'approved_final' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-emerald-700 border border-emerald-200 hover:bg-emerald-50' }}">
            <i class="fa-solid fa-circle-check text-xs"></i>
            <span>Disetujui Final</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'approved_final' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-900' }}">{{ $stats['approved_final'] }}</span>
        </a>

        <a href="{{ route('kepala-sekolah.peminjaman.index', ['status' => 'rejected']) }}"
            class="px-4 py-2.5 rounded-2xl text-xs md:text-sm font-bold transition flex items-center gap-2 whitespace-nowrap {{ request('status') === 'rejected' ? 'bg-red-600 text-white shadow-sm' : 'bg-white text-red-700 border border-red-200 hover:bg-red-50' }}">
            <i class="fa-solid fa-circle-xmark text-xs"></i>
            <span>Ditolak</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'rejected' ? 'bg-white/20 text-white' : 'bg-red-100 text-red-900' }}">{{ $stats['rejected'] }}</span>
        </a>
    </div>

    <!-- PENCARIAN & FILTER TANGGAL -->
    <div class="bg-white rounded-3xl p-5 border border-slate-100 figma-card-shadow">
        <form method="GET" action="{{ route('kepala-sekolah.peminjaman.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 items-end">
            @if (request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <!-- Search -->
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Cari Peminjam / ID</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nama pemohon / instansi..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs md:text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <span class="absolute right-3.5 top-3 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                </div>
            </div>

            <!-- Tanggal Dari -->
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Tanggal Mulai Dari</label>
                <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs md:text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Tanggal Sampai -->
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Hingga Tanggal</label>
                <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs md:text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Aksi Filter -->
            <div class="flex items-center gap-2">
                <button type="submit"
                    class="flex-1 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs md:text-sm transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Terapkan</span>
                </button>
                <a href="{{ route('kepala-sekolah.peminjaman.index', request('status') ? ['status' => request('status')] : []) }}"
                    class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-600 font-semibold text-xs md:text-sm transition text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- TABEL DAFTAR PEMINJAMAN -->
    <div class="bg-white rounded-3xl border border-slate-100 figma-card-shadow overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-extrabold text-slate-900 text-base md:text-lg tracking-tight">
                Daftar Permohonan Peminjaman Aula
            </h3>
            <span class="text-xs font-semibold text-slate-500">
                Menampilkan {{ $peminjamans->total() }} data
            </span>
        </div>

        @if ($peminjamans->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs md:text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 border-b border-slate-200/80">
                            <th class="py-3.5 px-4 font-bold uppercase text-[11px]">ID & Tanggal Pengajuan</th>
                            <th class="py-3.5 px-4 font-bold uppercase text-[11px]">Pemohon / Instansi</th>
                            <th class="py-3.5 px-4 font-bold uppercase text-[11px]">Paket & Fasilitas</th>
                            <th class="py-3.5 px-4 font-bold uppercase text-[11px]">Jadwal Pelaksanaan</th>
                            <th class="py-3.5 px-4 font-bold uppercase text-[11px] text-center">Verifikasi Sarpras</th>
                            <th class="py-3.5 px-4 font-bold uppercase text-[11px] text-center">Persetujuan Final</th>
                            <th class="py-3.5 px-4 font-bold uppercase text-[11px] text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($peminjamans as $item)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-4 px-4">
                                    <div class="font-mono font-bold text-slate-900">#{{ $item->id }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">{{ $item->created_at->translatedFormat('d M Y') }}</div>
                                </td>

                                <td class="py-4 px-4">
                                    <div class="font-bold text-slate-900">{{ $item->nama }}</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">{{ $item->email_instansi }}</div>
                                </td>

                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-brand-700 border border-blue-100">
                                        {{ $item->paketPeminjaman?->nama_paket ?: 'Paket Aula' }}
                                    </span>
                                    <div class="text-[11px] font-bold text-slate-700 mt-1">
                                        Rp {{ number_format($item->pembayaran?->total_tagihan ?? 0, 0, ',', '.') }}
                                    </div>
                                </td>

                                <td class="py-4 px-4">
                                    <div class="font-bold text-slate-800">
                                        {{ $item->tanggal_mulai ? $item->tanggal_mulai->translatedFormat('d M Y') : '-' }}
                                    </div>
                                    <div class="text-[11px] text-slate-500">
                                        {{ $item->tanggal_mulai ? $item->tanggal_mulai->format('H:i') : '' }} - {{ $item->tanggal_selesai ? $item->tanggal_selesai->format('H:i') : '' }} WIB
                                    </div>
                                </td>

                                <!-- Verifikasi Sarpras (Admin) -->
                                <td class="py-4 px-4 text-center">
                                    @php
                                        $tahap1 = $item->persetujuans->where('level', 'admin')->first();
                                    @endphp
                                    @if ($item->status === 'approved_1' || $item->status === 'approved_final' || ($tahap1 && $tahap1->status === 'approved'))
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                            <i class="fa-solid fa-check text-[10px]"></i> Disetujui
                                        </span>
                                    @elseif ($item->status === 'rejected')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-100 text-red-800">
                                            <i class="fa-solid fa-xmark text-[10px]"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600">
                                            <i class="fa-regular fa-clock text-[10px]"></i> Pending
                                        </span>
                                    @endif
                                </td>

                                <!-- Persetujuan Final (Kepala Sekolah) -->
                                <td class="py-4 px-4 text-center">
                                    @if ($item->status === 'approved_final')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-emerald-600 text-white shadow-2xs">
                                            <i class="fa-solid fa-circle-check text-[10px]"></i> Disetujui Final
                                        </span>
                                    @elseif ($item->status === 'approved_1')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-amber-400 text-slate-900 animate-pulse">
                                            <i class="fa-solid fa-signature text-[10px]"></i> Butuh Persetujuan
                                        </span>
                                    @elseif ($item->status === 'rejected')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-100 text-red-800">
                                            <i class="fa-solid fa-ban text-[10px]"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-medium bg-slate-100 text-slate-500">
                                            Menunggu Sarpras
                                        </span>
                                    @endif
                                </td>

                                <!-- Tombol Aksi -->
                                <td class="py-4 px-4 text-center">
                                    @if ($item->status === 'approved_1')
                                        <a href="{{ route('kepala-sekolah.peminjaman.show', $item->id) }}"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-900 font-extrabold text-xs transition shadow-2xs">
                                            <i class="fa-solid fa-signature"></i>
                                            <span>Tinjau & Putuskan</span>
                                        </a>
                                    @else
                                        <a href="{{ route('kepala-sekolah.peminjaman.show', $item->id) }}"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs transition">
                                            <i class="fa-regular fa-eye"></i>
                                            <span>Detail</span>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="p-4 border-t border-slate-100">
                {{ $peminjamans->links() }}
            </div>
        @else
            <div class="p-12 text-center text-slate-400 space-y-2">
                <i class="fa-regular fa-folder-open text-3xl block mb-2"></i>
                <h4 class="font-bold text-slate-700 text-sm">Tidak Ada Data Peminjaman</h4>
                <p class="text-xs text-slate-500">Tidak ada permohonan yang sesuai dengan filter pencarian saat ini.</p>
            </div>
        @endif
    </div>

</div>
@endsection
