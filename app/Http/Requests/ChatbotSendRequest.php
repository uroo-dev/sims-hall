<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Validasi request endpoint chatbot POST /chatbot/send.
 *
 * Data history dari frontend TIDAK dipercaya sepenuhnya:
 * role dibatasi hanya user|model (system instruction tidak bisa
 * disuntikkan dari frontend), jumlah pesan maksimal 10, dan
 * panjang tiap pesan maksimal 800 karakter.
 */
class ChatbotSendRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:800'],
            'history' => ['nullable', 'array', 'max:10'],
            'history.*.role' => ['required', 'string', 'in:user,model'],
            // Tanpa batas ini satu request bisa membawa ribuan part dan
            // meng accueilkan ratusan ribu karakter ke API berbayar.
            'history.*.parts' => ['required', 'array', 'max:8'],
            'history.*.parts.*.text' => ['required', 'string', 'max:800'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'message.required' => 'Pesan tidak boleh kosong.',
            'message.string' => 'Pesan harus berupa teks.',
            'message.max' => 'Pesan terlalu panjang. Maksimal 800 karakter.',
            'history.array' => 'Format riwayat percakapan tidak valid.',
            'history.max' => 'Riwayat percakapan terlalu banyak. Maksimal 10 pesan.',
            'history.*.role.in' => 'Peran pesan dalam riwayat tidak valid.',
            'history.*.parts.required' => 'Isi pesan dalam riwayat tidak valid.',
            'history.*.parts.max' => 'Isi pesan dalam riwayat terlalu banyak bagian.',
            'history.*.parts.*.text.max' => 'Pesan dalam riwayat terlalu panjang. Maksimal 800 karakter.',
        ];
    }

    /**
     * Bersihkan input sebelum validasi.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'message' => is_string($this->input('message'))
                ? mb_substr(trim($this->input('message')), 0, 800)
                : $this->input('message'),
        ]);
    }

    /**
     * Respons JSON ramah bila validasi gagal (bukan HTML error page).
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'message' => collect($validator->errors()->all())->first()
                ?? 'Data yang dikirim tidak valid. Silakan periksa kembali pertanyaan Anda.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
