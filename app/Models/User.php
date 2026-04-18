<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }

    public function salary(): HasOne
    {
        return $this->hasOne(Salary::class)->latestOfMany('effective_date');
    }

    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class);
    }

    public function salaryComponents(): HasMany
    {
        return $this->hasMany(SalaryComponent::class);
    }

    public function salarySlips(): HasMany
    {
        return $this->hasMany(SalarySlip::class);
    }

    // ─── Role helpers ─────────────────────────────────────────────
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function getPrimaryRoleAttribute(): ?string
    {
        $role = $this->roles()->first();
        return $role ? $role->name : null;
    }

    public function getPrimaryRoleLabelAttribute(): string
    {
        return match($this->primary_role) {
            'admin' => 'Administrator',
            'hrd'   => 'HRD',
            'staff' => 'Staff',
            default => 'User',
        };
    }

    // ─── Leave balance helper ──────────────────────────────────────
    public function currentLeaveBalance(): ?LeaveBalance
    {
        return $this->leaveBalances()->where('year', now()->year)->first();
    }

    // ─── Today's attendance ────────────────────────────────────────
    public function todayAttendance(): ?Attendance
    {
        return $this->attendances()->whereDate('date', today())->first();
    }
}