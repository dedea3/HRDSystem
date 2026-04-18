<x-app-layout>
@section('title', 'Dashboard')

@php $user = auth()->user(); @endphp

@if($user->hasAnyRole(['admin','hrd']))
{{-- ══════════ ADMIN / HRD DASHBOARD ══════════ --}}
<div class="space-y-6">

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <div class="stat-card group">
            <div class="stat-icon bg-indigo-600 shadow-indigo-900/50">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white">{{ $totalEmployees }}</p>
                <p class="text-xs text-slate-400">Total Karyawan</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-emerald-600 shadow-emerald-900/50">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white">{{ $todayAttendances }}</p>
                <p class="text-xs text-slate-400">Hadir Hari Ini</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-amber-500 shadow-amber-900/50">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white">{{ $pendingLeaves }}</p>
                <p class="text-xs text-slate-400">Cuti Menunggu Approval</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-violet-600 shadow-violet-900/50">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white">{{ $thisMonthSlips }}</p>
                <p class="text-xs text-slate-400">Slip Gaji Bulan Ini</p>
            </div>
        </div>

    </div>

    {{-- Charts & Tables Row --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        {{-- Attendance Chart (last 7 days) --}}
        <div class="xl:col-span-2 card">
            <div class="card-header">
                <div>
                    <h3 class="font-semibold text-white">Rekap Absensi 7 Hari Terakhir</h3>
                    <p class="text-xs text-slate-400">Hadir, Terlambat, Tidak Hadir</p>
                </div>
            </div>
            <div class="card-body">
                <div class="flex items-end gap-2 h-40">
                    @foreach($attendanceChart as $day)
                    @php $total = max(1, $day['present'] + $day['late'] + $day['absent']); @endphp
                    <div class="flex flex-1 flex-col items-center gap-1">
                        <div class="flex w-full flex-col-reverse gap-0.5 rounded-lg overflow-hidden"
                             style="height: 100px;">
                            @if($day['absent'] > 0)
                            <div class="bg-rose-500/70 w-full transition-all"
                                 style="height: {{ ($day['absent']/$total)*100 }}%"
                                 title="Tidak Hadir: {{ $day['absent'] }}"></div>
                            @endif
                            @if($day['late'] > 0)
                            <div class="bg-amber-400/70 w-full"
                                 style="height: {{ ($day['late']/$total)*100 }}%"
                                 title="Terlambat: {{ $day['late'] }}"></div>
                            @endif
                            @if($day['present'] > 0)
                            <div class="bg-emerald-500/70 w-full"
                                 style="height: {{ ($day['present']/$total)*100 }}%"
                                 title="Hadir: {{ $day['present'] }}"></div>
                            @endif
                            @if($day['present'] == 0 && $day['late'] == 0 && $day['absent'] == 0)
                            <div class="bg-slate-700 w-full" style="height: 10%"></div>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500">{{ $day['date'] }}</p>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3 flex gap-4 text-xs text-slate-400">
                    <span class="flex items-center gap-1"><span class="inline-block h-2.5 w-2.5 rounded-sm bg-emerald-500/70"></span> Hadir</span>
                    <span class="flex items-center gap-1"><span class="inline-block h-2.5 w-2.5 rounded-sm bg-amber-400/70"></span> Terlambat</span>
                    <span class="flex items-center gap-1"><span class="inline-block h-2.5 w-2.5 rounded-sm bg-rose-500/70"></span> Tidak Hadir</span>
                </div>
            </div>
        </div>

        {{-- Pending Leaves --}}
        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-white">Cuti Menunggu Approval</h3>
                <a href="{{ route('leave.index') }}" class="text-xs text-indigo-400 hover:text-indigo-300">Lihat Semua →</a>
            </div>
            <div class="divide-y divide-slate-800">
                @forelse($pendingLeaveList as $leave)
                <div class="flex items-start gap-3 px-4 py-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-900/50 text-xs font-bold text-amber-300">
                        {{ strtoupper(substr($leave->user->name,0,1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-white">{{ $leave->user->name }}</p>
                        <p class="text-xs text-slate-400">{{ $leave->leave_type_label }} · {{ $leave->total_days }} hari</p>
                        <p class="text-xs text-slate-500">{{ $leave->start_date->format('d M') }} – {{ $leave->end_date->format('d M Y') }}</p>
                    </div>
                    <form method="POST" action="{{ route('leave.approve', $leave) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="text-xs text-emerald-400 hover:text-emerald-300 font-medium">✓</button>
                    </form>
                </div>
                @empty
                <div class="px-4 py-8 text-center text-sm text-slate-500">Tidak ada cuti pending.</div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Today's Attendance --}}
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="font-semibold text-white">Absensi Hari Ini</h3>
                <p class="text-xs text-slate-400">{{ now()->format('l, d F Y') }}</p>
            </div>
            <a href="{{ route('attendance.index') }}" class="btn btn-secondary btn-sm">Kelola Absensi</a>
        </div>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Lama Kerja</th>
                        <th>Status</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentAttendances as $att)
                    <tr>
                        <td class="font-medium text-white">{{ $att->user->name }}</td>
                        <td>{{ $att->check_in }}</td>
                        <td>{{ $att->check_out ?? '-' }}</td>
                        <td>{{ $att->work_duration ?? '-' }}</td>
                        <td>
                            <span class="badge-{{ $att->status_badge }}">{{ $att->status_label }}</span>
                        </td>
                        <td class="text-slate-400 text-xs max-w-xs truncate">{{ $att->notes ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-8 text-center text-slate-500">Belum ada absensi hari ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@else
{{-- ══════════ STAFF DASHBOARD ══════════ --}}
<div class="space-y-6">

    {{-- Welcome card --}}
    <div class="relative overflow-hidden rounded-2xl border border-indigo-800/50 bg-gradient-to-br from-indigo-900/50 to-violet-900/40 p-6">
        <div class="relative z-10">
            <p class="text-sm text-indigo-300">Selamat datang kembali,</p>
            <h2 class="mt-1 text-2xl font-bold text-white">{{ $user->name }}</h2>
            <p class="mt-1 text-sm text-slate-400">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
        </div>
        <div class="absolute right-0 top-0 -mr-8 -mt-8 h-40 w-40 rounded-full bg-indigo-600/20 blur-2xl"></div>
        <div class="absolute bottom-0 right-16 -mb-8 h-32 w-32 rounded-full bg-violet-600/20 blur-2xl"></div>
    </div>

    {{-- Check In/Out --}}
    @php $today = $todayAttendance ?? null; @endphp
    <div class="card p-6">
        <h3 class="mb-4 font-semibold text-white">Absensi Hari Ini</h3>
        @if(!$today)
        <div class="flex flex-col items-center gap-4 py-4">
            <div class="text-4xl">⏰</div>
            <p class="text-sm text-slate-400">Anda belum check-in hari ini.</p>
            <form method="POST" action="{{ route('attendance.check-in') }}">
                @csrf
                <button type="submit" class="btn btn-success">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Check In Sekarang
                </button>
            </form>
        </div>
        @elseif($today && !$today->check_out)
        <div class="flex items-center justify-between rounded-xl border border-emerald-800/30 bg-emerald-900/20 p-4">
            <div>
                <p class="text-sm text-slate-400">Check In</p>
                <p class="text-2xl font-bold text-emerald-400">{{ $today->check_in }}</p>
                <span class="badge-{{ $today->status_badge }}">{{ $today->status_label }}</span>
            </div>
            <form method="POST" action="{{ route('attendance.check-out') }}">
                @csrf
                <button type="submit" class="btn btn-warning">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Check Out
                </button>
            </form>
        </div>
        @else
        <div class="grid grid-cols-3 gap-4 rounded-xl border border-slate-700 bg-slate-800/40 p-4">
            <div class="text-center">
                <p class="text-xs text-slate-400">Check In</p>
                <p class="text-xl font-bold text-emerald-400">{{ $today->check_in }}</p>
            </div>
            <div class="text-center">
                <p class="text-xs text-slate-400">Check Out</p>
                <p class="text-xl font-bold text-amber-400">{{ $today->check_out ?? '-' }}</p>
            </div>
            <div class="text-center">
                <p class="text-xs text-slate-400">Status</p>
                <div class="mt-1"><span class="badge-{{ $today->status_badge }}">{{ $today->status_label }}</span></div>
            </div>
        </div>
        @endif
    </div>

    {{-- Stats row --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="card p-5">
            <div class="flex items-center gap-4">
                <div class="stat-icon bg-emerald-600 shadow-emerald-900/50">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ $leaveBalance->remaining_days ?? 0 }}</p>
                    <p class="text-xs text-slate-400">Sisa Cuti</p>
                </div>
            </div>
        </div>
        <div class="card p-5">
            <div class="flex items-center gap-4">
                <div class="stat-icon bg-indigo-600 shadow-indigo-900/50">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ $recentAttendance->where('status','present')->count() + $recentAttendance->where('status','late')->count() }}</p>
                    <p class="text-xs text-slate-400">Hadir 7 Hari</p>
                </div>
            </div>
        </div>
        <div class="card p-5">
            <div class="flex items-center gap-4">
                <div class="stat-icon bg-violet-600 shadow-violet-900/50">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ $recentSlips->count() }}</p>
                    <p class="text-xs text-slate-400">Slip Gaji</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent leaves --}}
    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-white">Riwayat Cuti Saya</h3>
            <a href="{{ route('leave.create') }}" class="btn btn-primary btn-sm">+ Ajukan Cuti</a>
        </div>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Jenis</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Hari</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLeaves as $leave)
                    <tr>
                        <td>{{ $leave->leave_type_label }}</td>
                        <td>{{ $leave->start_date->format('d M Y') }}</td>
                        <td>{{ $leave->end_date->format('d M Y') }}</td>
                        <td>{{ $leave->total_days }}</td>
                        <td><span class="badge-{{ $leave->status_badge }}">{{ $leave->status_label }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-6 text-center text-slate-500">Belum ada riwayat cuti.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endif

</x-app-layout>
