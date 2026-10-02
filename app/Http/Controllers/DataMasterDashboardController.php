<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DataMasterDashboardController extends Controller
{
    // 1. Dashboard Utama Data Master
    public function index()
    {
        // Ambil data sekolah (hanya 1 baris)
        $sekolah = Sekolah::first() ?? new Sekolah;

        $totalUsers = User::count();
        $totalSekolah = Sekolah::count();
        $totalGuru = Guru::count();
        $totalSiswa = Siswa::count();

        return view('Admin.datamaster.index', compact('sekolah', 'totalUsers', 'totalSekolah', 'totalGuru', 'totalSiswa'));
    }

    // ==========================================
    // BAGIAN DATA SEKOLAH (DATA MASTER)
    // ==========================================
    public function editSekolah()
    {
        // Ambil data sekolah pertama, jika kosong buat instance baru
        $sekolah = Sekolah::first() ?? new Sekolah;

        return view('Admin.datamaster.data_master', compact('sekolah'));
    }

    public function updateSekolah(Request $request)
    {
        $request->validate([
            'profil_judul' => 'required|string|max:255',
            'profil_deskripsi' => 'required|string',
            'profil_dokumentasi' => 'nullable|image|max:2048',
            'judul' => 'required|string|max:255',
            'dokumentasi' => 'nullable|image|max:2048',
            'sejarah' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi' => 'required|string',
            'sambutan_kepsek' => 'nullable|string',
            'nama_kepsek' => 'nullable|string|max:255',
            'foto_kepsek' => 'nullable|image|max:2048',
            'yel_yel' => 'nullable|string',
        ]);

        $sekolah = Sekolah::first() ?? new Sekolah;

        // Ambil semua data kecuali file
        $data = $request->except([
            '_token', '_method',
            'profil_dokumentasi', 'dokumentasi', 'foto_kepsek',
        ]);

        // Helper function untuk upload gambar
        $uploadImage = function ($fileInput, $oldFileName, $prefix) use ($request) {
            if ($request->hasFile($fileInput)) {
                // Hapus file lama jika ada
                if ($oldFileName && file_exists(public_path('assets/'.$oldFileName))) {
                    unlink(public_path('assets/'.$oldFileName));
                }
                $file = $request->file($fileInput);
                $filename = $prefix.'_'.time().'_'.preg_replace('/\s+/', '_', $file->getClientOriginalName());
                $file->move(public_path('assets'), $filename);

                return $filename;
            }

            return null; // Return null jika tidak ada file baru
        };

        // Upload dokumentasi profil sekolah
        $newProfilDok = $uploadImage('profil_dokumentasi', $sekolah->profil_dokumentasi, 'profil');
        if ($newProfilDok) {
            $data['profil_dokumentasi'] = $newProfilDok;
        }

        // Upload gambar sejarah
        $newSejarahDok = $uploadImage('dokumentasi', $sekolah->dokumentasi, 'sejarah');
        if ($newSejarahDok) {
            $data['dokumentasi'] = $newSejarahDok;
        }

        // Upload foto kepala sekolah
        $newFotoKepsek = $uploadImage('foto_kepsek', $sekolah->foto_kepsek, 'kepsek');
        if ($newFotoKepsek) {
            $data['foto_kepsek'] = $newFotoKepsek;
        }

        $sekolah->fill($data)->save();

        return redirect()->back()->with('success', 'Data Master Sekolah berhasil diperbarui!');
    }

    // ==========================================
    // BAGIAN USERS (CRUD)
    // ==========================================
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        return view('Admin.datamaster.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,admin_aula,admin_master,admin_kesiswaan,admin_produk,admin_produk_unggulan,admin_ppdb,admin_pklbkk,admin_sekolah,super_admin,super_duper_admin,user,guru,kepala_sekolah,organisasi,instansi_luar_terikat,instansi_luar,pelanggan,bkk',
        ]);

        User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make('password123'),
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan!');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,'.$id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|in:admin,admin_aula,admin_master,admin_kesiswaan,admin_produk,admin_produk_unggulan,admin_ppdb,admin_pklbkk,admin_sekolah,super_admin,super_duper_admin,user,guru,kepala_sekolah,organisasi,instansi_luar_terikat,instansi_luar,pelanggan,bkk',
        ]);

        $user->update([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'User berhasil diperbarui!');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus!');
    }

    // ==========================================
    // BAGIAN DATA GURU (CRUD)
    // ==========================================
    public function guru()
    {
        $gurus = Guru::orderBy('nama', 'asc')->get();
        $jurusans = Jurusan::pluck('nama')->all();
        if (empty($jurusans)) {
            $jurusans = [
                'Rekayasa Perangkat Lunak (RPL)',
                'Teknik Komputer dan Jaringan (TKJ)',
                'Teknik Pemesinan (TP)',
                'Teknik Kendaraan Ringan Otomotif (TKRO)',
                'Teknik Pembuatan Kain (TPK)',
            ];
        }

        return view('Admin.datamaster.guru', compact('gurus', 'jurusans'));
    }

    public function storeGuru(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50|unique:gurus,nip',
            'jurusan' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
        ], [
            'nama.required' => 'Nama guru wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar untuk guru lain.',
            'jurusan.required' => 'Jurusan wajib dipilih/diisi.',
        ]);

        Guru::create([
            'nama' => $request->nama,
            'nip' => $request->nip ?: null,
            'jurusan' => $request->jurusan,
            'no_hp' => $request->no_hp ?: null,
        ]);

        return redirect()->back()->with('success', 'Data Guru berhasil ditambahkan!');
    }

    public function updateGuru(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50|unique:gurus,nip,'.$id,
            'jurusan' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
        ], [
            'nama.required' => 'Nama guru wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar untuk guru lain.',
            'jurusan.required' => 'Jurusan wajib dipilih/diisi.',
        ]);

        $guru->update([
            'nama' => $request->nama,
            'nip' => $request->nip ?: null,
            'jurusan' => $request->jurusan,
            'no_hp' => $request->no_hp ?: null,
        ]);

        return redirect()->back()->with('success', 'Data Guru berhasil diperbarui!');
    }

    public function destroyGuru($id)
    {
        $guru = Guru::findOrFail($id);
        $guru->delete();

        return redirect()->back()->with('success', 'Data Guru berhasil dihapus!');
    }

    // ==========================================
    // BAGIAN DATA SISWA (CRUD)
    // ==========================================
    public function siswa()
    {
        $siswas = Siswa::orderBy('nama', 'asc')->get();
        $jurusans = Jurusan::pluck('nama')->all();
        if (empty($jurusans)) {
            $jurusans = [
                'Rekayasa Perangkat Lunak (RPL)',
                'Teknik Komputer dan Jaringan (TKJ)',
                'Teknik Pemesinan (TP)',
                'Teknik Kendaraan Ringan Otomotif (TKRO)',
                'Teknik Pembuatan Kain (TPK)',
            ];
        }
        $kelas = Siswa::select('kelas')->distinct()->pluck('kelas')->all();

        return view('Admin.datamaster.siswa', compact('siswas', 'jurusans', 'kelas'));
    }

    public function storeSiswa(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|max:50|unique:siswas,nis',
            'kelas' => 'required|string|max:50',
            'jurusan' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
        ], [
            'nama.required' => 'Nama siswa wajib diisi.',
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah terdaftar untuk siswa lain.',
            'kelas.required' => 'Kelas wajib diisi.',
            'jurusan.required' => 'Jurusan wajib dipilih/diisi.',
        ]);

        Siswa::create([
            'nama' => $request->nama,
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'no_hp' => $request->no_hp ?: null,
        ]);

        return redirect()->back()->with('success', 'Data Siswa berhasil ditambahkan!');
    }

    public function updateSiswa(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|max:50|unique:siswas,nis,'.$id,
            'kelas' => 'required|string|max:50',
            'jurusan' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
        ], [
            'nama.required' => 'Nama siswa wajib diisi.',
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah terdaftar untuk siswa lain.',
            'kelas.required' => 'Kelas wajib diisi.',
            'jurusan.required' => 'Jurusan wajib dipilih/diisi.',
        ]);

        $siswa->update([
            'nama' => $request->nama,
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'no_hp' => $request->no_hp ?: null,
        ]);

        return redirect()->back()->with('success', 'Data Siswa berhasil diperbarui!');
    }

    public function destroySiswa($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->back()->with('success', 'Data Siswa berhasil dihapus!');
    }
}
