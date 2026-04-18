<x-app-layout>
@section('title', 'Semua Slip Gaji')

<div class="space-y-6">
    <div class="page-header">
        <div>
            <h2 class="page-title">Slip Gaji</h2>
            <p class="page-subtitle">Riwayat penggajian seluruh karyawan</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card p-4">
        <form method="GET" class="flex flex-wrap gap-3">
            <div class="w-36">
                <select name="month" class="form-select">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" @selected(request('month') == $m)>{{ \Carbon\Carbon::create(null,$m)->format('F') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-28">
                <input type="number" name="year" class="form-input" value="{{ request('year') }}" placeholder="Tahun">
            </div>
            <div class="w-52">
                <select name="user_id" class="form-select">
                    <option value="">Semua Karyawan</option>
                    @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" @selected(request('user_id') == $emp->id)>{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">Filter</button>
            <a href="{{ route('salary.slips') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Periode</th>
                        <th>Gaji Pokok</th>
                        <th>Tunjangan</th>
                        <th>Potongan</th>
                        <th>Take Home Pay</th>
                        <th>Dibuat</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($slips as $slip)
                    <tr>
                        <td class="font-medium text-white">{{ $slip->user->name }}</td>
                        <td>{{ $slip->period_label }}</td>
                        <td>Rp {{ number_format($slip->basic_salary, 0, ',', '.') }}</td>
                        <td class="text-emerald-400">Rp {{ number_format($slip->total_allowances, 0, ',', '.') }}</td>
                        <td class="text-rose-400">Rp {{ number_format($slip->total_deductions, 0, ',', '.') }}</td>
                        <td class="font-bold text-white">Rp {{ number_format($slip->net_salary, 0, ',', '.') }}</td>
                        <td class="text-xs text-slate-400">{{ $slip->generated_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('salary.slip', $slip) }}" class="btn btn-secondary btn-sm">Lihat</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="py-10 text-center text-slate-500">Belum ada slip gaji.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($slips->hasPages())
        <div class="card-footer">{{ $slips->links() }}</div>
        @endif
    </div>
</div>
</x-app-layout>
