<x-app-layout>
@section('title', 'Penggajian')

<div class="space-y-6">
    <div class="page-header">
        <div>
            <h2 class="page-title">Penggajian</h2>
            <p class="page-subtitle">Kelola gaji pokok dan komponen gaji karyawan</p>
        </div>
        <a href="{{ route('salary.slips') }}" class="btn btn-secondary">Lihat Semua Slip Gaji →</a>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Role</th>
                        <th>Gaji Pokok</th>
                        <th>Tunjangan</th>
                        <th>Potongan</th>
                        <th>Take Home Pay</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $emp)
                    @php
                        $salary     = $emp->salary;
                        $allowances = $emp->salaryComponents->where('type','allowance')->sum('amount');
                        $deductions = $emp->salaryComponents->where('type','deduction')->sum('amount');
                        $basic      = $salary ? $salary->basic_salary : 0;
                        $thp        = $basic + $allowances - $deductions;
                    @endphp
                    <tr>
                        <td class="font-medium text-white">{{ $emp->name }}</td>
                        <td>
                            @foreach($emp->roles as $r)
                            <span class="badge-neutral">{{ $r->display_name }}</span>
                            @endforeach
                        </td>
                        <td>{{ $basic > 0 ? 'Rp '.number_format($basic,0,',','.') : '-' }}</td>
                        <td class="text-emerald-400">{{ $allowances > 0 ? 'Rp '.number_format($allowances,0,',','.') : '-' }}</td>
                        <td class="text-rose-400">{{ $deductions > 0 ? 'Rp '.number_format($deductions,0,',','.') : '-' }}</td>
                        <td class="font-bold text-white">{{ $thp > 0 ? 'Rp '.number_format($thp,0,',','.') : '-' }}</td>
                        <td class="text-right">
                            <a href="{{ route('salary.manage', $emp) }}" class="btn btn-primary btn-sm">Kelola</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-app-layout>
