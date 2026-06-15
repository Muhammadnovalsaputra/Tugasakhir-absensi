<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceCorrection extends Model
{
    protected $fillable = [
        'user_id',
        'attendance_id',
        'date',
        'claimed_check_in',
        'proof_photo',
        'reason',
        'status',
        'reviewed_by',
        'reviewed_at',
        'reviewer_note',
    ];

    protected $casts = [
        'date'        => 'date',
        'reviewed_at' => 'datetime',
    ];

    const STATUS_PENDING  = 'Pending';
    const STATUS_APPROVED = 'Approved';
    const STATUS_REJECTED = 'Rejected';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
 
    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class);
    }
 
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
 
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
 
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
}
