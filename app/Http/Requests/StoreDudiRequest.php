<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDudiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['bkk', 'admin', 'super_admin', 'super_duper_admin'], true);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nama_dudi' => ['required', 'string', 'max:191'],
            'alamat' => ['required', 'string', 'max:1000'],
            'kota' => ['required', 'string', 'max:100'],
            'bidang_usaha' => ['required', 'string', 'max:191'],
            'kontak_person' => ['nullable', 'string', 'max:191'],
            'no_hp' => ['nullable', 'string', 'max:50'],
            'kuota_maksimal' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'is_mitra_resmi' => ['nullable', 'boolean'],
            'tampil_di_landing' => ['nullable', 'boolean'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_dudi.required' => 'Nama DUDI wajib diisi.',
            'alamat.required' => 'Alamat DUDI wajib diisi.',
            'kota.required' => 'Kota DUDI wajib diisi.',
            'bidang_usaha.required' => 'Bidang usaha DUDI wajib diisi.',
            'logo.image' => 'File logo harus berupa gambar.',
            'logo.max' => 'Ukuran file logo maksimal 2MB.',
        ];
    }
}
