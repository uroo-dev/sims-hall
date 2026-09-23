@extends('Admin.layout.app')

@section('title', 'Rekap Nilai PKL - BKK & PKL | SMK Negeri 2 Karanganyar')

@section('content')
    <main class="flex-1 h-full overflow-y-auto p-6 lg:p-8 space-y-6">

        @include('Admin.PklBkk.partials.header', ['pklPage' => 'Rekap Nilai PKL'])

        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Form Input Nilai -->
            <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
                <h2 class="text-sm font-extrabold text-brand-600 uppercase tracking-wider mb-5">Input Nilai Akhir PKL</h2>

                <form onsubmit="return saveNilai(event)" class="space-y-4">
                    <div>
                        <label for="n-siswa" class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Siswa <span class="text-red-500">*</span></label>
                        <select id="n-siswa" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:bg-white"></select>
                    </div>
                    <div>
                        <label for="n-nilai" class="block text-xs font-semibold text-slate-600 mb-1.5">Nilai <span class="text-red-500">*</span></label>
                        <input id="n-nilai" type="number" min="0" max="100" oninput="previewNilai()" placeholder="0 - 100"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15">
                    </div>
                    <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5">
                        <span class="text-xs font-semibold text-slate-500">Predikat</span>
                        <span id="n-predikat" class="text-sm font-extrabold text-brand-600">-</span>
                    </div>
                    <div>
                        <label for="n-status" class="block text-xs font-semibold text-slate-600 mb-1.5">Status</label>
                        <select id="n-status" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:bg-white">
                            <option>Tuntas</option>
                            <option>Belum</option>
                        </select>
                    </div>

                    <div id="n-message" class="hidden rounded-xl px-4 py-3 text-xs font-semibold"></div>

                    <button type="submit" class="w-full py-2.5 px-4 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-full text-sm shadow-md transition-colors">Simpan Nilai</button>
                </form>
            </div>

            <!-- Tabel Nilai -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                    <div class="flex items-center space-x-2">
                        <h2 class="text-sm font-extrabold text-brand-600 uppercase tracking-wider">Rekap Nilai Akhir PKL</h2>
                        <span id="nilai-count" class="px-2 py-0.5 bg-brand-50 text-brand-600 rounded-full text-[10px] font-bold"></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="exportNilai()" class="px-3.5 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-full text-[11px] font-semibold flex items-center space-x-1.5 transition-colors">
                            <i class="fa-solid fa-file-arrow-down text-[10px]"></i>
                            <span>Unduh Nilai</span>
                        </button>
                        <button onclick="window.print()" class="px-3.5 py-1.5 bg-brand-600 hover:bg-brand-700 text-white rounded-full text-[11px] font-semibold flex items-center space-x-1.5 transition-colors shadow-sm">
                            <i class="fa-solid fa-print text-[10px]"></i>
                            <span>Cetak</span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs md:text-sm">
                        <thead>
                            <tr class="border-b-2 border-slate-800 text-slate-800 font-extrabold">
                                <th class="py-3 px-2">ID</th>
                                <th class="py-3 px-2">Nama Siswa</th>
                                <th class="py-3 px-2">Kelas</th>
                                <th class="py-3 px-2">Semester</th>
                                <th class="py-3 px-2 text-center">Nilai</th>
                                <th class="py-3 px-2 text-center">Predikat</th>
                                <th class="py-3 px-2">Status</th>
                                <th class="py-3 px-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="nilai-table" class="divide-y divide-slate-200 text-slate-700 font-medium"></tbody>
                    </table>
                </div>
            </div>
        </section>

    </main>
@endsection

@push('scripts')
    <script>
        const STORAGE_KEY = 'pklbkk.nilais';
        const SISWA_LIST = [
            { id: 'NR-01', nama: 'Andi Pratama', kelas: 'XII RPL 1' },
            { id: 'NR-02', nama: 'Siti Rahmawati', kelas: 'XII TKR 2' },
            { id: 'NR-03', nama: 'Bagas Saputra', kelas: 'XII TKJ 1' },
            { id: 'NR-04', nama: 'Rani Kusuma', kelas: 'XII DKV 1' },
            { id: 'NR-05', nama: 'Dimas Anggara', kelas: 'XII TBSM 1' },
            { id: 'NR-06', nama: 'Fitri Handayani', kelas: 'XII AK 1' },
            { id: 'NR-07', nama: 'Yusuf Maulana', kelas: 'XII RPL 2' },
            { id: 'NR-08', nama: 'Dewi Lestari', kelas: 'XII TBSM 2' },
        ];

        const seedNilais = [
            { id: 'NR-01', nama: 'Andi Pratama', kelas: 'XII RPL 1', semester: 'Ganjil', nilai: 88, status: 'Tuntas' },
            { id: 'NR-02', nama: 'Siti Rahmawati', kelas: 'XII TKR 2', semester: 'Ganjil', nilai: 85, status: 'Tuntas' },
            { id: 'NR-03', nama: 'Bagas Saputra', kelas: 'XII TKJ 1', semester: 'Ganjil', nilai: 79, status: 'Tuntas' },
            { id: 'NR-04', nama: 'Rani Kusuma', kelas: 'XII DKV 1', semester: 'Ganjil', nilai: 91, status: 'Tuntas' },
            { id: 'NR-05', nama: 'Dimas Anggara', kelas: 'XII TBSM 1', semester: 'Ganjil', nilai: 74, status: 'Belum' },
            { id: 'NR-06', nama: 'Fitri Handayani', kelas: 'XII AK 1', semester: 'Ganjil', nilai: 68, status: 'Belum' },
            { id: 'NR-07', nama: 'Yusuf Maulana', kelas: 'XII RPL 2', semester: 'Ganjil', nilai: 86, status: 'Tuntas' },
            { id: 'NR-08', nama: 'Dewi Lestari', kelas: 'XII TBSM 2', semester: 'Ganjil', nilai: 55, status: 'Belum' },
        ];

        function loadNilais() {
            try {
                const raw = JSON.parse(localStorage.getItem(STORAGE_KEY));
                if (Array.isArray(raw) && raw.length) return raw;
            } catch (e) { /* abaikan */ }
            localStorage.setItem(STORAGE_KEY, JSON.stringify(seedNilais));
            return seedNilais;
        }

        function predikat(nilai) {
            return nilai >= 90 ? 'A' : nilai >= 80 ? 'B' : nilai >= 70 ? 'C' : 'D';
        }

        function statusBadge(status) {
            let tone = ['Aktif', 'Terverifikasi', 'Diterima', 'Terisi', 'Tuntas', 'Hadir'].includes(status) ? 'emerald'
                : ['Tutup', 'Revisi', 'Ditolak', 'Alpa', 'Belum'].includes(status) ? 'red' : 'amber';
            const badgeMap = { emerald: 'bg-emerald-100 text-emerald-700', red: 'bg-red-100 text-red-700', amber: 'bg-amber-100 text-amber-700' };
            const dotMap = { emerald: 'bg-emerald-500', red: 'bg-red-500', amber: 'bg-amber-500' };
            return '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide ' + badgeMap[tone] + '"><span class="w-1.5 h-1.5 rounded-full ' + dotMap[tone] + '"></span>' + status + '</span>';
        }

        function fillSiswaSelect() {
            document.getElementById('n-siswa').innerHTML = SISWA_LIST.map(s =>
                '<option value="' + s.id + '">' + s.nama + ' (' + s.kelas + ')</option>'
            ).join('');
        }

        function previewNilai() {
            const el = document.getElementById('n-predikat');
            const nilai = parseInt(document.getElementById('n-nilai').value, 10);
            el.innerText = isNaN(nilai) ? '-' : predikat(Math.max(0, Math.min(100, nilai)));
        }

        function showNilaiMessage(type, text) {
            const el = document.getElementById('n-message');
            el.classList.remove('hidden');
            el.className = 'rounded-xl px-4 py-3 text-xs font-semibold ' +
                (type === 'error' ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600');
            el.innerText = text;
        }

        function saveNilai(e) {
            e.preventDefault();
            const siswa = SISWA_LIST.find(s => s.id === document.getElementById('n-siswa').value);
            const nilai = parseInt(document.getElementById('n-nilai').value, 10);
            if (!siswa || isNaN(nilai)) return showNilaiMessage('error', 'Pilih siswa dan isi nilai terlebih dahulu.');
            if (nilai < 0 || nilai > 100) return showNilaiMessage('error', 'Nilai harus antara 0 sampai 100.');

            let list = loadNilais();
            let payload = {
                id: siswa.id,
                nama: siswa.nama,
                kelas: siswa.kelas,
                semester: 'Ganjil',
                nilai: nilai,
                status: document.getElementById('n-status').value,
            };
            const idx = list.findIndex(n => n.id === siswa.id);
            if (idx >= 0) list[idx] = payload;
            else list.unshift(payload);

            localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
            showNilaiMessage('success', 'Nilai ' + siswa.nama + ' berhasil disimpan.');
            renderTable();
            return false;
        }

        function inputNilai(id) {
            const item = loadNilais().find(n => n.id === id);
            if (!item) return;
            const nilai = prompt('Input nilai akhir PKL untuk ' + item.nama + ':', item.nilai);
            if (nilai === null) return;
            const num = Math.max(0, Math.min(100, parseInt(nilai, 10) || 0));
            let list = loadNilais();
            const target = list.find(n => n.id === id);
            target.nilai = num;
            target.status = num >= 70 ? 'Tuntas' : 'Belum';
            localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
            renderTable();
        }

        function renderTable() {
            const list = loadNilais();
            document.getElementById('nilai-count').innerText = list.length + ' siswa dinilai';
            document.getElementById('nilai-table').innerHTML = list.map(n => `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="py-3.5 px-2 font-bold text-slate-800">${n.id}</td>
                    <td class="py-3.5 px-2 font-semibold">${n.nama}</td>
                    <td class="py-3.5 px-2 text-slate-500">${n.kelas}</td>
                    <td class="py-3.5 px-2 text-slate-500">${n.semester}</td>
                    <td class="py-3.5 px-2">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-brand-50 text-brand-700 text-base font-extrabold">${n.nilai}</span>
                    </td>
                    <td class="py-3.5 px-2 text-center font-bold text-slate-600">${predikat(n.nilai)}</td>
                    <td class="py-3.5 px-2">${statusBadge(n.status)}</td>
                    <td class="py-3.5 px-2">
                        <div class="flex items-center justify-end">
                            <button onclick="inputNilai('${n.id}')" title="Input nilai"
                                    class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-brand-600 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-pen text-[10px]"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function exportNilai() {
            const list = loadNilais();
            const header = ['ID', 'Nama', 'Kelas', 'Semester', 'Nilai', 'Predikat', 'Status'];
            const csv = [header.join(','), ...list.map(n =>
                [n.id, '"' + n.nama + '"', n.kelas, n.semester, n.nilai, predikat(n.nilai), n.status].join(',')
            )].join('\n');
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'rekap-nilai-pkl.csv';
            a.click();
            URL.revokeObjectURL(a.href);
        }

        fillSiswaSelect();
        renderTable();
    </script>
@endpush