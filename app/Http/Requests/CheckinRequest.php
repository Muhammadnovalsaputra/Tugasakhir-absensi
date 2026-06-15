<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image_in'     => 'required',
            'latitude_in'  => 'required|numeric',
            'longitude_in' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'image_in.required'     => 'Foto absen wajib diambil.',
            'latitude_in.required'  => 'Data lokasi tidak ditemukan.',
            'longitude_in.required' => 'Data lokasi tidak ditemukan.',
        ];
    }
}