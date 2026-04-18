<x-app-layout>
@section('title', 'Edit Absensi')

<div class="max-w-2xl mx-auto space-y-6">
    <div class="page-header">
        <h2 class="page-title">Edit Absensi</h2>
        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="card">
        <div class="card-body">
            <p class="mb-5 text-sm text-slate-400">
                Karyawan: <span class="font-medium text-white">{{ $attendance->user->name }}</span>
                · Tanggal: <span class="font-medium text-white">{{ $attendance->date->format('d M Y') }}</span>
            </p>
            <form method="POST" action="{{ route('attendance.update', $attendance) }}" class="space-y-5">
                @csrf @method('PATCH')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Check In</label>
                        <input type="time" name="check_in" class="form-input"
                               value="{{ old('check_in', $attendance->check_in) }}" required>
                        @error('check_in')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Check Out</label>
                        <input type="time" name="check_out" class="form-input"
                               value="{{ old('check_out', $attendance->check_out) }}">
                    </div>
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="present" @selected(old('status', $attendance->status) === 'present')>Hadir</option>
                        <option value="late" @selected(old('status', $attendance->status) === 'late')>Terlambat</option>
                        <option value="absent" @selected(old('status', $attendance->status) === 'absent')>Tidak Hadir</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-input" rows="2">{{ old('notes', $attendance->notes) }}</textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn btn-primary flex-1">Update</button>
                    <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
