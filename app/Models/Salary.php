<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salary extends Model
{
    protected $fillable = [
        'user_id',
        'basic_salary',
        'effective_date',
    ];

    protected $casts = [
        'basic_salary'   => 'decimal:2',
        'effective_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
