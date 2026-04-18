<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryComponent extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'amount',
        'is_recurring',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'is_recurring' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'allowance'  => 'Tunjangan',
            'deduction'  => 'Potongan',
            default      => '-',
        };
    }
}
