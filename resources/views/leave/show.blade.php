<x-app-layout>
@section('title', 'Detail Cuti')

<div class="max-w-2xl mx-auto space-y-6">
    <div class="page-header">
        <h2 class="page-title">Detail Pengajuan Cuti</h2>
        <a href="{{ route('leave.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="font-semibold text-white">{{ $leave->user->name }}</h3>
                <p class="text-xs text-slate-400">{{ $leave->user->email }}</p>
            </div>
            <span class="badge-{{ $leave->status_badge }} text-sm px-3 py-1">{{ $leave->status_label }}</span>
        </div>
        <div class="card-body space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-slate-400">Jenis Cuti</p>
                    <p class="font-medium text-white">{{ $leave->leave_type_label }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400">Total Hari</p>
                    <p class="font-medium text-white">{{ $leave->total_days }} hari</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400">Tanggal Mulai</p>
                    <p class="font-medium text-white">{{ $leave->start_date->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400">Tanggal Selesai</p>
                    <p class="font-medium text-white">{{ $leave->end_date->format('d F Y') }}</p>
                </div>
            </div>
            <div>
                <p class="text-xs text-slate-400">Alasan</p>
                <p class="text-white">{{ $leave->reason }}</p>
            </div>
            @if($leave->approver)
            <div class="border-t border-slate-800 pt-4">
                <p class="text-xs text-slate-400">
                    {{ $leave->status === 'approved' ? 'Disetujui' : 'Ditolak' }} oleh
                    <span class="text-white">{{ $leave->approver->name }}</span>
                    pada {{ $leave->approved_at->format('d M Y H:i') }}
                </p>
            </div>
            @endif
        </div>
        @if($leave->status === 'pending')
        <div class="card-footer flex gap-3">
            <form method="POST" action="{{ route('leave.approve', $leave) }}" class="flex-1">
                @csrf @method('PATCH')
                <button class="btn btn-success w-full">✓ Setujui Cuti</button>
            </form>
            <form method="POST" action="{{ route('leave.reject', $leave) }}" class="flex-1">
                @csrf @method('PATCH')
                <button class="btn btn-danger w-full">✗ Tolak Cuti</button>
            </form>
        </div>
        @endif
    </div>
</div>
</x-app-layout>
