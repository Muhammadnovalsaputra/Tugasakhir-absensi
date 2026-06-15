<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeavePermitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'leave_type' => 'required|string|in:CutiTahunan,CutiSakit,IzinMendesak',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|min:5|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'leave_type.in' => 'Jenis cuti tidak valid.',
            'start_date.after_or_equal' => 'Tanggal mulai tidak boleh kurang dari hari ini.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'reason.min' => 'Alasan minimal 5 karakter.',
        ];
    }
}