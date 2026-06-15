<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewCorrectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'action' => 'required|in:approve,reject',
            'note'   => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Aksi wajib dipilih.',
            'action.in'       => 'Aksi tidak valid.',
        ];
    }
}