<x-app-layout>
@section('title', 'Edit Hari Libur')

<div class="max-w-xl mx-auto space-y-6">
    <div class="page-header">
        <h2 class="page-title">Edit Hari Libur</h2>
        <a href="{{ route('holiday.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('holiday.update', $holiday) }}" class="space-y-5">
                @csrf @method('PATCH')
                <div>
                    <label class="form-label">Nama Hari Libur</label>
                    <input type="text" name="name" class="form-input @error('name') border-rose-500 @enderror"
                           value="{{ old('name', $holiday->name) }}" required>
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="date" class="form-input"
                           value="{{ old('date', $holiday->date->format('Y-m-d')) }}" required>
                </div>
                <div>
                    <label class="form-label">Jenis</label>
                    <select name="type" class="form-select" required>
                        <option value="national" @selected(old('type', $holiday->type)==='national')>Nasional</option>
                        <option value="company" @selected(old('type', $holiday->type)==='company')>Perusahaan</option>
                    </select>
                </div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_recurring" class="h-4 w-4 rounded border-slate-600 bg-slate-700 text-indigo-600 focus:ring-indigo-500"
                           {{ old('is_recurring', $holiday->is_recurring) ? 'checked' : '' }}>
                    <span class="text-sm text-slate-300">Berulang setiap tahun</span>
                </label>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn btn-primary flex-1">Update</button>
                    <a href="{{ route('holiday.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
