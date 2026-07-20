<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceLocationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:100',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius'    => 'required|numeric|min:10',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'radius.min' => 'Radius minimal 10 meter.',
        ];
    }

    protected function prepareForValidation(): void
{
    $this->merge([
        'is_active' => $this->boolean('is_active'),
    ]);
}
}