<?php

namespace App\Models;

use App\Models\User;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class LeavePermit extends Model
{
    protected $fillable = [
        'user_id',
        'leave_type',
        'start_date',
        'end_date',
        'reason',
        'status'
    ];
    public function user() {
    return $this->belongsTo(User::class);
    }

    public function getDurationDaysAttribute(): int
    {
        return Carbon::parse($this->start_date)
            ->diffInDays(Carbon::parse($this->end_date)) + 1;
    }
}
