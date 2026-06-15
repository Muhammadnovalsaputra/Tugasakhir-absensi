<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'latitude_out'  => 'required|numeric',
            'longitude_out' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'latitude_out.required'  => 'Data lokasi tidak ditemukan.',
            'longitude_out.required' => 'Data lokasi tidak ditemukan.',
        ];
    }
}