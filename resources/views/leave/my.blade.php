<x-app-layout>
@section('title', 'Cuti Saya')

<div class="space-y-6">
    <div class="page-header">
        <div>
            <h2 class="page-title">Cuti Saya</h2>
            <p class="page-subtitle">Riwayat dan pengajuan cuti</p>
        </div>
        <a href="{{ route('leave.create') }}" class="btn btn-primary">+ Ajukan Cuti</a>
    </div>

    {{-- Leave balance card --}}
    @if($leaveBalance)
    <div class="grid grid-cols-3 gap-4">
        <div class="stat-card">
            <div class="stat-icon bg-indigo-600 shadow-indigo-900/50 h-10 w-10">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-xl font-bold text-white">{{ $leaveBalance->total_days }}</p>
                <p class="text-xs text-slate-400">Total Cuti {{ $leaveBalance->year }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-amber-500 shadow-amber-900/50 h-10 w-10">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xl font-bold text-white">{{ $leaveBalance->used_days }}</p>
                <p class="text-xs text-slate-400">Terpakai</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-emerald-600 shadow-emerald-900/50 h-10 w-10">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <p class="text-xl font-bold text-white">{{ $leaveBalance->remaining_days }}</p>
                <p class="text-xs text-slate-400">Sisa</p>
            </div>
        </div>
    </div>
    @endif

    <div class="card overflow-hidden">
        <table class="table">
            <thead>
                <tr>
                    <th>Jenis</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Hari</th>
                    <th>Alasan</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaves as $leave)
                <tr>
                    <td>{{ $leave->leave_type_label }}</td>
                    <td>{{ $leave->start_date->format('d M Y') }}</td>
                    <td>{{ $leave->end_date->format('d M Y') }}</td>
                    <td>{{ $leave->total_days }} hari</td>
                    <td class="max-w-xs"><p class="truncate text-sm text-slate-400">{{ $leave->reason }}</p></td>
                    <td><span class="badge-{{ $leave->status_badge }}">{{ $leave->status_label }}</span></td>
                    <td>
                        @if($leave->status === 'pending')
                        <form method="POST" action="{{ route('leave.destroy', $leave) }}"
                              onsubmit="return confirm('Batalkan pengajuan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Batalkan</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-10 text-center text-slate-500">Belum ada riwayat cuti.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($leaves->hasPages())
        <div class="card-footer">{{ $leaves->links() }}</div>
        @endif
    </div>
</div>
</x-app-layout>
