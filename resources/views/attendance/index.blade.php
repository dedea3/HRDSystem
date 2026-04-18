<x-app-layout>
@section('title', 'Manajemen Absensi')

<div class="space-y-6">
    <div class="page-header">
        <div>
            <h2 class="page-title">Manajemen Absensi</h2>
            <p class="page-subtitle">Monitor absensi seluruh karyawan</p>
        </div>
        <a href="{{ route('attendance.create') }}" class="btn btn-primary">+ Input Manual</a>
    </div>

    {{-- Filter --}}
    <div class="card p-4">
        <form method="GET" class="flex flex-wrap gap-3">
            <div>
                <input type="date" name="date" value="{{ request('date', today()->format('Y-m-d')) }}" class="form-input">
            </div>
            <div class="w-44">
                <select name="user_id" class="form-select">
                    <option value="">Semua Karyawan</option>
                    @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" @selected(request('user_id') == $emp->id)>{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-36">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="present" @selected(request('status') === 'present')>Hadir</option>
                    <option value="late" @selected(request('status') === 'late')>Terlambat</option>
                    <option value="absent" @selected(request('status') === 'absent')>Tidak Hadir</option>
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">Filter</button>
            <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Tanggal</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Lama Kerja</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $att)
                    <tr>
                        <td class="font-medium text-white">{{ $att->user->name }}</td>
                        <td>{{ $att->date->format('d M Y') }}</td>
                        <td>{{ $att->check_in }}</td>
                        <td>{{ $att->check_out ?? '-' }}</td>
                        <td>{{ $att->work_duration ?? '-' }}</td>
                        <td><span class="badge-{{ $att->status_badge }}">{{ $att->status_label }}</span></td>
                        <td class="text-xs text-slate-400 max-w-xs truncate">{{ $att->notes ?? '-' }}</td>
                        <td class="text-right">
                            <a href="{{ route('attendance.edit', $att) }}" class="btn btn-secondary btn-sm">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-500">Belum ada data absensi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($attendances->hasPages())
        <div class="card-footer">{{ $attendances->links() }}</div>
        @endif
    </div>
</div>
</x-app-layout>
