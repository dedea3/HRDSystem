<x-app-layout>
@section('title', 'Slip Gaji')

<div class="max-w-2xl mx-auto space-y-6">
    <div class="page-header">
        <div>
            <h2 class="page-title">Slip Gaji</h2>
            <p class="page-subtitle">{{ $slip->period_label }}</p>
        </div>
        <div class="flex gap-2">
            <button onclick="window.print()" class="btn btn-secondary btn-sm">🖨 Print</button>
            <a href="{{ auth()->user()->hasAnyRole(['admin','hrd']) ? route('salary.slips') : route('salary.my') }}"
               class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
    </div>

    {{-- Slip Card --}}
    <div class="card" id="slip-content">
        {{-- Header --}}
        <div class="card-header bg-gradient-to-r from-indigo-900/30 to-violet-900/30 rounded-t-2xl">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-white">HRD System</p>
                    <p class="text-xs text-slate-400">Slip Gaji Periode {{ $slip->period_label }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-400">Dibuat pada</p>
                <p class="text-sm text-white">{{ $slip->generated_at->format('d M Y') }}</p>
            </div>
        </div>

        <div class="card-body space-y-5">

            {{-- Employee Info --}}
            <div class="rounded-xl border border-slate-700 bg-slate-800/40 p-4">
                <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Data Karyawan</p>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-slate-400">Nama</p>
                        <p class="font-medium text-white">{{ $slip->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400">Email</p>
                        <p class="font-medium text-white">{{ $slip->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400">Periode</p>
                        <p class="font-medium text-white">{{ $slip->period_label }}</p>
                    </div>
                </div>
            </div>

            {{-- Salary Detail --}}
            <div>
                <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Rincian Gaji</p>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm py-2 border-b border-slate-800">
                        <span class="text-slate-300">Gaji Pokok</span>
                        <span class="font-medium text-white">Rp {{ number_format($slip->basic_salary, 0, ',', '.') }}</span>
                    </div>

                    {{-- Allowances --}}
                    @foreach($components->where('type','allowance') as $comp)
                    <div class="flex justify-between text-sm py-1">
                        <span class="text-slate-400 pl-3">+ {{ $comp->name }}</span>
                        <span class="text-emerald-400">Rp {{ number_format($comp->amount, 0, ',', '.') }}</span>
                    </div>
                    @endforeach

                    <div class="flex justify-between text-sm py-2 border-b border-slate-800">
                        <span class="text-slate-300 font-medium">Subtotal Tunjangan</span>
                        <span class="font-medium text-emerald-400">Rp {{ number_format($slip->total_allowances, 0, ',', '.') }}</span>
                    </div>

                    {{-- Deductions --}}
                    @foreach($components->where('type','deduction') as $comp)
                    <div class="flex justify-between text-sm py-1">
                        <span class="text-slate-400 pl-3">- {{ $comp->name }}</span>
                        <span class="text-rose-400">Rp {{ number_format($comp->amount, 0, ',', '.') }}</span>
                    </div>
                    @endforeach

                    <div class="flex justify-between text-sm py-2 border-b border-slate-800">
                        <span class="text-slate-300 font-medium">Subtotal Potongan</span>
                        <span class="font-medium text-rose-400">Rp {{ number_format($slip->total_deductions, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Total --}}
            <div class="rounded-xl bg-gradient-to-r from-indigo-900/50 to-violet-900/50 border border-indigo-700/50 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-400">Total Take Home Pay</p>
                        <p class="text-xs text-slate-500">{{ $slip->period_label }}</p>
                    </div>
                    <p class="text-2xl font-bold text-white">Rp {{ number_format($slip->net_salary, 0, ',', '.') }}</p>
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    body { background: white !important; color: black !important; }
    aside, header, .page-header > div:last-child { display: none !important; }
    .card { border: 1px solid #ddd !important; background: white !important; }
}
</style>
@endpush
</x-app-layout>
