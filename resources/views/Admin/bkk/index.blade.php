@extends('Admin.layout.app')

@section('title', 'Lowongan Kerja - BKK & PKL | SMK Negeri 2 Karanganyar')

@section('content')
    <main class="flex-1 h-full overflow-y-auto p-6 lg:p-8 space-y-6">

        @include('Admin.PklBkk.partials.header', ['pklPage' => 'Lowongan Kerja'])

        <section class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                <div class="flex items-center space-x-2">
                    <h2 class="text-sm font-extrabold text-brand-600 uppercase tracking-wider">Kelola BKK - Lowongan Kerja</h2>
                    <span id="loker-count" class="px-2 py-0.5 bg-brand-50 text-brand-600 rounded-full text-[10px] font-bold"></span>
                </div>
                <a href="{{ route('pklbkk.loker.create') }}" class="px-3.5 py-1.5 bg-brand-600 hover:bg-brand-700 text-white rounded-full text-[11px] font-semibold flex items-center space-x-1.5 transition-colors shadow-sm">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Tambah Loker</span>
                </a>
            </div>

            <!-- Filter Jurusan -->
            <div id="filter-loker" class="flex flex-wrap items-center gap-2 mb-5"></div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs md:text-sm">
                    <thead>
                        <tr class="border-b-2 border-slate-800 text-slate-800 font-extrabold">
                            <th class="py-3 px-2">ID</th>
                            <th class="py-3 px-2">Perusahaan</th>
                            <th class="py-3 px-2">Posisi</th>
                            <th class="py-3 px-2">Jurusan</th>
                            <th class="py-3 px-2 text-center">Kuota</th>
                            <th class="py-3 px-2">Tutup</th>
                            <th class="py-3 px-2">Status</th>
                            <th class="py-3 px-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="loker-table" class="divide-y divide-slate-200 text-slate-700 font-medium"></tbody>
                </table>
            </div>
        </section>

    </main>
@endsection

@push('scripts')
    <script>
        const JURUSAN = ['RPL', 'TKJ', 'TKR', 'TBSM', 'DKV', 'AK'];
        const STORAGE_KEY = 'pklbkk.lokers';
        const lokerEditUrl = "{{ route('pklbkk.loker.edit') }}";
        let lokerFilter = 'Semua';

        const seedLokers = [
            { id: 'LKR-01', perusahaan: 'PT Telkom Indonesia', posisi: 'Software Developer', jurusan: 'RPL', kuota: 3, deadline: '2026-10-15', status: 'Aktif' },
            { id: 'LKR-02', perusahaan: 'Nasmoco Karanganyar', posisi: 'Teknisi Mekanik', jurusan: 'TKR', kuota: 5, deadline: '2026-11-01', status: 'Aktif' },
            { id: 'LKR-03', perusahaan: 'Bank Jateng', posisi: 'Teller', jurusan: 'AK', kuota: 2, deadline: '2026-09-30', status: 'Pending' },
            { id: 'LKR-04', perusahaan: 'Indomaret Group', posisi: 'Staff Administrasi', jurusan: 'AK', kuota: 4, deadline: '2026-10-20', status: 'Aktif' },
            { id: 'LKR-05', perusahaan: 'Software House Solo', posisi: 'UI/UX Designer', jurusan: 'DKV', kuota: 2, deadline: '2026-10-05', status: 'Aktif' },
            { id: 'LKR-06', perusahaan: 'PT PLN ULP Karanganyar', posisi: 'Operator Jaringan', jurusan: 'TBSM', kuota: 3, deadline: '2026-09-25', status: 'Tutup' },
            { id: 'LKR-07', perusahaan: 'SOLO.NET', posisi: 'Network Administrator', jurusan: 'TKJ', kuota: 2, deadline: '2026-11-15', status: 'Pending' },
            { id: 'LKR-08', perusahaan: 'Astra Isuzu', posisi: 'Quality Control', jurusan: 'TKR', kuota: 2, deadline: '2026-10-10', status: 'Aktif' },
        ];

        function loadLokers() {
            try {
                const raw = JSON.parse(localStorage.getItem(STORAGE_KEY));
                if (Array.isArray(raw) && raw.length) return raw;
            } catch (e) { /* abaikan */ }
            const seeded = seedLokers;
            localStorage.setItem(STORAGE_KEY, JSON.stringify(seeded));
            return seeded;
        }

        function persistLokers(list) {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
        }

        function statusBadge(status) {
            let tone = ['Aktif', 'Terverifikasi', 'Diterima', 'Terisi', 'Tuntas', 'Hadir'].includes(status) ? 'emerald'
                : ['Tutup', 'Revisi', 'Ditolak', 'Alpa', 'Belum'].includes(status) ? 'red' : 'amber';
            const badgeMap = { emerald: 'bg-emerald-100 text-emerald-700', red: 'bg-red-100 text-red-700', amber: 'bg-amber-100 text-amber-700' };
            const dotMap = { emerald: 'bg-emerald-500', red: 'bg-red-500', amber: 'bg-amber-500' };
            return '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide ' + badgeMap[tone] + '"><span class="w-1.5 h-1.5 rounded-full ' + dotMap[tone] + '"></span>' + status + '</span>';
        }

        function renderChips() {
            const container = document.getElementById('filter-loker');
            container.innerHTML = ['Semua', ...JURUSAN].map(j =>
                '<button type="button" onclick="setLokerFilter(\'' + j + '\')" class="px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors ' +
                (lokerFilter === j
                    ? 'bg-brand-600 border-brand-600 text-white shadow-sm'
                    : 'border-slate-200 bg-white text-slate-600 hover:border-brand-500 hover:text-brand-600') + '">' + j + '</button>'
            ).join('');
        }

        function setLokerFilter(jurusan) {
            lokerFilter = jurusan;
            renderChips();
            renderTable();
        }

        function renderTable() {
            let lokers = loadLokers();
            const rows = lokerFilter === 'Semua' ? lokers : lokers.filter(l => l.jurusan === lokerFilter);

            document.getElementById('loker-count').innerText = lokers.length + ' loker terdaftar';
            document.getElementById('loker-table').innerHTML = rows.map(l => `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="py-3.5 px-2 font-bold text-slate-800">${l.id}</td>
                    <td class="py-3.5 px-2 text-slate-600">${l.perusahaan}</td>
                    <td class="py-3.5 px-2 font-semibold">${l.posisi}</td>
                    <td class="py-3.5 px-2"><span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md text-[10px] font-bold">${l.jurusan}</span></td>
                    <td class="py-3.5 px-2 text-center font-bold">${l.kuota}</td>
                    <td class="py-3.5 px-2 text-slate-500">${l.deadline}</td>
                    <td class="py-3.5 px-2">${statusBadge(l.status)}</td>
                    <td class="py-3.5 px-2">
                        <div class="flex items-center justify-end space-x-1.5">
                            <a href="${lokerEditUrl}?id=${l.id}" title="Edit"
                               class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-brand-600 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-pen text-[10px]"></i>
                            </a>
                            <button onclick="deleteLoker('${l.id}')" title="Hapus"
                                    class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-red-50 text-slate-400 hover:text-red-600 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-trash-can text-[10px]"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('') || '<tr><td colspan="8" class="py-8 text-center text-slate-400">Tidak ada loker untuk jurusan ini.</td></tr>';
        }

        function deleteLoker(id) {
            if (!confirm('Hapus loker ' + id + '?')) return;
            persistLokers(loadLokers().filter(l => l.id !== id));
            renderTable();
        }

        renderChips();
        renderTable();
    </script>
@endpush