<x-app-layout>
@section('title', 'Hari Libur')

<div class="space-y-6">
    <div class="page-header">
        <div>
            <h2 class="page-title">Hari Libur</h2>
            <p class="page-subtitle">Kelola hari libur nasional dan perusahaan</p>
        </div>
        <a href="{{ route('holiday.create') }}" class="btn btn-primary">+ Tambah Libur</a>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Tahunan</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($holidays as $holiday)
                    <tr>
                        <td class="font-medium text-white">{{ $holiday->name }}</td>
                        <td>{{ $holiday->date->format('d F Y') }}</td>
                        <td>
                            <span class="{{ $holiday->type === 'national' ? 'badge-danger' : 'badge-info' }}">
                                {{ $holiday->type_label }}
                            </span>
                        </td>
                        <td>
                            @if($holiday->is_recurring)
                            <span class="badge-success">Ya</span>
                            @else
                            <span class="badge-neutral">Tidak</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('holiday.edit', $holiday) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form method="POST" action="{{ route('holiday.destroy', $holiday) }}"
                                      onsubmit="return confirm('Hapus hari libur ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-10 text-center text-slate-500">Belum ada data hari libur.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($holidays->hasPages())
        <div class="card-footer">{{ $holidays->links() }}</div>
        @endif
    </div>
</div>
</x-app-layout>
