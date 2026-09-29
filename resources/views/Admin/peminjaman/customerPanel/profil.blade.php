@extends('Admin.layout.app')

@section('title', 'Profil User - SIMS Aula SMK N 2 Karanganyar')
@section('page_title', 'Profil')

@section('content')
<div class="space-y-6">

@if ($user)
    <div class="bg-white rounded-2xl figma-card-shadow p-6 md:p-8 border border-blue-50/50">

        <!-- Header Profil -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 border border-blue-200 flex items-center justify-center text-[#0070ba] text-2xl font-bold">
                    <i class="fa-regular fa-user"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $user->name ?? 'Pengguna' }}</h2>
                    <p class="text-xs text-gray-500 font-medium">{{ $user->email ?? '-' }}</p>
                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-[#0070ba]">
                        Akun Peminjam / Organisasi
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('customer.dashboard') }}" class="px-4 py-2 border border-gray-200 text-gray-700 text-xs font-semibold rounded-xl hover:bg-gray-50 transition">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Detail Informasi Pengguna & Organisasi -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 text-xs md:text-sm">
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider text-[#0070ba]">
                    Informasi Akun
                </h3>
                <div class="space-y-3 bg-gray-50/60 p-5 rounded-xl border border-gray-100">
                    <div>
                        <span class="text-gray-400 block text-xs">Nama Lengkap</span>
                        <span class="font-bold text-gray-800">{{ $user->name ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-xs">Username</span>
                        <span class="font-medium text-gray-800">{{ $user->username ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-xs">Email Instansi / Kontak</span>
                        <span class="font-medium text-gray-800">{{ $user->email ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-xs">Role Akses</span>
                        <span class="font-semibold text-gray-800 capitalize">{{ $user->role ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider text-[#0070ba]">
                    Instansi & Hak Akses Peminjaman
                </h3>
                <div class="space-y-3 bg-gray-50/60 p-5 rounded-xl border border-gray-100">
                    <div>
                        <span class="text-gray-400 block text-xs">Jenis Akun</span>
                        <span class="font-bold text-gray-800">Organisasi Eksternal / Mitra Sekolah</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-xs">Status Verifikasi</span>
                        <span class="inline-flex items-center gap-1 font-semibold text-[#00a844]">
                            <i class="fa-regular fa-circle-check text-xs"></i> Terverifikasi Aktif
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-xs">Hak Akses Layanan</span>
                        <span class="text-gray-700">Peminjaman Gedung Aula, Sarpras Audio Visual, dan Fasilitas TEFA.</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
@else
    <div class="bg-white rounded-2xl figma-card-shadow p-12 text-center border border-blue-50/50">
        <div class="flex flex-col items-center justify-center text-gray-400 space-y-3">
            <div class="w-16 h-16 rounded-full bg-blue-50/60 border border-blue-100 flex items-center justify-center text-[#0070ba] text-3xl">
                <i class="fa-regular fa-user"></i>
            </div>
            <div class="space-y-1">
                <h3 class="text-base font-bold text-gray-800">Data Profil Tidak Ditemukan</h3>
                <p class="text-xs md:text-sm text-gray-500 max-w-sm">Silakan login kembali untuk melihat informasi profil Anda.</p>
            </div>
        </div>
    </div>
@endif

</div>
@endsection
