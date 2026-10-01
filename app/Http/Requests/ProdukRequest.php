<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProdukRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'jurusanID' => ['nullable', 'integer', Rule::exists('jurusan', 'jurusanID')],
            'dokumentasi' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama produk wajib diisi.',
            'deskripsi.required' => 'Deskripsi produk wajib diisi.',
            'jurusanID.exists' => 'Jurusan yang dipilih tidak tersedia.',
            'dokumentasi.image' => 'Dokumentasi harus berupa gambar.',
            'dokumentasi.max' => 'Ukuran dokumentasi maksimal 1 MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nama' => 'nama produk',
            'deskripsi' => 'deskripsi',
            'jurusanID' => 'jurusan',
            'dokumentasi' => 'dokumentasi',
        ];
    }
}
