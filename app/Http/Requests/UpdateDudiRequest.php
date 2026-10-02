<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDudiRequest extends FormRequest
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
            // ACC / batal-ACC tayang di Landing Page oleh BKK
            'tampil_di_landing' => ['nullable', 'boolean'],
            'is_mitra_resmi' => ['nullable', 'boolean'],
            'kuota_maksimal' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'nama_dudi' => ['nullable', 'string', 'max:191'],
            'alamat' => ['nullable', 'string', 'max:1000'],
            'kota' => ['nullable', 'string', 'max:100'],
            'bidang_usaha' => ['nullable', 'string', 'max:191'],
            'kontak_person' => ['nullable', 'string', 'max:191'],
            'no_hp' => ['nullable', 'string', 'max:50'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
            'hapus_logo' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'logo.image' => 'File logo harus berupa gambar.',
            'logo.max' => 'Ukuran file logo maksimal 2MB.',
        ];
    }
}
