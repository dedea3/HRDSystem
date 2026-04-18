<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil role dari database
        $adminRole = Role::where('name', 'admin')->first();
        $hrdRole = Role::where('name', 'hrd')->first();
        $staffRole = Role::where('name', 'staff')->first();

        // Buat user Admin
        $admin = User::create([
            'name' => 'Admin System',
            'email' => 'admin@hrd.test',
            'password' => Hash::make('password'),
        ]);
        $admin->roles()->attach($adminRole);

        // Buat user HRD
        $hrd = User::create([
            'name' => 'HRD Manager',
            'email' => 'hrd@hrd.test',
            'password' => Hash::make('password'),
        ]);
        $hrd->roles()->attach($hrdRole);

        // Buat user Staff
        $staff = User::create([
            'name' => 'Staff Karyawan',
            'email' => 'staff@hrd.test',
            'password' => Hash::make('password'),
            'created_at' => now()->subMonths(6),
        ]);
        $staff->roles()->attach($staffRole);

        // Dummy Data for Aesthetic UI
        $users = [$admin, $hrd, $staff];

        foreach ($users as $user) {
            // Salary setup
            $user->salary()->create([
                'basic_salary' => rand(5000000, 15000000),
                'effective_date' => now()->subMonths(6),
            ]);

            $user->salaryComponents()->createMany([
                ['name' => 'Tunjangan Transport', 'type' => 'allowance', 'amount' => 500000, 'is_recurring' => true],
                ['name' => 'Tunjangan Makan', 'type' => 'allowance', 'amount' => 800000, 'is_recurring' => true],
                ['name' => 'BPJS Kesehatan', 'type' => 'deduction', 'amount' => 150000, 'is_recurring' => true],
            ]);

            // Leave Balance
            $user->leaveBalances()->create([
                'year' => date('Y'),
                'total_days' => 12,
                'used_days' => rand(0, 5),
            ]);

            // Fake Attendances for the last 10 days
            for ($i = 1; $i <= 10; $i++) {
                $date = now()->subDays($i);
                if ($date->isWeekday()) {
                    $isLate = rand(1, 10) > 8; // 20% chance late
                    $user->attendances()->create([
                        'date' => $date->format('Y-m-d'),
                        'check_in' => $isLate ? '08:'.rand(35, 59).':00' : '07:'.rand(45, 59).':00',
                        'check_out' => '17:'.rand(0, 30).':00',
                        'status' => $isLate ? 'late' : 'present',
                    ]);
                }
            }

            // Fake Leave History
            $user->leaves()->create([
                'leave_type' => 'annual',
                'start_date' => now()->subDays(20),
                'end_date' => now()->subDays(19),
                'total_days' => 2,
                'reason' => 'Keperluan keluarga',
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now()->subDays(22),
            ]);
        }
    }
}