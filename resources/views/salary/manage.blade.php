<x-app-layout>
@section('title', 'Kelola Gaji')

<div class="space-y-6">
    <div class="page-header">
        <div>
            <h2 class="page-title">Kelola Gaji — {{ $employee->name }}</h2>
            <p class="page-subtitle">{{ $employee->email }}</p>
        </div>
        <a href="{{ route('salary.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- Basic Salary --}}
        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-white">Gaji Pokok</h3>
                @if($salary)
                <span class="text-sm text-slate-400">Efektif: {{ $salary->effective_date->format('d M Y') }}</span>
                @endif
            </div>
            <div class="card-body">
                @if($salary)
                <div class="mb-5 rounded-xl bg-indigo-900/30 border border-indigo-700/50 p-4">
                    <p class="text-xs text-slate-400">Gaji Pokok Saat Ini</p>
                    <p class="text-2xl font-bold text-white">Rp {{ number_format($salary->basic_salary, 0, ',', '.') }}</p>
                </div>
                @endif

                <form method="POST" action="{{ route('salary.set-basic', $employee) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="form-label">Gaji Pokok Baru (Rp)</label>
                        <input type="number" name="basic_salary" class="form-input"
                               value="{{ old('basic_salary', $salary?->basic_salary) }}"
                               placeholder="0" min="0" required>
                    </div>
                    <div>
                        <label class="form-label">Tanggal Berlaku</label>
                        <input type="date" name="effective_date" class="form-input"
                               value="{{ old('effective_date', today()->format('Y-m-d')) }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Simpan Gaji Pokok</button>
                </form>
            </div>
        </div>

        {{-- Generate Slip --}}
        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-white">Generate Slip Gaji</h3>
            </div>
            <div class="card-body">
                @php
                    $allowances = $components->where('type','allowance')->sum('amount');
                    $deductions = $components->where('type','deduction')->sum('amount');
                    $thp = ($salary?->basic_salary ?? 0) + $allowances - $deductions;
                @endphp
                <div class="mb-5 grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-xl bg-slate-800 p-3">
                        <p class="text-xs text-slate-400">Gaji Pokok</p>
                        <p class="font-bold text-white">Rp {{ number_format($salary?->basic_salary ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-xl bg-emerald-900/30 border border-emerald-800/50 p-3">
                        <p class="text-xs text-slate-400">Total Tunjangan</p>
                        <p class="font-bold text-emerald-400">Rp {{ number_format($allowances, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-xl bg-rose-900/30 border border-rose-800/50 p-3">
                        <p class="text-xs text-slate-400">Total Potongan</p>
                        <p class="font-bold text-rose-400">Rp {{ number_format($deductions, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-xl bg-indigo-900/30 border border-indigo-800/50 p-3">
                        <p class="text-xs text-slate-400">Take Home Pay</p>
                        <p class="font-bold text-white">Rp {{ number_format($thp, 0, ',', '.') }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('salary.generate', $employee) }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="form-label">Bulan</label>
                            <select name="period_month" class="form-select" required>
                                @foreach(range(1,12) as $m)
                                <option value="{{ $m }}" @selected($m == now()->month)>
                                    {{ \Carbon\Carbon::create(null,$m)->format('F') }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Tahun</label>
                            <select name="period_year" class="form-select" required>
                                @foreach(range(now()->year - 1, now()->year + 1) as $y)
                                <option value="{{ $y }}" @selected($y == now()->year)>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-full">Generate Slip Gaji</button>
                </form>
            </div>
        </div>

    </div>

    {{-- Components --}}
    <div class="card overflow-hidden">
        <div class="card-header">
            <h3 class="font-semibold text-white">Komponen Gaji</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('salary.add-component', $employee) }}"
                  class="flex flex-wrap gap-3 items-end mb-6 border-b border-slate-800 pb-6">
                @csrf
                <div class="flex-1 min-w-40">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-input" placeholder="cth: Transport" required>
                </div>
                <div class="w-40">
                    <label class="form-label">Jenis</label>
                    <select name="type" class="form-select" required>
                        <option value="allowance">Tunjangan</option>
                        <option value="deduction">Potongan</option>
                    </select>
                </div>
                <div class="flex-1 min-w-40">
                    <label class="form-label">Jumlah (Rp)</label>
                    <input type="number" name="amount" class="form-input" placeholder="0" min="0" required>
                </div>
                <div class="flex items-center gap-2 pb-1">
                    <input type="checkbox" name="is_recurring" id="recurring" checked
                           class="h-4 w-4 rounded border-slate-600 bg-slate-700 text-indigo-600">
                    <label for="recurring" class="text-sm text-slate-300 cursor-pointer">Berulang</label>
                </div>
                <button type="submit" class="btn btn-primary">+ Tambah</button>
            </form>

            @if($components->count() > 0)
            <div class="space-y-2">
                @foreach($components->sortBy('type') as $comp)
                <div class="flex items-center justify-between rounded-xl border border-slate-700 bg-slate-800/40 px-4 py-3">
                    <div class="flex items-center gap-3">
                        <span class="{{ $comp->type === 'allowance' ? 'badge-success' : 'badge-danger' }}">
                            {{ $comp->type_label }}
                        </span>
                        <div>
                            <p class="text-sm font-medium text-white">{{ $comp->name }}</p>
                            <p class="text-xs text-slate-400">{{ $comp->is_recurring ? 'Berulang' : 'Sekali' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <p class="font-semibold {{ $comp->type === 'allowance' ? 'text-emerald-400' : 'text-rose-400' }}">
                            {{ $comp->type === 'deduction' ? '-' : '+' }} Rp {{ number_format($comp->amount, 0, ',', '.') }}
                        </p>
                        <form method="POST" action="{{ route('salary.delete-component', $comp) }}"
                              onsubmit="return confirm('Hapus komponen ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-center text-sm text-slate-500 py-4">Belum ada komponen gaji.</p>
            @endif
        </div>
    </div>

</div>
</x-app-layout>
