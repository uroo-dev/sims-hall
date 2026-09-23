@extends('Admin.layout.app')

@section('title', 'Form Lowongan Kerja - BKK & PKL | SMK Negeri 2 Karanganyar')

@section('content')
    <main class="flex-1 h-full overflow-y-auto p-6 lg:p-8 space-y-6">

        @include('Admin.PklBkk.partials.header', ['pklPage' => 'Form Lowongan Kerja'])

        <section class="max-w-3xl bg-white rounded-2xl p-6 md:p-8 figma-card-shadow border border-slate-100">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-100">
                <div>
                    <h2 id="form-title" class="text-sm font-extrabold text-brand-600 uppercase tracking-wider">Tambah Lowongan Kerja</h2>
                    <p class="text-[11px] text-slate-400 mt-1 font-medium">Isi data lowongan dari mitra DUDI.</p>
                </div>
                <a href="{{ route('pklbkk.loker') }}" class="px-3.5 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-full text-[11px] font-semibold flex items-center space-x-1.5 transition-colors">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Kembali</span>
                </a>
            </div>

            <form id="loker-form" onsubmit="return saveLoker(event)" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="in-perusahaan" class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Perusahaan <span class="text-red-500">*</span></label>
                        <input id="in-perusahaan" type="text" placeholder="PT Telkom Indonesia" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15">
                    </div>
                    <div>
                        <label for="in-posisi" class="block text-xs font-semibold text-slate-600 mb-1.5">Posisi / Jabatan <span class="text-red-500">*</span></label>
                        <input id="in-posisi" type="text" placeholder="Software Developer" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15">
                    </div>
                    <div>
                        <label for="in-jurusan" class="block text-xs font-semibold text-slate-600 mb-1.5">Jurusan</label>
                        <select id="in-jurusan" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:bg-white"></select>
                    </div>
                    <div>
                        <label for="in-kuota" class="block text-xs font-semibold text-slate-600 mb-1.5">Kuota</label>
                        <input id="in-kuota" type="number" min="0" placeholder="3" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15">
                    </div>
                    <div>
                        <label for="in-deadline" class="block text-xs font-semibold text-slate-600 mb-1.5">Batas Pendaftaran</label>
                        <input id="in-deadline" type="date" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:bg-white">
                    </div>
                    <div>
                        <label for="in-status" class="block text-xs font-semibold text-slate-600 mb-1.5">Status</label>
                        <select id="in-status" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:bg-white">
                            <option>Aktif</option>
                            <option>Pending</option>
                            <option>Tutup</option>
                        </select>
                    </div>
                </div>

                <div id="form-message" class="hidden rounded-xl px-4 py-3 text-xs font-semibold"></div>

                <div class="flex space-x-3 pt-2">
                    <a href="{{ route('pklbkk.loker') }}" class="flex-1 py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-full text-sm transition-colors text-center">Batal</a>
                    <button type="submit" class="flex-1 py-2.5 px-4 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-full text-sm shadow-md transition-colors">Simpan Lowongan</button>
                </div>
            </form>
        </section>

    </main>
@endsection

@push('scripts')
    <script>
        const JURUSAN = ['RPL', 'TKJ', 'TKR', 'TBSM', 'DKV', 'AK'];
        const STORAGE_KEY = 'pklbkk.lokers';
        const editId = new URLSearchParams(window.location.search).get('id') || null;

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
            localStorage.setItem(STORAGE_KEY, JSON.stringify(seedLokers));
            return seedLokers;
        }

        function nextId(list) {
            const max = list.reduce((acc, l) => {
                const num = parseInt(l.id.split('-')[1], 10);
                return num > acc ? num : acc;
            }, 0);
            return 'LKR-' + String(max + 1).padStart(2, '0');
        }

        function fillJurusan(selected) {
            document.getElementById('in-jurusan').innerHTML = JURUSAN.map(j =>
                '<option' + (j === selected ? ' selected' : '') + '>' + j + '</option>'
            ).join('');
        }

        function init() {
            const list = loadLokers();
            const existing = editId ? list.find(l => l.id === editId) : null;

            document.getElementById('form-title').innerText = existing ? 'Edit Lowongan Kerja' : 'Tambah Lowongan Kerja';

            if (existing) {
                document.getElementById('in-perusahaan').value = existing.perusahaan || '';
                document.getElementById('in-posisi').value = existing.posisi || '';
                document.getElementById('in-kuota').value = existing.kuota ?? '';
                document.getElementById('in-deadline').value = existing.deadline || '';
                document.getElementById('in-status').value = existing.status || 'Pending';
                fillJurusan(existing.jurusan);
            } else {
                document.getElementById('in-deadline').value = new Date(Date.now() + 30 * 86400000).toISOString().split('T')[0];
                fillJurusan(JURUSAN[0]);
            }
        }

        function showMessage(type, text) {
            const el = document.getElementById('form-message');
            el.classList.remove('hidden');
            el.className = 'rounded-xl px-4 py-3 text-xs font-semibold ' +
                (type === 'error' ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600');
            el.innerText = text;
        }

        function saveLoker(e) {
            e.preventDefault();
            const perusahaan = document.getElementById('in-perusahaan').value.trim();
            const posisi = document.getElementById('in-posisi').value.trim();

            if (!perusahaan || !posisi) {
                showMessage('error', 'Nama perusahaan dan posisi wajib diisi.');
                return false;
            }

            let list = loadLokers();
            const payload = {
                id: editId || nextId(list),
                perusahaan: perusahaan,
                posisi: posisi,
                jurusan: document.getElementById('in-jurusan').value,
                kuota: Math.max(0, parseInt(document.getElementById('in-kuota').value, 10) || 0),
                deadline: document.getElementById('in-deadline').value,
                status: document.getElementById('in-status').value,
            };

            const idx = list.findIndex(l => l.id === payload.id);
            if (idx >= 0) list[idx] = payload;
            else list.unshift(payload);

            localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
            showMessage('success', (editId ? 'Lowongan ' + editId + ' berhasil diperbarui.' : 'Lowongan baru berhasil ditambahkan.') + ' Mengalihkan ke daftar...');
            setTimeout(() => { window.location.href = "{{ route('pklbkk.loker') }}"; }, 900);
            return false;
        }

        init();
    </script>
@endpush