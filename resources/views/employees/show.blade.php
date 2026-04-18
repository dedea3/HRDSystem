<x-app-layout>
@section('title', 'Detail Karyawan')

<div class="space-y-6">

    <div class="page-header">
        <div>
            <h2 class="page-title">Detail Karyawan</h2>
            <p class="page-subtitle">Profil & riwayat karyawan</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary btn-sm">Edit</a>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Profile Card --}}
        <div class="card p-6 text-center">
            <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-indigo-600 text-3xl font-bold text-white">
                {{ strtoupper(substr($employee->name,0,1)) }}
            </div>
            <h3 class="text-lg font-bold text-white">{{ $employee->name }}</h3>
            <p class="text-sm text-slate-400">{{ $employee->email }}</p>
            <div class="mt-3 flex flex-wrap justify-center gap-1">
                @foreach($employee->roles as $role)
                <span class="badge-{{ $role->name === 'admin' ? 'danger' : ($role->name === 'hrd' ? 'info' : 'neutral') }}">{{ $role->display_name }}</span>
                @endforeach
            </div>
            <div class="mt-4 border-t border-slate-800 pt-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-400">Bergabung</span>
                    <span class="text-white">{{ $employee->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Sisa Cuti</span>
                    <span class="text-white">{{ $leaveBalance->remaining_days ?? '-' }} hari</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Gaji Pokok</span>
                    <span class="text-white">{{ $salary ? 'Rp ' . number_format($salary->basic_salary,0,',','.') : '-' }}</span>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('salary.manage', $employee) }}" class="btn btn-secondary btn-sm w-full">Kelola Gaji</a>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">

            {{-- Recent Attendance --}}
            <div class="card overflow-hidden">
                <div class="card-header">
                    <h3 class="font-semibold text-white">Absensi Terakhir</h3>
                </div>
                <table class="table">
                    <thead><tr><th>Tanggal</th><th>Check In</th><th>Check Out</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($employee->attendances as $att)
                        <tr>
                            <td>{{ $att->date->format('d M Y') }}</td>
                            <td>{{ $att->check_in }}</td>
                            <td>{{ $att->check_out ?? '-' }}</td>
                            <td><span class="badge-{{ $att->status_badge }}">{{ $att->status_label }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-4 text-center text-slate-500">Belum ada data absensi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Leave History --}}
            <div class="card overflow-hidden">
                <div class="card-header">
                    <h3 class="font-semibold text-white">Riwayat Cuti</h3>
                </div>
                <table class="table">
                    <thead><tr><th>Jenis</th><th>Mulai</th><th>Selesai</th><th>Hari</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($employee->leaves as $leave)
                        <tr>
                            <td>{{ $leave->leave_type_label }}</td>
                            <td>{{ $leave->start_date->format('d M Y') }}</td>
                            <td>{{ $leave->end_date->format('d M Y') }}</td>
                            <td>{{ $leave->total_days }}</td>
                            <td><span class="badge-{{ $leave->status_badge }}">{{ $leave->status_label }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-4 text-center text-slate-500">Belum ada riwayat cuti.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
</x-app-layout>
