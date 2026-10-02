<?php

namespace App\Http\Requests;

use App\Models\ProdukUnggulan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ProdukUnggulanRequest extends FormRequest
{
    /**
     * Maksimal jumlah dokumentasi landing page produk unggulan.
     */
    public const MAX_DOKUMENTASI = 4;

    /**
     * Ensure the request is authorized to perform this action.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string', 'max:5000'],
            'dokumentasi' => ['nullable', 'array', 'max:'.self::MAX_DOKUMENTASI],
            'dokumentasi.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'hapus_dokumentasi' => ['nullable', 'array'],
            'hapus_dokumentasi.*' => ['string'],
        ];
    }

    /**
     * Dokumentasi lama yang tidak dicentang untuk dihapus.
     *
     * @return list<string>
     */
    public function dokumentasiTetap(): array
    {
        $hapus = array_map('strval', (array) $this->input('hapus_dokumentasi', []));

        return array_values(array_filter(
            ProdukUnggulan::current()->dokumentasi_list,
            fn (string $path): bool => ! in_array($path, $hapus, true),
        ));
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul landing page wajib diisi.',
            'deskripsi.required' => 'Deskripsi singkat wajib diisi.',
            'dokumentasi.max' => 'Maksimal '.self::MAX_DOKUMENTASI.' dokumentasi yang dapat diunggah.',
            'dokumentasi.*.image' => 'Dokumentasi harus berupa gambar.',
            'dokumentasi.*.max' => 'Ukuran dokumentasi maksimal 1 MB.',
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
            'judul' => 'judul',
            'deskripsi' => 'deskripsi singkat',
            'dokumentasi.*' => 'dokumentasi',
        ];
    }

    /**
     * Pastikan jumlah dokumentasi lama yang dipertahankan + baru tidak melebihi batas.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $total = count($this->dokumentasiTetap()) + $this->jumlahFileBaru();

            if ($total > self::MAX_DOKUMENTASI) {
                $validator->errors()->add('dokumentasi', $this->messages()['dokumentasi.max']);
            }
        });
    }

    /**
     * Jumlah file dokumentasi baru yang diunggah pada request ini.
     */
    public function jumlahFileBaru(): int
    {
        $files = $this->file('dokumentasi');

        if (is_array($files)) {
            return count($files);
        }

        return $files ? 1 : 0;
    }
}
