<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getWorkDurationAttribute(): ?string
    {
        if (!$this->check_out || !$this->check_in) {
            return null;
        }
        $checkInTime = $this->check_in instanceof \Carbon\Carbon ? $this->check_in->format('H:i:s') : date('H:i:s', strtotime($this->check_in));
        $checkOutTime = $this->check_out instanceof \Carbon\Carbon ? $this->check_out->format('H:i:s') : date('H:i:s', strtotime($this->check_out));
        $checkIn = \Carbon\Carbon::parse($this->date->format('Y-m-d') . ' ' . $checkInTime);
        $checkOut = \Carbon\Carbon::parse($this->date->format('Y-m-d') . ' ' . $checkOutTime);
        $diff = $checkIn->diff($checkOut);
        return $diff->format('%H:%I');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'present' => 'success',
            'late'    => 'warning',
            'absent'  => 'danger',
            default   => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'present' => 'Hadir',
            'late'    => 'Terlambat',
            'absent'  => 'Tidak Hadir',
            default   => '-',
        };
    }
}
