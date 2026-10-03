@extends('Admin.layout.app')

@section('title', 'Profil Pengguna - SIMS SMK N 2 Karanganyar')
@section('page_title', 'Profil Saya')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    {{-- FLASH MESSAGES --}}
    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/90 text-emerald-800 px-5 py-4 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0">
                    <i class="fa-solid fa-circle-check text-base"></i>
                </div>
                <div>
                    <h4 class="font-bold text-emerald-900">Berhasil!</h4>
                    <p class="text-xs text-emerald-700">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50/90 text-rose-800 px-5 py-4 text-sm shadow-sm space-y-2">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 flex-shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-base"></i>
                </div>
                <div>
                    <h4 class="font-bold text-rose-900">Terjadi Kesalahan Pengisian Form</h4>
                    <p class="text-xs text-rose-700">Mohon periksa kembali kolom-kolom berikut:</p>
                </div>
            </div>
            <ul class="list-disc list-inside text-xs text-rose-700 pl-11 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- PROFILE HEADER CARD --}}
    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 relative overflow-hidden">
        {{-- Decorative background gradient accent --}}
        <div class="absolute top-0 right-0 w-96 h-36 bg-gradient-to-bl from-blue-100/50 via-sky-50/30 to-transparent pointer-events-none"></div>

        <div class="flex flex-col sm:flex-row items-center sm:items-start justify-between gap-6 relative z-10">
            <div class="flex flex-col sm:flex-row items-center gap-5 text-center sm:text-left">
                {{-- AVATAR DISPLAY --}}
                <div class="relative group">
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl overflow-hidden bg-gradient-to-tr from-blue-50 to-blue-100 border-4 border-white shadow-md flex-shrink-0 flex items-center justify-center">
                        <img id="header-avatar-preview"
                            src="{{ $user->foto_profil_url }}"
                            alt="{{ $user->name }}"
                            class="w-full h-full object-cover">
                    </div>
                    @if ($user->foto_profil)
                        <form action="{{ route('profile.destroy-photo') }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto profil?');"
                            class="absolute -top-1 -right-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                title="Hapus foto profil"
                                class="w-7 h-7 rounded-full bg-rose-500 text-white flex items-center justify-center text-xs shadow-md hover:bg-rose-600 transition cursor-pointer">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    @endif
                </div>

                <div class="space-y-1.5">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                        <h2 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">{{ $user->name }}</h2>
                        <span class="px-3 py-0.5 rounded-full text-xs font-bold bg-[#0073c6]/10 text-[#0073c6] border border-[#0073c6]/20 capitalize">
                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 font-medium flex items-center justify-center sm:justify-start gap-2">
                        <i class="fa-regular fa-envelope text-gray-400"></i>
                        <span>{{ $user->email }}</span>
                        <span class="text-gray-300">•</span>
                        <i class="fa-regular fa-user text-gray-400"></i>
                        <span>@<span>{{ $user->username }}</span></span>
                    </p>
                    <div class="pt-1 flex flex-wrap items-center justify-center sm:justify-start gap-3 text-[11px] text-gray-500">
                        <span class="inline-flex items-center gap-1.5 text-emerald-600 font-semibold bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Akun Aktif
                        </span>
                        <span class="text-gray-400">
                            Bergabung {{ $user->created_at ? $user->created_at->format('d M Y') : 'Baru' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 self-stretch sm:self-auto justify-center">
                <a href="{{ $user->dashboardRoute() }}"
                    class="px-4 py-2.5 rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-xs font-semibold shadow-xs transition flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-gray-400"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>
    </div>

    {{-- EDIT PROFILE FORM CARD --}}
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 space-y-8">

            {{-- SECTION 1: FOTO PROFIL --}}
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-camera text-[#0073c6]"></i>
                            <span>Foto Profil</span>
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Unggah foto profil resmi Anda (Maksimal 2 MB, format PNG, JPG, JPEG, WEBP).</p>
                    </div>
                </div>

                <div class="mt-4 flex flex-col sm:flex-row items-center gap-6">
                    {{-- Live Avatar Preview Box --}}
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl overflow-hidden bg-gray-50 border-2 border-dashed border-gray-300 flex items-center justify-center flex-shrink-0 shadow-inner">
                        <img id="form-avatar-preview"
                            src="{{ $user->foto_profil_url }}"
                            alt="{{ $user->name }}"
                            class="w-full h-full object-cover">
                    </div>

                    {{-- File Input & Info --}}
                    <div class="flex-1 w-full space-y-2">
                        <label for="foto_profil" class="block text-xs font-semibold text-gray-700">Pilih Foto Baru</label>
                        <input type="file"
                            id="foto_profil"
                            name="foto_profil"
                            accept="image/png,image/jpeg,image/jpg,image/webp"
                            onchange="previewImage(this)"
                            class="block w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#0073c6] hover:file:bg-blue-100 file:cursor-pointer cursor-pointer border border-gray-200 rounded-2xl focus:outline-none focus:border-[#0073c6]">

                        @if ($user->foto_profil)
                            <div class="pt-1 flex items-center gap-2">
                                <label class="inline-flex items-center gap-2 text-xs text-gray-600 cursor-pointer">
                                    <input type="checkbox" name="hapus_foto" value="1" class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                                    <span>Hapus foto saat ini dan gunakan inisial default</span>
                                </label>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- SECTION 2: INFORMASI DASAR AKUN --}}
            <div>
                <div class="pb-3 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-id-card text-[#0073c6]"></i>
                        <span>Informasi Dasar Akun</span>
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Perbarui nama lengkap, username untuk login, serta alamat email aktif Anda.</p>
                </div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Nama Lengkap --}}
                    <div class="space-y-1.5">
                        <label for="name" class="block text-xs font-bold text-gray-700">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                                <i class="fa-regular fa-user text-xs"></i>
                            </span>
                            <input type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                required
                                placeholder="Masukkan nama lengkap"
                                class="w-full pl-9 pr-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-2xl text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0073c6]/20 focus:border-[#0073c6] transition">
                        </div>
                    </div>

                    {{-- Username --}}
                    <div class="space-y-1.5">
                        <label for="username" class="block text-xs font-bold text-gray-700">
                            Username <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                                <i class="fa-solid fa-at text-xs"></i>
                            </span>
                            <input type="text"
                                id="username"
                                name="username"
                                value="{{ old('username', $user->username) }}"
                                required
                                placeholder="contoh: budi_admin"
                                class="w-full pl-9 pr-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-2xl text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0073c6]/20 focus:border-[#0073c6] transition">
                        </div>
                        <p class="text-[11px] text-gray-400">Digunakan untuk masuk ke sistem.</p>
                    </div>

                    {{-- Email --}}
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-bold text-gray-700">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                                <i class="fa-regular fa-envelope text-xs"></i>
                            </span>
                            <input type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                required
                                placeholder="contoh: admin@smk2kra.sch.id"
                                class="w-full pl-9 pr-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-2xl text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0073c6]/20 focus:border-[#0073c6] transition">
                        </div>
                    </div>

                    {{-- Role (Readonly) --}}
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-700">
                            Peran / Hak Akses
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                                <i class="fa-solid fa-shield-halved text-xs"></i>
                            </span>
                            <input type="text"
                                value="{{ ucwords(str_replace('_', ' ', $user->role)) }}"
                                readonly
                                disabled
                                class="w-full pl-9 pr-4 py-2.5 bg-gray-100 border border-gray-200 rounded-2xl text-xs sm:text-sm text-gray-600 cursor-not-allowed">
                        </div>
                        <p class="text-[11px] text-gray-400">Peran akun dikelola langsung oleh Super Admin.</p>
                    </div>
                </div>
            </div>

            {{-- SECTION 3: KEAMANAN & GANTI PASSWORD --}}
            <div>
                <div class="pb-3 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-key text-[#0073c6]"></i>
                        <span>Keamanan & Ganti Password</span>
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Biarkan kolom password kosong jika Anda tidak ingin mengubah password akun Anda saat ini.</p>
                </div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Password Baru --}}
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-bold text-gray-700">
                            Password Baru
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </span>
                            <input type="password"
                                id="password"
                                name="password"
                                autocomplete="new-password"
                                placeholder="Minimal 6 karakter"
                                class="w-full pl-9 pr-10 py-2.5 bg-gray-50/50 border border-gray-200 rounded-2xl text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0073c6]/20 focus:border-[#0073c6] transition">
                            <button type="button" onclick="togglePasswordVisibility('password', this)"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 cursor-pointer">
                                <i class="fa-regular fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Konfirmasi Password Baru --}}
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block text-xs font-bold text-gray-700">
                            Konfirmasi Password Baru
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </span>
                            <input type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                autocomplete="new-password"
                                placeholder="Ulangi password baru"
                                class="w-full pl-9 pr-10 py-2.5 bg-gray-50/50 border border-gray-200 rounded-2xl text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0073c6]/20 focus:border-[#0073c6] transition">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 cursor-pointer">
                                <i class="fa-regular fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-end gap-3">
                <a href="{{ $user->dashboardRoute() }}"
                    class="w-full sm:w-auto px-6 py-2.5 rounded-2xl border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs sm:text-sm font-semibold transition text-center">
                    Batal
                </a>
                <button type="submit"
                    class="w-full sm:w-auto px-7 py-2.5 rounded-2xl bg-[#0073c6] hover:bg-[#0060a8] text-white text-xs sm:text-sm font-bold shadow-md shadow-blue-500/20 active:scale-95 transition flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>

        </div>
    </form>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const headerAvatar = document.getElementById('header-avatar-preview');
                const formAvatar = document.getElementById('form-avatar-preview');
                if (headerAvatar) headerAvatar.src = e.target.result;
                if (formAvatar) formAvatar.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection
