@extends('Admin.layout.app')

@section('title', 'Data Users')
@section('content')

    <!-- CARD: DAFTAR USER -->
    <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-100/80">
        
        <!-- Header + Tombol Tambah -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h3 class="font-bold text-gray-800 text-base">Daftar User</h3>
                <p class="text-xs text-gray-500">Semua user yang terdaftar dalam sistem</p>
            </div>
            <button onclick="openAddModal()"
                class="bg-[#0073c6] hover:bg-brand-700 text-white text-xs font-semibold px-5 py-2.5 rounded-lg transition shadow-md flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Tambah User
            </button>
        </div>

        <!-- Search & Filter -->
        <div class="flex flex-col sm:flex-row gap-4 mb-6">
            <div class="relative flex-1 max-w-md">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" id="searchInput" placeholder="Cari user berdasarkan username, nama, email..."
                    onkeyup="filterUsers()"
                    class="w-full border border-gray-300 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:border-brand-500 transition shadow-sm">
            </div>
            <select id="roleFilter" onchange="filterUsers()"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-brand-500 text-gray-700">
                <option value="">Semua Role</option>
                <option value="admin">Admin</option>
                <option value="super_admin">Super Admin</option>
                <option value="super_duper_admin">Super Duper Admin</option>
                <option value="guru">Guru</option>
                <option value="kepala_sekolah">Kepala Sekolah</option>
                <option value="organisasi">Organisasi</option>
                <option value="instansi_luar_terikat">Instansi Luar Terikat</option>
                <option value="instansi_luar">Instansi Luar</option>
                <option value="user">User</option>
                <option value="pelanggan">Pelanggan</option>
            </select>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="userTable">
                <thead>
                    <tr class="border-b-2 border-gray-200 text-gray-700 font-bold text-xs">
                        <th class="py-3 px-2">Username</th>
                        <th class="py-3 px-2">Nama</th>
                        <th class="py-3 px-2">Email</th>
                        <th class="py-3 px-2">Role</th>
                        <th class="py-3 px-2">Dibuat</th>
                        <th class="py-3 px-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs text-gray-600" id="userTableBody">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition user-row" 
                        data-username="{{ strtolower($user->username) }}" 
                        data-name="{{ strtolower($user->name) }}" 
                        data-email="{{ strtolower($user->email) }}"
                        data-role="{{ $user->role }}">
                        <td class="py-3 px-2 font-semibold text-gray-800">{{ $user->username }}</td>
                        <td class="py-3 px-2">{{ $user->name }}</td>
                        <td class="py-3 px-2">{{ $user->email }}</td>
                        <td class="py-3 px-2">
                            @php
                                $badgeColor = match($user->role) {
                                    'admin'                  => 'bg-blue-100 text-blue-700',
                                    'super_admin'            => 'bg-indigo-100 text-indigo-700',
                                    'super_duper_admin'      => 'bg-purple-100 text-purple-700',
                                    'guru'                   => 'bg-cyan-100 text-cyan-700',
                                    'kepala_sekolah'         => 'bg-emerald-100 text-emerald-700',
                                    'organisasi'             => 'bg-amber-100 text-amber-700',
                                    'instansi_luar_terikat'  => 'bg-teal-100 text-teal-700',
                                    'instansi_luar'          => 'bg-orange-100 text-orange-700',
                                    'pelanggan'              => 'bg-pink-100 text-pink-700',
                                    default                  => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="{{ $badgeColor }} text-[10px] font-bold px-2 py-1 rounded">
                                {{ ucwords(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </td>
                        <td class="py-3 px-2">
                            {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->diffForHumans() : '-' }}
                        </td>
                        <td class="py-3 px-2">
                            <div class="flex justify-center items-center gap-1">
                                <!-- Tombol Edit -->
                                <button type="button" onclick="openEditModal({{ $user }})" 
                                    class="w-8 h-8 flex items-center justify-center text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition text-base">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                
                                <!-- Tombol Hapus -->
                                <button type="button" 
                                    onclick="openDeleteUserModal({{ $user->id }}, @js($user->username), '{{ route('datamaster.users.destroy', $user->id) }}')"
                                    class="w-8 h-8 flex items-center justify-center text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition text-base"
                                    title="Hapus User">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="6" class="text-center py-6 text-gray-500">Belum ada data user.</td>
                    </tr>
                    @endforelse
                    <tr id="noResultRow" class="hidden">
                        <td colspan="6" class="text-center py-6 text-gray-500">
                            <i class="fa-solid fa-magnifying-glass mr-2"></i>Tidak ada user yang cocok dengan pencarian.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('modals')
<!-- ==================== MODAL TAMBAH USER ==================== -->
<div id="addUserModal" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200 max-h-[90vh] flex flex-col" id="addUserModalBox">
        <!-- HEADER -->
        <div class="p-5 md:p-6 pb-2 md:pb-3 bg-white flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-brand-600 flex items-center justify-center text-lg border border-blue-100/80 shadow-xs flex-shrink-0">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base md:text-lg tracking-tight">Tambah User Baru</h3>
                    <p class="text-slate-500 text-xs mt-0.5">Daftarkan akun pengguna atau pengelola baru ke sistem</p>
                </div>
            </div>
            <button type="button" onclick="closeAddModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition cursor-pointer" title="Tutup Modal">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        
        <form action="{{ route('datamaster.users.store') }}" method="POST" class="p-5 md:p-6 space-y-4 overflow-y-auto">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" value="{{ old('username') }}" required placeholder="Contoh: guru_teknik"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                    @error('username') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso, S.Kom."
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                    @error('name') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="contoh@smkn2kra.sch.id"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                    @error('email') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Role (Hak Akses) <span class="text-red-500">*</span></label>
                    <select name="role" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Pilih Role --</option>
                        <optgroup label="Admin Sistem">
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="super_duper_admin" {{ old('role') == 'super_duper_admin' ? 'selected' : '' }}>Super Duper Admin</option>
                        </optgroup>
                        <optgroup label="Internal Sekolah">
                            <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="kepala_sekolah" {{ old('role') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                            <option value="bkk" {{ old('role') == 'bkk' ? 'selected' : '' }}>BKK & PKL</option>
                        </optgroup>
                        <optgroup label="Instansi & Mitra">
                            <option value="organisasi" {{ old('role') == 'organisasi' ? 'selected' : '' }}>Organisasi</option>
                            <option value="instansi_luar_terikat" {{ old('role') == 'instansi_luar_terikat' ? 'selected' : '' }}>Instansi Luar Terikat</option>
                            <option value="instansi_luar" {{ old('role') == 'instansi_luar' ? 'selected' : '' }}>Instansi Luar</option>
                        </optgroup>
                        <optgroup label="Lainnya">
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="pelanggan" {{ old('role') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                        </optgroup>
                    </select>
                    @error('role') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeAddModal()" 
                    class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" 
                    class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:scale-95 text-white text-xs md:text-sm font-bold shadow-sm transition flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Simpan User Baru</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL EDIT USER ==================== -->
<div id="editUserModal" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200 max-h-[90vh] flex flex-col" id="editUserModalBox">
        <!-- HEADER -->
        <div class="p-5 md:p-6 pb-2 md:pb-3 bg-white flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg border border-amber-100/80 shadow-xs flex-shrink-0">
                    <i class="fa-regular fa-pen-to-square"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base md:text-lg tracking-tight">Edit Data User</h3>
                    <p class="text-slate-500 text-xs mt-0.5">Perbarui informasi profil atau hak akses akun pengguna</p>
                </div>
            </div>
            <button type="button" onclick="closeEditModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition cursor-pointer" title="Tutup Modal">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        
        <form id="editUserForm" method="POST" class="p-5 md:p-6 space-y-4 overflow-y-auto">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" id="edit_username" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit_name" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="edit_email" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Role (Hak Akses) <span class="text-red-500">*</span></label>
                    <select name="role" id="edit_role" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                        <option value="" disabled>-- Pilih Role --</option>
                        <optgroup label="Admin Sistem">
                            <option value="admin">Admin</option>
                            <option value="super_admin">Super Admin</option>
                            <option value="super_duper_admin">Super Duper Admin</option>
                        </optgroup>
                        <optgroup label="Internal Sekolah">
                            <option value="guru">Guru</option>
                            <option value="kepala_sekolah">Kepala Sekolah</option>
                            <option value="bkk">BKK & PKL</option>
                        </optgroup>
                        <optgroup label="Instansi & Mitra">
                            <option value="organisasi">Organisasi</option>
                            <option value="instansi_luar_terikat">Instansi Luar Terikat</option>
                            <option value="instansi_luar">Instansi Luar</option>
                        </optgroup>
                        <optgroup label="Lainnya">
                            <option value="user">User</option>
                            <option value="pelanggan">Pelanggan</option>
                        </optgroup>
                    </select>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeEditModal()" 
                    class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" 
                    class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 active:scale-95 text-white text-xs md:text-sm font-bold shadow-sm transition flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL HAPUS USER ==================== -->
<div id="modalDeleteUser" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200" id="modalDeleteUserBox">
        <div class="p-6 text-center space-y-4">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-red-50 text-red-600 border border-red-100/80 flex items-center justify-center text-2xl shadow-xs">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-900 text-lg tracking-tight">Konfirmasi Hapus User</h3>
                <p class="text-xs text-slate-500 mt-1">
                    Apakah Anda yakin ingin menghapus akun user:
                </p>
                <div id="delete_username_text" class="font-bold text-slate-800 text-sm mt-2 bg-slate-50 py-2.5 px-3 rounded-xl border border-slate-200 font-mono">
                    -
                </div>
                <p class="text-[11px] text-red-500 mt-2 font-medium">
                    Tindakan ini tidak dapat dibatalkan. Seluruh data terkait akun ini akan terhapus.
                </p>
            </div>

            <form id="formDeleteUser" method="POST" class="pt-2 flex items-center justify-center gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteUserModal()"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 active:scale-95 text-white text-xs md:text-sm font-bold shadow-sm transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <i class="fa-regular fa-trash-can text-xs"></i>
                    <span>Ya, Hapus</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    // ========== MODAL TAMBAH USER ==========
    function openAddModal() {
        const modal = document.getElementById('addUserModal');
        const box = document.getElementById('addUserModalBox');
        if (!modal || !box) return;

        document.body.classList.add('overflow-hidden');
        modal.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
        }, 10);
    }

    function closeAddModal() {
        const modal = document.getElementById('addUserModal');
        const box = document.getElementById('addUserModalBox');
        if (!modal || !box) return;

        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 150);
    }

    // ========== MODAL EDIT USER ==========
    function openEditModal(user) {
        const modal = document.getElementById('editUserModal');
        const box = document.getElementById('editUserModalBox');
        if (!modal || !box) return;

        document.getElementById('edit_username').value = user.username;
        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role').value = user.role;

        const updateRoute = "{{ route('datamaster.users.update', ':id') }}";
        document.getElementById('editUserForm').action = updateRoute.replace(':id', user.id);

        document.body.classList.add('overflow-hidden');
        modal.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
        }, 10);
    }

    function closeEditModal() {
        const modal = document.getElementById('editUserModal');
        const box = document.getElementById('editUserModalBox');
        if (!modal || !box) return;

        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 150);
    }

    // ========== MODAL HAPUS USER ==========
    function openDeleteUserModal(id, username, deleteUrl) {
        const modal = document.getElementById('modalDeleteUser');
        const box = document.getElementById('modalDeleteUserBox');
        const form = document.getElementById('formDeleteUser');
        if (!modal || !box || !form) return;

        form.action = deleteUrl;
        document.getElementById('delete_username_text').innerText = username;

        document.body.classList.add('overflow-hidden');
        modal.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
        }, 10);
    }

    function closeDeleteUserModal() {
        const modal = document.getElementById('modalDeleteUser');
        const box = document.getElementById('modalDeleteUserBox');
        if (!modal || !box) return;

        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 150);
    }

    // ========== FITUR FILTER OTOMATIS ==========
    function filterUsers() {
        const keyword = document.getElementById('searchInput').value.toLowerCase().trim();
        const roleFilter = document.getElementById('roleFilter').value.toLowerCase();
        const rows = document.querySelectorAll('.user-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const username = row.dataset.username || '';
            const name = row.dataset.name || '';
            const email = row.dataset.email || '';
            const role = row.dataset.role || '';

            const matchKeyword = !keyword || 
                username.includes(keyword) || 
                name.includes(keyword) || 
                email.includes(keyword);
            
            const matchRole = !roleFilter || role === roleFilter;

            if (matchKeyword && matchRole) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const noResult = document.getElementById('noResultRow');
        const emptyRow = document.getElementById('emptyRow');
        
        if (rows.length > 0 && visibleCount === 0) {
            noResult.classList.remove('hidden');
        } else {
            noResult.classList.add('hidden');
        }

        if (emptyRow) {
            if (rows.length > 0) {
                emptyRow.classList.add('hidden');
            } else {
                emptyRow.classList.remove('hidden');
            }
        }
    }

    // ========== TUTUP MODAL DENGAN ESCAPE ==========
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddModal();
            closeEditModal();
            closeDeleteUserModal();
        }
    });

    // ========== KLIK AREA LUAR MODAL UNTUK MENUTUP ==========
    ['addUserModal', 'editUserModal', 'modalDeleteUser'].forEach(modalId => {
        const el = document.getElementById(modalId);
        if (el) {
            el.addEventListener('click', function(e) {
                if (e.target === this) {
                    if (modalId === 'addUserModal') closeAddModal();
                    if (modalId === 'editUserModal') closeEditModal();
                    if (modalId === 'modalDeleteUser') closeDeleteUserModal();
                }
            });
        }
    });

    // ========== AUTO OPEN MODAL JIKA ADA ERROR VALIDASI ==========
    @if($errors->any())
        openAddModal();
    @endif
</script>
@endpush