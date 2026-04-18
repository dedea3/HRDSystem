<x-app-layout>
@section('title', 'Karyawan')

<div class="space-y-6">

    {{-- Header --}}
    <div class="page-header">
        <div>
            <h2 class="page-title">Data Karyawan</h2>
            <p class="page-subtitle">Kelola semua data karyawan perusahaan</p>
        </div>
        <a href="{{ route('employees.create') }}" class="btn btn-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Tambah Karyawan
        </a>
    </div>

    {{-- Filters --}}
    <div class="card p-4">
        <form method="GET" action="{{ route('employees.index') }}" class="flex flex-wrap gap-3">
            <div class="flex-1 min-w-48">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                       class="form-input">
            </div>
            <div class="w-44">
                <select name="role" class="form-select">
                    <option value="">Semua Role</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->name }}" @selected(request('role') === $role->name)>{{ $role->display_name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">Filter</button>
            @if(request()->anyFilled(['search','role']))
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Bergabung</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                    <tr>
                        <td class="text-slate-500">{{ $employees->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-600/30 text-sm font-bold text-indigo-300">
                                    {{ strtoupper(substr($emp->name,0,1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-white">{{ $emp->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-slate-400">{{ $emp->email }}</td>
                        <td>
                            @foreach($emp->roles as $role)
                            <span class="badge-{{ $role->name === 'admin' ? 'danger' : ($role->name === 'hrd' ? 'info' : 'neutral') }}">
                                {{ $role->display_name }}
                            </span>
                            @endforeach
                        </td>
                        <td class="text-slate-400">{{ $emp->created_at->format('d M Y') }}</td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('employees.show', $emp) }}" class="btn btn-secondary btn-sm">Detail</a>
                                <a href="{{ route('employees.edit', $emp) }}" class="btn btn-primary btn-sm">Edit</a>
                                @if($emp->id !== auth()->id())
                                <form method="POST" action="{{ route('employees.destroy', $emp) }}"
                                      onsubmit="return confirm('Hapus karyawan {{ $emp->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-500">
                            Tidak ada data karyawan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($employees->hasPages())
        <div class="card-footer">
            {{ $employees->links() }}
        </div>
        @endif
    </div>

</div>
</x-app-layout>
