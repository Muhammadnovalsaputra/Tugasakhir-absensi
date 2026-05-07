<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSetting extends Model
{
    protected $fillable = [
        'latitude',
        'longitude',
        'radius',
        'start_time',
        'quit_time',
        'work_schedule'
    ];

    protected $casts = [
        'work_schedule' => 'array'
    ];

    public static function getActive(): ?self
    {
        return self::latest()->first();
    }
}
