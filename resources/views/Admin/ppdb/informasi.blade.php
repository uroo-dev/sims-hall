@extends('Admin.layout.app')

@section('title', 'Informasi PPDB - SMK Negeri 2 Karanganyar')
@section('page_title', 'Informasi PPDB')

@section('content')
    @if (session('success'))
        <div
            class="mb-4 p-3 rounded-xl text-xs font-medium bg-green-50 text-green-700 border border-green-200 flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-sm shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div
            class="mb-4 p-3 rounded-xl text-xs font-medium bg-red-50 text-red-700 border border-red-200 flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-sm shrink-0"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
        <span class="text-gray-600 font-semibold text-lg">Header</span>
        <form action="{{ route('update.informasi.ppdb') }}" method="POST" class="flex flex-col mt-5">
            @csrf


            <div class="flex gap-6">

                <div class="w-[30%] flex flex-col min-w-0">
                    <label for="judul" class="text-xs font-extrabold uppercase tracking-wider text-brand-600 mb-1">
                        Judul
                    </label>
                    <input type="text" name="judul" id="judul" maxlength="100"
                        value="{{ old('judul', $informasi?->judul) }}" placeholder="PPDB ..."
                        class="w-full px-4 py-3 bg-slate-50 text-slate-800 text-sm font-medium rounded-xl border-2 border-slate-200 focus:border-brand-600 focus:ring-4 focus:ring-brand-100 focus:outline-none transition placeholder:text-slate-400">
                </div>

                <div class="flex-1 flex flex-col min-w-0">
                    <label for="keterangan" class="text-xs font-extrabold uppercase tracking-wider text-brand-600 mb-1">
                        Keterangan
                    </label>
                    <input type="text" name="keterangan" id="keterangan" maxlength="255"
                        value="{{ old('keterangan', $informasi?->keterangan) }}"
                        placeholder="Tuliskan keterangan informasi PPDB ..."
                        class="w-full px-4 py-3 bg-slate-50 text-slate-800 text-sm font-medium rounded-xl border-2 border-slate-200 focus:border-brand-600 focus:ring-4 focus:ring-brand-100 focus:outline-none transition placeholder:text-slate-400">
                </div>

            </div>

            <button type="submit" class="bg-brand-600 hover:bg-brand-700 w-fit p-3 rounded-xl text-white mt-5">Simpan
                Perubahan</button>
        </form>
    </div>

    {{-- ================= TANGGAL PENTING ================= --}}
    <div class="flex flex-col gap-6 items-stretch bg-white rounded-2xl p-6">
        <span class="text-gray-600 font-semibold text-lg">Tanggal Penting</span>
        <div class="flex gap-6">
            {{-- CARD KALENDER --}}
            <div class="w-[55%] min-w-0">
                <div
                    class="relative isolate bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 h-full flex flex-col">

                    <div class="flex items-center justify-between mb-4">
                        <button type="button" id="btnPrev" title="Bulan sebelumnya"
                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </button>
                        <span id="calendarTitle"
                            class="text-sm font-extrabold uppercase tracking-wider text-brand-600"></span>
                        <button type="button" id="btnNext" title="Bulan berikutnya"
                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-7 gap-1 mb-1">
                        @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $hari)
                            <div
                                class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 text-center py-1">
                                {{ $hari }}</div>
                        @endforeach
                    </div>

                    <div id="calendarGrid" class="grid grid-cols-7 gap-1"></div>

                    <div class="flex flex-wrap items-center gap-4 mt-4 text-[10px] font-semibold text-slate-500">
                        <span class="flex items-center gap-1.5">
                            <i class="w-3 h-3 rounded bg-brand-600"></i> Tersimpan
                        </span>
                        <span class="flex items-center gap-1.5">
                            <i class="w-3 h-3 rounded bg-brand-100"></i> Dipilih
                        </span>
                    </div>

                    <div id="agendaTooltip"
                        class="hidden absolute z-50 w-64 p-3 rounded-xl bg-slate-800 text-white text-xs shadow-xl pointer-events-auto">
                        <div class="pointer-events-auto" data-tooltip-content></div>
                    </div>
                </div>
            </div>

            {{-- CARD FORM --}}
            <form id="agendaForm" action="{{ route('post.tanggal-penting.ppdb') }}" method="POST"
                class="flex-1 min-w-0 bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 flex flex-col gap-4">
                @csrf
                <div id="agendaMethod"></div>

                <div id="editBanner"
                    class="hidden p-3 rounded-xl text-xs font-medium bg-blue-50 text-brand-800 border border-brand-100 flex items-center justify-between gap-2">
                    <span class="flex items-center gap-2 min-w-0">
                        <i class="fa-solid fa-pen text-xs shrink-0"></i>
                        <span class="truncate">Mode edit: <strong id="editName"></strong></span>
                    </span>
                    <button type="button" onclick="resetForm()" title="Keluar dari mode edit"
                        class="shrink-0 w-5 h-5 flex items-center justify-center rounded-full hover:bg-brand-100 transition">
                        <i class="fa-solid fa-xmark text-[10px]"></i>
                    </button>
                </div>

                <div class="flex flex-col">
                    <label for="namaAgenda" class="text-xs font-extrabold uppercase tracking-wider text-brand-600 mb-1">
                        Nama Agenda
                    </label>
                    <input type="text" name="nama_agenda" id="namaAgenda" maxlength="100"
                        value="{{ old('nama_agenda') }}" placeholder="Contoh: Gelombang 1"
                        class="w-full px-4 py-3 bg-slate-50 text-slate-800 text-sm font-medium rounded-xl border-2 border-slate-200 focus:border-brand-600 focus:ring-4 focus:ring-brand-100 focus:outline-none transition placeholder:text-slate-400">
                </div>

                <div class="flex flex-col">
                    <label for="tanggalMulai" class="text-xs font-extrabold uppercase tracking-wider text-brand-600 mb-1">
                        Tanggal Mulai
                    </label>
                    <input type="date" name="tanggal_mulai" id="tanggalMulai" value="{{ old('tanggal_mulai') }}"
                        class="w-full px-4 py-3 bg-slate-50 text-slate-800 text-sm font-medium rounded-xl border-2 border-slate-200 focus:border-brand-600 focus:ring-4 focus:ring-brand-100 focus:outline-none transition">
                </div>

                <div class="flex flex-col">
                    <label for="tanggalSelesai" class="text-xs font-extrabold uppercase tracking-wider text-brand-600 mb-1">
                        Tanggal Selesai
                    </label>
                    <input type="date" name="tanggal_selesai" id="tanggalSelesai" value="{{ old('tanggal_selesai') }}"
                        class="w-full px-4 py-3 bg-slate-50 text-slate-800 text-sm font-medium rounded-xl border-2 border-slate-200 focus:border-brand-600 focus:ring-4 focus:ring-brand-100 focus:outline-none transition">
                </div>

                <div class="flex flex-col">
                    <label for="keteranganAgenda"
                        class="text-xs font-extrabold uppercase tracking-wider text-brand-600 mb-1">
                        Keterangan
                    </label>
                    <input type="text" name="keterangan" id="keteranganAgenda" maxlength="150"
                        value="{{ old('keterangan') }}" placeholder="Contoh: kegiatan rutin hari minggu"
                        class="w-full px-4 py-3 bg-slate-50 text-slate-800 text-sm font-medium rounded-xl border-2 border-slate-200 focus:border-brand-600 focus:ring-4 focus:ring-brand-100 focus:outline-none transition placeholder:text-slate-400">
                </div>

                <div class="flex flex-wrap items-center gap-3 mt-1">
                    <button type="submit"
                        class="bg-brand-600 hover:bg-brand-700 p-3 rounded-xl text-white text-sm font-medium transition">
                        <span id="simpanLabel">Simpan Agenda</span>
                    </button>

                    <button type="button" id="btnClear" onclick="resetForm()"
                        class="p-3 rounded-xl text-sm font-medium bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                        Clear
                    </button>

                    <button type="button" id="btnHapus" onclick="hapusAgenda()"
                        class="hidden p-3 rounded-xl text-sm font-medium bg-red-50 hover:bg-red-100 text-red-700 transition">
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
    {{-- Persyaratan --}}
    <div id="section-persyaratan" class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
        <span class="text-gray-600 font-semibold text-lg">Persyaratan</span>
        <div class="flex flex-col mt-5">
            @foreach ($persyaratan as $syarat)
                <div class="flex gap-5 mt-2">
                    <input type="text" value="{{ $syarat->syarat }}" disabled
                        class="w-full px-4 py-3 bg-slate-50 text-slate-800 text-sm font-medium rounded-xl border-2 border-slate-200 focus:border-brand-600 focus:ring-4 focus:ring-brand-100 focus:outline-none transition placeholder:text-slate-400">
                    <a href="{{ route('delete.persyaratan.ppdb', $syarat->id) }}"
                        class="cursor-pointer px-4 py-3 bg-red-50 text-slate-800 text-sm font-medium rounded-xl border-2 border-red-200 focus:border-brand-600 focus:ring-4 focus:ring-brand-100 focus:outline-none transition placeholder:text-slate-400 "><i
                            class="fa-solid fa-trash"></i></a>
                </div>
            @endforeach
        </div>
        <form action="{{ route('post.persyaratan.ppdb') }}" method="POST" class="flex gap-5 mt-2">
            @csrf
            <input type="text" name="syarat"
                class="w-full px-4 py-3 bg-slate-50 text-slate-800 text-sm font-medium rounded-xl border-2 border-slate-200 focus:border-brand-600 focus:ring-4 focus:ring-brand-100 focus:outline-none transition placeholder:text-slate-400">
            <button type="submit"
                class=" px-4 py-3 bg-slate-50 text-slate-800 text-sm font-medium rounded-xl border-2 border-slate-200 focus:border-brand-600 focus:ring-4 focus:ring-brand-100 focus:outline-none transition placeholder:text-slate-400 "><i
                    class="fa-solid fa-plus"></i></button>
        </form>
        {{-- Form Upload File Persyaratan --}}
        <form action="{{ route('upload.persyaratan.file.ppdb') }}" method="POST" enctype="multipart/form-data"
            class="flex flex-col mt-5 gap-3">
            @csrf
            <label for="path_file" class="text-gray-600 font-medium">Upload file persyaratan</label>
            @if ($informasi && $informasi->path_file)
                <div class="flex items-center gap-3 text-sm">
                    <a href="{{ asset('storage/' . $informasi->path_file) }}" target="_blank"
                        class="text-brand-600 hover:text-brand-700 underline truncate max-w-xs">
                        Lihat file persyaratan saat ini
                    </a>
                </div>
            @endif
            <div class="flex gap-3 items-center">
                <input type="file" name="path_file" id="path_file"
                    class="w-fit px-4 py-3 bg-slate-50 text-slate-800 text-sm font-medium rounded-xl border-2 border-slate-200 focus:border-brand-600 focus:ring-4 focus:ring-brand-100 focus:outline-none transition placeholder:text-slate-400">
                <button type="submit"
                    class="px-4 py-3 bg-brand-600 text-white text-sm font-medium rounded-xl border-2 border-brand-600 hover:bg-brand-700 focus:ring-4 focus:ring-brand-100 focus:outline-none transition">
                    Upload
                </button>
            </div>
            @error('path_file')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </form>
        @if ($informasi && $informasi->path_file)
            <form action="{{ route('delete.persyaratan.file.ppdb') }}" method="POST" class="mt-2">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-4 py-3 bg-red-50 text-red-700 text-sm font-medium rounded-xl border-2 border-red-200 hover:bg-red-100 focus:ring-4 focus:ring-red-100 focus:outline-none transition"
                    onclick="return confirm('Yakin ingin menghapus file persyaratan?')">
                    <i class="fa-solid fa-trash"></i> Hapus File
                </button>
            </form>
        @endif
    </div>

    <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
        <span class="text-gray-600 font-semibold text-lg">Jurusan</span>
        <div class="flex w-full gap-5 mt-5">
            <div class="w-[35%] flex-initial flex flex-col gap-5">
                {{-- Daya Tampung (Jurusan) --}}
                <div id="section-jurusan"
                    class="bg-white rounded-2xl p-5 figma-card-shadow border border-slate-100 shadow">
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-gray-600 font-semibold text-base">Daya Tampung</span>
                    </div>

                    <div class="flex justify-between my-5 pt-3 border-t border-slate-100">
                        <label class="text-xs text-gray-500">Total Daya Tampung</label>
                        <span type="number" readonly value="">{{ $totalDayaTampung ?? 0 }}</span>
                    </div>

                    <div class="flex flex-col gap-2 max-h-[220px] overflow-y-auto pr-1 mb-5">
                        @foreach ($jurusans as $jurusan)
                            <div class="flex gap-2 items-center">
                                <form action="{{ route('update.jurusan.ppdb', $jurusan) }}" method="POST"
                                    class="flex flex-1 gap-2 items-center">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="nama_jurusan" value="{{ $jurusan->nama_jurusan }}"
                                        class="flex-1 px-2.5 py-2 bg-white text-slate-800 text-xs font-medium rounded-lg border border-slate-200 focus:border-brand-600 focus:ring-2 focus:ring-brand-100 focus:outline-none transition"
                                        placeholder="Nama Jurusan">
                                    <input type="number" name="daya_tampung" value="{{ $jurusan->daya_tampung }}"
                                        min="0"
                                        class="w-20 px-2.5 py-2 bg-white text-slate-800 text-xs font-medium rounded-lg border border-slate-200 focus:border-brand-600 focus:ring-2 focus:ring-brand-100 focus:outline-none transition"
                                        placeholder="0">
                                    <button type="submit" title="Simpan"
                                        class="px-2 py-2 text-slate-600 hover:text-brand-700 transition">
                                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                                    </button>
                                </form>
                                <form action="{{ route('delete.jurusan.ppdb', $jurusan) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus"
                                        onclick="return confirm('Yakin ingin menghapus jurusan ini?')"
                                        class="px-2 py-2 text-red-500 hover:text-red-700 transition">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>



                    <form id="form-jurusan-tambah" action="{{ route('post.jurusan.ppdb') }}" method="POST"
                        class="mt-3 flex gap-2 items-center">
                        @csrf
                        <input type="text" name="nama_jurusan" required
                            class="flex-1 px-2.5 py-2 bg-slate-50 text-slate-800 text-xs font-medium rounded-lg border border-slate-200 focus:border-brand-600 focus:ring-2 focus:ring-brand-100 focus:outline-none transition"
                            placeholder="Tambah Nama Jurusan">
                        <input type="number" name="daya_tampung" required min="0"
                            class="w-20 px-2.5 py-2 bg-slate-50 text-slate-800 text-xs font-medium rounded-lg border border-slate-200 focus:border-brand-600 focus:ring-2 focus:ring-brand-100 focus:outline-none transition"
                            placeholder="0">
                        <button type="submit" title="Tambah"
                            class="px-2 py-2 bg-brand-600 text-white rounded-lg hover:bg-brand-700 transition">
                            <i class="fa-solid fa-plus text-xs"></i>
                        </button>
                    </form>
                    @error('nama_jurusan')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @error('daya_tampung')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jalur Seleksi --}}
                <div id="section-jalur" class="bg-white rounded-2xl p-5 figma-card-shadow border border-slate-100 shadow">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-gray-600 font-semibold text-base">Jalur Seleksi</span>
                    </div>

                    <div class="flex justify-between my-5 pt-3 border-t border-slate-100">
                        <label class="text-xs text-gray-500">Total Persentase</label>
                        <span type="number" readonly value="">{{ $totalPercentase ?? 0 }}%</span>
                    </div>

                    <div class="flex flex-col gap-2 max-h-[220px] overflow-y-auto pr-1">
                        @foreach ($jalurs as $jalur)
                            <div class="flex gap-2 items-center">
                                <form action="{{ route('update.jalur.ppdb', $jalur) }}" method="POST"
                                    class="flex flex-1 gap-2 items-center">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="nama_jalur" value="{{ $jalur->nama_jalur }}"
                                        class="flex-1 px-2.5 py-2 bg-white text-slate-800 text-xs font-medium rounded-lg border border-slate-200 focus:border-brand-600 focus:ring-2 focus:ring-brand-100 focus:outline-none transition"
                                        placeholder="Nama Jalur">
                                    <input type="number" step="0.01" name="percentase"
                                        value="{{ $jalur->percentase }}" min="0" max="100"
                                        class="w-20 px-2.5 py-2 bg-white text-slate-800 text-xs font-medium rounded-lg border border-slate-200 focus:border-brand-600 focus:ring-2 focus:ring-brand-100 focus:outline-none transition"
                                        placeholder="0">
                                    <span>%</span>
                                    <button type="submit" title="Simpan"
                                        class="px-2 py-2 text-slate-600 hover:text-brand-700 transition">
                                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                                    </button>
                                </form>
                                <form action="{{ route('delete.jalur.ppdb', $jalur) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus"
                                        onclick="return confirm('Yakin ingin menghapus jalur ini?')"
                                        class="px-2 py-2 text-red-500 hover:text-red-700 transition">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>

                    <form id="form-jalur-tambah" action="{{ route('post.jalur.ppdb') }}" method="POST"
                        class="mt-3 flex gap-2 items-center">
                        @csrf
                        <input type="text" name="nama_jalur" required value="{{ old('nama_jalur') }}"
                            class="flex-1 px-2.5 py-2 bg-slate-50 text-slate-800 text-xs font-medium rounded-lg border border-slate-200 focus:border-brand-600 focus:ring-2 focus:ring-brand-100 focus:outline-none transition"
                            placeholder="Tambah Nama Jalur">
                        <input type="number" step="0.01" name="percentase" required min="0" max="100"
                            value="{{ old('percentase') }}"
                            class="w-20 px-2.5 py-2 bg-slate-50 text-slate-800 text-xs font-medium rounded-lg border border-slate-200 focus:border-brand-600 focus:ring-2 focus:ring-brand-100 focus:outline-none transition"
                            placeholder="0">
                        <button type="submit" title="Tambah" @if (($totalPercentase ?? 0) >= 100) disabled @endif
                            class="px-2 py-2 bg-brand-600 text-white rounded-lg hover:bg-brand-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fa-solid fa-plus text-xs"></i>
                        </button>
                    </form>
                    @error('nama_jalur')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @error('percentase')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div id="section-gambar-jurusan"
                class="flex-1 bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 shadow">
                <span class="text-gray-600 font-semibold text-lg mb-4 block">Gambar Jurusan</span>
                <div class="flex flex-col gap-3 h-[400px]">
                    {{-- Baris 1 --}}
                    <div class="flex gap-3 h-1/2">
                        {{-- Slot 1 - 70% --}}
                        @php
                            $slot1 = $jurusans->get(0);
                            $slot2 = $jurusans->get(1);
                            $slot3 = $jurusans->get(2);
                            $slot4 = $jurusans->get(3);
                        @endphp
                        <div class="w-[70%] relative group cursor-pointer border-2 border-dashed border-slate-300 rounded-xl overflow-hidden hover:border-brand-600 transition"
                            onclick="openImageModal(0)">
                            @if ($slot1 && $slot1->img)
                                <img src="{{ asset('storage/' . $slot1->img) }}" alt="{{ $slot1->nama_jurusan }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                    <i class="fa-solid fa-image text-2xl mb-2"></i>
                                    <span class="text-xs text-center px-2">Slot
                                        1<br>{{ $slot1?->nama_jurusan ?? 'Pilih Jurusan' }}</span>
                                </div>
                            @endif
                            <div
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                <i class="fa-solid fa-camera text-white text-2xl"></i>
                            </div>
                            @if ($slot1 && $slot1->img)
                                <button type="button"
                                    onclick="event.stopPropagation(); if(confirm('Hapus gambar {{ $slot1->nama_jurusan }}?')) { document.getElementById('delete-img-{{ $slot1->id }}').submit(); }"
                                    class="absolute top-2 right-2 bg-red-500/80 hover:bg-red-600 text-white rounded-full w-7 h-7 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                                <form id="delete-img-{{ $slot1->id }}"
                                    action="{{ route('delete.jurusan.image.ppdb', $slot1) }}" method="POST"
                                    class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </div>
                        {{-- Slot 2 - 30% --}}
                        <div class="w-[30%] relative group cursor-pointer border-2 border-dashed border-slate-300 rounded-xl overflow-hidden hover:border-brand-600 transition"
                            onclick="openImageModal(1)">
                            @if ($slot2 && $slot2->img)
                                <img src="{{ asset('storage/' . $slot2->img) }}" alt="{{ $slot2->nama_jurusan }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                    <i class="fa-solid fa-image text-2xl mb-2"></i>
                                    <span class="text-xs text-center px-2">Slot
                                        2<br>{{ $slot2?->nama_jurusan ?? 'Pilih Jurusan' }}</span>
                                </div>
                            @endif
                            <div
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                <i class="fa-solid fa-camera text-white text-2xl"></i>
                            </div>
                            @if ($slot2 && $slot2->img)
                                <button type="button"
                                    onclick="event.stopPropagation(); if(confirm('Hapus gambar {{ $slot2->nama_jurusan }}?')) { document.getElementById('delete-img-{{ $slot2->id }}').submit(); }"
                                    class="absolute top-2 right-2 bg-red-500/80 hover:bg-red-600 text-white rounded-full w-7 h-7 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                                <form id="delete-img-{{ $slot2->id }}"
                                    action="{{ route('delete.jurusan.image.ppdb', $slot2) }}" method="POST"
                                    class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </div>
                    </div>
                    {{-- Baris 2 --}}
                    <div class="flex gap-3 h-1/2">
                        {{-- Slot 3 - 30% --}}
                        <div class="w-[30%] relative group cursor-pointer border-2 border-dashed border-slate-300 rounded-xl overflow-hidden hover:border-brand-600 transition"
                            onclick="openImageModal(2)">
                            @if ($slot3 && $slot3->img)
                                <img src="{{ asset('storage/' . $slot3->img) }}" alt="{{ $slot3->nama_jurusan }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                    <i class="fa-solid fa-image text-2xl mb-2"></i>
                                    <span class="text-xs text-center px-2">Slot
                                        3<br>{{ $slot3?->nama_jurusan ?? 'Pilih Jurusan' }}</span>
                                </div>
                            @endif
                            <div
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                <i class="fa-solid fa-camera text-white text-2xl"></i>
                            </div>
                            @if ($slot3 && $slot3->img)
                                <button type="button"
                                    onclick="event.stopPropagation(); if(confirm('Hapus gambar {{ $slot3->nama_jurusan }}?')) { document.getElementById('delete-img-{{ $slot3->id }}').submit(); }"
                                    class="absolute top-2 right-2 bg-red-500/80 hover:bg-red-600 text-white rounded-full w-7 h-7 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                                <form id="delete-img-{{ $slot3->id }}"
                                    action="{{ route('delete.jurusan.image.ppdb', $slot3) }}" method="POST"
                                    class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </div>
                        {{-- Slot 4 - 70% --}}
                        <div class="w-[70%] relative group cursor-pointer border-2 border-dashed border-slate-300 rounded-xl overflow-hidden hover:border-brand-600 transition"
                            onclick="openImageModal(3)">
                            @if ($slot4 && $slot4->img)
                                <img src="{{ asset('storage/' . $slot4->img) }}" alt="{{ $slot4->nama_jurusan }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                    <i class="fa-solid fa-image text-2xl mb-2"></i>
                                    <span class="text-xs text-center px-2">Slot
                                        4<br>{{ $slot4?->nama_jurusan ?? 'Pilih Jurusan' }}</span>
                                </div>
                            @endif
                            <div
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                <i class="fa-solid fa-camera text-white text-2xl"></i>
                            </div>
                            @if ($slot4 && $slot4->img)
                                <button type="button"
                                    onclick="event.stopPropagation(); if(confirm('Hapus gambar {{ $slot4->nama_jurusan }}?')) { document.getElementById('delete-img-{{ $slot4->id }}').submit(); }"
                                    class="absolute top-2 right-2 bg-red-500/80 hover:bg-red-600 text-white rounded-full w-7 h-7 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                                <form id="delete-img-{{ $slot4->id }}"
                                    action="{{ route('delete.jurusan.image.ppdb', $slot4) }}" method="POST"
                                    class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Hasil Seleksi --}}
    <div id="section-hasil-seleksi" class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 mt-5">
        <span class="text-gray-600 font-semibold text-lg">Hasil Seleksi</span>
        <form action="{{ route('upload.hasil-seleksi.file.ppdb') }}" method="POST" enctype="multipart/form-data"
            class="flex flex-col mt-5 gap-3">
            @csrf
            <label for="path_file_hasil" class="text-gray-600 font-medium">Upload file hasil seleksi</label>
            @if ($informasi && $informasi->path_file_hasil)
                <div class="flex items-center gap-3 text-sm">
                    <a href="{{ asset('storage/' . $informasi->path_file_hasil) }}" target="_blank"
                        class="text-brand-600 hover:text-brand-700 underline truncate max-w-xs">
                        Lihat file hasil seleksi saat ini
                    </a>
                </div>
            @endif
            <div class="flex gap-3 items-center">
                <input type="file" name="path_file_hasil" id="path_file_hasil" accept=".pdf,.doc,.docx"
                    class="w-fit px-4 py-3 bg-slate-50 text-slate-800 text-sm font-medium rounded-xl border-2 border-slate-200 focus:border-brand-600 focus:ring-4 focus:ring-brand-100 focus:outline-none transition placeholder:text-slate-400">
                <button type="submit"
                    class="px-4 py-3 bg-brand-600 text-white text-sm font-medium rounded-xl border-2 border-brand-600 hover:bg-brand-700 focus:ring-4 focus:ring-brand-100 focus:outline-none transition">
                    Upload
                </button>
            </div>
            @error('path_file_hasil')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </form>
        @if ($informasi && $informasi->path_file_hasil)
            <form action="{{ route('delete.hasil-seleksi.file.ppdb') }}" method="POST" class="mt-2">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-4 py-3 bg-red-50 text-red-700 text-sm font-medium rounded-xl border-2 border-red-200 hover:bg-red-100 focus:ring-4 focus:ring-red-100 focus:outline-none transition"
                    onclick="return confirm('Yakin ingin menghapus file hasil seleksi?')">
                    <i class="fa-solid fa-trash"></i> Hapus File
                </button>
            </form>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const AGENDAS = @json($agendas);
            const BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                'Oktober', 'November', 'Desember'
            ];
            const HARI_MINGGU = 0;

            const FORM = document.getElementById('agendaForm');
            const METHOD_SLOT = document.getElementById('agendaMethod');
            const GRID = document.getElementById('calendarGrid');
            const TITLE = document.getElementById('calendarTitle');
            const TOOLTIP = document.getElementById('agendaTooltip');
            const TOOLTIP_CONTENT = TOOLTIP.querySelector('[data-tooltip-content]');
            const CAL_CARD = TOOLTIP.parentElement;
            const SHOW_DELAY = 150;
            const HIDE_DELAY = 400;
            const BANNER = document.getElementById('editBanner');
            const EDIT_NAME = document.getElementById('editName');
            const BTN_HAPUS = document.getElementById('btnHapus');
            const LABEL_SIMPAN = document.getElementById('simpanLabel');
            const INPUT_NAMA = document.getElementById('namaAgenda');
            const INPUT_MULAI = document.getElementById('tanggalMulai');
            const INPUT_SELESAI = document.getElementById('tanggalSelesai');
            const INPUT_KETERANGAN = document.getElementById('keteranganAgenda');

            const URL_POST = @json(route('post.tanggal-penting.ppdb'));
            const URL_UPDATE = @json(route('update.tanggal-penting.ppdb', ['agenda' => 0]));
            const URL_DELETE = @json(route('delete.tanggal-penting.ppdb', ['agenda' => 0]));

            const now = new Date();
            let viewYear = now.getFullYear();
            let viewMonth = now.getMonth();
            let start = '';
            let end = '';
            let editingId = null;
            let tooltipTimer = null;
            let hoverRanges = [];

            const pad = (n) => String(n).padStart(2, '0');

            function toKey(date) {
                return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate());
            }

            function parseKey(key) {
                const parts = key.split('-').map(Number);
                return new Date(parts[0], parts[1] - 1, parts[2]);
            }

            function labelTanggal(key) {
                if (!key) return '';
                const d = parseKey(key);
                return d.getDate() + ' ' + BULAN[d.getMonth()] + ' ' + d.getFullYear();
            }

            function rentangLabel(a, b) {
                if (!a || !b) return '';
                if (a === b) return labelTanggal(a);

                const mulai = parseKey(a);
                const selesai = parseKey(b);
                const samaTahun = mulai.getFullYear() === selesai.getFullYear();
                const samaBulan = samaTahun && mulai.getMonth() === selesai.getMonth();

                if (samaBulan) {
                    return mulai.getDate() + ' - ' + selesai.getDate() + ' ' + BULAN[selesai.getMonth()] +
                        ' ' + selesai.getFullYear();
                }

                if (samaTahun) {
                    return mulai.getDate() + ' ' + BULAN[mulai.getMonth()] + ' - ' + selesai.getDate() + ' ' +
                        BULAN[selesai.getMonth()] + ' ' + selesai.getFullYear();
                }

                return labelTanggal(a) + ' - ' + labelTanggal(b);
            }

            function agendaDiTanggal(key) {
                return AGENDAS.filter((a) => key >= a.tanggal_mulai && key <= a.tanggal_selesai);
            }

            function adaDraft(key) {
                if (!start || !end) return false;
                return key >= start && key <= end;
            }

            function selClass(key, hoverRangesArr = []) {
                const tersimpanList = agendaDiTanggal(key);
                const tersimpan = tersimpanList.length > 0;
                const draft = adaDraft(key);
                const inHover = hoverRangesArr.some(r => key >= r[0] && key <= r[1]);
                const kelas = [
                    'h-9 flex items-center justify-center rounded-lg text-xs font-semibold transition cursor-pointer select-none'
                ];

                if (inHover) {
                    kelas.push('bg-brand-700 text-white font-extrabold');
                } else if (tersimpan) {
                    kelas.push('bg-brand-500/70 text-white hover:bg-brand-600');
                } else if (draft) {
                    kelas.push('bg-brand-100 text-brand-800 hover:bg-brand-200');
                } else {
                    kelas.push('text-slate-600 hover:bg-slate-100');
                }

                if (start && key === start) kelas.push('rounded-l-full');
                if (end && key === end) kelas.push('rounded-r-full');
                if (key === toKey(now)) kelas.push('ring-1 ring-brand-500');

                return kelas.join(' ');
            }

            function updateCellClasses() {
                GRID.querySelectorAll('[data-key]').forEach((sel) => {
                    const key = sel.dataset.key;
                    const luarBulan = sel.dataset.luar === '1';
                    sel.className = selClass(key, hoverRanges) + (luarBulan ? ' opacity-35' : '');
                });
            }

            function renderCalendar() {
                TITLE.textContent = BULAN[viewMonth] + ' ' + viewYear;
                hoverRanges = [];
                TOOLTIP.classList.add('hidden');
                GRID.innerHTML = '';

                const pertama = new Date(viewYear, viewMonth, 1);
                let offset = pertama.getDay() - 1;
                if (offset < 0) offset = 6;

                const mulaiGrid = new Date(viewYear, viewMonth, 1 - offset);

                for (let i = 0; i < 42; i++) {
                    const d = new Date(mulaiGrid.getFullYear(), mulaiGrid.getMonth(), mulaiGrid.getDate() + i);
                    const key = toKey(d);
                    const luarBulan = d.getMonth() !== viewMonth;

                    const sel = document.createElement('div');
                    sel.textContent = d.getDate();
                    sel.dataset.key = key;
                    sel.dataset.luar = luarBulan ? '1' : '0';
                    sel.className = selClass(key, hoverRanges) + (luarBulan ? ' opacity-35' : '');

                    sel.addEventListener('click', () => pilihTanggal(key));
                    sel.addEventListener('mouseenter', () => {
                        clearTimeout(tooltipTimer);
                        const list = agendaDiTanggal(key);
                        hoverRanges = list.map(a => [a.tanggal_mulai, a.tanggal_selesai]);
                        updateCellClasses();
                        tampilTooltip(sel, list, key);
                    });
                    sel.addEventListener('mouseleave', (e) => {
                        if (e.relatedTarget && TOOLTIP.contains(e.relatedTarget)) return;
                        sembunyiTooltip();
                    });

                    GRID.appendChild(sel);
                }
            }

            function tampilTooltip(anchor, list, key) {
                clearTimeout(tooltipTimer);
                tooltipTimer = setTimeout(() => {
                    let html = '';

                    if (list.length) {
                        html += list.map((a) => {
                            const judul = rentangLabel(a.tanggal_mulai, a.tanggal_selesai);
                            return '<div class="mb-2 last:mb-0 flex items-start justify-between gap-2">' +
                                '<div class="min-w-0">' +
                                '<div class="text-brand-200 font-semibold truncate">' + judul +
                                '</div>' +
                                '<div class="font-bold truncate">' + a.nama_agenda + '</div>' +
                                '<div class="text-slate-300 truncate">' + a.keterangan + '</div>' +
                                '</div>' +
                                '<button type="button" class="shrink-0 text-slate-300 hover:text-white transition" data-action="edit" data-id="' +
                                a.id + '">' +
                                '<i class="fa-solid fa-pen text-xs"></i>' +
                                '</button>' +
                                '</div>';
                        }).join('');
                    }

                    html +=
                        '<div class="mt-2 pt-2 border-t border-white/10 flex items-center justify-center">' +
                        '<button type="button" class="flex items-center gap-1.5 text-brand-200 hover:text-white text-xs font-semibold transition" data-action="add" data-key="' +
                        key + '">' +
                        '<i class="fa-solid fa-plus"></i>' +
                        '<span>Tambah agenda</span>' +
                        '</button>' +
                        '</div>';

                    TOOLTIP_CONTENT.innerHTML = html;
                    TOOLTIP.classList.remove('hidden');

                    const induk = TOOLTIP.parentElement;
                    const lebarInduk = induk.clientWidth;
                    const lebarTip = TOOLTIP.offsetWidth;

                    let kiri = anchor.offsetLeft + anchor.offsetWidth / 2 - lebarTip / 2;
                    kiri = Math.max(8, Math.min(kiri, lebarInduk - lebarTip - 8));

                    let atas = anchor.offsetTop - TOOLTIP.offsetHeight - 8;
                    if (atas < 0) atas = anchor.offsetTop + anchor.offsetHeight + 8;

                    TOOLTIP.style.left = kiri + 'px';
                    TOOLTIP.style.top = atas + 'px';

                    TOOLTIP_CONTENT.querySelectorAll('[data-action="edit"]').forEach(btn => {
                        btn.addEventListener('click', (e) => {
                            e.stopPropagation();
                            const id = parseInt(btn.dataset.id);
                            const agenda = AGENDAS.find(a => a.id === id);
                            if (agenda) {
                                loadAgenda(agenda);
                                hoverRanges = [];
                                renderCalendar();
                                TOOLTIP.classList.add('hidden');
                            }
                        });
                    });

                    TOOLTIP_CONTENT.querySelectorAll('[data-action="add"]').forEach(btn => {
                        btn.addEventListener('click', (e) => {
                            e.stopPropagation();
                            const keyTanggal = btn.dataset.key;
                            resetForm();
                            if (keyTanggal) {
                                start = keyTanggal;
                                end = '';
                                INPUT_MULAI.value = keyTanggal;
                                INPUT_SELESAI.value = '';
                            }
                            hoverRanges = [];
                            renderCalendar();
                            TOOLTIP.classList.add('hidden');
                        });
                    });
                }, SHOW_DELAY);
            }

            function hideTooltipNow() {
                clearTimeout(tooltipTimer);
                tooltipTimer = null;
                TOOLTIP.classList.add('hidden');
                hoverRanges = [];
                updateCellClasses();
            }

            function sembunyiTooltip() {
                clearTimeout(tooltipTimer);
                tooltipTimer = setTimeout(hideTooltipNow, HIDE_DELAY);
            }

            function pilihTanggal(key) {
                const tersimpan = agendaDiTanggal(key);

                if (tersimpan.length) {
                    if (editingId !== null) {
                        loadAgenda(tersimpan[0]);
                        renderCalendar();
                        return;
                    }
                }

                if (editingId !== null) {
                    resetForm();
                }

                if (!start || end) {
                    start = key;
                    end = '';
                    INPUT_MULAI.value = key;
                    INPUT_SELESAI.value = '';
                } else {
                    if (key < start) {
                        end = start;
                        start = key;
                    } else {
                        end = key;
                    }
                    INPUT_SELESAI.value = end;
                    if (INPUT_MULAI.value !== start) INPUT_MULAI.value = start;
                }

                renderCalendar();
            }

            function loadAgenda(agenda) {
                editingId = agenda.id;
                start = agenda.tanggal_mulai;
                end = agenda.tanggal_selesai;

                INPUT_NAMA.value = agenda.nama_agenda;
                INPUT_MULAI.value = agenda.tanggal_mulai;
                INPUT_SELESAI.value = agenda.tanggal_selesai;
                INPUT_KETERANGAN.value = agenda.keterangan;

                FORM.action = URL_UPDATE.replace(/\/0$/, '/' + agenda.id);
                METHOD_SLOT.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                LABEL_SIMPAN.textContent = 'Perbarui Agenda';
                EDIT_NAME.textContent = agenda.nama_agenda;
                BANNER.classList.remove('hidden');
                BTN_HAPUS.classList.remove('hidden');
            }

            function resetForm() {
                editingId = null;
                start = '';
                end = '';

                FORM.reset();
                FORM.action = URL_POST;
                METHOD_SLOT.innerHTML = '';
                LABEL_SIMPAN.textContent = 'Simpan Agenda';
                BANNER.classList.add('hidden');
                BTN_HAPUS.classList.add('hidden');
                TOOLTIP.classList.add('hidden');

                renderCalendar();
            }

            function hapusAgenda() {
                if (!editingId) return;
                if (!confirm('Hapus agenda ini?')) return;

                FORM.action = URL_DELETE.replace(/\/0$/, '/' + editingId);
                METHOD_SLOT.innerHTML = '<input type="hidden" name="_method" value="DELETE">';
                FORM.submit();
            }

            GRID.addEventListener('mouseleave', (e) => {
                if (e.relatedTarget && TOOLTIP.contains(e.relatedTarget)) return;
                sembunyiTooltip();
            });

            TOOLTIP.addEventListener('mouseenter', () => {
                clearTimeout(tooltipTimer);
            });

            TOOLTIP.addEventListener('mouseleave', (e) => {
                if (e.relatedTarget && (GRID.contains(e.relatedTarget) || CAL_CARD.contains(e.relatedTarget))) {
                    if (e.relatedTarget.closest && e.relatedTarget.closest('[data-key]')) return;
                }
                sembunyiTooltip();
            });

            CAL_CARD.addEventListener('mouseleave', (e) => {
                if (e.relatedTarget && TOOLTIP.contains(e.relatedTarget)) return;
                sembunyiTooltip();
            });

            document.getElementById('btnPrev').addEventListener('click', () => {
                viewMonth--;
                if (viewMonth < 0) {
                    viewMonth = 11;
                    viewYear--;
                }
                renderCalendar();
            });

            document.getElementById('btnNext').addEventListener('click', () => {
                viewMonth++;
                if (viewMonth > 11) {
                    viewMonth = 0;
                    viewYear++;
                }
                renderCalendar();
            });

            [INPUT_MULAI, INPUT_SELESAI].forEach((input) => {
                input.addEventListener('change', () => {
                    start = INPUT_MULAI.value;
                    end = INPUT_SELESAI.value;
                    if (start) {
                        const d = parseKey(start);
                        viewYear = d.getFullYear();
                        viewMonth = d.getMonth();
                    }
                    renderCalendar();
                });
            });

            INPUT_SELESAI.addEventListener('change', () => {
                if (start && end && end < start) {
                    INPUT_SELESAI.value = start;
                    end = start;
                }
            });

            if (start || end) {
                const acuan = parseKey(start || end);
                viewYear = acuan.getFullYear();
                viewMonth = acuan.getMonth();
            }

            window.resetForm = resetForm;
            window.hapusAgenda = hapusAgenda;

            renderCalendar();
        })();
    </script>
    <!-- Modal Upload Gambar Jurusan -->
    <div id="modalImageJurusan" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Upload Gambar Jurusan</h3>
                <button type="button" onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form id="formImageJurusan" action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex flex-col gap-4">
                    <div>
                        <label for="jurusanSelect" class="block text-sm font-medium text-gray-700 mb-2">Pilih
                            Jurusan</label>
                        <select id="jurusanSelect" name="jurusan_id" required
                            class="w-full px-4 py-3 bg-white text-slate-800 text-sm font-medium rounded-xl border-2 border-slate-200 focus:border-brand-600 focus:ring-4 focus:ring-brand-100 focus:outline-none transition">
                            <option value="">-- Pilih Jurusan --</option>
                            @foreach ($jurusans as $jurusan)
                                <option value="{{ $jurusan->id }}"
                                    data-route="{{ route('update.jurusan.image.ppdb', $jurusan) }}">
                                    {{ $jurusan->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="imgInput" class="block text-sm font-medium text-gray-700 mb-2">Pilih Gambar</label>
                        <input type="file" id="imgInput" name="img"
                            accept="image/jpeg,image/jpg,image/png,image/webp" required
                            class="w-full px-4 py-3 bg-slate-50 text-slate-800 text-sm font-medium rounded-xl border-2 border-slate-200 focus:border-brand-600 focus:ring-4 focus:ring-brand-100 focus:outline-none transition">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG, WEBP. Maksimal 2 MB.</p>
                    </div>
                    <div class="flex gap-3 mt-4">
                        <button type="button" onclick="closeImageModal()"
                            class="flex-1 px-4 py-3 bg-slate-100 text-slate-800 text-sm font-medium rounded-xl border-2 border-slate-200 hover:bg-slate-200 focus:ring-4 focus:ring-slate-100 focus:outline-none transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 bg-brand-600 text-white text-sm font-medium rounded-xl border-2 border-brand-600 hover:bg-brand-700 focus:ring-4 focus:ring-brand-100 focus:outline-none transition">
                            Upload
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openImageModal() {
            const modal = document.getElementById('modalImageJurusan');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeImageModal() {
            const modal = document.getElementById('modalImageJurusan');
            const form = document.getElementById('formImageJurusan');
            const select = document.getElementById('jurusanSelect');
            const imgInput = document.getElementById('imgInput');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            form.reset();
            select.value = '';
            form.action = '';
        }

        document.getElementById('jurusanSelect').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const route = selected.dataset.route;
            const form = document.getElementById('formImageJurusan');
            if (route) {
                form.action = route;
            } else {
                form.action = '';
            }
        });

        document.getElementById('modalImageJurusan').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });
    </script>
@endpush
