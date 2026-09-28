<?php

namespace App\Http\Requests;

use App\Models\PenempatanPkl;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStatusPenempatanRequest extends FormRequest
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
            'status_penempatan' => [
                'required',
                Rule::in([PenempatanPkl::STATUS_FIX, PenempatanPkl::STATUS_DITOLAK]),
            ],
            'catatan' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status_penempatan.in' => 'Status hanya dapat diubah menjadi FIX (diterima) atau ditolak.',
            'catatan.max' => 'Catatan balasan DUDI maksimal 2000 karakter.',
        ];
    }
}
