<x-app-layout>
@section('title', 'Slip Gaji Saya')

<div class="space-y-6">
    <div class="page-header">
        <h2 class="page-title">Slip Gaji Saya</h2>
    </div>

    <div class="card overflow-hidden">
        <table class="table">
            <thead><tr><th>Periode</th><th>Gaji Pokok</th><th>Tunjangan</th><th>Potongan</th><th>Take Home Pay</th><th>Dibuat</th><th></th></tr></thead>
            <tbody>
                @forelse($slips as $slip)
                <tr>
                    <td>{{ $slip->period_label }}</td>
                    <td>Rp {{ number_format($slip->basic_salary, 0, ',', '.') }}</td>
                    <td class="text-emerald-400">Rp {{ number_format($slip->total_allowances, 0, ',', '.') }}</td>
                    <td class="text-rose-400">Rp {{ number_format($slip->total_deductions, 0, ',', '.') }}</td>
                    <td class="font-bold text-white">Rp {{ number_format($slip->net_salary, 0, ',', '.') }}</td>
                    <td class="text-xs text-slate-400">{{ $slip->generated_at->format('d M Y') }}</td>
                    <td><a href="{{ route('salary.slip', $slip) }}" class="btn btn-secondary btn-sm">Lihat</a></td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-10 text-center text-slate-500">Belum ada slip gaji.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($slips->hasPages())
        <div class="card-footer">{{ $slips->links() }}</div>
        @endif
    </div>
</div>
</x-app-layout>
