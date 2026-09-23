@extends('Admin.layout.app')

@section('title', 'Data Pelamar - BKK & PKL | SMK Negeri 2 Karanganyar')

@section('content')
    <main class="flex-1 h-full overflow-y-auto p-6 lg:p-8 space-y-6">

        @include('Admin.PklBkk.partials.header', ['pklPage' => 'Data Pelamar'])

        <section class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                <div class="flex items-center space-x-2">
                    <h2 class="text-sm font-extrabold text-brand-600 uppercase tracking-wider">Data Pelamar &amp; Tracer Study</h2>
                    <span id="pelamar-count" class="px-2 py-0.5 bg-brand-50 text-brand-600 rounded-full text-[10px] font-bold"></span>
                </div>
                <button onclick="exportPelamar()" class="px-3.5 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-full text-[11px] font-semibold flex items-center space-x-1.5 transition-colors">
                    <i class="fa-solid fa-file-export text-[10px]"></i>
                    <span>Unduh CSV</span>
                </button>
            </div>

            <!-- Pencarian & Filter Jurusan -->
            <div class="flex flex-wrap items-center gap-3 mb-5">
                <div class="relative flex-1 min-w-[220px]">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                    <input id="search-pelamar" type="text" placeholder="Cari nama alumni / posisi lamaran..." oninput="renderTable()"
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-9 pr-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15">
                </div>
                <div id="filter-pelamar" class="flex flex-wrap items-center gap-2"></div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs md:text-sm">
                    <thead>
                        <tr class="border-b-2 border-slate-800 text-slate-800 font-extrabold">
                            <th class="py-3 px-2">ID</th>
                            <th class="py-3 px-2">Nama Alumni</th>
                            <th class="py-3 px-2">Angkatan</th>
                            <th class="py-3 px-2">Jurusan</th>
                            <th class="py-3 px-2">Melamar Di</th>
                            <th class="py-3 px-2">Status</th>
                            <th class="py-3 px-2">Tracer Study</th>
                        </tr>
                    </thead>
                    <tbody id="pelamar-table" class="divide-y divide-slate-200 text-slate-700 font-medium"></tbody>
                </table>
            </div>
        </section>

    </main>
@endsection

@push('scripts')
    <script>
        const JURUSAN = ['RPL', 'TKJ', 'TKR', 'TBSM', 'DKV', 'AK'];
        const STORAGE_KEY = 'pklbkk.pelamars';
        let pelamarFilter = 'Semua';

        const seedPelamars = [
            { id: 'PLM-01', nama: 'Andika Pratama', angkatan: 2025, jurusan: 'RPL', melamar: 'Software Developer - PT Telkom', status: 'Diterima', tracer: 'Terisi' },
            { id: 'PLM-02', nama: 'Siti Nurhaliza', angkatan: 2024, jurusan: 'AK', melamar: 'Staff Admin - Indomaret', status: 'Proses', tracer: 'Belum' },
            { id: 'PLM-03', nama: 'Bagas Saputra', angkatan: 2025, jurusan: 'TKJ', melamar: 'Network Admin - SOLO.NET', status: 'Proses', tracer: 'Terisi' },
            { id: 'PLM-04', nama: 'Rani Kusuma', angkatan: 2025, jurusan: 'DKV', melamar: 'UI/UX - Software House Solo', status: 'Diterima', tracer: 'Terisi' },
            { id: 'PLM-05', nama: 'Dimas Anggara', angkatan: 2024, jurusan: 'TKR', melamar: 'Teknisi - Nasmoco', status: 'Ditolak', tracer: 'Terisi' },
            { id: 'PLM-06', nama: 'Fitri Handayani', angkatan: 2025, jurusan: 'AK', melamar: 'Teller - Bank Jateng', status: 'Proses', tracer: 'Belum' },
            { id: 'PLM-07', nama: 'Yusuf Maulana', angkatan: 2024, jurusan: 'RPL', melamar: 'Software Developer - PT Telkom', status: 'Diterima', tracer: 'Terisi' },
            { id: 'PLM-08', nama: 'Dewi Lestari', angkatan: 2025, jurusan: 'TBSM', melamar: 'Operator - PT PLN', status: 'Proses', tracer: 'Belum' },
        ];

        function loadPelamars() {
            try {
                const raw = JSON.parse(localStorage.getItem(STORAGE_KEY));
                if (Array.isArray(raw) && raw.length) return raw;
            } catch (e) { /* abaikan */ }
            localStorage.setItem(STORAGE_KEY, JSON.stringify(seedPelamars));
            return seedPelamars;
        }

        function statusBadge(status) {
            let tone = ['Aktif', 'Terverifikasi', 'Diterima', 'Terisi', 'Tuntas', 'Hadir'].includes(status) ? 'emerald'
                : ['Tutup', 'Revisi', 'Ditolak', 'Alpa', 'Belum'].includes(status) ? 'red' : 'amber';
            const badgeMap = { emerald: 'bg-emerald-100 text-emerald-700', red: 'bg-red-100 text-red-700', amber: 'bg-amber-100 text-amber-700' };
            const dotMap = { emerald: 'bg-emerald-500', red: 'bg-red-500', amber: 'bg-amber-500' };
            return '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide ' + badgeMap[tone] + '"><span class="w-1.5 h-1.5 rounded-full ' + dotMap[tone] + '"></span>' + status + '</span>';
        }

        function renderChips() {
            const container = document.getElementById('filter-pelamar');
            container.innerHTML = ['Semua', ...JURUSAN].map(j =>
                '<button type="button" onclick="setPelamarFilter(\'' + j + '\')" class="px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors ' +
                (pelamarFilter === j
                    ? 'bg-brand-600 border-brand-600 text-white shadow-sm'
                    : 'border-slate-200 bg-white text-slate-600 hover:border-brand-500 hover:text-brand-600') + '">' + j + '</button>'
            ).join('');
        }

        function setPelamarFilter(jurusan) {
            pelamarFilter = jurusan;
            renderChips();
            renderTable();
        }

        function renderTable() {
            const q = (document.getElementById('search-pelamar').value || '').toLowerCase();
            let list = loadPelamars();
            const rows = list.filter(p =>
                (pelamarFilter === 'Semua' || p.jurusan === pelamarFilter) &&
                (p.nama.toLowerCase().includes(q) || p.melamar.toLowerCase().includes(q))
            );

            document.getElementById('pelamar-count').innerText = list.length + ' pelamar terdaftar';
            document.getElementById('pelamar-table').innerHTML = rows.map(p => `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="py-3.5 px-2 font-bold text-slate-800">${p.id}</td>
                    <td class="py-3.5 px-2 font-semibold">${p.nama}</td>
                    <td class="py-3.5 px-2 text-slate-500">${p.angkatan}</td>
                    <td class="py-3.5 px-2"><span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md text-[10px] font-bold">${p.jurusan}</span></td>
                    <td class="py-3.5 px-2 text-slate-600">${p.melamar}</td>
                    <td class="py-3.5 px-2">${statusBadge(p.status)}</td>
                    <td class="py-3.5 px-2">${statusBadge(p.tracer)}</td>
                </tr>
            `).join('') || '<tr><td colspan="7" class="py-8 text-center text-slate-400">Tidak ada data yang cocok.</td></tr>';
        }

        function exportPelamar() {
            const list = loadPelamars();
            const header = ['ID', 'Nama', 'Angkatan', 'Jurusan', 'Melamar Di', 'Status', 'Tracer Study'];
            const csv = [header.join(','), ...list.map(p =>
                [p.id, '"' + p.nama + '"', p.angkatan, p.jurusan, '"' + p.melamar + '"', p.status, p.tracer].join(',')
            )].join('\n');
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'data-pelamar-bkk.csv';
            a.click();
            URL.revokeObjectURL(a.href);
        }

        renderChips();
        renderTable();
    </script>
@endpush