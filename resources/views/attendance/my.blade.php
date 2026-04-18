<x-app-layout>
@section('title', 'Absensi Saya')

<div class="space-y-6">

    <div class="page-header">
        <div>
            <h2 class="page-title">Absensi Saya</h2>
            <p class="page-subtitle">{{ now()->format('F Y') }}</p>
        </div>
    </div>

    {{-- Summary stats --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="stat-card">
            <div class="stat-icon bg-emerald-600 shadow-emerald-900/50 h-10 w-10">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xl font-bold text-white">{{ $summary['present'] }}</p>
                <p class="text-xs text-slate-400">Hadir</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-amber-500 shadow-amber-900/50 h-10 w-10">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xl font-bold text-white">{{ $summary['late'] }}</p>
                <p class="text-xs text-slate-400">Terlambat</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-rose-600 shadow-rose-900/50 h-10 w-10">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xl font-bold text-white">{{ $summary['absent'] }}</p>
                <p class="text-xs text-slate-400">Tidak Hadir</p>
            </div>
        </div>
    </div>

    {{-- Month selector --}}
    <div class="card p-4">
        <form method="GET" class="flex gap-3">
            <select name="month" class="form-select w-40">
                @foreach(range(1,12) as $m)
                <option value="{{ $m }}" @selected($m == $month)>
                    {{ \Carbon\Carbon::create(null,$m)->format('F') }}
                </option>
                @endforeach
            </select>
            <select name="year" class="form-select w-28">
                @foreach(range(now()->year - 2, now()->year) as $y)
                <option value="{{ $y }}" @selected($y == $year)>{{ $y }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary">Tampilkan</button>
        </form>
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden">
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Lama Kerja</th>
                    <th>Status</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $att)
                <tr>
                    <td>{{ $att->date->format('l, d M Y') }}</td>
                    <td>{{ $att->check_in }}</td>
                    <td>{{ $att->check_out ?? '-' }}</td>
                    <td>{{ $att->work_duration ?? '-' }}</td>
                    <td><span class="badge-{{ $att->status_badge }}">{{ $att->status_label }}</span></td>
                    <td class="text-xs text-slate-400">{{ $att->notes ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-10 text-center text-slate-500">Belum ada absensi pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</x-app-layout>
