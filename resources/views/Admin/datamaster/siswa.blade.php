@extends('Admin.layout.app')

@section('title', 'Data Siswa - Data Master')
@section('content')

    <!-- ALERT NOTIFIKASI -->
    @if(session('success'))
        <div class="mb-5 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs md:text-sm font-semibold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                </div>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 transition">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs md:text-sm font-medium shadow-xs">
            <div class="flex items-center gap-3 mb-2 font-bold text-rose-900">
                <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                </div>
                <span>Terdapat kesalahan validasi input:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 pl-11 text-xs">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- CARD: DAFTAR SISWA -->
    <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-100/80">
        
        <!-- Header + Tombol Tambah -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-base">Daftar Siswa</h3>
                </div>
                <p class="text-xs text-gray-500 mt-1">Data siswa aktif peserta didik di SMKN 2 Karanganyar</p>
            </div>
            <button onclick="openAddModal()"
                class="bg-[#0073c6] hover:bg-brand-700 text-white text-xs font-semibold px-5 py-2.5 rounded-xl transition shadow-sm hover:shadow-md flex items-center gap-2 cursor-pointer active:scale-95">
                <i class="fa-solid fa-plus text-xs"></i> Tambah Siswa
            </button>
        </div>

        <!-- Search & Filter -->
        <div class="flex flex-col sm:flex-row gap-3 mb-6">
            <div class="relative flex-1 max-w-md">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" id="searchInput" placeholder="Cari siswa berdasarkan NIS, nama, kelas, kontak..."
                    onkeyup="filterSiswa()"
                    class="w-full border border-gray-200 rounded-xl pl-10 pr-3.5 py-2.5 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition shadow-xs">
            </div>
            <select id="jurusanFilter" onchange="filterSiswa()"
                class="border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-gray-700 bg-white shadow-xs">
                <option value="">Semua Jurusan</option>
                @foreach($jurusans as $j)
                    <option value="{{ strtolower($j) }}">{{ $j }}</option>
                @endforeach
            </select>
            @if(!empty($kelas))
            <select id="kelasFilter" onchange="filterSiswa()"
                class="border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-gray-700 bg-white shadow-xs">
                <option value="">Semua Kelas</option>
                @foreach($kelas as $k)
                    <option value="{{ strtolower($k) }}">{{ $k }}</option>
                @endforeach
            </select>
            @endif
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border border-gray-100">
            <table class="w-full text-left border-collapse" id="siswaTable">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200 text-gray-700 font-bold text-xs">
                        <th class="py-3 px-4">NIS</th>
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">Kelas & Jurusan</th>
                        <th class="py-3 px-4">Kontak / No. HP</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs text-gray-600" id="siswaTableBody">
                    @forelse($siswas as $siswa)
                    <tr class="hover:bg-gray-50/70 transition siswa-row" 
                        data-nis="{{ strtolower($siswa->nis) }}" 
                        data-nama="{{ strtolower($siswa->nama) }}" 
                        data-kelas="{{ strtolower($siswa->kelas) }}"
                        data-jurusan="{{ strtolower($siswa->jurusan) }}"
                        data-nohp="{{ strtolower($siswa->no_hp ?? '') }}">
                        
                        <td class="py-3.5 px-4 font-mono font-medium text-gray-700">
                            <span class="bg-slate-100 text-slate-800 text-[11px] font-mono px-2 py-0.5 rounded font-semibold border border-slate-200">
                                {{ $siswa->nis }}
                            </span>
                        </td>

                        <td class="py-3.5 px-4 font-semibold text-gray-800">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-[11px] border border-emerald-100">
                                    {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                                </div>
                                <span>{{ $siswa->nama }}</span>
                            </div>
                        </td>

                        <td class="py-3.5 px-4">
                            <div class="flex flex-col gap-1 items-start">
                                <span class="bg-gray-100 text-gray-800 font-bold text-[10px] px-2 py-0.5 rounded border border-gray-200">
                                    {{ $siswa->kelas }}
                                </span>
                                <span class="text-[11px] text-gray-600">
                                    {{ $siswa->jurusan }}
                                </span>
                            </div>
                        </td>

                        <td class="py-3.5 px-4">
                            @if($siswa->no_hp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siswa->no_hp) }}" target="_blank" class="inline-flex items-center gap-1.5 text-emerald-600 hover:text-emerald-700 hover:underline">
                                    <i class="fa-brands fa-whatsapp text-sm"></i>
                                    <span>{{ $siswa->no_hp }}</span>
                                </a>
                            @else
                                <span class="text-gray-400 italic text-[11px]">-</span>
                            @endif
                        </td>

                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button type="button" 
                                    onclick="openEditModal({{ json_encode($siswa) }})" 
                                    class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition flex items-center justify-center cursor-pointer"
                                    title="Edit Data Siswa">
                                    <i class="fa-regular fa-pen-to-square text-xs"></i>
                                </button>
                                <button type="button" 
                                    onclick="openDeleteModal({{ $siswa->id }}, '{{ addslashes($siswa->nama) }}')" 
                                    class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition flex items-center justify-center cursor-pointer"
                                    title="Hapus Data Siswa">
                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="5" class="py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300 text-xl">
                                    <i class="fa-solid fa-user-graduate"></i>
                                </div>
                                <span class="text-xs font-medium">Belum ada data siswa tersimpan.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-xs text-gray-500 flex items-center justify-between">
            <span>Total: <strong class="text-gray-800">{{ count($siswas) }}</strong> siswa</span>
            <span id="filteredCount" class="hidden text-brand-600 font-semibold"></span>
        </div>
    </div>

    <!-- ==================== MODAL TAMBAH SISWA ==================== -->
    <div id="addSiswaModal" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-3xl w-full max-w-xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200 max-h-[90vh] flex flex-col" id="addSiswaModalBox">
            <!-- HEADER -->
            <div class="p-5 md:p-6 pb-2 md:pb-3 bg-white flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg border border-emerald-100/80 shadow-xs flex-shrink-0">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base md:text-lg tracking-tight">Tambah Data Siswa</h3>
                        <p class="text-slate-500 text-xs mt-0.5">Lengkapi formulir data pokok siswa SMKN 2 Karanganyar</p>
                    </div>
                </div>
                <button type="button" onclick="closeAddModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition cursor-pointer" title="Tutup Modal">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            
            <form action="{{ route('datamaster.siswa.store') }}" method="POST" class="p-5 md:p-6 space-y-4 overflow-y-auto">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">NIS (Nomor Induk Siswa) <span class="text-red-500">*</span></label>
                            <input type="text" name="nis" value="{{ old('nis') }}" required placeholder="Contoh: 20241001"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nama Lengkap Siswa <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Ahmad Fauzan"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Kelas <span class="text-red-500">*</span></label>
                            <input type="text" name="kelas" value="{{ old('kelas') }}" required placeholder="Contoh: XII RPL 1"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nomor HP / WhatsApp</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Kompetensi Keahlian / Jurusan <span class="text-red-500">*</span></label>
                        <select name="jurusan" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                            <option value="" disabled {{ old('jurusan') ? '' : 'selected' }}>-- Pilih Jurusan --</option>
                            @foreach($jurusans as $j)
                                <option value="{{ $j }}" {{ old('jurusan') == $j ? 'selected' : '' }}>{{ $j }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeAddModal()" 
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:scale-95 text-white text-xs md:text-sm font-bold shadow-sm transition flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Simpan Siswa Baru</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== MODAL EDIT SISWA ==================== -->
    <div id="editSiswaModal" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-3xl w-full max-w-xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200 max-h-[90vh] flex flex-col" id="editSiswaModalBox">
            <!-- HEADER -->
            <div class="p-5 md:p-6 pb-2 md:pb-3 bg-white flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg border border-amber-100/80 shadow-xs flex-shrink-0">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base md:text-lg tracking-tight">Edit Data Siswa</h3>
                        <p class="text-slate-500 text-xs mt-0.5">Perbarui data siswa yang dipilih</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition cursor-pointer" title="Tutup Modal">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            
            <form id="editSiswaForm" method="POST" class="p-5 md:p-6 space-y-4 overflow-y-auto">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">NIS (Nomor Induk Siswa) <span class="text-red-500">*</span></label>
                            <input type="text" name="nis" id="edit_nis" required placeholder="Contoh: 20241001"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nama Lengkap Siswa <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="edit_nama" required placeholder="Contoh: Ahmad Fauzan"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Kelas <span class="text-red-500">*</span></label>
                            <input type="text" name="kelas" id="edit_kelas" required placeholder="Contoh: XII RPL 1"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nomor HP / WhatsApp</label>
                            <input type="text" name="no_hp" id="edit_no_hp" placeholder="Contoh: 081234567890"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Kompetensi Keahlian / Jurusan <span class="text-red-500">*</span></label>
                        <select name="jurusan" id="edit_jurusan" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                            <option value="" disabled>-- Pilih Jurusan --</option>
                            @foreach($jurusans as $j)
                                <option value="{{ $j }}">{{ $j }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeEditModal()" 
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 active:scale-95 text-white text-xs md:text-sm font-bold shadow-sm transition flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-check text-xs"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== MODAL HAPUS SISWA ==================== -->
    <div id="deleteSiswaModal" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200 p-6 text-center" id="deleteSiswaModalBox">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl mx-auto mb-4 border border-rose-100 shadow-xs">
                <i class="fa-regular fa-trash-can"></i>
            </div>
            <h3 class="font-extrabold text-slate-900 text-lg mb-1">Hapus Data Siswa?</h3>
            <p class="text-slate-500 text-xs mb-5 leading-relaxed">
                Apakah Anda yakin ingin menghapus data siswa <strong id="deleteSiswaName" class="text-slate-800"></strong>? Tindakan ini tidak dapat dibatalkan.
            </p>
            <form id="deleteSiswaForm" method="POST" class="flex items-center justify-center gap-2.5">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteModal()" 
                    class="w-full py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" 
                    class="w-full py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs md:text-sm font-bold shadow-sm transition cursor-pointer">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        function filterSiswa() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const jurusan = document.getElementById('jurusanFilter').value.toLowerCase();
            const kelasFilter = document.getElementById('kelasFilter');
            const kelas = kelasFilter ? kelasFilter.value.toLowerCase() : '';
            const rows = document.querySelectorAll('.siswa-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const nis = row.getAttribute('data-nis') || '';
                const nama = row.getAttribute('data-nama') || '';
                const rowKelas = row.getAttribute('data-kelas') || '';
                const rowJurusan = row.getAttribute('data-jurusan') || '';
                const nohp = row.getAttribute('data-nohp') || '';

                const matchesQuery = !query || 
                    nis.includes(query) || 
                    nama.includes(query) || 
                    rowKelas.includes(query) || 
                    nohp.includes(query);

                const matchesJurusan = !jurusan || rowJurusan.includes(jurusan);
                const matchesKelas = !kelas || rowKelas === kelas;

                if (matchesQuery && matchesJurusan && matchesKelas) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const emptyRow = document.getElementById('emptyRow');
            const filteredCount = document.getElementById('filteredCount');

            if (emptyRow) {
                emptyRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
            }

            if (query || jurusan || kelas) {
                filteredCount.classList.remove('hidden');
                filteredCount.innerText = `Menampilkan ${visibleCount} siswa`;
            } else {
                filteredCount.classList.add('hidden');
            }
        }

        // ADD MODAL
        function openAddModal() {
            const modal = document.getElementById('addSiswaModal');
            const box = document.getElementById('addSiswaModalBox');
            modal.classList.remove('hidden');
            setTimeout(() => {
                box.classList.remove('scale-95');
                box.classList.add('scale-100');
            }, 10);
        }

        function closeAddModal() {
            const modal = document.getElementById('addSiswaModal');
            const box = document.getElementById('addSiswaModalBox');
            box.classList.remove('scale-100');
            box.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }

        // EDIT MODAL
        function openEditModal(siswa) {
            const form = document.getElementById('editSiswaForm');
            form.action = `{{ url('dashboard/data-master/siswa') }}/${siswa.id}`;

            document.getElementById('edit_nis').value = siswa.nis || '';
            document.getElementById('edit_nama').value = siswa.nama || '';
            document.getElementById('edit_kelas').value = siswa.kelas || '';
            document.getElementById('edit_no_hp').value = siswa.no_hp || '';
            document.getElementById('edit_jurusan').value = siswa.jurusan || '';

            const modal = document.getElementById('editSiswaModal');
            const box = document.getElementById('editSiswaModalBox');
            modal.classList.remove('hidden');
            setTimeout(() => {
                box.classList.remove('scale-95');
                box.classList.add('scale-100');
            }, 10);
        }

        function closeEditModal() {
            const modal = document.getElementById('editSiswaModal');
            const box = document.getElementById('editSiswaModalBox');
            box.classList.remove('scale-100');
            box.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }

        // DELETE MODAL
        function openDeleteModal(id, nama) {
            const form = document.getElementById('deleteSiswaForm');
            form.action = `{{ url('dashboard/data-master/siswa') }}/${id}`;
            document.getElementById('deleteSiswaName').innerText = nama;

            const modal = document.getElementById('deleteSiswaModal');
            const box = document.getElementById('deleteSiswaModalBox');
            modal.classList.remove('hidden');
            setTimeout(() => {
                box.classList.remove('scale-95');
                box.classList.add('scale-100');
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteSiswaModal');
            const box = document.getElementById('deleteSiswaModalBox');
            box.classList.remove('scale-100');
            box.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }

        // Close on backdrop click or Escape
        window.addEventListener('click', function(e) {
            if (e.target.id === 'addSiswaModal') closeAddModal();
            if (e.target.id === 'editSiswaModal') closeEditModal();
            if (e.target.id === 'deleteSiswaModal') closeDeleteModal();
        });

        window.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddModal();
                closeEditModal();
                closeDeleteModal();
            }
        });
    </script>
@endsection
