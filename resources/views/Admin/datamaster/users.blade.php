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
                                <form action="{{ route('datamaster.users.destroy', $user->id) }}" method="POST" 
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->username }}?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="w-8 h-8 flex items-center justify-center text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition text-base">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
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

    <!-- ==================== MODAL TAMBAH USER ==================== -->
    <div id="addUserModal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-brand-600"></i>
                    <h3 class="text-lg font-bold text-gray-800">Tambah User Baru</h3>
                </div>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
            
            <form action="{{ route('datamaster.users.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" value="{{ old('username') }}" required placeholder="Masukkan username"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-brand-500 transition shadow-sm">
                        @error('username') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: PT Toyota Motor"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-brand-500 transition shadow-sm">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="contoh@smkn2kra.sch.id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-brand-500 transition shadow-sm">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Role (Akses)</label>
                        <select name="role" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-brand-500 transition shadow-sm text-gray-700">
                            <option value="">Pilih Role</option>
                            <optgroup label="Admin Sistem">
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                <option value="super_duper_admin" {{ old('role') == 'super_duper_admin' ? 'selected' : '' }}>Super Duper Admin</option>
                            </optgroup>
                            <optgroup label="Internal Sekolah">
                                <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                                <option value="kepala_sekolah" {{ old('role') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                            </optgroup>
                            <optgroup label="Instansi">
                                <option value="organisasi" {{ old('role') == 'organisasi' ? 'selected' : '' }}>Organisasi</option>
                                <option value="instansi_luar_terikat" {{ old('role') == 'instansi_luar_terikat' ? 'selected' : '' }}>Instansi Luar Terikat</option>
                                <option value="instansi_luar" {{ old('role') == 'instansi_luar' ? 'selected' : '' }}>Instansi Luar</option>
                            </optgroup>
                            <optgroup label="Lainnya">
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="pelanggan" {{ old('role') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                            </optgroup>
                        </select>
                        @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" onclick="closeAddModal()" 
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold px-4 py-2 rounded-lg transition">Batal</button>
                    <button type="submit" 
                        class="bg-[#0073c6] hover:bg-brand-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition shadow-md flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Tambah User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== MODAL EDIT USER ==================== -->
    <div id="editUserModal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-pen-to-square text-brand-600"></i>
                    <h3 class="text-lg font-bold text-gray-800">Edit User</h3>
                </div>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
            
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" id="edit_username" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-brand-500 transition shadow-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama</label>
                        <input type="text" name="name" id="edit_name" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-brand-500 transition shadow-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="edit_email" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-brand-500 transition shadow-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Role (Akses)</label>
                        <select name="role" id="edit_role" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-brand-500 transition shadow-sm text-gray-700">
                            <option value="">Pilih Role</option>
                            <optgroup label="Admin Sistem">
                                <option value="admin">Admin</option>
                                <option value="super_admin">Super Admin</option>
                                <option value="super_duper_admin">Super Duper Admin</option>
                            </optgroup>
                            <optgroup label="Internal Sekolah">
                                <option value="guru">Guru</option>
                                <option value="kepala_sekolah">Kepala Sekolah</option>
                            </optgroup>
                            <optgroup label="Instansi">
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
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()" 
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold px-4 py-2 rounded-lg transition">Batal</button>
                    <button type="submit" 
                        class="bg-[#0073c6] hover:bg-brand-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition shadow-md flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== JAVASCRIPT ==================== -->
    <script>
        // ========== MODAL TAMBAH ==========
        function openAddModal() {
            document.getElementById('addUserModal').classList.remove('hidden');
            document.getElementById('addUserModal').classList.add('flex');
        }

        function closeAddModal() {
            document.getElementById('addUserModal').classList.add('hidden');
            document.getElementById('addUserModal').classList.remove('flex');
        }

        // ========== MODAL EDIT ==========
        function openEditModal(user) {
            document.getElementById('edit_username').value = user.username;
            document.getElementById('edit_name').value = user.name;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_role').value = user.role;

            const updateRoute = "{{ route('datamaster.users.update', ':id') }}";
            document.getElementById('editUserForm').action = updateRoute.replace(':id', user.id);

            document.getElementById('editUserModal').classList.remove('hidden');
            document.getElementById('editUserModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editUserModal').classList.add('hidden');
            document.getElementById('editUserModal').classList.remove('flex');
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
            }
        });

        // ========== KLIK AREA LUAR MODAL UNTUK MENUTUP ==========
        document.getElementById('addUserModal').addEventListener('click', function(e) {
            if (e.target === this) closeAddModal();
        });

        document.getElementById('editUserModal').addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });

        // ========== AUTO OPEN MODAL JIKA ADA ERROR VALIDASI ==========
        @if($errors->any())
            openAddModal();
        @endif
    </script>
@endsection