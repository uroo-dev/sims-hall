<?php

namespace App\Http\Requests;

use App\Models\Lowongan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LowonganRequest extends FormRequest
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
            'nama_perusahaan' => ['required', 'string', 'max:191'],
            'posisi' => ['required', 'string', 'max:191'],
            'tipe' => ['required', Rule::in([Lowongan::TIPE_PEKERJAAN, Lowongan::TIPE_MAGANG])],
            'jurusan_sesuai' => ['required', 'string', 'max:191'],
            'deskripsi' => ['required', 'string', 'min:20', 'max:5000'],
            'link_daftar' => ['required', 'url', 'max:1000'],
            'deadline' => ['required', 'date', 'after_or_equal:today'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'deskripsi.min' => 'Deskripsi lowongan minimal 20 karakter agar jelas bagi calon pendaftar.',
            'link_daftar.url' => 'Link daftar harus berupa URL yang valid (Google Form / WhatsApp / website resmi).',
            'deadline.after_or_equal' => 'Deadline tidak boleh di masa lalu.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('jurusan_sesuai') && str_contains((string) $this->jurusan_sesuai, ',')) {
            $jurusan = collect(explode(',', (string) $this->jurusan_sesuai))
                ->map(fn ($j) => trim($j))
                ->filter()
                ->unique();

            $this->merge(['jurusan_sesuai' => $jurusan->implode(', ')]);
        }
    }
}
