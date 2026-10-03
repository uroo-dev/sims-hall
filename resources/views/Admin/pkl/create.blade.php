@extends('Admin.layout.app')

@section('title', 'Buat Pengajuan PKL')
@section('page_title', $pageTitle ?? 'Buat Pengajuan PKL')

@section('content')


    <form method="POST" action="{{ route('pkl.store') }}" id="form-pengajuan">
        @csrf

        <div class="space-y-4">

            {{-- STEP 1: PILIH / BUAT DUDI --}}
            <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-5">
                <div class="flex items-center gap-2 mb-4">
                    <span
                        class="w-6 h-6 rounded-full bg-brand-600 text-white text-xs font-bold flex items-center justify-center">1</span>
                    <h2 class="font-bold text-gray-900 text-sm">Pilih DUDI Tujuan</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">
                            DUDI Terdaftar <span class="text-xs font-normal text-brand-600">(Dapat Dicari)</span>
                        </label>
                        {{-- Hidden real select for form payload compatibility --}}
                        <select name="dudi_id" id="dudi-terdaftar" class="hidden">
                            <option value="">-- Buat DUDI baru (isi form di bawah) --</option>
                            @foreach ($dudis as $d)
                                <option value="{{ $d->id }}"
                                    data-sisa="{{ $d->sisa_kuota }}"
                                    data-nama="{{ $d->nama_dudi }}"
                                    @selected(old('dudi_id') == $d->id)>
                                    {{ $d->nama_dudi }} ({{ $d->kota }}) - sisa {{ $d->sisa_kuota }} kuota
                                </option>
                            @endforeach
                        </select>

                        {{-- Searchable Combobox UI --}}
                        <div class="relative" id="dudi-combobox">
                            <button type="button" id="dudi-combo-btn"
                                class="w-full text-left bg-white text-sm border border-gray-200 rounded-lg px-3 py-2 flex items-center justify-between focus:ring-2 focus:ring-brand-500 focus:border-brand-500 cursor-pointer shadow-xs">
                                <span id="dudi-combo-label" class="truncate text-gray-700 font-medium">
                                    -- Pilih / Cari DUDI terdaftar --
                                </span>
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400 ml-2 transition-transform duration-200" id="dudi-combo-icon"></i>
                            </button>

                            <div id="dudi-combo-menu"
                                class="hidden absolute left-0 right-0 z-50 mt-1.5 bg-white border border-gray-200 rounded-xl shadow-xl overflow-hidden text-sm">
                                <div class="p-2 border-b border-gray-100 bg-gray-50/80">
                                    <div class="relative">
                                        <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2.5 text-xs text-gray-400"></i>
                                        <input type="text" id="dudi-search-input" placeholder="Ketik nama perusahaan, kota, bidang usaha..."
                                            autocomplete="off"
                                            class="w-full pl-8 pr-3 py-1.5 text-xs border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                    </div>
                                </div>

                                <div class="max-h-60 overflow-y-auto divide-y divide-gray-50 p-1" id="dudi-combo-options">
                                    <div class="dudi-opt-item px-3 py-2.5 hover:bg-brand-50 text-brand-700 font-semibold text-xs cursor-pointer rounded-lg flex items-center gap-2 transition"
                                        data-val="" data-name="-- Buat DUDI baru (isi form di bawah) --" data-search="buat baru ketik baru">
                                        <i class="fa-solid fa-circle-plus text-brand-600"></i>
                                        <span>-- Buat DUDI baru (isi form di bawah) --</span>
                                    </div>
                                    @foreach ($dudis as $d)
                                        <div class="dudi-opt-item px-3 py-2 hover:bg-gray-50 cursor-pointer rounded-lg transition"
                                            data-val="{{ $d->id }}"
                                            data-name="{{ $d->nama_dudi }}"
                                            data-search="{{ strtolower($d->nama_dudi . ' ' . $d->kota . ' ' . $d->bidang_usaha) }}">
                                            <div class="flex items-center justify-between gap-2">
                                                <span class="font-semibold text-gray-900 text-xs truncate">{{ $d->nama_dudi }}</span>
                                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-blue-50 text-brand-700 shrink-0">
                                                    Sisa {{ $d->sisa_kuota }}
                                                </span>
                                            </div>
                                            <div class="text-[11px] text-gray-500 flex items-center gap-2 mt-0.5">
                                                <span><i class="fa-solid fa-location-dot text-[10px] mr-1 text-gray-400"></i>{{ $d->kota }}</span>
                                                <span>&bull;</span>
                                                <span class="truncate">{{ $d->bidang_usaha }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div id="dudi-combo-empty" class="hidden px-3 py-4 text-center text-xs text-gray-400">
                                        <i class="fa-solid fa-building-circle-xmark text-lg text-gray-300 mb-1 block"></i>
                                        Tidak ada DUDI yang cocok dengan pencarian
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1">Cari DUDI atau pilih opsi buat DUDI baru.</p>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">
                            Atau Ketik DUDI Baru
                        </label>
                        <input type="text" name="dudi_baru[nama]"
                            value="{{ old('dudi_baru.nama') }}"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                            placeholder="Nama perusahaan / lembaga baru" id="dudi-baru">
                        <p class="text-[10px] text-amber-600 mt-1">
                            <i class="fa-solid fa-circle-info mr-1"></i>
                            DUDI baru otomatis masuk master dengan status <strong>belum tayang</strong> di Landing Page.
                        </p>
                    </div>
                </div>

                {{-- Form detail DUDI baru (hanya saat DUDI baru) --}}
                <div id="panel-dudi-baru" class="hidden mt-4 pt-4 border-t border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Alamat</label>
                            <input type="text" name="dudi_baru[alamat]"
                                value="{{ old('dudi_baru.alamat') }}"
                                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Kota</label>
                            <input type="text" name="dudi_baru[kota]"
                                value="{{ old('dudi_baru.kota') }}"
                                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Bidang Usaha</label>
                            <input type="text" name="dudi_baru[bidang_usaha]"
                                value="{{ old('dudi_baru.bidang_usaha') }}"
                                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Kontak Person</label>
                            <input type="text" name="dudi_baru[kontak_person]"
                                value="{{ old('dudi_baru.kontak_person') }}"
                                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">No. HP DUDI</label>
                            <input type="text" name="dudi_baru[no_hp]"
                                value="{{ old('dudi_baru.no_hp') }}"
                                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Kuota Maksimal</label>
                            <input type="number" name="dudi_baru[kuota_maksimal]" min="0" value="{{ old('dudi_baru.kuota_maksimal', 0) }}"
                                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 2: MULTI-SELECT SISWA --}}
            <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-5">
                <div class="flex items-center gap-2 mb-4">
                    <span
                        class="w-6 h-6 rounded-full bg-brand-600 text-white text-xs font-bold flex items-center justify-center">2</span>
                    <h2 class="font-bold text-gray-900 text-sm">Pilih Siswa</h2>
                    <span class="text-[11px] text-gray-400">— hanya siswa yang belum punya tempat PKL FIX</span>
                </div>

                @if ($siswas->isEmpty())
                    <p class="text-sm text-gray-500 py-4 text-center">
                        Semua siswa sudah memiliki penempatan PKL berstatus FIX.
                    </p>
                @else
                    <div class="mb-3 flex items-center gap-3">
                        <input type="search" id="cari-siswa" placeholder="Cari nama / NIS siswa..."
                            class="flex-1 text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        <button type="button" id="pilih-semua"
                            class="text-xs font-semibold text-brand-600 hover:text-brand-700 px-3 py-2 rounded-lg transition shrink-0">
                            Pilih semua
                        </button>
                        <button type="button" id="hapus-semua"
                            class="text-xs font-semibold text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg transition shrink-0">
                            Kosongkan
                        </button>
                    </div>

                    <p class="text-xs text-gray-500 mb-2">
                        Terpilih: <strong id="jumlah-terpilih" class="text-brand-600">0</strong> siswa
                    </p>

                    <div class="max-h-72 overflow-y-auto border border-gray-200 rounded-lg divide-y divide-gray-100">
                        @foreach ($siswas as $s)
                            <label class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 cursor-pointer transition"
                                data-nama="{{ strtolower($s->nama . ' ' . $s->nis) }}">
                                <input type="checkbox" name="siswa_ids[]" value="{{ $s->id }}"
                                    @checked(in_array($s->id, old('siswa_ids', [])))
                                    class="row-checkbox w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                                <span class="flex-1 min-w-0">
                                    <span class="text-sm font-medium text-gray-800 block truncate">{{ $s->nama }}</span>
                                    <span class="text-[11px] text-gray-500">
                                        {{ $s->label_kelas }} &middot; NIS {{ $s->nis }}
                                    </span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- STEP 3: GURU PEMBIMBING & TANGGAL --}}
            <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-5">
                <div class="flex items-center gap-2 mb-4">
                    <span
                        class="w-6 h-6 rounded-full bg-brand-600 text-white text-xs font-bold flex items-center justify-center">3</span>
                    <h2 class="font-bold text-gray-900 text-sm">Guru Pembimbing &amp; Jadwal PKL</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">
                            Guru Pembimbing <span class="text-red-500">*</span>
                        </label>
                        <select name="guru_id" required
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            <option value="">-- Pilih Guru --</option>
                            @foreach ($gurus as $g)
                                <option value="{{ $g->id }}" @selected(old('guru_id') == $g->id)>
                                    {{ $g->nama }} ({{ $g->jurusan }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">
                            Tanggal Surat <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_surat" required max="{{ now()->toDateString() }}"
                            value="{{ old('tanggal_surat', now()->toDateString()) }}"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">
                            Mulai PKL <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tgl_mulai_pkl" required min="{{ now()->toDateString() }}"
                            value="{{ old('tgl_mulai_pkl', now()->toDateString()) }}"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">
                            Selesai PKL <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tgl_selesai_pkl" required
                            value="{{ old('tgl_selesai_pkl', now()->addMonths(3)->toDateString()) }}"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit"
                    class="text-sm font-semibold bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-lg transition">
                    <i class="fa-solid fa-file-signature mr-1.5"></i> Simpan &amp; Generate Surat PDF
                </button>
                <a href="{{ route('pkl.index') }}"
                    class="text-sm font-semibold text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-lg transition">
                    Batal
                </a>
            </div>

        </div>
    </form>

@endsection

@push('scripts')
    <script>
        (function() {
            // Helper: aman dipanggil walau elemen tidak ada (mis. tidak ada
            // siswa yang belum FIX, sehingga panelilih siswa tidak dirender).
            function on(id, event, handler) {
                const el = document.getElementById(id);
                if (el) el.addEventListener(event, handler);
                return el;
            }

            // --- DUDI terdaftar vs DUDI baru ---
            const selectDudi = document.getElementById('dudi-terdaftar');
            const inputDudiBaru = document.getElementById('dudi-baru');
            const panelBaru = document.getElementById('panel-dudi-baru');

            // --- Searchable Combobox Controls ---
            const comboWrapper = document.getElementById('dudi-combobox');
            const comboBtn = document.getElementById('dudi-combo-btn');
            const comboLabel = document.getElementById('dudi-combo-label');
            const comboIcon = document.getElementById('dudi-combo-icon');
            const comboMenu = document.getElementById('dudi-combo-menu');
            const searchInput = document.getElementById('dudi-search-input');
            const optItems = Array.from(document.querySelectorAll('.dudi-opt-item'));
            const emptyMsg = document.getElementById('dudi-combo-empty');

            function updateComboDisplay(val) {
                if (val === '') {
                    comboLabel.textContent = '-- Buat DUDI baru (isi form di bawah) --';
                    comboLabel.classList.add('text-brand-600', 'font-semibold');
                } else {
                    const item = optItems.find(function(it) { return it.dataset.val === String(val); });
                    comboLabel.textContent = item ? item.dataset.name : '-- Pilih / Cari DUDI terdaftar --';
                    comboLabel.classList.remove('text-brand-600');
                }
            }

            function openCombo() {
                if (!comboMenu) return;
                comboMenu.classList.remove('hidden');
                if (comboIcon) comboIcon.classList.add('rotate-180');
                if (searchInput) {
                    searchInput.value = '';
                    filterOptions('');
                    setTimeout(function() { searchInput.focus(); }, 50);
                }
            }

            function closeCombo() {
                if (!comboMenu) return;
                comboMenu.classList.add('hidden');
                if (comboIcon) comboIcon.classList.remove('rotate-180');
            }

            function filterOptions(query) {
                const q = query.toLowerCase().trim();
                let visibleCount = 0;
                optItems.forEach(function(item) {
                    const match = !q || item.dataset.search.includes(q) || item.dataset.name.toLowerCase().includes(q);
                    item.style.display = match ? '' : 'none';
                    if (match) visibleCount++;
                });
                if (emptyMsg) {
                    emptyMsg.classList.toggle('hidden', visibleCount > 0);
                }
            }

            if (comboBtn && comboMenu) {
                comboBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (comboMenu.classList.contains('hidden')) {
                        openCombo();
                    } else {
                        closeCombo();
                    }
                });

                if (searchInput) {
                    searchInput.addEventListener('input', function(e) {
                        filterOptions(e.target.value);
                    });
                    searchInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') closeCombo();
                    });
                }

                optItems.forEach(function(item) {
                    item.addEventListener('click', function() {
                        const val = this.dataset.val;
                        if (selectDudi) {
                            selectDudi.value = val;
                            selectDudi.dispatchEvent(new Event('change'));
                        }
                        updateComboDisplay(val);
                        closeCombo();
                    });
                });

                document.addEventListener('click', function(e) {
                    if (comboWrapper && !comboWrapper.contains(e.target)) {
                        closeCombo();
                    }
                });
            }

            if (selectDudi && inputDudiBaru && panelBaru) {
                function syncDudi() {
                    const pakaiBaru = selectDudi.value === '';
                    inputDudiBaru.disabled = !pakaiBaru;
                    inputDudiBaru.required = pakaiBaru;
                    panelBaru.classList.toggle('hidden', !pakaiBaru);
                    panelBaru.querySelectorAll('input').forEach(function(i) {
                        i.disabled = !pakaiBaru;
                    });
                    updateComboDisplay(selectDudi.value);
                }
                selectDudi.addEventListener('change', syncDudi);
                syncDudi();
            }

            // --- Multi-select siswa ---
            const checkboxes = Array.from(document.querySelectorAll('.row-checkbox'));
            const jumlah = document.getElementById('jumlah-terpilih');

            function updateJumlah() {
                if (jumlah) {
                    jumlah.textContent = checkboxes.filter(function(c) {
                        return c.checked;
                    }).length;
                }
            }
            checkboxes.forEach(function(c) {
                c.addEventListener('change', updateJumlah);
            });
            updateJumlah();

            on('pilih-semua', 'click', function() {
                checkboxes.forEach(function(c) {
                    if (c.closest('label').style.display !== 'none') c.checked = true;
                });
                updateJumlah();
            });
            on('hapus-semua', 'click', function() {
                checkboxes.forEach(function(c) {
                    c.checked = false;
                });
                updateJumlah();
            });

            // Pencarian siswa
            on('cari-siswa', 'input', function(e) {
                const q = e.target.value.toLowerCase().trim();
                checkboxes.forEach(function(c) {
                    const label = c.closest('label');
                    label.style.display = !q || label.dataset.nama.includes(q) ? '' : 'none';
                });
            });

            // Cegah submit tanpa siswa terpilih
            on('form-pengajuan', 'submit', function(e) {
                if (checkboxes.length === 0) return; // tidak ada siswa -> biarkan validasi server
                if (checkboxes.filter(function(c) {
                        return c.checked;
                    }).length === 0) {
                    e.preventDefault();
                    alert('Pilih minimal satu siswa sebelum menyimpan pengajuan.');
                }
            });
        })();
    </script>
@endpush
