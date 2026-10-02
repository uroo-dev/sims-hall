@extends('Admin.layout.app')

@section('title', 'Data Guru - Data Master')
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

    <!-- CARD: DAFTAR GURU -->
    <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-100/80">
        
        <!-- Header + Tombol Tambah -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-cyan-100 text-cyan-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-base">Daftar Guru</h3>
                </div>
                <p class="text-xs text-gray-500 mt-1">Data pendidik dan guru pengampu kejuruan di SMKN 2 Karanganyar</p>
            </div>
            <button onclick="openAddModal()"
                class="bg-[#0073c6] hover:bg-brand-700 text-white text-xs font-semibold px-5 py-2.5 rounded-xl transition shadow-sm hover:shadow-md flex items-center gap-2 cursor-pointer active:scale-95">
                <i class="fa-solid fa-plus text-xs"></i> Tambah Guru
            </button>
        </div>

        <!-- Search & Filter -->
        <div class="flex flex-col sm:flex-row gap-3 mb-6">
            <div class="relative flex-1 max-w-md">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" id="searchInput" placeholder="Cari guru berdasarkan nama, NIP, kontak..."
                    onkeyup="filterGuru()"
                    class="w-full border border-gray-200 rounded-xl pl-10 pr-3.5 py-2.5 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition shadow-xs">
            </div>
            <select id="jurusanFilter" onchange="filterGuru()"
                class="border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-gray-700 bg-white shadow-xs">
                <option value="">Semua Jurusan</option>
                @foreach($jurusans as $j)
                    <option value="{{ strtolower($j) }}">{{ $j }}</option>
                @endforeach
            </select>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border border-gray-100">
            <table class="w-full text-left border-collapse" id="guruTable">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200 text-gray-700 font-bold text-xs">
                        <th class="py-3 px-4">NIP</th>
                        <th class="py-3 px-4">Nama Lengkap</th>
                        <th class="py-3 px-4">Jurusan</th>
                        <th class="py-3 px-4">Kontak / No. HP</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs text-gray-600" id="guruTableBody">
                    @forelse($gurus as $guru)
                    <tr class="hover:bg-gray-50/70 transition guru-row" 
                        data-nip="{{ strtolower($guru->nip ?? '') }}" 
                        data-nama="{{ strtolower($guru->nama) }}" 
                        data-jurusan="{{ strtolower($guru->jurusan) }}"
                        data-nohp="{{ strtolower($guru->no_hp ?? '') }}">
                        
                        <td class="py-3.5 px-4 font-mono font-medium text-gray-700">
                            @if($guru->nip)
                                <span class="bg-slate-100 text-slate-700 text-[11px] px-2 py-0.5 rounded font-mono">
                                    {{ $guru->nip }}
                                </span>
                            @else
                                <span class="text-gray-400 italic text-[11px]">- Belum ada NIP -</span>
                            @endif
                        </td>

                        <td class="py-3.5 px-4 font-semibold text-gray-800">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-cyan-50 text-cyan-600 flex items-center justify-center font-bold text-[11px] border border-cyan-100">
                                    {{ strtoupper(substr($guru->nama, 0, 1)) }}
                                </div>
                                <span>{{ $guru->nama }}</span>
                            </div>
                        </td>

                        <td class="py-3.5 px-4">
                            <span class="bg-blue-50 text-blue-700 border border-blue-100 text-[11px] font-semibold px-2.5 py-1 rounded-full inline-block">
                                {{ $guru->jurusan }}
                            </span>
                        </td>

                        <td class="py-3.5 px-4">
                            @if($guru->no_hp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $guru->no_hp) }}" target="_blank" class="inline-flex items-center gap-1.5 text-emerald-600 hover:text-emerald-700 hover:underline">
                                    <i class="fa-brands fa-whatsapp text-sm"></i>
                                    <span>{{ $guru->no_hp }}</span>
                                </a>
                            @else
                                <span class="text-gray-400 italic text-[11px]">-</span>
                            @endif
                        </td>

                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button type="button" 
                                    onclick="openEditModal({{ json_encode($guru) }})" 
                                    class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition flex items-center justify-center cursor-pointer"
                                    title="Edit Data Guru">
                                    <i class="fa-regular fa-pen-to-square text-xs"></i>
                                </button>
                                <button type="button" 
                                    onclick="openDeleteModal({{ $guru->id }}, '{{ addslashes($guru->nama) }}')" 
                                    class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition flex items-center justify-center cursor-pointer"
                                    title="Hapus Data Guru">
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
                                    <i class="fa-solid fa-chalkboard-user"></i>
                                </div>
                                <span class="text-xs font-medium">Belum ada data guru tersimpan.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-xs text-gray-500 flex items-center justify-between">
            <span>Total: <strong class="text-gray-800">{{ count($gurus) }}</strong> guru</span>
            <span id="filteredCount" class="hidden text-brand-600 font-semibold"></span>
        </div>
    </div>

    <!-- ==================== MODAL TAMBAH GURU ==================== -->
    <div id="addGuruModal" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-3xl w-full max-w-xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200 max-h-[90vh] flex flex-col" id="addGuruModalBox">
            <!-- HEADER -->
            <div class="p-5 md:p-6 pb-2 md:pb-3 bg-white flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-lg border border-brand-100/80 shadow-xs flex-shrink-0">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base md:text-lg tracking-tight">Tambah Data Guru</h3>
                        <p class="text-slate-500 text-xs mt-0.5">Lengkapi formulir data pendidik SMKN 2 Karanganyar</p>
                    </div>
                </div>
                <button type="button" onclick="closeAddModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition cursor-pointer" title="Tutup Modal">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            
            <form action="{{ route('datamaster.guru.store') }}" method="POST" class="p-5 md:p-6 space-y-4 overflow-y-auto">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nama Lengkap Guru <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Drs. Bambang Sutrisno, M.Kom."
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">NIP <span class="text-slate-400 font-normal">(Opsional)</span></label>
                            <input type="text" name="nip" value="{{ old('nip') }}" placeholder="Contoh: 198005122005011003"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nomor HP / WhatsApp</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Jurusan Pengampu <span class="text-red-500">*</span></label>
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
                        <span>Simpan Guru Baru</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== MODAL EDIT GURU ==================== -->
    <div id="editGuruModal" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-3xl w-full max-w-xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200 max-h-[90vh] flex flex-col" id="editGuruModalBox">
            <!-- HEADER -->
            <div class="p-5 md:p-6 pb-2 md:pb-3 bg-white flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg border border-amber-100/80 shadow-xs flex-shrink-0">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base md:text-lg tracking-tight">Edit Data Guru</h3>
                        <p class="text-slate-500 text-xs mt-0.5">Perbarui data guru yang dipilih</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition cursor-pointer" title="Tutup Modal">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            
            <form id="editGuruForm" method="POST" class="p-5 md:p-6 space-y-4 overflow-y-auto">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nama Lengkap Guru <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="edit_nama" required placeholder="Contoh: Drs. Bambang Sutrisno, M.Kom."
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">NIP <span class="text-slate-400 font-normal">(Opsional)</span></label>
                            <input type="text" name="nip" id="edit_nip" placeholder="Contoh: 198005122005011003"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nomor HP / WhatsApp</label>
                            <input type="text" name="no_hp" id="edit_no_hp" placeholder="Contoh: 081234567890"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Jurusan Pengampu <span class="text-red-500">*</span></label>
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

    <!-- ==================== MODAL HAPUS GURU ==================== -->
    <div id="deleteGuruModal" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200 p-6 text-center" id="deleteGuruModalBox">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl mx-auto mb-4 border border-rose-100 shadow-xs">
                <i class="fa-regular fa-trash-can"></i>
            </div>
            <h3 class="font-extrabold text-slate-900 text-lg mb-1">Hapus Data Guru?</h3>
            <p class="text-slate-500 text-xs mb-5 leading-relaxed">
                Apakah Anda yakin ingin menghapus guru <strong id="deleteGuruName" class="text-slate-800"></strong>? Tindakan ini tidak dapat dibatalkan.
            </p>
            <form id="deleteGuruForm" method="POST" class="flex items-center justify-center gap-2.5">
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
        function filterGuru() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const jurusan = document.getElementById('jurusanFilter').value.toLowerCase();
            const rows = document.querySelectorAll('.guru-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const nip = row.getAttribute('data-nip') || '';
                const nama = row.getAttribute('data-nama') || '';
                const rowJurusan = row.getAttribute('data-jurusan') || '';
                const nohp = row.getAttribute('data-nohp') || '';

                const matchesQuery = !query || 
                    nip.includes(query) || 
                    nama.includes(query) || 
                    nohp.includes(query);

                const matchesJurusan = !jurusan || rowJurusan.includes(jurusan);

                if (matchesQuery && matchesJurusan) {
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

            if (query || jurusan) {
                filteredCount.classList.remove('hidden');
                filteredCount.innerText = `Menampilkan ${visibleCount} guru`;
            } else {
                filteredCount.classList.add('hidden');
            }
        }

        // ADD MODAL
        function openAddModal() {
            const modal = document.getElementById('addGuruModal');
            const box = document.getElementById('addGuruModalBox');
            modal.classList.remove('hidden');
            setTimeout(() => {
                box.classList.remove('scale-95');
                box.classList.add('scale-100');
            }, 10);
        }

        function closeAddModal() {
            const modal = document.getElementById('addGuruModal');
            const box = document.getElementById('addGuruModalBox');
            box.classList.remove('scale-100');
            box.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }

        // EDIT MODAL
        function openEditModal(guru) {
            const form = document.getElementById('editGuruForm');
            form.action = `{{ url('dashboard/data-master/guru') }}/${guru.id}`;

            document.getElementById('edit_nama').value = guru.nama || '';
            document.getElementById('edit_nip').value = guru.nip || '';
            document.getElementById('edit_no_hp').value = guru.no_hp || '';
            document.getElementById('edit_jurusan').value = guru.jurusan || '';

            const modal = document.getElementById('editGuruModal');
            const box = document.getElementById('editGuruModalBox');
            modal.classList.remove('hidden');
            setTimeout(() => {
                box.classList.remove('scale-95');
                box.classList.add('scale-100');
            }, 10);
        }

        function closeEditModal() {
            const modal = document.getElementById('editGuruModal');
            const box = document.getElementById('editGuruModalBox');
            box.classList.remove('scale-100');
            box.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }

        // DELETE MODAL
        function openDeleteModal(id, nama) {
            const form = document.getElementById('deleteGuruForm');
            form.action = `{{ url('dashboard/data-master/guru') }}/${id}`;
            document.getElementById('deleteGuruName').innerText = nama;

            const modal = document.getElementById('deleteGuruModal');
            const box = document.getElementById('deleteGuruModalBox');
            modal.classList.remove('hidden');
            setTimeout(() => {
                box.classList.remove('scale-95');
                box.classList.add('scale-100');
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteGuruModal');
            const box = document.getElementById('deleteGuruModalBox');
            box.classList.remove('scale-100');
            box.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }

        // Close on backdrop click or Escape
        window.addEventListener('click', function(e) {
            if (e.target.id === 'addGuruModal') closeAddModal();
            if (e.target.id === 'editGuruModal') closeEditModal();
            if (e.target.id === 'deleteGuruModal') closeDeleteModal();
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
