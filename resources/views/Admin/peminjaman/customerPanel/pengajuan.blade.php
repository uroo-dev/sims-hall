@extends('Admin.layout.app')

@section('title', 'Formulir Pengajuan Sewa Aula - SMKN 2 Karanganyar')

@section('content')
<div class="space-y-6">

    <!-- HEADER TITLE -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl md:text-2xl font-extrabold text-slate-800 tracking-tight">
                Pengajuan Peminjaman Aula
            </h1>
            <p class="text-xs md:text-sm text-slate-500 mt-0.5">
                Lengkapi formulir dan dokumen persyaratan untuk memproses jadwal sewa gedung aula SMKN 2 Karanganyar.
            </p>
        </div>
        <div>
            <a href="{{ route('customer.paket') }}"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold transition shadow-2xs">
                <i class="fa-solid fa-arrow-left text-xs text-slate-400"></i>
                <span>Kembali ke Paket</span>
            </a>
        </div>
    </div>

    <!-- STEP PROGRESS TRACKER -->
    <div class="bg-white rounded-2xl p-4 md:p-6 figma-card-shadow border border-slate-100">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 text-center">
            <!-- Step 1 -->
            <div class="flex items-center gap-2.5 p-2 rounded-xl bg-slate-50 text-slate-500">
                <div class="w-7 h-7 rounded-full bg-emerald-500 text-white font-bold text-xs flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div class="text-left">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Langkah 1</span>
                    <span class="text-xs font-bold text-slate-700">Pilih Paket</span>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="flex items-center gap-2.5 p-2 rounded-xl bg-brand-50 border border-brand-200 text-brand-700">
                <div class="w-7 h-7 rounded-full bg-brand-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-xs">
                    2
                </div>
                <div class="text-left">
                    <span class="block text-[10px] font-bold text-brand-500 uppercase">Langkah 2</span>
                    <span class="text-xs font-black text-brand-700">Form Pengajuan</span>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="flex items-center gap-2.5 p-2 rounded-xl bg-slate-50 text-slate-400 opacity-60">
                <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-500 font-bold text-xs flex items-center justify-center flex-shrink-0">
                    3
                </div>
                <div class="text-left">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Langkah 3</span>
                    <span class="text-xs font-bold text-slate-500">Pembayaran</span>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="flex items-center gap-2.5 p-2 rounded-xl bg-slate-50 text-slate-400 opacity-60">
                <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-500 font-bold text-xs flex items-center justify-center flex-shrink-0">
                    4
                </div>
                <div class="text-left">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Langkah 4</span>
                    <span class="text-xs font-bold text-slate-500">Verifikasi</span>
                </div>
            </div>
        </div>
    </div>

    <!-- NOTIFIKASI ERROR VALIDASI -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 space-y-2 shadow-sm animate-fade-in">
            <div class="flex items-center gap-2 font-bold text-xs md:text-sm text-red-700">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Terdapat kesalahan pada isian formulir:</span>
            </div>
            <ul class="list-disc list-inside text-xs text-red-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORM PENGAJUAN -->
    <form action="{{ route('customer.peminjaman.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- KOLOM KIRI (SPAN 2): FORMULIR DATA PENGAJUAN -->
            <div class="lg:col-span-2 space-y-6">

                <!-- 1. DATA PEMOHON & INSTANSI -->
                <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 space-y-4">
                    <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-user-pen"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Identitas Pemohon / Instansi</h2>
                            <p class="text-xs text-slate-500">Informasi penanggung jawab peminjaman aula</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nama" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                                Nama Pemohon / Penanggung Jawab <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" id="nama" required maxlength="150"
                                value="{{ old('nama', $user?->name) }}"
                                placeholder="Contoh: Budi Santoso / CV Mahakarya"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition font-medium">
                        </div>

                        <div>
                            <label for="email_instansi" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                                Email Resmi / Instansi <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email_instansi" id="email_instansi" required maxlength="150"
                                value="{{ old('email_instansi', $user?->email) }}"
                                placeholder="Contoh: panitia@instansi.go.id"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition font-medium">
                        </div>
                    </div>
                </div>

                <!-- 2. WAKTU & JADWAL PEMAKAIAN AULA -->
                <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 space-y-4">
                    <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-calendar-day"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Jadwal & Waktu Acara Aula</h2>
                            <p class="text-xs text-slate-500">Tentukan rentang tanggal dan jam pelaksanaan kegiatan</p>
                        </div>
                    </div>

                    @php
                        $minHariBooking = (int) ($paymentConfig->minimal_hari_booking ?? 3);
                        $earliestDate = now()->startOfDay()->addDays($minHariBooking)->setHour(8)->setMinute(0);
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tanggal_mulai" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                                Waktu Mulai Peminjaman <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="tanggal_mulai" id="tanggal_mulai" required
                                min="{{ $earliestDate->format('Y-m-d\TH:i') }}"
                                value="{{ old('tanggal_mulai', $earliestDate->format('Y-m-d\TH:i')) }}"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('tanggal_mulai') border-red-500 @else border-slate-200 @enderror rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition font-medium">
                            @error('tanggal_mulai')
                                <p class="text-[11px] text-red-600 font-semibold mt-1">{{ $message }}</p>
                            @else
                                <p class="text-[11px] text-slate-400 mt-1">Pemesanan minimal {{ $minHariBooking }} hari sebelum acara (paling awal: {{ $earliestDate->translatedFormat('d F Y, H:i') }} WIB).</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_selesai" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                                Waktu Selesai Peminjaman <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="tanggal_selesai" id="tanggal_selesai" required
                                min="{{ $earliestDate->copy()->addHour()->format('Y-m-d\TH:i') }}"
                                value="{{ old('tanggal_selesai', $earliestDate->copy()->setHour(20)->setMinute(0)->format('Y-m-d\TH:i')) }}"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border @error('tanggal_selesai') border-red-500 @else border-slate-200 @enderror rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition font-medium">
                            @error('tanggal_selesai')
                                <p class="text-[11px] text-red-600 font-semibold mt-1">{{ $message }}</p>
                            @else
                                <p class="text-[11px] text-slate-400 mt-1">Tanggal dan jam selesai acara / aula dikosongkan.</p>
                            @enderror
                        </div>
                    </div>

                    @if(isset($approvedBookings) && $approvedBookings->isNotEmpty())
                        <div class="pt-3 border-t border-slate-100">
                            <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wider flex items-center gap-1.5 mb-2">
                                <i class="fa-solid fa-calendar-xmark text-amber-600"></i>
                                Jadwal Aula yang Sudah Terisi (Approved):
                            </span>
                            <div class="flex flex-wrap gap-2">
                                @foreach($approvedBookings as $b)
                                    <div class="px-2.5 py-1 bg-amber-50 border border-amber-200 text-amber-800 rounded-lg text-[11px] font-medium flex items-center gap-1.5">
                                        <i class="fa-solid fa-clock text-[10px] text-amber-600"></i>
                                        <span>{{ \Carbon\Carbon::parse($b->tanggal_mulai)->translatedFormat('d M Y (H:i') }} - {{ \Carbon\Carbon::parse($b->tanggal_selesai)->translatedFormat('H:i)') }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1.5 italic">* Pastikan jadwal pilihan Anda tidak bentrok dengan rentang waktu di atas.</p>
                        </div>
                    @endif
                </div>

                <!-- 3. DOKUMEN & SURAT PENGANTAR -->
                <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 space-y-4">
                    <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-file-contract"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Dokumen Pengajuan & Keperluan Acara</h2>
                            <p class="text-xs text-slate-500">Unggah berkas surat resmi instansi dan jelaskan kebutuhan kegiatan</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="surat_pengantar" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                                Berkas Surat Permohonan / Pengantar Resmi
                            </label>
                            <div class="p-4 bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl space-y-2">
                                <input type="file" name="surat_pengantar" id="surat_pengantar" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                    class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 cursor-pointer">
                                <p class="text-[11px] text-slate-400">
                                    Format berkas: PDF, JPG, PNG, DOC, DOCX. Ukuran maksimal: 5MB. Surat pengantar ditujukan kepada Kepala SMK Negeri 2 Karanganyar.
                                </p>
                            </div>
                        </div>

                        <div>
                            <label for="catatan" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                                Deskripsi Kegiatan / Catatan Khusus
                            </label>
                            <textarea name="catatan" id="catatan" rows="3" maxlength="2000"
                                placeholder="Tuliskan nama acara, estimasi jumlah peserta, atau permintaan tata letak kursi/fasilitas tambahan..."
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition leading-relaxed">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN (SPAN 1): PILIHAN PAKET & RINGKASAN BIAYA -->
            <div class="space-y-6">

                <!-- CARD PAKET TERPILIH -->
                <div class="bg-white rounded-2xl p-6 figma-card-shadow border-2 border-brand-500 space-y-4 relative overflow-hidden">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <span class="text-xs font-bold text-brand-600 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-solid fa-cube"></i>
                            Pilihan Paket Aula
                        </span>
                        <a href="{{ route('customer.paket') }}" class="text-[11px] font-semibold text-brand-600 hover:underline">
                            Ganti Paket
                        </a>
                    </div>

                    <!-- Dropdown Pemilihan Paket -->
                    <div>
                        <label for="paket_peminjaman_id" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                            Pilih Paket Peminjaman <span class="text-red-500">*</span>
                        </label>
                        <select name="paket_peminjaman_id" id="paket_peminjaman_id" required onchange="updateSelectedPaket(this.value)"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                            @foreach ($pakets as $p)
                                <option value="{{ $p->id }}" {{ (old('paket_peminjaman_id', $selectedPaket?->id) == $p->id) ? 'selected' : '' }}>
                                    {{ $p->nama_paket ?: ucfirst($p->kategori) }} - Rp {{ number_format($p->harga, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Ringkasan Biaya Paket Dinamis -->
                    <div class="p-4 bg-gradient-to-br from-blue-50/70 to-slate-50 border border-blue-100 rounded-xl space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-600 font-medium">Harga Sewa Paket:</span>
                            <span class="text-base md:text-lg font-black text-brand-700" id="summaryHarga">
                                Rp {{ number_format($selectedPaket?->harga ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-xs pt-2 border-t border-blue-100">
                            <span class="text-slate-600 font-medium flex items-center gap-1">
                                <i class="fa-solid fa-shield-halved text-emerald-600"></i>
                                Minimal DP (Uang Muka):
                            </span>
                            <span class="font-extrabold text-emerald-600" id="summaryDp">
                                @if ($selectedPaket && $selectedPaket->harga_dp)
                                    Rp {{ number_format($selectedPaket->harga_dp, 0, ',', '.') }}
                                @else
                                    Rp {{ number_format(($selectedPaket?->harga ?? 0) * 0.3, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Fasilitas Paket -->
                    <div class="space-y-2">
                        <span class="block text-xs font-bold text-slate-700 uppercase tracking-wide">
                            Fasilitas yang Termasuk:
                        </span>
                        <div id="summaryFacilities" class="space-y-1.5 max-h-40 overflow-y-auto pr-1 text-xs text-slate-600">
                            @if ($selectedPaket && $selectedPaket->facilities->isNotEmpty())
                                @foreach ($selectedPaket->facilities as $fac)
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-circle-check text-brand-600 text-xs flex-shrink-0"></i>
                                        <span>{{ $fac->judul }}</span>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-slate-400 italic">Termasuk fasilitas standar aula SMK Negeri 2 Karanganyar.</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- INFO TENGGAT WAKTU & KEBIJAKAN -->
                <div class="bg-amber-50/80 border border-amber-200 rounded-2xl p-5 space-y-2 text-xs text-amber-900 leading-relaxed shadow-sm">
                    <div class="flex items-center gap-2 font-bold text-amber-800">
                        <i class="fa-solid fa-circle-exclamation text-sm"></i>
                        <span>Informasi Pembayaran Sekolah</span>
                    </div>
                    <p>
                        Setelah formulir disimpan, tagihan pembayaran otomatis dibuat. Anda akan diberi batas waktu transfer DP selama <strong>{{ $paymentConfig->jatuh_tempo_dp_jam }} Jam</strong>.
                    </p>
                    <p class="text-[11px] text-amber-700">
                        Anda dapat langsung membayar uang muka (DP) atau langsung melunasi tagihan sewa.
                    </p>
                </div>

                <!-- TOMBOL SUBMIT -->
                <div class="space-y-2.5">
                    <button type="submit"
                        class="w-full py-3.5 px-4 bg-brand-600 hover:bg-brand-700 active:scale-95 text-white font-bold rounded-xl text-sm shadow-md transition flex items-center justify-center gap-2">
                        <span>Lanjut ke Pembayaran</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                    <a href="{{ route('customer.paket') }}"
                        class="w-full py-2.5 px-4 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold text-center block transition">
                        Batal
                    </a>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    const paketsData = @js($pakets);

    function updateSelectedPaket(paketId) {
        const paket = paketsData.find(p => p.id == paketId);
        if (!paket) return;

        const formatRupiah = (val) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);

        document.getElementById('summaryHarga').innerText = formatRupiah(paket.harga);

        const dpNominal = (paket.harga_dp && Number(paket.harga_dp) > 0) ? Number(paket.harga_dp) : (Number(paket.harga) * 0.3);
        document.getElementById('summaryDp').innerText = formatRupiah(dpNominal);

        const facilitiesContainer = document.getElementById('summaryFacilities');
        facilitiesContainer.innerHTML = '';

        if (paket.facilities && paket.facilities.length > 0) {
            paket.facilities.forEach(f => {
                const item = document.createElement('div');
                item.className = 'flex items-center gap-2';
                item.innerHTML = `<i class="fa-solid fa-circle-check text-brand-600 text-xs flex-shrink-0"></i> <span>${f.judul}</span>`;
                facilitiesContainer.appendChild(item);
            });
        } else {
            facilitiesContainer.innerHTML = '<div class="text-slate-400 italic">Termasuk fasilitas standar aula SMK Negeri 2 Karanganyar.</div>';
        }
    }
</script>
@endpush
