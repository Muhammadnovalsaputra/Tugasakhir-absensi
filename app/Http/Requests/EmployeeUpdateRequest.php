<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id');
        
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $userId,
            'role'     => 'required|string|in:Finance,KetuaTeknisi,Teknisi,Sekretaris,Marketing,Admin,Pimpinan',
            'photo'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'role.in' => 'Role yang dipilih tidak valid. Pilih salah satu dari: Finance, KetuaTeknisi, Teknisi, Sekretaris, Marketing, Admin, Pimpinan',
        ];
    }
}