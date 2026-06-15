<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'latitude'        => 'required|numeric',
            'longitude'       => 'required|numeric',
            'radius'          => 'required|numeric|min:10',
            'start_time'      => 'required|date_format:H:i',
            'quit_time'       => 'required|date_format:H:i|after:start_time',
            'work_schedule'   => 'required|array|min:1',
            'work_schedule.*' => 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
        ];
    }

    public function messages(): array
    {
        return [
            'quit_time.after' => 'Waktu selesai harus setelah waktu mulai.',
            'radius.min'      => 'Radius minimal 10 meter.',
            'work_schedule.min' => 'Pilih minimal 1 hari kerja.',
        ];
    }
}