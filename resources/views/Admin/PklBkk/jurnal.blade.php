@extends('Admin.layout.app')

@section('title', 'Jurnal & Absensi - BKK & PKL | SMK Negeri 2 Karanganyar')

@section('content')
    <main class="flex-1 h-full overflow-y-auto p-6 lg:p-8 space-y-6">

        @include('Admin.PklBkk.partials.header', ['pklPage' => 'Jurnal & Absensi'])

        <section class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                <div class="flex items-center space-x-2">
                    <h2 class="text-sm font-extrabold text-brand-600 uppercase tracking-wider">Rekap Jurnal &amp; Absensi Siswa</h2>
                    <span class="text-[10px] font-semibold text-slate-400">Laporan harian dari siswa PKL</span>
                </div>
                <div id="filter-jurnal" class="flex flex-wrap items-center gap-2"></div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs md:text-sm">
                    <thead>
                        <tr class="border-b-2 border-slate-800 text-slate-800 font-extrabold">
                            <th class="py-3 px-2">ID</th>
                            <th class="py-3 px-2">Nama Siswa</th>
                            <th class="py-3 px-2">Jurusan</th>
                            <th class="py-3 px-2">Tempat PKL</th>
                            <th class="py-3 px-2">Kehadiran</th>
                            <th class="py-3 px-2 text-center">Progress</th>
                            <th class="py-3 px-2">Verifikasi</th>
                            <th class="py-3 px-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="jurnal-table" class="divide-y divide-slate-200 text-slate-700 font-medium"></tbody>
                </table>
            </div>
        </section>

    </main>
@endsection

@push('scripts')
    <script>
        const JURUSAN = ['RPL', 'TKJ', 'TKR', 'TBSM', 'DKV', 'AK'];
        const STORAGE_KEY = 'pklbkk.jurnals';
        let jurnalFilter = 'Semua';

        const seedJurnals = [
            { id: 'JR-001', nama: 'Andi Pratama', jurusan: 'RPL', tempat: 'PT Telkom Indonesia', kehadiran: 'Hadir', progress: '38/90', status: 'Terverifikasi' },
            { id: 'JR-002', nama: 'Siti Rahmawati', jurusan: 'TKR', tempat: 'Nasmoco Karanganyar', kehadiran: 'Hadir', progress: '41/90', status: 'Terverifikasi' },
            { id: 'JR-003', nama: 'Bagas Saputra', jurusan: 'TKJ', tempat: 'PT PLN ULP Karanganyar', kehadiran: 'Sakit', progress: '22/90', status: 'Pending' },
            { id: 'JR-004', nama: 'Rani Kusuma', jurusan: 'DKV', tempat: 'Software House Solo', kehadiran: 'Hadir', progress: '45/90', status: 'Terverifikasi' },
            { id: 'JR-005', nama: 'Dimas Anggara', jurusan: 'TBSM', tempat: 'Pindad Enjiniring', kehadiran: 'Izin', progress: '30/90', status: 'Pending' },
            { id: 'JR-006', nama: 'Fitri Handayani', jurusan: 'AK', tempat: 'Hotel Brothers', kehadiran: 'Hadir', progress: '12/90', status: 'Pending' },
            { id: 'JR-007', nama: 'Yusuf Maulana', jurusan: 'RPL', tempat: 'PT Telkom Indonesia', kehadiran: 'Hadir', progress: '50/90', status: 'Terverifikasi' },
            { id: 'JR-008', nama: 'Dewi Lestari', jurusan: 'TBSM', tempat: 'PT Pertamina Patra Niaga', kehadiran: 'Alpa', progress: '5/90', status: 'Revisi' },
        ];

        function loadJurnals() {
            try {
                const raw = JSON.parse(localStorage.getItem(STORAGE_KEY));
                if (Array.isArray(raw) && raw.length) return raw;
            } catch (e) { /* abaikan */ }
            localStorage.setItem(STORAGE_KEY, JSON.stringify(seedJurnals));
            return seedJurnals;
        }

        function statusBadge(status) {
            let tone = ['Aktif', 'Terverifikasi', 'Diterima', 'Terisi', 'Tuntas', 'Hadir'].includes(status) ? 'emerald'
                : ['Tutup', 'Revisi', 'Ditolak', 'Alpa', 'Belum'].includes(status) ? 'red' : 'amber';
            const badgeMap = { emerald: 'bg-emerald-100 text-emerald-700', red: 'bg-red-100 text-red-700', amber: 'bg-amber-100 text-amber-700' };
            const dotMap = { emerald: 'bg-emerald-500', red: 'bg-red-500', amber: 'bg-amber-500' };
            return '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide ' + badgeMap[tone] + '"><span class="w-1.5 h-1.5 rounded-full ' + dotMap[tone] + '"></span>' + status + '</span>';
        }

        function renderChips() {
            const container = document.getElementById('filter-jurnal');
            container.innerHTML = ['Semua', ...JURUSAN].map(j =>
                '<button type="button" onclick="setJurnalFilter(\'' + j + '\')" class="px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors ' +
                (jurnalFilter === j
                    ? 'bg-brand-600 border-brand-600 text-white shadow-sm'
                    : 'border-slate-200 bg-white text-slate-600 hover:border-brand-500 hover:text-brand-600') + '">' + j + '</button>'
            ).join('');
        }

        function setJurnalFilter(jurusan) {
            jurnalFilter = jurusan;
            renderChips();
            renderTable();
        }

        function renderTable() {
            const list = loadJurnals();
            const rows = jurnalFilter === 'Semua' ? list : list.filter(j => j.jurusan === jurnalFilter);

            document.getElementById('jurnal-table').innerHTML = rows.map(j => `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="py-3.5 px-2 font-bold text-slate-800">${j.id}</td>
                    <td class="py-3.5 px-2 font-semibold">${j.nama}</td>
                    <td class="py-3.5 px-2"><span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md text-[10px] font-bold">${j.jurusan}</span></td>
                    <td class="py-3.5 px-2 text-slate-600">${j.tempat}</td>
                    <td class="py-3.5 px-2">${statusBadge(j.kehadiran)}</td>
                    <td class="py-3.5 px-2">
                        <div class="flex items-center justify-center space-x-2">
                            <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full bg-brand-600" style="width:${parseInt(j.progress.split('/')[0], 10)}%"></div>
                            </div>
                            <span class="text-[10px] font-bold text-slate-500">${j.progress}</span>
                        </div>
                    </td>
                    <td class="py-3.5 px-2">${statusBadge(j.status)}</td>
                    <td class="py-3.5 px-2">
                        <div class="flex items-center justify-end">
                            ${j.status === 'Pending' || j.status === 'Revisi'
                                ? '<button onclick="verifyJurnal(\'' + j.id + '\')" class="px-2.5 py-1 bg-brand-600 hover:bg-brand-700 text-white rounded-full text-[10px] font-bold transition-colors">Verifikasi</button>'
                                : '<span class="text-[10px] font-bold text-emerald-600"><i class="fa-solid fa-circle-check mr-1"></i>Sudah</span>'}
                        </div>
                    </td>
                </tr>
            `).join('') || '<tr><td colspan="8" class="py-8 text-center text-slate-400">Tidak ada jurnal untuk jurusan ini.</td></tr>';
        }

        function verifyJurnal(id) {
            let list = loadJurnals();
            const item = list.find(j => j.id === id);
            if (!item) return;
            item.status = 'Terverifikasi';
            localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
            renderTable();
        }

        renderChips();
        renderTable();
    </script>
@endpush