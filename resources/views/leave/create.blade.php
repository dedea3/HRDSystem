<x-app-layout>
@section('title', 'Ajukan Cuti')

<div class="max-w-2xl mx-auto space-y-6">
    <div class="page-header">
        <h2 class="page-title">Ajukan Cuti</h2>
        <a href="{{ route('leave.my') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('leave.store') }}" class="space-y-5" id="leaveForm">
                @csrf

                <div>
                    <label class="form-label">Jenis Cuti</label>
                    <select name="leave_type" class="form-select" required>
                        <option value="">-- Pilih Jenis Cuti --</option>
                        <option value="annual" @selected(old('leave_type')==='annual')>Cuti Tahunan</option>
                        <option value="sick" @selected(old('leave_type')==='sick')>Cuti Sakit</option>
                        <option value="emergency" @selected(old('leave_type')==='emergency')>Cuti Darurat</option>
                    </select>
                    @error('leave_type')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="startDate"
                               class="form-input" value="{{ old('start_date') }}"
                               min="{{ today()->format('Y-m-d') }}" required>
                        @error('start_date')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="endDate"
                               class="form-input" value="{{ old('end_date') }}"
                               min="{{ today()->format('Y-m-d') }}" required>
                        @error('end_date')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Day count indicator --}}
                <div id="dayCount" class="hidden rounded-xl bg-indigo-900/30 border border-indigo-700/50 px-4 py-3">
                    <p class="text-sm text-indigo-300">
                        Total hari cuti: <span id="dayCountNum" class="font-bold text-white">0</span> hari
                    </p>
                </div>

                <div>
                    <label class="form-label">Alasan</label>
                    <textarea name="reason" class="form-input" rows="4"
                              placeholder="Jelaskan alasan pengajuan cuti..." required>{{ old('reason') }}</textarea>
                    @error('reason')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn btn-primary flex-1">Kirim Pengajuan</button>
                    <a href="{{ route('leave.my') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function calcDays() {
    const start = document.getElementById('startDate').value;
    const end   = document.getElementById('endDate').value;
    const box   = document.getElementById('dayCount');
    const num   = document.getElementById('dayCountNum');
    if (start && end) {
        const s   = new Date(start);
        const e   = new Date(end);
        let days  = 0;
        let cur   = new Date(s);
        while (cur <= e) {
            const d = cur.getDay();
            if (d !== 0 && d !== 6) days++;
            cur.setDate(cur.getDate() + 1);
        }
        num.textContent = days;
        box.classList.remove('hidden');
    } else {
        box.classList.add('hidden');
    }
}
document.getElementById('startDate').addEventListener('change', calcDays);
document.getElementById('endDate').addEventListener('change', calcDays);
</script>
@endpush
</x-app-layout>
