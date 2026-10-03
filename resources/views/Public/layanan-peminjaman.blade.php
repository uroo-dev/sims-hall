@extends('Public.layout.app')

@section('title', 'Layanan Peminjaman Aula - SMK Negeri 2 Karanganyar')
@section('meta_description', 'Informasi dan reservasi online peminjaman Aula SMKN 2 Karanganyar: cek ketersediaan jadwal, paket tarif, dan fasilitas lengkap.')

@section('content')

    <style>
        /* Calendar specific styling */
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
            position: relative;
        }
        .calendar-day:hover:not(.disabled) {
            background-color: #e2e8f0;
            transform: scale(1.05);
        }
        .calendar-day.active {
            background-color: #0066C4;
            color: white;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(0, 102, 196, 0.35);
        }
        .calendar-day.disabled {
            color: #cbd5e1;
            cursor: not-allowed;
            background-color: transparent !important;
            transform: none !important;
        }
        .calendar-day.today {
            border: 2px solid #0066C4;
        }
        .calendar-day.selected {
            ring: 3px solid #28A745;
            background-color: #dcfce7;
            color: #166534;
            font-weight: 700;
        }
    </style>

    <!-- ============================================================
         1. HERO SECTION
         ============================================================ -->
    <section id="hero" class="relative py-16 md:py-24 overflow-hidden bg-white">
        <!-- Abstract Background Shapes -->
        <div class="absolute top-0 right-0 w-1/3 h-2/3 bg-blue-50 rounded-bl-[10rem] -z-10 opacity-70"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-100 rounded-full -z-10 opacity-50 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Grid Layout: 6 for text, 6 for images -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">

                <!-- LEFT: Text Content -->
                <div class="lg:col-span-6 space-y-6 lg:pr-6">
                    <span class="inline-block text-brand-blue font-bold tracking-wide text-sm sm:text-base uppercase">
                        {{ $sekolah->nama_sekolah ?? 'SMK Negeri 2 Karanganyar' }} – Sekolah Pusat Keunggulan
                    </span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-[1.1]">
                        {{ $aula->judul ?: 'Layanan Peminjaman' }}<br />
                        <span class="text-brand-blue">{{ $aula->nama ?: 'Aula SKANDAKRA' }}</span>
                    </h1>
                    
                    <!-- Bullet Points -->
                    <ul class="space-y-3 text-slate-600 text-sm sm:text-base font-medium pt-2">
                        <li class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-brand-blue flex-shrink-0"></span>
                            <span>Booking Aula Online Terintegrasi.</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-brand-blue flex-shrink-0"></span>
                            <span>Gedung Luas Berkapasitas Hingga 500+ Orang.</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-brand-blue flex-shrink-0"></span>
                            <span>Fasilitas Lengkap (Sound System, Genset, AC/Transit).</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-brand-blue flex-shrink-0"></span>
                            <span>Area Parkir Kendaraan Luas & Aman.</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-brand-blue flex-shrink-0"></span>
                            <span>Kebersihan Terjamin & Petugas Siaga.</span>
                        </li>
                    </ul>

                    <p class="text-slate-600 text-sm leading-relaxed max-w-md pt-2">
                        {{ $aula->deskripsi ?: 'Kami siap membantu menciptakan tempat kegiatan yang nyaman, representatif, dan berkualitas untuk institusi, korporat, maupun umum.' }}
                    </p>

                    <div class="flex flex-wrap gap-4 pt-4">
                        <a href="#informasi"
                            class="bg-brand-blue hover:bg-brand-darkBlue text-white font-bold px-8 py-3.5 rounded-xl text-sm transition-all shadow-lg shadow-blue-500/30 flex items-center gap-2">
                            <span>Pelayanan Kami</span>
                            <i class="fa-solid fa-arrow-down text-xs"></i>
                        </a>
                        <a href="#paket"
                            class="bg-white border-2 border-slate-200 hover:border-brand-blue text-slate-700 hover:text-brand-blue font-bold px-8 py-3.5 rounded-xl text-sm transition-all shadow-sm flex items-center gap-2">
                            <span>Paket & Tarif</span>
                        </a>
                    </div>
                </div>

                <!-- RIGHT: Visual Composition -->
                <div class="lg:col-span-6 relative mt-12 lg:mt-0 flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-[450px] aspect-[4/3]">
                        
                        <!-- Aksen Lingkaran Biru Besar (Background) -->
                        <div class="absolute -bottom-8 -right-8 w-64 h-64 border-[20px] border-brand-blue rounded-full opacity-20 z-0"></div>
                        
                        <!-- Gambar Utama: Auditorium (Dokumentasi 1) -->
                        <div class="absolute top-0 right-0 w-[85%] h-[80%] rounded-3xl overflow-hidden shadow-2xl z-10 border border-slate-100 {{ $aula->has_custom_dokumentasi ? 'bg-slate-100' : 'bg-gradient-to-br from-blue-50 to-slate-100 flex items-center justify-center p-6' }}">
                            @if($aula->has_custom_dokumentasi)
                                <img src="{{ $aula->foto_dokumentasi_url }}"
                                    alt="{{ $aula->nama }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex flex-col items-center justify-center text-center p-4">
                                    <img src="{{ $aula->foto_dokumentasi_url }}" alt="Logo SMK" class="w-24 h-24 object-contain mb-3 drop-shadow-sm">
                                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ $aula->nama }}</span>
                                    <span class="text-[10px] text-slate-500 mt-0.5">Gedung Pertemuan &amp; Serbaguna</span>
                                </div>
                            @endif
                            <div class="absolute top-0 right-0 w-24 h-24 bg-brand-blue rounded-bl-[3rem] opacity-90 pointer-events-none"></div>
                        </div>

                        <!-- Gambar Overlay: Mockup Reservasi / Dokumentasi 2 -->
                        <div class="absolute bottom-0 left-0 w-[55%] sm:w-[60%] rounded-2xl overflow-hidden shadow-2xl border-4 border-white bg-white z-20 transition-transform duration-300 hover:scale-105">
                            @if($aula->has_custom_dokumentasi_2)
                                <img src="{{ $aula->foto_dokumentasi_2_url }}"
                                    alt="Interior {{ $aula->nama }}" class="w-full h-auto object-cover">
                            @else
                                <div class="p-3 bg-blue-50/80 flex items-center gap-2.5">
                                    <img src="{{ $aula->foto_dokumentasi_2_url }}" alt="Logo Alternatif" class="w-9 h-9 object-contain">
                                    <div class="text-left">
                                        <span class="block text-[11px] font-bold text-slate-800 leading-tight">Fasilitas Resmi</span>
                                        <span class="block text-[9px] text-slate-500">SMK Negeri 2 Kra</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ============================================================
         2. INFORMASI LAYANAN
         ============================================================ -->
    <section id="informasi" class="py-16 bg-[#FAFCFF] relative overflow-hidden">
        <div class="absolute top-10 right-10 w-32 h-32 dot-pattern opacity-40 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto mb-14">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-3 tracking-tight">
                    Informasi Layanan Peminjaman Aula
                </h2>
                <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                    Fasilitas sekolah dengan kapasitas luas untuk berbagai kebutuhan acara institusi,<br class="hidden sm:block">
                    perusahaan, dan masyarakat umum.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                
                <!-- LEFT CARD: Info & Images -->
                <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-xl flex flex-col justify-between relative">
                    <div>
                        <!-- Title -->
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center text-lg">
                                <i class="fa-solid fa-circle-info"></i>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold text-slate-900 border-b-2 border-brand-blue pb-1 inline-block">
                                Ketentuan Peminjaman Aula
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                            <!-- Text Content -->
                            <div class="md:col-span-6 space-y-4">
                                <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                    {{ $aula->deskripsi ?: 'Kami menyediakan layanan peminjaman aula sekolah untuk berbagai kebutuhan kegiatan. Mulai dari acara sekolah, organisasi, rapat, seminar, resepsi, hingga kegiatan instansi luar.' }}
                                </p>

                                <div>
                                    <p class="text-slate-800 font-semibold text-xs sm:text-sm mb-1.5">Keunggulan layanan:</p>
                                    <ul class="list-disc list-inside text-slate-600 text-xs sm:text-sm space-y-1 pl-1">
                                        <li>Booking Online terjadwal real-time.</li>
                                        <li>Paket fleksibel (Reguler & Custom Fasilitas).</li>
                                        <li>Didukung tata suara & proyektor berkualitas.</li>
                                        <li>Kebersihan gedung & toilet terawat.</li>
                                        <li>Keamanan 24 jam dengan staf siaga.</li>
                                    </ul>
                                </div>

                                <div class="bg-blue-50/70 border border-blue-100 rounded-xl p-3 text-[11px] text-blue-900 leading-snug">
                                    <i class="fa-solid fa-bell text-brand-blue mr-1"></i>
                                    <strong>Ketentuan Pemesanan:</strong> Pemesanan diajukan minimal <strong>H-{{ $paymentConfig->minimal_hari_booking ?? 3 }}</strong> sebelum pelaksanaan acara.
                                </div>

                                <div class="pt-2">
                                    @auth
                                        @if(auth()->user()->role === 'pelanggan')
                                            <a href="{{ route('customer.peminjaman.create') }}"
                                                class="inline-block bg-[#0066B2] hover:bg-blue-700 text-white font-semibold text-xs sm:text-sm px-6 py-2.5 rounded-full transition-all duration-200 shadow-md">
                                                Mulai Pengajuan Sekarang
                                            </a>
                                        @else
                                            <a href="{{ route('dashboard') }}"
                                                class="inline-block bg-slate-700 hover:bg-slate-800 text-white font-semibold text-xs sm:text-sm px-6 py-2.5 rounded-full transition-all duration-200 shadow-md">
                                                Buka Dashboard Internal
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}"
                                            class="inline-block bg-[#0066B2] hover:bg-blue-700 text-white font-semibold text-xs sm:text-sm px-6 py-2.5 rounded-full transition-all duration-200 shadow-md">
                                            Masuk untuk Meminjam
                                        </a>
                                    @endauth
                                </div>
                            </div>

                            <!-- Images (Overlapping Dokumentasi 1 & 2) -->
                            <div class="md:col-span-6 relative mt-4 md:mt-0 pl-0 sm:pl-2">
                                <div class="relative rounded-2xl overflow-hidden shadow-md h-64 sm:h-80 w-full border border-slate-100 {{ $aula->has_custom_dokumentasi ? 'bg-slate-200' : 'bg-gradient-to-br from-blue-50 to-slate-100 flex items-center justify-center p-6' }}">
                                    @if($aula->has_custom_dokumentasi)
                                        <img src="{{ $aula->foto_dokumentasi_url }}"
                                            alt="{{ $aula->nama }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="flex flex-col items-center justify-center text-center p-4">
                                            <img src="{{ $aula->foto_dokumentasi_url }}" alt="Logo SMK" class="w-20 h-20 object-contain mb-2 drop-shadow-sm">
                                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ $aula->nama }}</span>
                                            <span class="text-[10px] text-slate-500">Peminjaman Aula Terpadu</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="absolute -bottom-3 -left-3 sm:-left-6 w-32 sm:w-44 rounded-xl overflow-hidden shadow-2xl border-2 border-white bg-white transition-transform duration-300 hover:scale-105 hidden sm:block z-20">
                                    @if($aula->has_custom_dokumentasi_2)
                                        <img src="{{ $aula->foto_dokumentasi_2_url }}"
                                            alt="Interior Aula" class="w-full h-auto object-cover">
                                    @else
                                        <div class="p-2.5 bg-blue-50 flex items-center gap-2">
                                            <img src="{{ $aula->foto_dokumentasi_2_url }}" alt="Logo SMK" class="w-8 h-8 object-contain">
                                            <div class="text-left">
                                                <span class="block text-[9px] font-bold text-slate-800 leading-tight">Fasilitas</span>
                                                <span class="block text-[7px] text-slate-500">SMKN 2 Kra</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT CARD: Login / Register / Customer Action -->
                <div class="lg:col-span-5 bg-brand-blue rounded-3xl p-8 sm:p-10 text-white shadow-xl flex flex-col justify-center">
                    @auth
                        <div class="inline-flex items-center gap-2 bg-white/20 text-white px-3 py-1 rounded-full text-xs font-semibold w-max mb-4">
                            <i class="fa-solid fa-user-check"></i>
                            <span>Sedang Login</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Halo, {{ auth()->user()->name }}!</h3>
                        <p class="text-blue-100 text-sm leading-relaxed mb-6">
                            Anda masuk dengan hak akses <strong class="text-white">{{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}</strong>. Kelola peminjaman aula atau buat pengajuan baru langsung melalui portal ini.
                        </p>

                        <div class="space-y-3">
                            @if(auth()->user()->role === 'pelanggan')
                                <a href="{{ route('customer.peminjaman.create') }}"
                                    class="w-full bg-white text-brand-blue font-bold text-sm py-3.5 px-4 rounded-xl hover:bg-blue-50 transition text-center block shadow-md">
                                    <i class="fa-solid fa-calendar-plus mr-1.5"></i> Buat Pengajuan Peminjaman
                                </a>
                                <a href="{{ route('customer.dashboard') }}"
                                    class="w-full border-2 border-white/40 text-white font-bold text-sm py-3.5 px-4 rounded-xl hover:bg-white/10 transition text-center block">
                                    <i class="fa-solid fa-gauge mr-1.5"></i> Buka Dashboard Pelanggan
                                </a>
                            @else
                                <div class="bg-white/15 rounded-xl p-3.5 text-xs text-blue-100 border border-white/20">
                                    <i class="fa-solid fa-circle-info mr-1 text-white"></i> Anda masuk sebagai <strong class="text-white">{{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}</strong>. Pengajuan sewa aula dikhususkan bagi akun peminjam (role <strong>Pelanggan</strong>).
                                </div>
                                <a href="{{ route('dashboard') }}"
                                    class="w-full bg-white text-brand-blue font-bold text-sm py-3.5 px-4 rounded-xl hover:bg-blue-50 transition text-center block shadow-md">
                                    <i class="fa-solid fa-gauge mr-1.5"></i> Buka Dashboard Sistem
                                </a>
                            @endif
                        </div>
                    @else
                        <h3 class="text-2xl font-bold mb-3">Mulai Peminjaman</h3>
                        <p class="text-blue-100 text-sm leading-relaxed mb-8">
                            Masuk ke dashboard peminjaman untuk melakukan reservasi aula sesuai tanggal dan kelengkapan fasilitas pilihanmu.
                        </p>

                        <div class="space-y-4">
                            <a href="{{ route('login') }}"
                                class="w-full bg-brand-lightBlue text-brand-blue font-bold text-sm py-3.5 px-4 rounded-xl hover:bg-white transition text-center block shadow-md">
                                <i class="fa-solid fa-right-to-bracket mr-1.5"></i> Login Akun
                            </a>
                            
                            <div class="flex items-center gap-4 my-4">
                                <div class="h-px bg-blue-400/50 flex-1"></div>
                                <span class="text-xs text-blue-200 font-medium">atau</span>
                                <div class="h-px bg-blue-400/50 flex-1"></div>
                            </div>

                            <a href="{{ route('registrasi') }}"
                                class="w-full border-2 border-white/30 text-white font-bold text-sm py-3.5 px-4 rounded-xl hover:bg-white/10 transition text-center block">
                                <i class="fa-solid fa-user-plus mr-1.5"></i> Daftar Akun Baru
                            </a>
                        </div>
                    @endauth
                </div>

            </div>
        </div>
    </section>

    <!-- ============================================================
         3. PAKET PEMINJAMAN AULA
         ============================================================ -->
    <section id="paket" class="py-16 bg-white relative overflow-hidden">
        <div class="absolute bottom-10 right-10 w-32 h-32 dot-pattern opacity-40 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Header -->
            <div class="mb-12">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-3 tracking-tight">
                            Paket Peminjaman Aula
                        </h2>
                        <p class="text-slate-500 text-sm sm:text-base leading-relaxed max-w-2xl">
                            Ruang serbaguna modern dengan kapasitas besar, fasilitas lengkap, dan lokasi strategis.
                            Ideal untuk pernikahan, seminar, rapat kerja, maupun pertemuan instansi.
                        </p>
                    </div>
                    @if($paymentConfig->minimal_hari_booking)
                        <div class="bg-amber-50 border border-amber-200 text-amber-800 text-xs px-4 py-2 rounded-xl flex items-center gap-2 self-start md:self-auto font-medium">
                            <i class="fa-solid fa-clock"></i>
                            <span>Booking Minimal H-{{ $paymentConfig->minimal_hari_booking }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Dynamic Pricing Grid -->
            @if($paketPeminjamans->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                    @foreach($paketPeminjamans as $paket)
                        @php
                            $isUnggulan = strtolower($paket->kategori) === 'unggulan';
                        @endphp
                        <div class="bg-white border-2 {{ $isUnggulan ? 'border-brand-blue shadow-lg relative' : 'border-slate-200 hover:border-brand-blue shadow-sm' }} rounded-3xl p-6 transition-all duration-200 flex flex-col justify-between">
                            
                            <!-- Kategori Badge -->
                            <div class="mb-4">
                                <span class="{{ $isUnggulan ? 'bg-brand-blue text-white' : 'bg-blue-50 text-brand-blue' }} text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider inline-block">
                                    {{ $paket->kategori ?: 'Paket Sewa' }}
                                </span>
                            </div>

                            <div class="flex-1">
                                <h3 class="font-bold text-slate-900 text-lg mb-2">
                                    {{ $paket->nama_paket ?: 'Paket '.ucfirst($paket->kategori) }}
                                </h3>

                                <!-- Price -->
                                <div class="flex items-baseline gap-1 mb-4 pb-4 border-b border-slate-100">
                                    <span class="text-xs font-medium text-slate-400">Rp</span>
                                    <span class="text-3xl font-black text-brand-blue">
                                        {{ number_format($paket->harga, 0, ',', '.') }}
                                    </span>
                                    <span class="text-xs text-slate-400 font-medium">/ {{ $paket->durasi }}</span>
                                </div>

                                @if($paket->harga_dp)
                                    <div class="text-[11px] text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg font-semibold inline-block mb-4 border border-emerald-100">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> DP Min: Rp {{ number_format($paket->harga_dp, 0, ',', '.') }}
                                    </div>
                                @endif

                                <!-- Facilities list -->
                                <div class="space-y-2 mb-6">
                                    <p class="text-slate-800 font-bold text-xs uppercase tracking-wider mb-2">Fasilitas Termasuk:</p>
                                    @if($paket->facilities->isNotEmpty())
                                        <ul class="space-y-2 text-slate-700 text-xs font-medium">
                                            @foreach($paket->facilities as $fac)
                                                <li class="flex items-start gap-2">
                                                    <i class="fa-regular fa-circle-check text-brand-blue mt-0.5 flex-shrink-0"></i>
                                                    <span>{{ $fac->judul }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @elseif($paket->deskripsi)
                                        <div class="text-slate-600 text-xs leading-relaxed space-y-1">
                                            {!! nl2br(e($paket->deskripsi)) !!}
                                        </div>
                                    @else
                                        <p class="text-xs text-slate-400 italic">Fasilitas standar aula & sound system.</p>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-100">
                                @auth
                                    @if(auth()->user()->role === 'pelanggan')
                                        <a href="{{ route('customer.peminjaman.create', ['paket' => $paket->id]) }}"
                                            class="w-full {{ $isUnggulan ? 'bg-brand-blue text-white hover:bg-brand-darkBlue shadow-md' : 'bg-white border-2 border-brand-blue text-brand-blue hover:bg-brand-blue hover:text-white' }} text-xs font-bold py-3 rounded-xl transition text-center block">
                                            Pesan Paket Ini
                                        </a>
                                    @else
                                        <button type="button" onclick="openModal('Pengajuan Khusus Pelanggan', 'Akun Anda saat ini memiliki role {{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}. Pengajuan dan pemesanan aula hanya dapat dilakukan oleh akun dengan role Pelanggan.')"
                                            class="w-full bg-slate-100 border border-slate-300 text-slate-600 hover:bg-slate-200 text-xs font-bold py-3 rounded-xl transition text-center block">
                                            Pesan Paket Ini
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"
                                        class="w-full {{ $isUnggulan ? 'bg-brand-blue text-white hover:bg-brand-darkBlue shadow-md' : 'bg-white border-2 border-brand-blue text-brand-blue hover:bg-brand-blue hover:text-white' }} text-xs font-bold py-3 rounded-xl transition text-center block">
                                        Pesan Paket Ini
                                    </a>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State jika paket peminjaman belum tersedia di database -->
                <div class="bg-white border border-slate-200 rounded-3xl p-10 sm:p-14 text-center max-w-2xl mx-auto mb-10 shadow-sm">
                    <div class="w-16 h-16 rounded-full bg-blue-50 text-brand-blue flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Paket Peminjaman</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-6">
                        Daftar paket peminjaman aula saat ini belum dipublikasikan atau sedang dalam penyesuaian oleh pihak pengelola. Silakan cek kembali dalam waktu dekat atau hubungi pihak sekolah.
                    </p>
                    <a href="#informasi" class="inline-flex items-center gap-2 text-xs font-bold text-brand-blue hover:text-brand-darkBlue">
                        <i class="fa-solid fa-circle-info"></i> Lihat Ketentuan Peminjaman
                    </a>
                </div>
            @endif

            <!-- Bottom Action Button -->
            <div class="text-center relative pt-4">
                <div class="absolute inset-x-0 top-1/2 h-px bg-slate-200 -z-10"></div>
                @auth
                    @if(auth()->user()->role === 'pelanggan')
                        <a href="{{ route('customer.peminjaman.create') }}"
                            class="inline-block bg-brand-blue hover:bg-brand-darkBlue text-white font-bold text-sm px-10 py-3.5 rounded-full transition-all duration-200 shadow-lg relative z-10">
                            <i class="fa-solid fa-paper-plane mr-2"></i> Pinjam Sekarang
                        </a>
                    @else
                        <button type="button" onclick="openModal('Pengajuan Khusus Pelanggan', 'Akun Anda saat ini memiliki role {{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}. Pengajuan aula hanya dapat dilakukan oleh akun dengan role Pelanggan.')"
                            class="inline-block bg-slate-600 hover:bg-slate-700 text-white font-bold text-sm px-10 py-3.5 rounded-full transition-all duration-200 shadow-lg relative z-10">
                            <i class="fa-solid fa-circle-info mr-2"></i> Pengajuan Khusus Pelanggan
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                        class="inline-block bg-brand-blue hover:bg-brand-darkBlue text-white font-bold text-sm px-10 py-3.5 rounded-full transition-all duration-200 shadow-lg relative z-10">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Pinjam Sekarang
                    </a>
                @endauth
            </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         4. FASILITAS UNGGULAN
         ============================================================ -->
    <section id="fasilitas" class="py-16 bg-[#F8FAFC] relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header with Capacity Badge -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-6">
                <div class="max-w-2xl">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-3 tracking-tight">
                        Fasilitas Unggulan
                    </h2>
                    <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                        Kami menyediakan sarana prasarana penunjang untuk mensukseskan acara Anda,<br class="hidden sm:block">
                        didukung oleh fasilitas sekolah yang lengkap dan terawat.
                    </p>
                </div>
                <!-- Capacity Badge -->
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 flex flex-col items-center justify-center text-center shadow-sm min-w-[140px]">
                    <i class="fa-solid fa-users text-brand-blue text-xl mb-1"></i>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Kapasitas</span>
                    <span class="text-2xl font-black text-brand-blue">1.200+</span>
                </div>
            </div>

            <!-- Facilities Grid from Database -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @if($facilities->isNotEmpty())
                    @foreach($facilities as $index => $facility)
                        @php
                            $isEven = $index % 2 === 1;
                            $titleLower = strtolower($facility->judul);
                            
                            $icon = 'fa-circle-check';
                            if (str_contains($titleLower, 'sound') || str_contains($titleLower, 'audio') || str_contains($titleLower, 'mic')) {
                                $icon = 'fa-volume-high';
                            } elseif (str_contains($titleLower, 'parkir') || str_contains($titleLower, 'mobil') || str_contains($titleLower, 'motor')) {
                                $icon = 'fa-car';
                            } elseif (str_contains($titleLower, 'genset') || str_contains($titleLower, 'listrik') || str_contains($titleLower, 'daya')) {
                                $icon = 'fa-bolt';
                            } elseif (str_contains($titleLower, 'transit') || str_contains($titleLower, 'ruang') || str_contains($titleLower, 'vip') || str_contains($titleLower, 'kamar')) {
                                $icon = 'fa-door-open';
                            } elseif (str_contains($titleLower, 'kursi') || str_contains($titleLower, 'meja')) {
                                $icon = 'fa-chair';
                            } elseif (str_contains($titleLower, 'proyektor') || str_contains($titleLower, 'screen') || str_contains($titleLower, 'layar')) {
                                $icon = 'fa-video';
                            } elseif (str_contains($titleLower, 'ac') || str_contains($titleLower, 'pendingin')) {
                                $icon = 'fa-snowflake';
                            } elseif (str_contains($titleLower, 'wifi') || str_contains($titleLower, 'internet')) {
                                $icon = 'fa-wifi';
                            }
                        @endphp

                        @if($isEven)
                            <!-- Blue Solid Card -->
                            <div class="bg-brand-blue text-white rounded-3xl p-6 flex flex-col h-64 sm:h-72 transition-transform hover:-translate-y-1 shadow-lg shadow-blue-500/20">
                                <div class="w-12 h-12 rounded-xl bg-white/20 text-white flex items-center justify-center text-xl mb-6">
                                    <i class="fa-solid {{ $icon }}"></i>
                                </div>
                                <h3 class="text-lg font-bold mb-2">{{ $facility->judul }}</h3>
                                <p class="text-blue-100 text-sm leading-relaxed overflow-hidden text-ellipsis line-clamp-4">
                                    {{ $facility->deskripsi ?: 'Tersedia untuk menunjang kelancaran dan kenyamanan jalannya acara Anda.' }}
                                </p>
                            </div>
                        @else
                            <!-- White Card -->
                            <div class="bg-white border border-slate-200 rounded-3xl p-6 flex flex-col h-64 sm:h-72 transition-transform hover:-translate-y-1 shadow-sm">
                                <div class="w-12 h-12 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center text-xl mb-6">
                                    <i class="fa-solid {{ $icon }}"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 mb-2">{{ $facility->judul }}</h3>
                                <p class="text-slate-500 text-sm leading-relaxed overflow-hidden text-ellipsis line-clamp-4">
                                    {{ $facility->deskripsi ?: 'Tersedia untuk menunjang kelancaran dan kenyamanan jalannya acara Anda.' }}
                                </p>
                            </div>
                        @endif
                    @endforeach
                @else
                    <!-- Fallback Facilities -->
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 flex flex-col h-64 sm:h-72 transition-transform hover:-translate-y-1">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center text-xl mb-6">
                            <i class="fa-solid fa-volume-high"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Sound System</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            Sistem tata suara profesional dengan 4 wireless mic dan mixer audio multi-channel.
                        </p>
                    </div>

                    <div class="bg-brand-blue text-white rounded-3xl p-6 flex flex-col h-64 sm:h-72 transition-transform hover:-translate-y-1 shadow-lg shadow-blue-500/20">
                        <div class="w-12 h-12 rounded-xl bg-white/20 text-white flex items-center justify-center text-xl mb-6">
                            <i class="fa-solid fa-car"></i>
                        </div>
                        <h3 class="text-lg font-bold mb-2">Parkir Luas</h3>
                        <p class="text-blue-100 text-sm leading-relaxed">
                            Area parkir yang mampu menampung hingga 100 mobil dan 300 motor dengan aman.
                        </p>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-3xl p-6 flex flex-col h-64 sm:h-72 transition-transform hover:-translate-y-1">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center text-xl mb-6">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Genset Cadangan</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            Backup listrik otomatis berkapasitas tinggi untuk menjamin kelancaran acara tanpa padam.
                        </p>
                    </div>

                    <div class="bg-brand-blue text-white rounded-3xl p-6 flex flex-col h-64 sm:h-72 transition-transform hover:-translate-y-1 shadow-lg shadow-blue-500/20">
                        <div class="w-12 h-12 rounded-xl bg-white/20 text-white flex items-center justify-center text-xl mb-6">
                            <i class="fa-solid fa-door-open"></i>
                        </div>
                        <h3 class="text-lg font-bold mb-2">Ruang Transit</h3>
                        <p class="text-blue-100 text-sm leading-relaxed">
                            Tersedia ruang transit VIP ber-AC untuk transit tamu kehormatan maupun ruang rias.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- ============================================================
         5. CEK KETERSEDIAAN JADWAL AULA
         ============================================================ -->
    <section id="jadwal" class="py-16 bg-white relative overflow-hidden">
        <div class="absolute bottom-10 left-10 w-32 h-32 dot-pattern opacity-40 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left: Text & Legend -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="inline-flex items-center gap-2 bg-blue-50 text-brand-blue text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">
                        <i class="fa-regular fa-calendar-check"></i>
                        <span>Kalender Real-Time</span>
                    </div>

                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Cek Ketersediaan
                    </h2>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Pilih tanggal rencana kegiatan Anda untuk melihat apakah aula tersedia. Tanggal yang berwarna biru sudah terisi atau sedang dalam proses peminjaman pihak lain.
                    </p>

                    <!-- Legend -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center gap-3 bg-blue-50/50 border border-blue-100 rounded-xl px-4 py-3">
                            <div class="w-4 h-4 rounded-md bg-brand-blue shadow-sm"></div>
                            <span class="text-sm font-semibold text-slate-700">Terisi / Sedang Diproses</span>
                        </div>
                        <div class="flex items-center gap-3 bg-white border border-slate-200 rounded-xl px-4 py-3">
                            <div class="w-4 h-4 rounded-md border-2 border-slate-300"></div>
                            <span class="text-sm font-semibold text-slate-700">Tersedia untuk Dipinjam</span>
                        </div>
                    </div>

                    <!-- Note -->
                    <div class="text-xs text-slate-500 bg-slate-50 p-4 rounded-2xl border border-slate-200">
                        <p class="flex items-center gap-2 font-semibold text-slate-700 mb-1">
                            <i class="fa-solid fa-circle-exclamation text-brand-blue"></i>
                            <span>Catatan Reservasi:</span>
                        </p>
                        <p>Pengajuan peminjaman dianjurkan minimal <strong>H-{{ $paymentConfig->minimal_hari_booking ?? 3 }}</strong> sebelum tanggal pelaksanaan untuk verifikasi berkas dan teknis ruangan.</p>
                    </div>
                </div>

                <!-- Right: Interactive Dynamic Calendar -->
                <div class="lg:col-span-7">
                    <div class="bg-[#F0F6FF] rounded-3xl p-6 sm:p-8 border border-blue-100 shadow-md max-w-lg mx-auto lg:mx-0 lg:ml-auto">
                        <!-- Calendar Header -->
                        <div class="flex justify-between items-center mb-6">
                            <h3 id="cal-month-year" class="text-lg font-bold text-slate-800 tracking-tight">
                                Bulan Tahun
                            </h3>
                            <div class="flex gap-2">
                                <button id="cal-prev-btn" onclick="prevMonth()" title="Bulan Sebelumnya"
                                    class="w-9 h-9 rounded-full bg-white shadow-sm border border-slate-200 flex items-center justify-center text-slate-600 hover:text-brand-blue hover:border-brand-blue transition-all cursor-pointer">
                                    <i class="fa-solid fa-chevron-left text-xs"></i>
                                </button>
                                <button id="cal-next-btn" onclick="nextMonth()" title="Bulan Berikutnya"
                                    class="w-9 h-9 rounded-full bg-white shadow-sm border border-slate-200 flex items-center justify-center text-slate-600 hover:text-brand-blue hover:border-brand-blue transition-all cursor-pointer">
                                    <i class="fa-solid fa-chevron-right text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Days of Week -->
                        <div class="calendar-grid mb-2 text-center text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            <div>Min</div>
                            <div>Sen</div>
                            <div>Sel</div>
                            <div>Rab</div>
                            <div>Kam</div>
                            <div>Jum</div>
                            <div>Sab</div>
                        </div>

                        <!-- Calendar Dates Grid -->
                        <div id="calendar-days-container" class="calendar-grid text-center">
                            <!-- Populated by JavaScript -->
                        </div>

                        <!-- Selected Date Info Banner -->
                        <div id="selected-date-info" class="mt-6 pt-4 border-t border-blue-200/60 hidden">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                                <div>
                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Tanggal Dipilih</span>
                                    <span id="info-date-text" class="text-sm font-bold text-slate-800">12 Oktober 2026</span>
                                    <span id="info-status-badge" class="inline-block mt-0.5 text-[11px] px-2 py-0.5 rounded font-semibold">Tersedia</span>
                                </div>
                                <div id="info-action-btn">
                                    @auth
                                        @if(auth()->user()->role === 'pelanggan')
                                            <a href="{{ route('customer.peminjaman.create') }}" class="bg-brand-blue hover:bg-brand-darkBlue text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-xs">
                                                Booking Sekarang
                                            </a>
                                        @else
                                            <button type="button" onclick="openModal('Pengajuan Khusus Pelanggan', 'Akun Anda saat ini memiliki role {{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}. Pengajuan sewa aula hanya dapat dilakukan oleh akun dengan role Pelanggan.')" class="bg-slate-100 border border-slate-300 text-slate-700 text-xs font-bold px-4 py-2 rounded-xl transition shadow-xs">
                                                Booking Sekarang
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="bg-brand-blue hover:bg-brand-darkBlue text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-xs">
                                            Login untuk Booking
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- CALENDAR JAVASCRIPT LOGIC -->
    @push('scripts')
    <script>
        // Data tanggal yang sudah terisi dari database (format: 'YYYY-MM-DD')
        const bookedDates = @json($bookedDates ?? []);
        const minBookingDays = {{ (int) ($paymentConfig->minimal_hari_booking ?? 3) }};

        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        let currentDate = new Date();
        let activeMonth = currentDate.getMonth();
        let activeYear = currentDate.getFullYear();

        function renderCalendar() {
            const container = document.getElementById('calendar-days-container');
            const header = document.getElementById('cal-month-year');
            if (!container || !header) return;

            header.innerText = `${monthNames[activeMonth]} ${activeYear}`;
            container.innerHTML = '';

            const firstDayIndex = new Date(activeYear, activeMonth, 1).getDay();
            const totalDaysInMonth = new Date(activeYear, activeMonth + 1, 0).getDate();
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            // Empty slots for days before 1st of month
            for (let i = 0; i < firstDayIndex; i++) {
                const emptySlot = document.createElement('div');
                emptySlot.className = 'calendar-day disabled';
                container.appendChild(emptySlot);
            }

            // Fill days of the month
            for (let day = 1; day <= totalDaysInMonth; day++) {
                const daySlot = document.createElement('div');
                const thisDate = new Date(activeYear, activeMonth, day);
                thisDate.setHours(0, 0, 0, 0);

                const dateStr = `${activeYear}-${String(activeMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                daySlot.innerText = day;

                const isBooked = bookedDates && bookedDates[dateStr];
                const isPast = thisDate < today;

                let classes = 'calendar-day';

                if (isPast) {
                    classes += ' disabled text-slate-300';
                } else if (isBooked) {
                    classes += ' active';
                    daySlot.title = `Sudah terisi (${bookedDates[dateStr].nama || 'Peminjaman'})`;
                } else {
                    classes += ' bg-white border border-slate-200 text-slate-700';
                }

                if (thisDate.getTime() === today.getTime()) {
                    classes += ' today';
                }

                daySlot.className = classes;

                // Click event on available or booked dates
                if (!isPast) {
                    daySlot.addEventListener('click', () => {
                        selectCalendarDate(day, dateStr, isBooked);
                    });
                }

                container.appendChild(daySlot);
            }
        }

        function selectCalendarDate(day, dateStr, isBooked) {
            const infoBanner = document.getElementById('selected-date-info');
            const dateText = document.getElementById('info-date-text');
            const badge = document.getElementById('info-status-badge');
            const actionBtn = document.getElementById('info-action-btn');

            if (!infoBanner) return;

            infoBanner.classList.remove('hidden');
            dateText.innerText = `${day} ${monthNames[activeMonth]} ${activeYear}`;

            if (isBooked) {
                badge.className = 'inline-block mt-0.5 text-[11px] px-2 py-0.5 rounded font-semibold bg-rose-100 text-rose-700';
                badge.innerText = 'Terisi / Sedang Diproses';
                actionBtn.innerHTML = `
                    <button type="button" onclick="openModal('Jadwal Terisi', 'Tanggal ${day} ${monthNames[activeMonth]} ${activeYear} sudah terisi atau sedang dalam proses peminjaman oleh pihak lain. Silakan pilih tanggal alternatif yang tersedia.')" class="bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold px-3 py-1.5 rounded-xl hover:bg-rose-100 transition">
                        Lihat Keterangan
                    </button>
                `;
            } else {
                badge.className = 'inline-block mt-0.5 text-[11px] px-2 py-0.5 rounded font-semibold bg-emerald-100 text-emerald-700';
                badge.innerText = 'Tersedia untuk Dipinjam';

                @auth
                    @if(auth()->user()->role === 'pelanggan')
                        actionBtn.innerHTML = `
                            <a href="{{ route('customer.peminjaman.create') }}?tgl=${dateStr}" class="bg-brand-blue hover:bg-brand-darkBlue text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-xs inline-block">
                                Ajukan Tanggal Ini
                            </a>
                        `;
                    @else
                        actionBtn.innerHTML = `
                            <button type="button" onclick="openModal('Pengajuan Khusus Pelanggan', 'Pengajuan sewa aula pada tanggal ${day} ${monthNames[activeMonth]} ${activeYear} hanya dapat dilakukan oleh akun dengan role Pelanggan.')" class="bg-slate-100 border border-slate-300 text-slate-700 text-xs font-bold px-4 py-2 rounded-xl transition shadow-xs inline-block">
                                Ajukan Tanggal Ini
                            </button>
                        `;
                    @endif
                @else
                    actionBtn.innerHTML = `
                        <a href="{{ route('login') }}" class="bg-brand-blue hover:bg-brand-darkBlue text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-xs inline-block">
                            Login untuk Booking
                        </a>
                    `;
                @endauth
            }
        }

        function prevMonth() {
            activeMonth--;
            if (activeMonth < 0) {
                activeMonth = 11;
                activeYear--;
            }
            renderCalendar();
        }

        function nextMonth() {
            activeMonth++;
            if (activeMonth > 11) {
                activeMonth = 0;
                activeYear++;
            }
            renderCalendar();
        }

        // Initialize calendar on DOM loaded
        document.addEventListener('DOMContentLoaded', () => {
            renderCalendar();
        });
    </script>
    @endpush

@endsection
