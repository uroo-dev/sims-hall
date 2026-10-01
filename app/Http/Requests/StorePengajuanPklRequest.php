<?php

namespace App\Http\Requests;

use App\Models\PenempatanPkl;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi pengajuan PKL baru.
 *
 * Catatan payload DUDI:
 * - `dudi_id`        : ID DUDI yang sudah ada
 * - `dudi_baru[...]` : data DUDI baru (nested)
 *
 * Keduanya tidak boleh diisi bersamaan. Field DUDI baru dikelompokkan dalam
 * satu array karena PHP tidak dapat menyimpan satu key sekaligus sebagai
 * string (nama DUDI) dan sebagai array (detail DUDI).
 */
class StorePengajuanPklRequest extends FormRequest
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
            'dudi_id' => ['nullable', 'integer', Rule::exists('dudis', 'id')],
            'dudi_baru' => ['nullable', 'array', Rule::prohibitedIf($this->filled('dudi_id'))],

            'dudi_baru.nama' => ['required_without:dudi_id', 'string', 'max:191'],
            'dudi_baru.alamat' => ['required_with:dudi_baru.nama', 'string', 'max:1000'],
            'dudi_baru.kota' => ['required_with:dudi_baru.nama', 'string', 'max:100'],
            'dudi_baru.bidang_usaha' => ['required_with:dudi_baru.nama', 'string', 'max:191'],
            'dudi_baru.kontak_person' => ['nullable', 'string', 'max:191'],
            'dudi_baru.no_hp' => ['nullable', 'string', 'max:50'],
            'dudi_baru.kuota_maksimal' => ['nullable', 'integer', 'min:0', 'max:1000'],

            // Multi-select siswa
            'siswa_ids' => ['required', 'array', 'min:1', 'max:50'],
            'siswa_ids.*' => [
                'integer',
                Rule::exists('siswas', 'id'),
                // Siswa yang sudah FIX tidak boleh ditambah lagi
                Rule::unique('penempatan_pkls', 'siswa_id')
                    ->where(fn ($query) => $query->where('status_penempatan', PenempatanPkl::STATUS_FIX)),
            ],

            'guru_id' => ['required', 'integer', Rule::exists('gurus', 'id')],

            'tanggal_surat' => ['required', 'date', 'before_or_equal:today'],
            'tgl_mulai_pkl' => ['required', 'date', 'after_or_equal:today'],
            'tgl_selesai_pkl' => ['required', 'date', 'after:tgl_mulai_pkl'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'dudi_baru.prohibited' => 'Pilih DUDI yang sudah ada ATAU isi DUDI baru, jangan keduanya.',
            'dudi_baru.nama.required_without' => 'Pilih DUDI yang sudah ada, atau isi nama DUDI baru.',
            'dudi_baru.alamat.required_with' => 'Alamat DUDI baru wajib diisi.',
            'dudi_baru.kota.required_with' => 'Kota DUDI baru wajib diisi.',
            'dudi_baru.bidang_usaha.required_with' => 'Bidang usaha DUDI baru wajib diisi.',
            'dudi_baru.kuota_maksimal.min' => 'Kuota maksimal minimal 0.',

            'siswa_ids.required' => 'Pilih minimal satu siswa untuk pengajuan PKL.',
            'siswa_ids.*.unique' => 'Salah satu siswa yang dipilih sudah punya penempatan PKL status FIX.',
            'guru_id.required' => 'Pilih satu guru pembimbing.',
            'tgl_mulai_pkl.after_or_equal' => 'Tanggal mulai PKL tidak boleh di masa lalu.',
            'tgl_selesai_pkl.after' => 'Tanggal selesai PKL harus setelah tanggal mulai.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (is_array($this->siswa_ids)) {
            $this->merge([
                'siswa_ids' => array_values(array_unique(array_map('intval', $this->siswa_ids))),
            ]);
        }

        if (is_array($this->dudi_baru) && isset($this->dudi_baru['nama'])
            && is_string($this->dudi_baru['nama'])) {
            $this->merge([
                'dudi_baru' => array_merge($this->dudi_baru, [
                    'nama' => trim($this->dudi_baru['nama']),
                ]),
            ]);
        }
    }
}
