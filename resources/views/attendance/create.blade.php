<x-app-layout>
@section('title', 'Input Absensi Manual')

<div class="max-w-2xl mx-auto space-y-6">
    <div class="page-header">
        <h2 class="page-title">Input Absensi Manual</h2>
        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('attendance.store') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="form-label">Karyawan</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" @selected(old('user_id') == $emp->id)>{{ $emp->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="date" class="form-input" value="{{ old('date', today()->format('Y-m-d')) }}" required>
                    @error('date')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Check In</label>
                        <input type="time" name="check_in" class="form-input" value="{{ old('check_in') }}">
                        @error('check_in')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Check Out</label>
                        <input type="time" name="check_out" class="form-input" value="{{ old('check_out') }}">
                    </div>
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="present" @selected(old('status') === 'present')>Hadir</option>
                        <option value="late" @selected(old('status') === 'late')>Terlambat</option>
                        <option value="absent" @selected(old('status') === 'absent')>Tidak Hadir</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-input" rows="2" placeholder="Catatan (opsional)">{{ old('notes') }}</textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn btn-primary flex-1">Simpan</button>
                    <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
