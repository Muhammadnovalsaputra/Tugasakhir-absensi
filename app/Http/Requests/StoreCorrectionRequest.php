<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCorrectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'claimed_check_in' => 'required|date_format:H:i',
            'proof_photo'      => 'required|image|mimes:jpeg,png,jpg|max:3072',
            'reason_category'  => 'required|string', 
            'reason'           => 'required_if:reason_category,Lainnya|nullable|string|max:500', 
        ];
    }

    public function messages(): array
    {
        return [
            'claimed_check_in.required'    => 'Jam masuk yang diklaim wajib diisi.',
            'claimed_check_in.date_format' => 'Format jam harus HH:MM (contoh: 08:30).',
            'proof_photo.required'         => 'Foto bukti wajib diupload.',
            'proof_photo.image'            => 'File harus berupa gambar.',
            'proof_photo.max'              => 'Ukuran foto maksimal 3MB.',
            'reason_category.required'     => 'Silakan pilih salah satu alasan kendala absen pada dropdown.',
            'reason.required_if'           => 'Karena Anda memilih opsi Lainnya, harap isi penjelasan detail alasan Anda.',
            'reason.max'                   => 'Penjelasan alasan tidak boleh lebih dari 500 karakter.',
        ];
    }
}