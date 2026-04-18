<x-app-layout>
@section('title', 'Manajemen Cuti')

<div class="space-y-6">
    <div class="page-header">
        <div>
            <h2 class="page-title">Manajemen Cuti</h2>
            <p class="page-subtitle">Kelola dan setujui pengajuan cuti</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card p-4">
        <form method="GET" class="flex flex-wrap gap-3">
            <div class="w-40">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(request('status')==='pending')>Menunggu</option>
                    <option value="approved" @selected(request('status')==='approved')>Disetujui</option>
                    <option value="rejected" @selected(request('status')==='rejected')>Ditolak</option>
                </select>
            </div>
            <div class="w-52">
                <select name="user_id" class="form-select">
                    <option value="">Semua Karyawan</option>
                    @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" @selected(request('user_id')==$emp->id)>{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">Filter</button>
            <a href="{{ route('leave.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Jenis Cuti</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Hari</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                    <tr>
                        <td class="font-medium text-white">{{ $leave->user->name }}</td>
                        <td>{{ $leave->leave_type_label }}</td>
                        <td>{{ $leave->start_date->format('d M Y') }}</td>
                        <td>{{ $leave->end_date->format('d M Y') }}</td>
                        <td>{{ $leave->total_days }} hari</td>
                        <td class="max-w-xs">
                            <p class="truncate text-sm text-slate-400">{{ $leave->reason }}</p>
                        </td>
                        <td>
                            <span class="badge-{{ $leave->status_badge }}">{{ $leave->status_label }}</span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('leave.show', $leave) }}" class="btn btn-secondary btn-sm">Detail</a>
                                @if($leave->status === 'pending')
                                <form method="POST" action="{{ route('leave.approve', $leave) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-success btn-sm">✓ Setuju</button>
                                </form>
                                <form method="POST" action="{{ route('leave.reject', $leave) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-danger btn-sm">✗ Tolak</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="py-10 text-center text-slate-500">Tidak ada data cuti.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($leaves->hasPages())
        <div class="card-footer">{{ $leaves->links() }}</div>
        @endif
    </div>
</div>
</x-app-layout>
