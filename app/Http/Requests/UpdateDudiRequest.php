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
            'tampil_di_landing' => ['required', 'boolean'],
            'is_mitra_resmi' => ['nullable', 'boolean'],
            'kuota_maksimal' => ['nullable', 'integer', 'min:0', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tampil_di_landing.required' => 'Status tayang di landing page wajib diisi.',
        ];
    }
}
