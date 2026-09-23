@extends('Admin.layout.app')

@section('title', 'Tempat PKL - BKK & PKL | SMK Negeri 2 Karanganyar')

@section('content')
    <main class="flex-1 h-full overflow-y-auto p-6 lg:p-8 space-y-6">

        @include('Admin.PklBkk.partials.header', ['pklPage' => 'Tempat PKL'])

        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Form Tambah Mitra -->
            <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
                <h2 class="text-sm font-extrabold text-brand-600 uppercase tracking-wider mb-1">Kelola PKL - Tempat PKL</h2>
                <p class="text-[11px] text-slate-400 mb-5 font-medium">Tambah / perbarui mitra DUDI.</p>

                <form onsubmit="return saveMitra(event)" class="space-y-4">
                    <div>
                        <label for="m-nama" class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Perusahaan <span class="text-red-500">*</span></label>
                        <input id="m-nama" type="text" placeholder="PT Telekomunikasi Indonesia" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15">
                    </div>
                    <div>
                        <label for="m-bidang" class="block text-xs font-semibold text-slate-600 mb-1.5">Bidang Industri</label>
                        <input id="m-bidang" type="text" placeholder="Jaringan &amp; IT" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15">
                    </div>
                    <div>
                        <label for="m-jurusan" class="block text-xs font-semibold text-slate-600 mb-1.5">Jurusan</label>
                        <select id="m-jurusan" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:bg-white"></select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="m-kuota" class="block text-xs font-semibold text-slate-600 mb-1.5">Kuota</label>
                            <input id="m-kuota" type="number" min="0" placeholder="12" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15">
                        </div>
                        <div>
                            <label for="m-status" class="block text-xs font-semibold text-slate-600 mb-1.5">Status</label>
                            <select id="m-status" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:bg-white">
                                <option>Aktif</option>
                                <option>Pending</option>
                                <option>Tutup</option>
                            </select>
                        </div>
                    </div>

                    <div id="m-message" class="hidden rounded-xl px-4 py-3 text-xs font-semibold"></div>

                    <div class="flex space-x-3">
                        <button type="button" onclick="resetMitraForm()" class="flex-1 py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-full text-sm transition-colors">Reset</button>
                        <button type="submit" class="flex-1 py-2.5 px-4 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-full text-sm shadow-md transition-colors">Simpan</button>
                    </div>
                </form>
            </div>

            <!-- Tabel Mitra -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                    <div class="flex items-center space-x-2">
                        <h2 class="text-sm font-extrabold text-brand-600 uppercase tracking-wider">Daftar Tempat PKL</h2>
                        <span id="mitra-count" class="px-2 py-0.5 bg-brand-50 text-brand-600 rounded-full text-[10px] font-bold"></span>
                    </div>
                    <div id="filter-mitra" class="flex flex-wrap items-center gap-2"></div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs md:text-sm">
                        <thead>
                            <tr class="border-b-2 border-slate-800 text-slate-800 font-extrabold">
                                <th class="py-3 px-2">ID</th>
                                <th class="py-3 px-2">Nama Perusahaan</th>
                                <th class="py-3 px-2">Jurusan</th>
                                <th class="py-3 px-2">Bidang</th>
                                <th class="py-3 px-2 text-center">Kuota</th>
                                <th class="py-3 px-2">Status</th>
                                <th class="py-3 px-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="mitra-table" class="divide-y divide-slate-200 text-slate-700 font-medium"></tbody>
                    </table>
                </div>
            </div>
        </section>

    </main>
@endsection

@push('scripts')
    <script>
        const JURUSAN = ['RPL', 'TKJ', 'TKR', 'TBSM', 'DKV', 'AK'];
        const STORAGE_KEY = 'pklbkk.mitras';
        const DEFAULT_STATUS = ['Aktif', 'Pending', 'Tutup'];
        let mitraFilter = 'Semua';
        let editingMitraId = null;

        const seedMitras = [
            { id: 'DUDI-01', nama: 'PT Telekomunikasi Indonesia', jurusan: 'RPL', bidang: 'Jaringan & IT', kuota: 12, status: 'Aktif' },
            { id: 'DUDI-02', nama: 'Nasmoco Karanganyar', jurusan: 'TKR', bidang: 'Otomotif', kuota: 8, status: 'Aktif' },
            { id: 'DUDI-03', nama: 'Software House Solo', jurusan: 'RPL', bidang: 'Software Development', kuota: 15, status: 'Aktif' },
            { id: 'DUDI-04', nama: 'PT PLN ULP Karanganyar', jurusan: 'TBSM', bidang: 'Kelistrikan & Jaringan', kuota: 6, status: 'Aktif' },
            { id: 'DUDI-05', nama: 'Hotel Brothers Karanganyar', jurusan: 'AK', bidang: 'Hospitality & Administrasi', kuota: 5, status: 'Pending' },
            { id: 'DUDI-06', nama: 'Pindad Enjiniring Indonesia', jurusan: 'TBSM', bidang: 'Manufaktur & Perbengkelan', kuota: 10, status: 'Aktif' },
            { id: 'DUDI-07', nama: 'Dinas Kominfo Kab. Karanganyar', jurusan: 'DKV', bidang: 'IT & Public Relation', kuota: 4, status: 'Pending' },
            { id: 'DUDI-08', nama: 'PT Pertamina Patra Niaga', jurusan: 'TBSM', bidang: 'Logistik & Energi', kuota: 6, status: 'Aktif' },
        ];

        function loadMitras() {
            try {
                const raw = JSON.parse(localStorage.getItem(STORAGE_KEY));
                if (Array.isArray(raw) && raw.length) return raw;
            } catch (e) { /* abaikan */ }
            localStorage.setItem(STORAGE_KEY, JSON.stringify(seedMitras));
            return seedMitras;
        }

        function statusBadge(status) {
            let tone = ['Aktif', 'Terverifikasi', 'Diterima', 'Terisi', 'Tuntas', 'Hadir'].includes(status) ? 'emerald'
                : ['Tutup', 'Revisi', 'Ditolak', 'Alpa', 'Belum'].includes(status) ? 'red' : 'amber';
            const badgeMap = { emerald: 'bg-emerald-100 text-emerald-700', red: 'bg-red-100 text-red-700', amber: 'bg-amber-100 text-amber-700' };
            const dotMap = { emerald: 'bg-emerald-500', red: 'bg-red-500', amber: 'bg-amber-500' };
            return '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide ' + badgeMap[tone] + '"><span class="w-1.5 h-1.5 rounded-full ' + dotMap[tone] + '"></span>' + status + '</span>';
        }

        function fillJurusan(selected) {
            document.getElementById('m-jurusan').innerHTML = JURUSAN.map(j =>
                '<option' + (j === selected ? ' selected' : '') + '>' + j + '</option>'
            ).join('');
        }

        function nextId(list) {
            const max = list.reduce((acc, m) => {
                const num = parseInt(m.id.split('-')[1], 10);
                return num > acc ? num : acc;
            }, 0);
            return 'DUDI-' + String(max + 1).padStart(2, '0');
        }

        function resetMitraForm() {
            editingMitraId = null;
            document.getElementById('m-nama').value = '';
            document.getElementById('m-bidang').value = '';
            document.getElementById('m-kuota').value = '';
            document.getElementById('m-status').value = 'Aktif';
            fillJurusan(JURUSAN[0]);
            const msg = document.getElementById('m-message');
            msg.classList.add('hidden');
        }

        function editMitra(id) {
            const mitra = loadMitras().find(m => m.id === id);
            if (!mitra) return;
            editingMitraId = id;
            document.getElementById('m-nama').value = mitra.nama || '';
            document.getElementById('m-bidang').value = mitra.bidang || '';
            document.getElementById('m-kuota').value = mitra.kuota ?? '';
            document.getElementById('m-status').value = mitra.status || 'Aktif';
            fillJurusan(mitra.jurusan);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function showMitraMessage(type, text) {
            const el = document.getElementById('m-message');
            el.classList.remove('hidden');
            el.className = 'rounded-xl px-4 py-3 text-xs font-semibold ' +
                (type === 'error' ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600');
            el.innerText = text;
        }

        function saveMitra(e) {
            e.preventDefault();
            const nama = document.getElementById('m-nama').value.trim();
            if (!nama) return showMitraMessage('error', 'Nama perusahaan wajib diisi.');

            let list = loadMitras();
            const payload = {
                id: editingMitraId || nextId(list),
                nama: nama,
                jurusan: document.getElementById('m-jurusan').value,
                bidang: document.getElementById('m-bidang').value.trim(),
                kuota: Math.max(0, parseInt(document.getElementById('m-kuota').value, 10) || 0),
                status: document.getElementById('m-status').value,
            };

            const idx = list.findIndex(m => m.id === payload.id);
            if (idx >= 0) list[idx] = payload;
            else list.unshift(payload);

            localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
            showMitraMessage('success', (editingMitraId ? 'Tempat PKL ' + editingMitraId + ' diperbarui.' : 'Tempat PKL baru ditambahkan.'));
            resetMitraForm();
            renderTable();
            return false;
        }

        function deleteMitra(id) {
            if (!confirm('Hapus mitra ' + id + '?')) return;
            localStorage.setItem(STORAGE_KEY, JSON.stringify(loadMitras().filter(m => m.id !== id)));
            renderTable();
        }

        function renderChips() {
            const container = document.getElementById('filter-mitra');
            container.innerHTML = ['Semua', ...JURUSAN].map(j =>
                '<button type="button" onclick="setMitraFilter(\'' + j + '\')" class="px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors ' +
                (mitraFilter === j
                    ? 'bg-brand-600 border-brand-600 text-white shadow-sm'
                    : 'border-slate-200 bg-white text-slate-600 hover:border-brand-500 hover:text-brand-600') + '">' + j + '</button>'
            ).join('');
        }

        function setMitraFilter(jurusan) {
            mitraFilter = jurusan;
            renderChips();
            renderTable();
        }

        function renderTable() {
            let list = loadMitras();
            const rows = mitraFilter === 'Semua' ? list : list.filter(m => m.jurusan === mitraFilter);

            document.getElementById('mitra-count').innerText = list.length + ' mitra terdaftar';
            document.getElementById('mitra-table').innerHTML = rows.map(m => `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="py-3.5 px-2 font-bold text-slate-800">${m.id}</td>
                    <td class="py-3.5 px-2 text-slate-600 font-semibold">${m.nama}</td>
                    <td class="py-3.5 px-2"><span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md text-[10px] font-bold">${m.jurusan}</span></td>
                    <td class="py-3.5 px-2 text-slate-500">${m.bidang}</td>
                    <td class="py-3.5 px-2 text-center font-bold">${m.kuota}</td>
                    <td class="py-3.5 px-2">${statusBadge(m.status)}</td>
                    <td class="py-3.5 px-2">
                        <div class="flex items-center justify-end space-x-1.5">
                            <button onclick="editMitra('${m.id}')" title="Edit"
                                    class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-brand-600 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-pen text-[10px]"></i>
                            </button>
                            <button onclick="deleteMitra('${m.id}')" title="Hapus"
                                    class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-red-50 text-slate-400 hover:text-red-600 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-trash-can text-[10px]"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('') || '<tr><td colspan="7" class="py-8 text-center text-slate-400">Tidak ada mitra untuk jurusan ini.</td></tr>';
        }

        resetMitraForm();
        renderChips();
        renderTable();
    </script>
@endpush