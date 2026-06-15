<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeaveStatusUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $role = auth()->user()->role;
        
        return in_array($role, ['Pimpinan', 'Admin', 'Finance', 'Teknisi']);
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in(['Approved', 'Rejected'])
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
        ];
    }
}