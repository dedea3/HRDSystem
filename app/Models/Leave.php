<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    protected $fillable = [
        'user_id',
        'leave_type',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getLeaveTypeLabelAttribute(): string
    {
        return match($this->leave_type) {
            'annual'    => 'Cuti Tahunan',
            'sick'      => 'Cuti Sakit',
            'emergency' => 'Cuti Darurat',
            default     => '-',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'approved' => 'success',
            'pending'  => 'warning',
            'rejected' => 'danger',
            default    => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'approved' => 'Disetujui',
            'pending'  => 'Menunggu',
            'rejected' => 'Ditolak',
            default    => '-',
        };
    }
}
