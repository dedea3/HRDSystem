<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalarySlip extends Model
{
    protected $fillable = [
        'user_id',
        'period_month',
        'period_year',
        'basic_salary',
        'total_allowances',
        'total_deductions',
        'net_salary',
        'generated_at',
    ];

    protected $casts = [
        'basic_salary'     => 'decimal:2',
        'total_allowances' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary'       => 'decimal:2',
        'generated_at'     => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPeriodLabelAttribute(): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        return ($months[$this->period_month] ?? $this->period_month) . ' ' . $this->period_year;
    }
}
