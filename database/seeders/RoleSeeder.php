<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Akses penuh ke semua fitur sistem',
            ],
            [
                'name' => 'hrd',
                'display_name' => 'HRD',
                'description' => 'Mengelola karyawan, absensi, cuti, dan penggajian',
            ],
            [
                'name' => 'staff',
                'display_name' => 'Staff',
                'description' => 'Karyawan biasa - absensi, cuti, lihat slip gaji',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}